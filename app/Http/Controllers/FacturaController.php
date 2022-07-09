<?php

namespace App\Http\Controllers;

use App\Models\ClientesReactivados;
use App\Models\Factura;
use App\Models\Factura_Detalle;
use App\Models\Producto;
use App\Models\ReciboHistorialContado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FacturaController extends Controller
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

        if(!is_null($request['estado'])) $parametros[] = ["status", $request['estado']];
        if(!is_null($request['tipo_venta'])) $parametros[] = ["tipo_venta", $request['tipo_venta']];
        if(!is_null($request['status_pagado'])) $parametros[] = ["status_pagado", $request['status_pagado']];
        if(!is_null($request['status_pagado'])) $parametros[] = ["status_pagado", $request['status_pagado']];
        if(!is_null($request['status_entrega'])) $parametros[] = ["entregado", $request['status_entrega']];
        if(!is_null($request['despachado'])) $parametros[] = ["despachado", $request['despachado']];
        if(!is_null($request['created_at'])){
            $created_at = Carbon::parse($request['created_at']);
            $parametros[] = ["created_at",'>=', $created_at." 00:00:00"];
        }

        // dd($facturaEstado);
        $facturas =  Factura::where($parametros)->get();

        if(count($facturas) > 0){
            foreach ($facturas as $key => $factura) {
                $factura->user;
                $factura->cliente->factura_historial;
                $factura->factura_detalle = $factura->factura_detalle()->where([
                    ['estado', '=', 1],
                ])->get();
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
        $validation = Validator::make($request->all() ,[
            'user_id'           => 'required|numeric',
            'cliente_id'        => 'required|numeric',
            'monto'             => 'required|numeric',
            'fecha_vencimiento' => 'required|date',
            'tipo_venta'        => 'required|numeric',
            'status_pagado'     => 'required|boolean',
            'iva'               => 'required|numeric',
            'despachado'        => 'required|numeric|max:1',
            'estado'            => 'required|numeric|max:1',

            'factura_detalle'                   => 'required|array',
            'factura_detalle.*.producto_id'     => 'required|numeric',
            'factura_detalle.*.cantidad'        => 'required|numeric',
            'factura_detalle.*.precio'          => 'required|numeric',
            'factura_detalle.*.precio_unidad'   => 'required|numeric',
            'factura_detalle.*.estado'          => 'required|numeric',

            'numero'       => [
                'required',
                'numeric',
                Rule::unique('recibo_historial_contado')->where(fn ($query) => $query->where('estado', 1)),
            ],
            'recibo_id'         => 'numeric|required',
            'rango'             => 'required|string',

        ],[
            'numero.unique' => 'El número de recibo ya existe en nuestra base de datos.',
        ]);

        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }


        DB::beginTransaction();
        try {
            $facturaInsert = [
                'user_id'           => $request['user_id'],
                'cliente_id'        => $request['cliente_id'],
                'monto'             => $request['monto'],
                'saldo_restante'    => $request['monto'],
                'fecha_vencimiento' => $request['fecha_vencimiento'],
                'iva'               => $request['iva'],
                'tipo_venta'        => $request['tipo_venta'],
                'status_pagado'     => $request['status_pagado'],
                'despachado'        => $request['despachado'],
                'status'            => $request['estado'],
            ];

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
                c.id = ".$request['cliente_id']; // Valido que el cliente este en la lista de clientes inactivos

            $clientesInactivos = DB::select($query);

            $factura = Factura::create($facturaInsert); // inserto factura

            if($request["tipo_venta"] == 2 ){ // si es contado
                $recibo = ReciboHistorialContado::create([ // genero recibo de factura
                    'numero'                => $request['numero'],
                    'recibo_id'             => $request['recibo_id'],
                    'factura_id'            => $factura->id,
                    'rango'                 => $request['rango'],
                    'estado'                => 1,
                ]);
            }

            $currentDate = Carbon::now('utc')->toDateTimeString();
            foreach ($request['factura_detalle'] as $key => $productoDetalle) { // cargo productos
                $producto = Producto::firstWhere('id', $productoDetalle['producto_id']);

                if(!$producto){
                    return response()->json(["mensaje"=> "El producto no existe o fue removido"], 400);
                }

                if($producto->estado == 0  ){
                    return response()->json(["mensaje" => "El producto {$producto->descripcion} esta inactivo o eliminado"], 400);
                }

                if($producto->stock < $productoDetalle["cantidad"]){
                    return response()->json(["mensaje" => "El producto {$producto->descripcion} solo posee {$producto->stock} en stock"], 400);
                }

                $producto->stock = $producto->stock - $productoDetalle['cantidad'];
                $producto->save();

                $fDetalles[] = [
                    'producto_id'       => $productoDetalle['producto_id'],
                    'factura_id'        => $factura->id,
                    'cantidad'          => $productoDetalle['cantidad'],
                    'precio'            => $productoDetalle['precio'],
                    'precio_unidad'     => $productoDetalle['precio_unidad'],
                    'created_at'        => $currentDate,
                    'updated_at'        => $currentDate,
                    'estado'            => $productoDetalle['estado']
                ];
            }


            $factura_Detalle = Factura_Detalle::insert($fDetalles); // inserto detalle de factura

            validarStatusPagadoGlobal( $request['cliente_id']); // valido si todas las facturas y ajusto en caso de que se le deba al cliente

            validarReactivacionCliente($request['user_id'],$request['cliente_id'],$factura->id, $clientesInactivos);

            DB::commit();
            return response()->json([
                "factura_id" => $factura->id,
                "status" => $factura_Detalle,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return response()->json(["mensaje" => "Error al insertar el pedido"], 400);
        }
    }

    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $response = [];
        $status = 400;
        $facturaEstado = 1; // Activo

        if(is_numeric($id)){

            // if($request->input("estado") != null) $facturaEstado = $request->input("estado");
            // dd($productoEstado);
            $factura =  Factura::with('cliente','user')->where([
                ['id', '=', $id],
                // ['estado', '=', $facturaEstado],
            ])->first();
            // validarStatusPagadoGlobal($factura->cliente->id);
            $factura->factura_detalle = $factura->factura_detalle()->where([
                ['estado', '=', 1],
            ])->get();
            $factura->cliente->factura_historial = $factura->cliente->factura_historial()->where([
                ['estado', '=', 1],
                ['debitado', '=', 1] // 0 = en proceso | 1 = Finalizado,
            ])->get();

            if(count($factura->factura_detalle)>0){
                foreach ($factura->factura_detalle as $key => $productoDetalle) {
                    $producto = Producto::find($productoDetalle["producto_id"]);

                    $productoDetalle["marca"]       = $producto->marca;
                    $productoDetalle["modelo"]      = $producto->modelo;
                    // $productoDetalle["stock"]       = $producto->stock;
                    // $productoDetalle["precio"]      = $producto->precio;
                    $productoDetalle["linea"]       = $producto->linea;
                    $productoDetalle["descripcion"] = $producto->descripcion;
                    // $productoDetalle["estado"]      = $producto->estado;
                }
            }

            // if(count($factura->factura_historial)>0){
            //     foreach ($factura->factura_historial as $key => $itemHistorial) {
            //         $user = User::find($itemHistorial["user_id"]);

            //         $itemHistorial["name"]      = $user->name;
            //         $itemHistorial["apellido"]  = $user->apellido;
            //     }
            // }



            if($factura){
                $response = $factura;
                $status = 200;

            }else{
                $response[] = "La factura no existe o fue eliminado.";
            }

        }else{
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

        if(is_numeric($id)){
            $cliente =  Factura::find($id);
            // dd($cliente->estado);
            if($cliente){
                $validation = Validator::make($request->all() ,[
                    'user_id' => 'required|numeric',
                    'cliente_id' => 'required|numeric',
                    'monto' => 'required|numeric',
                    'nruc' => 'required|string',
                    'fecha_vencimiento' => 'required|date',
                    'iva' => 'required|numeric',
                    'tcambio' => 'required|numeric',
                    'estado' => 'required|numeric|max:1',
                ]);

                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {


                    $clienteUpdate = $cliente->update([
                        'user_id' => $request['user_id'],
                        'cliente_id' => $request['cliente_id'],
                        'monto' => $request['monto'],
                        'nruc' => $request['nruc'],
                        'fecha_vencimiento' => $request['fecha_vencimiento'],
                        'iva' => $request['iva'],
                        'tcambio' => $request['tcambio'],
                        'estado' => $request['estado'],
                    ]);


                    if($clienteUpdate){
                        $response[] = 'La factura fue modificada con exito.';
                        $status = 200;

                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "La factura no existe.";
            }

        }else{
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
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $factura =  Factura::find($id);

            if($factura){
                if($factura->saldo_restante == $factura->monto){
                    $facturaDelete = $factura->update([
                        'status' => 0,
                    ]);

                    if($facturaDelete){

                        $factura->factura_detalle;
                        // print_r(json_encode($factura));
                        foreach ($factura->factura_detalle as $key => $productoDetalle) {
                            $producto =  Producto::find($productoDetalle["producto_id"]);
                            // print_r(json_encode($producto));
                            if($producto){
                                $producto->stock += $productoDetalle["cantidad"];
                                $producto->update();
                            }
                            // print_r(json_encode($producto));
                            // print_r("------------------------");
                        }
                        // print_r(json_encode($factura));

                        $response[] = 'La factura fue eliminada con exito.';
                        $status = 200;

                    }else{
                        $response[] = 'Error al eliminar la factura.';
                    }

                }else{
                    $response[] = 'La factura ya debitó un abono';
                }

            }else{
                $response[] = "La factura no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }


    public function despachar($id,Request $request)
    {
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $factura =  Factura::find($id);

            if($factura){
                if(!is_null($request['despachado'])) $factura->despachado = $request['despachado'];

                $factura->update();
                $status = 200;

                if($request['despachado'] == 1){
                    $response[] = 'La factura fue despachada con exito.';
                }else{
                    $response[] = 'La factura ha sido devuelta a la sección de facturas despachadas.';
                }

            }else{
                $response[] = "La factura no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }

    public function entregada($id)
    {
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $factura =  Factura::find($id);
            // print_r($factura);
            if($factura){
                $factura->entregado = 1;
                $factura->save();

                // print_r($factura);
                $status = 200;
                $response[] = 'La factura fue marcada como entregada.';

            }else{
                $response[] = "La factura no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }
}
