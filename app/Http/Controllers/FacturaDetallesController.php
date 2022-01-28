<?php

namespace App\Http\Controllers;

use App\Models\Factura_Detalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacturaDetallesController extends Controller
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
        // $facturaDetalleEstado = 1; // Activo
        
        // if($request->input("estado") != "null") $facturaDetalleEstado = $request->input("estado");
        
        // dd($clienteEstado);
        $facturaDetalle =  Factura_Detalle::all();
        
        if(count($facturaDetalle) > 0){
            $response[] = $facturaDetalle;
        }
        
        return response()->json($facturaDetalle, $status);
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
            'producto_id' => 'required|numeric',
            'factura_id' => 'required|numeric',
            'cantidad' => 'required|numeric',
            'precio' => 'required|numeric',
            'porcentaje' => 'required|numeric',

        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {
            
            $user = Factura_Detalle::create([
                'producto_id' => $request['producto_id'],
                'factura_id' => $request['factura_id'],
                'cantidad' => $request['cantidad'],
                'precio' => $request['precio'],
                'porcentaje' => $request['porcentaje'],
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
        // $productoEstado = 1; // Activo
        
        if(is_numeric($id)){
                    
            // if($request->input("estado") != null) $productoEstado = $request->input("estado");
            // dd($productoEstado);
        
            $producto =  Factura_Detalle::find($id);
        
        
            // $cliente =  Cliente::find($id);
            if($producto){
                $response = $producto;
                $status = 200;

            }else{
                $response[] = "El detalle no existe o fue eliminado.";
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
            $producto =  Factura_Detalle::find($id);
            
            if($producto){ 
                $validation = Validator::make($request->all() ,[
                    'producto_id' => 'required|numeric',
                    'factura_id' => 'required|numeric',
                    'cantidad' => 'required|numeric',
                    'precio' => 'required|numeric',
                    'porcentaje' => 'required|numeric',
                ]);
                
                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    
                    $productoUpdate = $producto->update([
                        'producto_id' => $request['producto_id'],
                        'factura_id' => $request['factura_id'],
                        'cantidad' => $request['cantidad'],
                        'precio' => $request['precio'],
                        'porcentaje' => $request['porcentaje'],
                    ]);

                    
                    if($productoUpdate){                  
                        $response[] = 'El detalle fue modificado con exito.';
                        $status = 200;
                        
                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "El detalle no existe.";
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
        // $response = [];
        // $status = 400;
        
        // if(is_numeric($id)){
        //     $producto =  Factura_Detalle::find($id);
        //     // dd($producto);
        //     if(!empty($producto)){ 
        //         $productoDelete = $producto->update([
        //             'estado' => 0,
        //         ]);
                
        //         if($productoDelete){                  
        //             $response[] = 'El producto fue eliminado con exito.';
        //             $status = 200;
                    
        //         }else{
        //             $response[] = 'Error al eliminar el producto.';
        //         }

        //     }else{
        //         $response[] = "El producto no existe.";
        //     }
            
        // }else{
        //     $response[] = "El Valor de Id debe ser numerico.";
        // }
        
        // return response()->json($response, $status);
    }
}
