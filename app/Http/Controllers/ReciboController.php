<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReciboController extends Controller
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
        $parametros = [];

        if(!is_null($request['estado'])) $parametros[] = ["estado", $request['estado']];
        if(!is_null($request['recibo_cerrado'])) $parametros[] = ["recibo_cerrado", $request['recibo_cerrado']];


        // dd($facturaEstado);
        $recibos =  Recibo::where($parametros)->get();

        if(count($recibos) > 0){
            foreach ($recibos as $recibo) {
                $recibo->user;

            }

            $response = $recibos;
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
        $validation = Validator::make($request->all(), [
            'user_id'        => 'required|numeric',
            'max'            => 'numeric|required',
            'min'            => 'numeric|required',
            'recibo_cerrado' => 'required|numeric|max:1',
            'estado'         => 'required|numeric|max:1',
        ]);
        if ($validation->fails()) {
            return response()->json([$validation->errors()], 400);
        } else {
            $response = array();
            $error = 401;

            if($this->validNumberRange($request['min'],$request['max'],false)){
                $response = Recibo::create([
                    'max' => $request['max'],
                    'min' => $request['min'],
                    'user_id' => $request['user_id'],
                    'estado' => $request['estado'],
                ]);
                $error = 201;
            }else{
                $response[] = "El rango numerico del recibo ya coincide con uno existente.";
                $error = 401;
            }


            return response()->json($response, $error);
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
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $recibo =  Recibo::find($id);

            if($recibo){
                $validation = Validator::make($request->all() ,[
                    'user_id'        => 'required|numeric',
                    'max'            => 'numeric|required',
                    'min'            => 'numeric|required',
                    'recibo_cerrado' => 'required|numeric|max:1',
                    'estado'         => 'required|numeric|max:1',
                ]);

                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {
                    if($this->validNumberRange($request['min'],$request['max'],$id)){
                        $reciboUpdate = $recibo->update([
                            'max' => $request['max'],
                            'min' => $request['min'],
                            'user_id' => $request['user_id'],
                            'estado' => $request['estado'],
                        ]);

                        if($reciboUpdate){
                            $response = $recibo;
                            $status = 200;

                        }else{
                            $response[] = 'Error al modificar los datos.';
                        }
                    }else{
                        $response[] = "El rango numerico del recibo ya coincide con uno existente.";
                    }
                }

            }else{
                $response[] = "El Recibo no existe.";
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
        //
    }


    private function validNumberRange($min,$max,$id){

        if($id){
            $minimo = DB::table('recibos')->where('id',"!=",$id)->whereBetween('min', [$min, $max])->get();
            $maximo = DB::table('recibos')->where('id',"!=",$id)->whereBetween('max', [$min, $max])->get();
        }else{
            $minimo = DB::table('recibos')->whereBetween('min', [$min, $max])->get();
            $maximo = DB::table('recibos')->whereBetween('max', [$min, $max])->get();
        }

        // print_r (json_encode($minimo));
        // print_r (json_encode($maximo));
        if(count($minimo) == 0 && count($maximo) == 0){
            return true;
        }

        return false;
    }
}
