<?php

namespace App\Http\Controllers;

use App\Models\ClientesReactivados;
use App\Models\DevolucionFactura;
use App\Models\Factura;
use App\Models\ReciboHistorialContado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DevolucionFacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $status = 200;
        // $facturaEstado = 1; // Activo
        $parametros = [];

        if (!is_null($request['estado'])) $parametros[] = ["estado", $request['estado']];

        // dd($facturaEstado);
        $facturas =  DevolucionFactura::where($parametros)->get();

        // echo "<pre>";
        // print_r (json_encode($facturas));
        // echo "</pre>";

        if (count($facturas) > 0) {
            foreach ($facturas as $key => $factura) {

                $factura->factura;
                $factura->user;
                // $factura->factura_historial;
            }

            $response = $facturas;
        }

        return response()->json($response, $status);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [];
        $status = 400;


        $validation = Validator::make($request->all(), [
            'factura_id' => 'required|numeric',
            'user_id' => 'required|numeric',
            'descripcion' => 'string|nullable',
            'estado' => 'required|numeric|max:1',
        ]);

        if ($validation->fails()) {
            $response[] = $validation->errors();
        } else {
            $factura =  Factura::find($request['factura_id']);
            if ($factura) {

                DB::beginTransaction();
                try {
                    // 1 - Busco los productos de la factura que esten activos
                    $factura_detalle = $factura->factura_detalle()->where([
                        ['estado', '=', 1],
                    ])->get();

                    // 2 - Recorro los productos y devuelvo el stock de cada uno al inventario y al terminar los coloco en estado 0
                    foreach ($factura_detalle as $productoDetalle) {
                        devolverStockProducto($productoDetalle["id"], $productoDetalle["cantidad"]);
                        $productoDetalle->update([
                            'estado' => 0
                        ]);
                    }

                    // 3 - Actualizo el estadp de la factura
                    $factura->update([
                        'status' => 0,
                    ]);

                    // 4 - Inserto la devolucion de la factura
                    $devolucionFactura = DevolucionFactura::create([
                        'factura_id' => $request['factura_id'],
                        'user_id'    => $request['user_id'],
                        'descripcion' => $request['descripcion'],
                        'estado'     => $request['estado'],
                    ]);

                    if($factura->tipo_venta == 1){ // si es venta a credito
                        // 5 - Recalculo la deuda del cliente
                        validarStatusPagadoGlobal($factura->cliente_id);
                    }else{ // si es venta a contado
                        // print_r(json_encode($factura->recibo_historial_contado));

                        ReciboHistorialContado::where('factura_id', $factura->id)->update([
                            'estado' => 0,
                        ]);
                    }

                    // 6 - elimino la reactivacion del cliente
                    $query = "SELECT
                    c.*,
                    q.cantidad_factura,
                    q.cantidad_finalizadas,
                    if(q.cantidad_factura = q.cantidad_finalizadas, 1, 0) AS cliente_inactivo
                    FROM clientes c
                    INNER JOIN (
                        SELECT
                            c.id AS cliente_id,
                            c.user_id AS user_id,
                            COUNT(c.id) AS cantidad_factura,
                            SUM(if(f.status_pagado = 1, 1, 0)) AS cantidad_finalizadas
                        FROM clientes c
                        INNER JOIN facturas f ON c.id = f.cliente_id
                        WHERE  f.`status` = 1
                        GROUP BY c.id
                        ORDER BY c.id ASC
                    )q ON c.id = q.cliente_id
                    WHERE
                        q.cantidad_factura = q.cantidad_finalizadas AND
                        c.id = ".$factura->cliente_id; // Valido que el cliente este en la lista de clientes inactivos

                    $clientesInactivos = DB::select($query);

                    if(count($clientesInactivos)>0){
                        ClientesReactivados::where([
                            ['factura_id', '=', $factura->id],
                        ])->update([
                            'estado'     => 0,
                        ]);
                    }

                    if ($devolucionFactura) {
                        $response[] = 'la factura fue devuelta con exito';
                        $status = 200;

                        DB::commit();
                        // DB::rollback();
                    } else {
                        $response[] = 'Error al modificar los datos.';
                        DB::rollback();
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    // print_r(json_encode($e));
                    return response()->json(["mensaje" => json_encode($e)], 400);
                }

            } else {
                $response[] = "La factura no existe.";
            }
        }

        return response()->json($response, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [];
        $status = 400;
        // $productoEstado = 1; // Activo

        if (is_numeric($id)) {

            // if($request->input("estado") != null) $productoEstado = $request->input("estado");
            // dd($productoEstado);

            $devolucionFactura =  DevolucionFactura::where([
                ['id', '=', $id],
                // ['estado', '=', $productoEstado],
            ])->first();


            // $cliente =  Cliente::find($id);
            if ($devolucionFactura) {
                $response = $devolucionFactura;
                $status = 200;
            } else {
                $response[] = "El producto no existe o fue eliminado.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = [];
        $status = 400;

        if (is_numeric($id)) {
            $factura =  Factura::find($id);

            if ($factura) {
                $validation = Validator::make($request->all(), [
                    'factura_id' => 'required|numeric',
                    'user_id' => 'required|numeric',
                    'descripcion' => 'string|nullable',
                    'estado' => 'required|numeric|max:1',
                ]);

                if ($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    DB::beginTransaction();
                    try {
                        // 1 - Busco los productos de la factura que esten activos
                        $factura_detalle = $factura->factura_detalle()->where([
                            ['estado', '=', 1],
                        ])->get();

                        // 2 - Recorro los productos y devuelvo el stock de cada uno al inventario y al terminar los coloco en estado 0
                        foreach ($factura_detalle as $productoDetalle) {
                            devolverStockProducto($productoDetalle["id"], $productoDetalle["cantidad"]);
                            $productoDetalle->update([
                                'estado' => 0
                            ]);
                        }
                        // 3 - Actualizo el estadp de la factura
                        $factura->update([
                            'status' => 0,
                        ]);

                        // 4 - Recalculo la deuda del cliente sin la factura eliminada
                        validarStatusPagadoGlobal($factura->cliente_id);

                        // 5 - Inserto la devolucion de la factura
                        $devolucionFactura = DevolucionFactura::create([
                            'factura_id' => $request['factura_id'],
                            'user_id'    => $request['user_id'],
                            'descripcion' => $request['descripcion'],
                            'estado'     => $request['estado'],
                        ]);


                        if ($devolucionFactura) {
                            $response[] = 'la factura fue devuelta con exito';
                            $status = 200;
                        } else {
                            $response[] = 'Error al modificar los datos.';
                        }
                    } catch (Exception $e) {
                        DB::rollback();
                        // print_r(json_encode($e));
                        return response()->json(["mensaje" => json_encode($e)], 400);
                    }


                }
            } else {
                $response[] = "La factura no existe.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
