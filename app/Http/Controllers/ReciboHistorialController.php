<?php

namespace App\Http\Controllers;

use App\Models\FacturaHistorial;
use App\Models\ReciboHistorial;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ReciboHistorialController extends Controller
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

        // DB::enableQueryLog();

        // if(!is_null($request['userId']))       $parametros[] = ["estado", $request['userId']];
        if(!is_null($request['estado']))       $parametros[] = ["estado", $request['estado']];
        if(!is_null($request['numeroRecibo']) && !empty($request['numeroRecibo'])) $parametros[] = ["numero", $request['numeroRecibo']];

        if($request->allDates == "false"){
            $recibos =  ReciboHistorial::where($parametros)
            ->whereBetween('created_at', [$request['dateIni']." 00:00:00",  $request['dateFin']." 23:59:59"])
            ->get();
            // if(!is_null($request['dateIni']))      $parametros[] = ["created_at", ">=" ,$request['dateIni']];
            // if(!is_null($request['dateFin']))      $parametros[] = ["created_at" ,"<=",$request['dateFin']];
        }else{
            $recibos =  ReciboHistorial::where($parametros)->get();
        }
        // dd($facturaEstado);
        // $query = DB::getQueryLog();
        // dd($query);
        if(count($recibos) > 0){
            foreach ($recibos as $recibo) {
                $recibo->recibo->user;
                $recibo->factura_historial->cliente;
                // $recibo->created_at = Carbon::parse($recibo->created_at)->toDateString();
                // $recibo->updated_at = Carbon::parse($recibo->updated_at)->toDateString();
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
            'user_id'        => 'required|numeric|unique:recibos,user_id',
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
                $response = ReciboHistorial::create([
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
        $response = [];
        $status = 400;
        // $clienteEstado = 1; // Activo
        // User::whereHas("roles", function($q){ $q->where("name", "admin"); })->get()

        if(is_numeric($id)){

            // if($request->input("estado") !== null) $clienteEstado = $request->input("estado");

            // dd($clienteEstado);
            $recibo =  ReciboHistorial::where([
                ['id', '=', $id],
                // ['estado', '=', $clienteEstado],
            ])->first();



            // $cliente =  Cliente::find($id);
            if($recibo){
                $recibo->recibo;
                $recibo->factura_historial;

                $response = $recibo;
                $status = 200;

            }else{
                $response[] = "El recibo no existe o fue eliminado.";
            }

        }else{
            $response[] = "El recibo de Id debe ser numerico.";
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
            $recibo =  ReciboHistorial::find($id);

            if($recibo){
                $validation = Validator::make($request->all() ,[
                    'numero'       => 'required|numeric',
                    'recibo_id'           => 'numeric|required',
                    'factura_historial_id'           => 'numeric|required',
                    'rango'             => 'required|string',
                    'estado'        => 'required|numeric|max:1',
                ]);

                if($validation->fails()) {
                    $response[] = $validation->errors();
                } else {
                    if($this->validNumberRange($request['min'],$request['max'],$id)){
                        $reciboUpdate = $recibo->update([
                            'numero' => $request['numero'],
                            'recibo_id' => $request['recibo_id'],
                            'factura_historial_id' => $request['factura_historial_id'],
                            'rango' => $request['rango'],
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
        $response = [];
        $status = 400;

        if(is_numeric($id)){
            $recibo =  ReciboHistorial::find($id);

            if($recibo){

                DB::beginTransaction();
                try {
                    $reciboDelete = $recibo->update([
                        'estado' => 0,
                    ]);
                    if($reciboDelete){
                        $abono = FacturaHistorial::where('id',$recibo->factura_historial_id)->first();
                        $abono->update([
                            'estado' => 0,
                        ]);
                        // print_r(json_encode($abono));

                        validarStatusPagadoGlobal($abono->cliente_id);

                        $response[] = 'El recibo fue eliminado con exito.';
                        $status = 200;
                    }else{
                        $response[] = 'Error al eliminar el recibo.';
                    }
                    // DB::rollback();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    // print_r(json_encode($e));
                    return response()->json(["mensaje" => json_encode($e)], 400);
                }


            }else{
                $response[] = "El recibo no existe.";
            }

        }else{
            $response[] = "El Valor de Id debe ser numerico.";
        }

        return response()->json($response, $status);

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
