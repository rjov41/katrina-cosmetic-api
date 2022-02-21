<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Factura_Detalle;
use Illuminate\Http\Request;
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
        
        if(!is_null($request['estado'])) $facturaEstado = $request['estado'];
        
        // dd($facturaEstado);
        $facturas =  Factura::where('status',$facturaEstado)->get();
        
        if(count($facturas) > 0){
            foreach ($facturas as $key => $factura) {
                $factura->user;
                $factura->cliente;
                $factura->factura_detalle;
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
            'user_id' => 'required|numeric',
            'cliente_id' => 'required|numeric',
            'monto' => 'required|numeric',
            'nruc' => 'required|string',
            'fecha_vencimiento' => 'required|date',
            'iva' => 'required|numeric',
            'tcambio' => 'required|numeric',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {
            
            $user = Factura::create([
                'user_id' => $request['user_id'],
                'cliente_id' => $request['cliente_id'],
                'monto' => $request['monto'],
                'nruc' => $request['nruc'],
                'fecha_vencimiento' => $request['fecha_vencimiento'],
                'iva' => $request['iva'],
                'tcambio' => $request['tcambio'],
                'estado' => $request['estado'],
            ]);
            
            return response()->json([
                // 'success' => 'Usuario Insertado con exito',
                // 'data' =>[
                    'id' => $user->id,
                // ]
            ], 201);
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
        
            $factura =  Factura::with('factura_detalle')->where([
                ['id', '=', $id],
                // ['estado', '=', $facturaEstado],
            ])->get();
            
            $factura_detalle =  Factura::find($id)->factura_detalle->first();
            $factura->factura_detalle = $factura_detalle;
            // App\Post::find(1)->comments()->where('title', 'foo')->first()
            // Factura_Detalle::with('factura')->get();
        
            // $cliente =  Cliente::find($id);
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
                    'estado' => 0,
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
