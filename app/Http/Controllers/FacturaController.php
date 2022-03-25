<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Factura_Detalle;
use App\Models\FacturaHistorial;
use App\Models\Producto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $facturaEstado = 1; // Activo
        $parametros = [];

        if(!is_null($request['estado'])) $parametros[] = ["status", $request['estado']];
        if(!is_null($request['tipo_venta'])) $parametros[] = ["tipo_venta", $request['tipo_venta']];
        if(!is_null($request['status_pagado'])) $parametros[] = ["status_pagado", $request['status_pagado']];

        // dd($facturaEstado);
        $facturas =  Factura::where($parametros)->get();

        if(count($facturas) > 0){
            foreach ($facturas as $key => $factura) {
                $factura->user;
                $factura->cliente;
                $factura->factura_detalle;
                $factura->factura_historial;
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
            'estado'            => 'required|numeric|max:1',

            'factura_detalle'               => 'required|array',
            'factura_detalle.*.producto_id' => 'required|numeric',
            'factura_detalle.*.cantidad'    => 'required|numeric',
            'factura_detalle.*.precio'      => 'required|numeric',
            'factura_detalle.*.estado'      => 'required|numeric',
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
                'fecha_vencimiento' => $request['fecha_vencimiento'],
                'iva'               => $request['iva'],
                'tipo_venta'        => $request['tipo_venta'],
                'status_pagado'     => $request['status_pagado'],
                'status'            => $request['estado'],
            ];
            $factura = Factura::create($facturaInsert); // inserto factura

            $currentDate = Carbon::now('utc')->toDateTimeString();
            foreach ($request['factura_detalle'] as $key => $productoDetalle) {
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

                    'producto_id' => $productoDetalle['producto_id'],
                    'factura_id'  => $factura->id,
                    'cantidad'    => $productoDetalle['cantidad'],
                    'precio'      => $productoDetalle['precio'],
                    'created_at'  => $currentDate,
                    'updated_at'  => $currentDate,
                    'estado'      => $productoDetalle['estado']
                ];
            }


            $factura_Detalle = Factura_Detalle::insert($fDetalles); // inserto detalle de factura

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

            $factura =  Factura::with('factura_detalle','cliente','factura_historial')->where([
                ['id', '=', $id],
                // ['estado', '=', $facturaEstado],
            ])->first();

            if(count($factura->factura_detalle)>0){
                foreach ($factura->factura_detalle as $key => $productoDetalle) {
                    $producto = Producto::find($productoDetalle["producto_id"]);
                    // dd($productoDetalle["id"]);
                    $productoDetalle["marca"]       = $producto->marca;
                    $productoDetalle["modelo"]      = $producto->modelo;
                    // $productoDetalle["stock"]       = $producto->stock;
                    // $productoDetalle["precio"]      = $producto->precio;
                    $productoDetalle["linea"]       = $producto->linea;
                    $productoDetalle["descripcion"] = $producto->descripcion;
                    // $productoDetalle["estado"]      = $producto->estado;
                }
            }

            if(count($factura->factura_historial)>0){
                foreach ($factura->factura_historial as $key => $itemHistorial) {
                    $user = User::find($itemHistorial["user_id"]);

                    $itemHistorial["name"]      = $user->name;
                    $itemHistorial["apellido"]  = $user->apellido;
                }
            }



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
                $facturaDelete = $factura->update([
                    'status' => 0,
                ]);

                if($facturaDelete){
                    $response[] = 'La factura fue eliminada con exito.';
                    $status = 200;

                }else{
                    $response[] = 'Error al eliminar la factura.';
                }

            }else{
                $response[] = "La factura no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }
}
