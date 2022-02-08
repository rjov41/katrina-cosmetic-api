<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [];
        $status = 200;
        $clienteEstado = 1; // Activo
        
        if($request->input()) $clienteEstado = $request->input("estado");
        
        // dd($clienteEstado);
        $clientes =  Cliente::where('estado',$clienteEstado)->get();
        
        if(count($clientes) > 0){
            foreach ($clientes as $key => $cliente) {
                // dd($cliente->frecuencias);
                $clientes->frecuencia = $cliente->frecuencia;
                $clientes->categoria = $cliente->categoria;
                $clientes->facturas = $cliente->facturas;
            }
            
            $response[] = $clientes;
        }
        
        return response()->json($clientes, $status);
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
            'categoria_id' => 'required|numeric',
            'frecuencia_id' => 'required|numeric',
            'nombre' => 'required|string|max:80',
            'apellido' => 'required|string|max:80',
            'celular' => 'required|numeric',
            'telefono' => 'required|numeric|unique:clientes,telefono',
            'direccion_casa' => 'required|string|max:180',
            'direccion_negocio' => 'required|string|max:180',
            'cedula' => 'required|string|max:22',
            'dias_cobro' => 'required|string|max:20',
            // 'fecha_vencimiento' => 'required|date',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            return response()->json($validation->errors(), 400);
        } else {
            
            $user = Cliente::create([
                'categoria_id' => $request['categoria_id'],
                'frecuencia_id' => $request['frecuencia_id'],
                'nombre' => $request['nombre'],
                'apellido' => $request['apellido'],
                'celular' => $request['celular'],
                'telefono' => $request['telefono'],
                'direccion_casa' => $request['direccion_casa'],
                'direccion_negocio' => $request['direccion_negocio'],
                'cedula' => $request['cedula'],
                'dias_cobro' => $request['dias_cobro'],
                // 'fecha_vencimiento' => $request['fecha_vencimiento'],
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
        $clienteEstado = 1; // Activo
        
        if(is_numeric($id)){
                    
            if($request->input("estado")) $clienteEstado = $request->input("estado");
        
            // dd($clienteEstado);
            $cliente =  Cliente::where([
                ['id', '=', $id],
                ['estado', '=', $clienteEstado],
            ])->first();
        
        
            // $cliente =  Cliente::find($id);
            if($cliente){
                $response = $cliente;
                $status = 200;

            }else{
                $response[] = "El cliente no existe o fue eliminado.";
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
            $cliente =  Cliente::find($id);
            
            if($cliente){ 
                $validation = Validator::make($request->all() ,[
                    'categoria_id' => 'required|numeric',
                    'frecuencia_id' => 'required|numeric',
                    'nombre' => 'required|string|max:80',
                    'apellido' => 'required|string|max:80',
                    'celular' => 'required|numeric',
                    'telefono' => 'required|numeric',
                    'direccion_casa' => 'required|string|max:180',
                    'direccion_negocio' => 'required|string|max:180',
                    'cedula' => 'required|string|max:22',
                    'dias_cobro' => 'required|string|max:20',
                    // 'fecha_vencimiento' => 'required|date',
                    'estado' => 'required|numeric|max:1',
                ]);
                
                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {

                    
                    $clienteUpdate = $cliente->update([
                        'categoria_id' => $request['categoria_id'],
                        'frecuencia_id' => $request['frecuencia_id'],
                        'nombre' => $request['nombre'],
                        'apellido' => $request['apellido'],
                        'celular' => $request['celular'],
                        'telefono' => $request['telefono'],
                        'direccion_casa' => $request['direccion_casa'],
                        'direccion_negocio' => $request['direccion_negocio'],
                        'cedula' => $request['cedula'],
                        'dias_cobro' => $request['dias_cobro'],
                        // 'fecha_vencimiento' => $request['fecha_vencimiento'],
                        'estado' => $request['estado'],
                    ]);

                    
                    if($clienteUpdate){                  
                        $response[] = 'Cliente modificado con exito.';
                        $status = 200;
                        
                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "El cliente no existe.";
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
            $cliente =  Cliente::find($id);
            
            if($cliente){ 
                $clienteDelete = $cliente->update([
                    'estado' => 0,
                ]);
                
                if($clienteDelete){                  
                    $response[] = 'El cliente fue eliminado con exito.';
                    $status = 200;
                    
                }else{
                    $response[] = 'Error al eliminar el cliente.';
                }

            }else{
                $response[] = "El cliente no existe.";
            }
            
        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }
        
        return response()->json($response, $status);
    }
}
