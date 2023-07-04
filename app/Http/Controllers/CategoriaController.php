<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
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
        $clienteEstado = 1; // Activo

        // if($request->input() != null) $clienteEstado = $request->input("estado");

        // dd($clienteEstado);
        $cliente =  Categoria::where('estado',$clienteEstado)->get();

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
        $response = [];
        $status = 400;

        $validation = Validator::make($request->all() ,[
            'tipo' => 'required|string|unique:categorias,tipo',
            'descripcion' => 'required|string',
            // 'valor_dias' => 'required|numeric',
            'monto_menor' => 'required|numeric',
            'monto_maximo' => 'required|numeric',
            'condicion' => 'required|numeric',
            'estado' => 'required|numeric|max:1',
        ]);
        // dd($request->all());
        // dd($validation->errors());
        if($validation->fails()) {
            $response[] =  $validation->errors();
        } else {

            $categoria = Categoria::create([
                'tipo' => $request['tipo'],
                'descripcion' => $request['descripcion'],
                // 'valor_dias' => $request['valor_dias'],
                'monto_menor' => $request['monto_menor'],
                'monto_maximo' => $request['monto_maximo'],
                'condicion' => $request['condicion'],
                'estado' => $request['estado'],
            ]);

            $response['id'] =  $categoria->id;
            $status = 201;
        }
        return response()->json($response, $status);
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

            // if($request->input("estado") != null) $clienteEstado = $request->input("estado");

            // dd($clienteEstado);
            $categoria =  Categoria::where([
                ['id', '=', $id],
                // ['estado', '=', $clienteEstado],
            ])->first();


            // $cliente =  Cliente::find($id);
            if($categoria){
                $response = $categoria;
                $status = 200;

            }else{
                $response[] = "La categoria no existe o fue eliminado.";
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
            $categoria =  Categoria::find($id);

            if($categoria){
                $validation = Validator::make($request->all() ,[
                    'tipo' => 'required|string|unique:categorias,tipo,'.$id,
                    'descripcion' => 'required|string',
                    'monto_menor' => 'required|numeric',
                    'monto_maximo' => 'required|numeric',
                    'condicion' => 'required|numeric',
                    // 'valor_dias' => 'required|numeric',
                    'estado' => 'required|numeric|max:1',
                ]);

                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {


                    $categoriaUpdate = $categoria->update([
                        'tipo' => $request['tipo'],
                        'descripcion' => $request['descripcion'],
                        // 'valor_dias' => $request['valor_dias'],
                        'monto_menor' => $request['monto_menor'],
                        'monto_maximo' => $request['monto_maximo'],
                        'condicion' => $request['condicion'],
                        'estado' => $request['estado'],
                    ]);


                    if($categoriaUpdate){
                        $response[] = 'Categoria modificada con exito.';
                        $status = 200;

                    }else{
                        $response[] = 'Error al modificar los datos.';
                    }

                }

            }else{
                $response[] = "La categoria no existe.";
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
            $cliente =  Categoria::find($id);

            if($cliente){
                $clienteDelete = $cliente->update([
                    'estado' => 0,
                ]);

                if($clienteDelete){
                    $response[] = 'La Categoria fue eliminado con exito.';
                    $status = 200;

                }else{
                    $response[] = 'Error al eliminar la categoria.';
                }

            }else{
                $response[] = "La categoria no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);
    }
}
