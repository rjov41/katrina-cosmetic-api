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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'celular' => 'required|numeric',
            'telefono' => 'required|numeric',
            'direccion_casa' => 'required|string|max:180',
            'direccion_negocio' => 'required|string|max:180',
            'cedula' => 'required|string|max:22',
            'dias_cobro' => 'required|string|max:20',
            'fecha_vencimiento' => 'required|date',
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
                'celular' => $request['celular'],
                'telefono' => $request['telefono'],
                'direccion_casa' => $request['direccion_casa'],
                'direccion_negocio' => $request['direccion_negocio'],
                'cedula' => $request['cedula'],
                'dias_cobro' => $request['dias_cobro'],
                'fecha_vencimiento' => $request['fecha_vencimiento'],
                'estado' => $request['estado'],
            ]);
            
            return response()->json([
                'success' => 'Usuario Insertado con exito',
                'data' =>[
                    'id' => $user->id,
                ]
            ], 201);
        }
        


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
