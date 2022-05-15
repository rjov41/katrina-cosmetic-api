<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Recibo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogisticaController extends Controller
{
    function carteraDate(Request $request)
    {
        $response = [
            'factura' => [],
            'total' => 0,
        ];

        $userId = $request['userId'];
        // "dateIni": "2022-03-15",
        // "dateFin": "2022-03-15",
        if(empty($request->dateIni)){
            $dateIni = Carbon::now();
        }else{
            $dateIni = Carbon::parse($request->dateIni);
        }

        if(empty($request->dateIni)){
            $dateFin = Carbon::now();
        }else{
            $dateFin = Carbon::parse($request->dateFin);
        }

        // DB::enableQueryLog();
        // print_r(json_encode($dateIni->toDateString()." 00:00:00"));
        if($request->allDates){
            $facturas = Factura::select("*")
            // ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
            ->where('tipo_venta', $request->tipo_venta ? $request->tipo_venta : 1) // si envian valor lo tomo, si no por defecto toma credito
            ->where('status_pagado', $request->status_pagado ? $request->status_pagado : 0) // si envian valor lo tomo, si no por defecto asigno por pagar = 0
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();
        }else{
            $facturas = Factura::select("*")
                ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
                ->where('tipo_venta', $request->tipo_venta ? $request->tipo_venta : 1) // si envian valor lo tomo, si no por defecto toma credito
                ->where('status_pagado', $request->status_pagado ? $request->status_pagado : 0) // si envian valor lo tomo, si no por defecto asigno por pagar = 0
                ->where('user_id', $userId)
                ->where('status', 1)
                ->get();
        }

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){
            $total = 0;
            foreach ($facturas as $factura) {
                $total += $factura->saldo_restante;

                $factura->user;
                $factura->cliente->factura_historial;
                $factura->factura_detalle = $factura->factura_detalle()->where([
                    ['estado', '=', 1],
                ])->get();
            }

            $response["total"]    = number_format($total, 2,".","");
            $response["factura"] = $facturas;
        }

        return response()->json($response, 200);
    }

    // recuperacion
    function reciboDate(Request $request)
    {
        $response = [
            'recibo' => [],
            'total_contado' => 0,
            'total_credito' => 0,
            'total' => 0,
        ];

        $userId = $request['userId'];
        // "dateIni": "2022-03-15",
        // "dateFin": "2022-03-15",
        if(empty($request->dateIni)){
            $dateIni = Carbon::now();
        }else{
            $dateIni = Carbon::parse($request->dateIni);
        }

        if(empty($request->dateFin)){
            $dateFin = Carbon::now();
        }else{
            $dateFin = Carbon::parse($request->dateFin);
        }

        // DB::enableQueryLog();
        // print_r(json_encode($dateIni->toDateString()." 00:00:00"));

        $recibo = Recibo::select("*")
            // ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
            ->where('user_id', $userId)
            ->where('estado', 1)
            ->first();

        // $query = DB::getQueryLog();
        // print_r(json_encode($recibos));

        if($recibo){

            $recibo->user;

            //credito
            if($request->allDates){
                $recibo->recibo_historial = $recibo->recibo_historial()->where([
                    ['estado', '=', 1],
                ])
                    ->orderBy('created_at', 'desc')
                    ->get(); // traigo los recibos credito
            }else{
                $recibo->recibo_historial = $recibo->recibo_historial()->where([
                    ['estado', '=', 1],
                ])
                ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
                ->orderBy('created_at', 'desc')
                ->get(); // traigo los recibos credito
            }

            if(count($recibo->recibo_historial)>0){
                foreach ($recibo->recibo_historial as $recibo_historial) {
                    // print_r(json_encode($recibo_historial));

                    $recibo_historial->factura_historial = $recibo_historial->factura_historial()->where([
                        ['estado', '=', 1],
                    ])->first(); // traigo los abonos de facturas de tipo credito

                    $recibo_historial->factura_historial->cliente;

                    if($recibo_historial->factura_historial){
                        $response["total_contado"] += $recibo_historial->factura_historial->precio;
                    }

                }

            }
            if($request->allDates){
                // contado
                $recibo->recibo_historial_contado = $recibo->recibo_historial_contado()->where([
                    ['estado', '=', 1],
                ])
                    ->orderBy('created_at', 'desc')
                    ->get(); // traigo los recibos de facturas contados
            }else{
                // contado
                $recibo->recibo_historial_contado = $recibo->recibo_historial_contado()->where([
                    ['estado', '=', 1],
                ])
                    ->orderBy('created_at', 'desc')
                    ->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"])
                    ->get(); // traigo los recibos de facturas contados
            }



            if(count($recibo->recibo_historial_contado)>0){
                foreach ($recibo->recibo_historial_contado as  $recibo_historial_contado) {
                    $recibo_historial_contado->factura = $recibo_historial_contado->factura()->where([
                        ['status', '=', 1],
                    ])->first(); // traigo las facturas contado //monto

                    $recibo_historial_contado->factura->cliente;

                    if($recibo_historial_contado->factura){
                        $response["total_credito"] += $recibo_historial_contado->factura->monto;
                    }
                }

            }



            $response["total_credito"] = number_format($response["total_credito"], 2,".","");
            $response["total_contado"] = number_format($response["total_contado"], 2,".","");
            $response["total"]         = number_format($response["total_contado"] + $response["total_credito"], 2,".","");
            $response["recibo"]        = $recibo;

        }
        return response()->json($response, 200);

    }

    function Mora30A60(Request $request)
    {
        $response = [
            'factura' => [],
        ];

        $fechaActual = Carbon::now();

        $facturas = Factura::select("*")
        ->where('status_pagado', 0)
        ->where('status', 1)
        ->get();

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){

            foreach ($facturas as $factura) {
                $fechaPasado30DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(30)->toDateTimeString();
                $fechaPasado60DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(60)->toDateTimeString();
                // $dt->addDays(29);

                // var_dump($fechaActual->gte($fechaPasado30DiasVencimiento));
                // printf(" $fechaActual ===== $fechaPasado30DiasVencimiento \n");
                // printf(" $fechaActual ===== $fechaPasado60DiasVencimiento \n");
                // printf(" ------------------------------------------------------------------------------- \n");

                if($fechaActual->gte($fechaPasado30DiasVencimiento) && $fechaActual->lte($fechaPasado60DiasVencimiento)){
                    // && $fechaActual->lte($fechaPasado60DiasVencimiento)
                    // printf("%s\n fecha->Vencimiento:::", $fechaPasado60DiasVencimiento);
                    // printf("%s\n fecha->60:::", $fechaPasado60DiasVencimiento);

                    $factura->user;
                    $factura->cliente;
                    $factura->vencimiento30 = $fechaPasado30DiasVencimiento;
                    $factura->vencimiento60 = $fechaPasado60DiasVencimiento;
                    $factura->diferenciaDias = Carbon::parse($factura->fecha_vencimiento)->diffInDays($fechaActual);
                    array_push($response["factura"],$factura);
                }
            }
        }

        return response()->json($response, 200);
    }

    function Mora60A90()
    {
        $response = [
            'factura' => [],
        ];

        $fechaActual = Carbon::now();

        $facturas = Factura::select("*")
        ->where('status_pagado', 0)
        ->where('status', 1)
        ->get();

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){

            foreach ($facturas as $factura) {
                $fechaPasado60DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(60)->toDateTimeString();
                $fechaPasado90DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(90)->toDateTimeString();
                // $dt->addDays(29);

                // var_dump($fechaActual->gte($fechaPasado90DiasVencimiento));
                // printf(" $fechaActual ===== $fechaPasado90DiasVencimiento \n");
                // printf(" $fechaActual ===== $fechaPasado60DiasVencimiento \n");
                // printf(" ------------------------------------------------------------------------------- \n");

                if($fechaActual->gte($fechaPasado60DiasVencimiento) && $fechaActual->lte($fechaPasado90DiasVencimiento)){
                    // && $fechaActual->lte($fechaPasado60DiasVencimiento)
                    // printf("%s\n fecha->Vencimiento:::", $fechaPasado60DiasVencimiento);
                    // printf("%s\n fecha->60:::", $fechaPasado60DiasVencimiento);

                    $factura->user;
                    $factura->cliente;
                    $factura->vencimiento60  = $fechaPasado60DiasVencimiento;
                    $factura->vencimiento90  = $fechaPasado90DiasVencimiento;
                    $factura->diferenciaDias = Carbon::parse($factura->fecha_vencimiento)->diffInDays($fechaActual);
                    array_push($response["factura"],$factura);
                }
            }
        }

        return response()->json($response, 200);
    }


    function ventasDAte()
    {
        $response = [];
    }
}
