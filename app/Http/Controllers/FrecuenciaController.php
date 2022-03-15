<?php

namespace App\Http\Controllers;

use App\Models\Frecuencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FrecuenciaController extends Controller
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
        $frecuenciaEstado = 1; // Activo
        
        // if($request->input() != null) $frecuenciaEstado = $request->input("estado");
        
        // dd($clienteEstado);
        $cliente =  Frecuencia::where('estado',$frecuenciaEstado)->get();
        
        if(count($cliente) > 0){
            $response[] = $cliente;
        }
        
        return response()->json($cliente, $status);
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
            'descripcion' => 'required|string',
            'dias' => 'required|numeric',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {
            
            $frecuencia = Frecuencia::create([
                'descripcion' => $request['descripcion'],
                'dias' => $request['dias'],
                'estado' => $request['estado'],
            ]);
            
            return response()->json([
                // 'success' => 'Usuario Insertado con exito',
                // 'data' =>[
                    'id' => $frecuencia->id,
                // ]
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $response = [];
        $status = 400;
        $frecuenciaEstado = 1; // Activo
        
        if(is_numeric($id)){
                    
            // if($request->input("estado") != null) $frecuenciaEstado = $request->input("estado");
        
            // dd($clienteEstado);
            $frecuencia =  Frecuencia::where([
                ['id', '=', $id],
                // ['estado', '=', $frecuenciaEstado],
            ])->first();
        
        
            // $cliente =  Cliente::find($id);
            if($frecuencia){
                $response = $frecuencia;
                $status = 200;

            }else{
                $response[] = "La frecuencia no existe o fue eliminado.";
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
            $categoria =  Frecuencia::find($id);
            
            if($categoria){ 
                $validation = Validator::make($request->all() ,[
                    'descripcion' => 'required|string',
                    'dias' => 'required|numeric',
                    'estado' => 'required|numeric|max:1',
                ]);
                
                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    
                    $categoriaUpdate = $categoria->update([
                        'descripcion' => $request['descripcion'],
                        'dias' => $request['dias'],
                        'estado' => $request['estado'],
                    ]);

                    
                    if($categoriaUpdate){                  
                        $response[] = 'Frecuencia modificada con exito.';
                        $status = 200;
                        
                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "La Frecuencia no existe.";
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
            $frecuencia =  Frecuencia::find($id);
            
            if($frecuencia){ 
                $frecuenciaDelete = $frecuencia->update([
                    'estado' => 0,
                ]);
                
                if($frecuenciaDelete){                  
                    $response[] = 'La frecuencia fue eliminado con exito.';
                    $status = 200;
                    
                }else{
                    $response[] = 'Error al eliminar la categoria.';
                }

            }else{
                $response[] = "La frecuencia no existe.";
            }
            
        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }
        
        return response()->json($response, $status);
    }
}
