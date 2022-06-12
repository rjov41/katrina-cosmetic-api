<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Frecuencia;
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
        $reciboStore = Recibo::select("*")
            ->where('estado', 1)
            ->where('user_id', $userId);

        $recibo = $reciboStore->first();

        // $query = DB::getQueryLog();
        // print_r(json_encode($recibos));

        if($recibo){

            $recibo->user;

            //temporal
            $reciboHistorial = $recibo->recibo_historial()->where([
                ['estado', '=', 1],
            ])
                ->orderBy('created_at', 'desc');
            // print(count($reciboHistorial->get()));

            if(!$request->allDates){
                $reciboHistorial = $reciboHistorial->whereBetween('created_at', [$dateIni->toDateString(),  $dateFin->toDateString()]);
            }

            if(!$request->allNumber){
                if ($request->numRecibo != 0) {
                    $reciboHistorial = $reciboHistorial->where('numero', '>=', $request->numRecibo);
                }
            }

            $recibo->recibo_historial = $reciboHistorial->get();

            if(count($recibo->recibo_historial)>0){
                foreach ($recibo->recibo_historial as $recibo_historial) {
                    // print_r(json_encode($recibo_historial));

                    $recibo_historial->factura_historial = $recibo_historial->factura_historial()->where([
                        ['estado', '=', 1],
                    ])->first(); // traigo los abonos de facturas de tipo credito

                    $recibo_historial->factura_historial->cliente;
                    $recibo_historial->factura_historial->metodo_pago;

                    if($recibo_historial->factura_historial->metodo_pago){
                        $recibo_historial->factura_historial->metodo_pago->tipoPago = $recibo_historial->factura_historial->metodo_pago->getTipoPago();
                    }

                    if($recibo_historial->factura_historial){
                        $response["total_contado"] += $recibo_historial->factura_historial->precio;
                    }

                }

            }

            ///////////////// Contado (factura) /////////////////////////////

            $recibo_historial_contado = $recibo->recibo_historial_contado()->where([
                ['estado', '=', 1],
            ]);

            if(!$request->allDates){
                $recibo_historial_contado = $recibo_historial_contado->whereBetween('created_at', [$dateIni->toDateString(),  $dateFin->toDateString()]);
            }

            if(!$request->allNumber){
                if ($request->numRecibo != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->where('numero', '>=', $request->numRecibo);
                }
            }

            if(!$request->allNumber){
                if ($request->numDesde != 0 && $request->numHasta != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->whereBetween('numero', [$request->numDesde, $request->numHasta]);
                }else if ($request->numDesde != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->where('numero', '=', $request->numDesde);
                }
            }

            $recibo->recibo_historial_contado = $recibo_historial_contado->get();

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
        $userId = $request['userId'];
        $fechaActual = Carbon::now();

        if($request->allUsers){

            $facturas = Factura::select("*")
            ->where('status_pagado', 0)
            ->where('status', 1)
            ->get();
        }else{
            $facturas = Factura::select("*")
            ->where('status_pagado', 0)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();
        }

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){

            foreach ($facturas as $factura) {
                // $fechaPasado30DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(30)->toDateTimeString();
                // $fechaPasado60DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(60)->toDateTimeString();
                $fechaPasado30DiasVencimiento = Carbon::parse($factura->created_at)->addDays(30)->toDateTimeString();
                $fechaPasado60DiasVencimiento = Carbon::parse($factura->created_at)->addDays(60)->toDateTimeString();

                if($fechaActual->gte($fechaPasado30DiasVencimiento) && $fechaActual->lte($fechaPasado60DiasVencimiento)){
                    $factura->user;
                    $factura->cliente;
                    $factura->vencimiento30 = $fechaPasado30DiasVencimiento;
                    $factura->vencimiento60 = $fechaPasado60DiasVencimiento;

                    // $factura->diferenciaDias = Carbon::parse($factura->fecha_vencimiento)->diffInDays($fechaActual);
                    $factura->diferenciaDias = Carbon::parse($factura->created_at)->diffInDays($fechaActual);

                    array_push($response["factura"],$factura);
                }
            }
        }

        return response()->json($response, 200);
    }

    function Mora60A90(Request $request)
    {
        $response = [
            'factura' => [],
        ];

        $userId = $request['userId'];
        $fechaActual = Carbon::now();

        if($request->allUsers){

            $facturas = Factura::select("*")
            ->where('status_pagado', 0)
            ->where('status', 1)
            ->get();
        }else{
            $facturas = Factura::select("*")
            ->where('status_pagado', 0)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->get();
        }

        // $query = DB::getQueryLog();
        // dd($query);

        if(count($facturas) > 0){

            foreach ($facturas as $factura) {

                // $fechaPasado60DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(60)->toDateTimeString();
                // $fechaPasado90DiasVencimiento = Carbon::parse($factura->fecha_vencimiento)->addDays(90)->toDateTimeString();
                $fechaPasado60DiasVencimiento = Carbon::parse($factura->created_at)->addDays(60)->toDateTimeString();
                $fechaPasado90DiasVencimiento = Carbon::parse($factura->created_at)->addDays(90)->toDateTimeString();

                if($fechaActual->gte($fechaPasado60DiasVencimiento) && $fechaActual->lte($fechaPasado90DiasVencimiento)){
                    $factura->user;
                    $factura->cliente;
                    $factura->vencimiento60  = $fechaPasado60DiasVencimiento;
                    $factura->vencimiento90  = $fechaPasado90DiasVencimiento;

                    // $factura->diferenciaDias = Carbon::parse($factura->fecha_vencimiento)->diffInDays($fechaActual);
                    $factura->diferenciaDias = Carbon::parse($factura->created_at)->diffInDays($fechaActual);

                    array_push($response["factura"],$factura);
                }
            }
        }

        return response()->json($response, 200);
    }


    function clienteDate(Request $request)
    {
        $response = [];


        // $userId = $request['userId'];
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
        $clienteStore = Cliente::select("*")->where('estado', 1);

        if(!$request->allDates){
            $clienteStore = $clienteStore->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"]);
        }

        if($request->userId != 0){
            $clienteStore = $clienteStore->where('user_id', $request->userId);
        }

        $clientes = $clienteStore->get();

        // $query = DB::getQueryLog();
        // print_r(json_encode($query));
        if (count($clientes) > 0) {
            foreach ($clientes as $cliente) {
                $clientes->frecuencia = $cliente->frecuencia;
                $clientes->categoria = $cliente->categoria;
                $clientes->facturas = $cliente->facturas;
            }

            $response = $clientes;
        }


        return response()->json($response, 200);
    }

    function clienteInactivo(Request $request)
    {
        $response = [];

        // if(empty($request->dateIni)){
        //     $dateIni = Carbon::now();
        // }else{
        //     $dateIni = Carbon::parse($request->dateIni);
        // }

        // if(empty($request->dateFin)){
        //     $dateFin = Carbon::now();
        // }else{
        //     $dateFin = Carbon::parse($request->dateFin);
        // }

        // DB::enableQueryLog();

        $query = "SELECT
            c.*,
            q.cantidad_factura,
            q.cantidad_finalizadas,
            if(q.cantidad_factura = q.cantidad_finalizadas, 1, 0) AS cliente_inactivo
            FROM clientes c
            INNER JOIN (
                SELECT
                    c.id AS cliente_id,
                    c.user_id AS user_id,
                    COUNT(c.id) AS cantidad_factura,
                    SUM(if(f.status_pagado = 1, 1, 0)) AS cantidad_finalizadas
                FROM clientes c
                INNER JOIN facturas f ON c.id = f.cliente_id
                WHERE  f.`status` = 1
                GROUP BY c.id
                ORDER BY c.id ASC
            )q ON c.id = q.cliente_id
            WHERE
                q.cantidad_factura = q.cantidad_finalizadas
        ";

        if($request->userId != 0){
            $query = $query." AND c.user_id = ".$request->userId;
        }

        $clientes = DB::select($query);

        if (count($clientes)>0) {
            foreach ($clientes as $cliente) {
                $cliente->frecuencia = Frecuencia::find($cliente->frecuencia_id);
                $cliente->categoria = Categoria::find($cliente->categoria_id);
                $cliente->user = User::find($cliente->user_id);
            }

            $response = $clientes;
        }

        // print_r(count($cliente));
        return response()->json($response, 200);
    }


    // recuperacion
    function incentivo(Request $request)
    {
        $response = [
            'recibo' => [],
            'total_contado' => 0,
            'total_credito' => 0,
            'porcentaje20' => 0,
            'total' => 0,
        ];

        $userId = $request['userId'];
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
        $reciboStore = Recibo::select("*")
            ->where('estado', 1)
            ->where('user_id', $userId);

        $recibo = $reciboStore->first();

        // $query = DB::getQueryLog();
        // print_r(json_encode($recibos));

        if($recibo){

            $recibo->user;

            //temporal
            $reciboHistorial = $recibo->recibo_historial()->where([
                ['estado', '=', 1],
            ])
                ->orderBy('created_at', 'desc');
            // print(count($reciboHistorial->get()));

            if(!$request->allDates){
                $reciboHistorial = $reciboHistorial->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"]);
            }

            if(!$request->allNumber){
                if ($request->numRecibo != 0) {
                    $reciboHistorial = $reciboHistorial->where('numero', '>=', $request->numRecibo);
                }
            }

            $recibo->recibo_historial = $reciboHistorial->get();

            if(count($recibo->recibo_historial)>0){
                foreach ($recibo->recibo_historial as $recibo_historial) {
                    // print_r(json_encode($recibo_historial));

                    $recibo_historial->factura_historial = $recibo_historial->factura_historial()->where([
                        ['estado', '=', 1],
                    ])->first(); // traigo los abonos de facturas de tipo credito

                    $recibo_historial->factura_historial->cliente;
                    $recibo_historial->factura_historial->metodo_pago;

                    if($recibo_historial->factura_historial->metodo_pago){
                        $recibo_historial->factura_historial->metodo_pago->tipoPago = $recibo_historial->factura_historial->metodo_pago->getTipoPago();
                    }

                    if($recibo_historial->factura_historial){
                        $response["total_contado"] += $recibo_historial->factura_historial->precio;
                    }

                }

            }

            ///////////////// Contado (factura) /////////////////////////////

            $recibo_historial_contado = $recibo->recibo_historial_contado()->where([
                ['estado', '=', 1],
            ]);

            if(!$request->allDates){
                $recibo_historial_contado = $recibo_historial_contado->whereBetween('created_at', [$dateIni->toDateString()." 00:00:00",  $dateFin->toDateString()." 23:59:59"]);
            }

            if(!$request->allNumber){
                if ($request->numRecibo != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->where('numero', '>=', $request->numRecibo);
                }
            }

            if(!$request->allNumber){
                if ($request->numDesde != 0 && $request->numHasta != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->whereBetween('numero', [$request->numDesde, $request->numHasta]);
                }else if ($request->numDesde != 0) {
                    $recibo_historial_contado = $recibo_historial_contado->where('numero', '=', $request->numDesde);
                }
            }

            $recibo->recibo_historial_contado = $recibo_historial_contado->get();

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
            $response["porcentaje20"]  = number_format($response["total"] * 0.20 , 2,".","");

            $response["recibo"]        = $recibo;

        }
        return response()->json($response, 200);

    }

    function estadoCuenta(Request $request)
    {
        $response = queryEstadoCuenta($request->cliente_id);
        $response["cliente"] = Cliente::find($request->cliente_id);

        return response()->json($response, 200);
    }


}
