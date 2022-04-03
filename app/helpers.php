<?php

use App\Models\Cliente;

    function validarStatusPagadoGlobal($clienteID){

        $cliente = Cliente::find($clienteID);

        $cliente->factura = $cliente->facturas()->where([
            ['status', '=', 1],
            ['tipo_venta', '=', 1]  // 1 = Credito | 2 = Contado,
            // ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
        ])->get();

        $cliente->factura_historial = $cliente->factura_historial()->where([
            ['estado', '=', 1],
            // ['debitado', '=', 0] // 0 = aun no usado el abono | 1 = ya se uso el abono,
        ])->get();

        $totalAbonos = 0 ;
        if(count($cliente->factura_historial)>0){
            foreach ($cliente->factura_historial as $itemHistorial) {
                $totalAbonos += $itemHistorial["precio"] ;

                $itemHistorial["debitado"] = 1; // coloco como debitado los abonos que ya fuy sumando al acumulador
                $itemHistorial->update();

            }
        }

        if(count($cliente->factura)>0){
            $tieneSaldo = TRUE; // Bandera para saber cuando debo de dejar ajustar el calculo de saldo restante de las facturas

            foreach ($cliente->factura as $factura) {
                if($tieneSaldo){
                    print_r (json_encode( ["monto" => $factura["monto"] , "totalAbonos"=>$totalAbonos ]));
                    $totalAbonos =  $totalAbonos - $factura["monto"];
                    if($totalAbonos < 0){ // si el precio es mas alto que el total de abonos (dejo la factura abierta y ajusto el saldo_restante)
                        $tieneSaldo = FALSE;
                        $factura["status_pagado"] = 0;
                        $factura["saldo_restante"] = abs($totalAbonos);

                    }else{// cierro la factura y el saldo restante lo dejo 0
                        $factura["saldo_restante"] = 0;
                        $factura["status_pagado"] = 1;
                    }

                }else{ // si no tiene saldo reinicio la factura
                    $factura["saldo_restante"] = $factura["monto"];
                    $factura["status_pagado"] = 0;
                }

                $factura->update();
            }
        }

        print_r (json_encode( ["cliente" => $cliente, "totalAbonos"=>$totalAbonos ]));

    }

    function debitarAbonosClientes($clienteID){
        $cliente = Cliente::find($clienteID);
        // $cliente = Cliente::find($clienteID);
        // $cliente = $abono->cliente;

        $cliente->factura = $cliente->facturas()->where([
            ['status', '=', 1],
            ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
        ])->get();

        $cliente->factura_historial = $cliente->factura_historial()->where([
            ['estado', '=', 1],
            ['debitado', '=', 0] // 0 = aun no usado el abono | 1 = ya se uso el abono,
        ])->get();

        // print_r(json_encode($cliente->factura_historial));
        $totalAbonos = 0 ;
        if(count($cliente->factura_historial)>0){
            foreach ($cliente->factura_historial as $itemHistorial) {
                $totalAbonos += $itemHistorial["precio"] ;

                $itemHistorial["debitado"] = 1; // coloco como debitado los abonos que ya fuy sumando al acumulador
                $itemHistorial->update();

            }
        }

        if(count($cliente->factura)>0){
            $tieneSaldo = TRUE; // Bandera para saber cuando debo de dejar ajustar el calculo de saldo restante de las facturas

            foreach ($cliente->factura as $factura) {
                if($tieneSaldo){
                    // 200 - 500 = -300 ajusta el restante
                    // 500 - 500 = 0  cierra factura y ajusta restante
                    $totalAbonos =  $totalAbonos - $factura["saldo_restante"];

                    if($totalAbonos < 0){ // si el precio es mas alto que el total de abonos (dejo la factura abierta y ajusto el saldo_restante)
                        $tieneSaldo = FALSE;
                        $factura["saldo_restante"] = abs($totalAbonos) ;

                    }else{// cierro la factura y el saldo restante lo dejo 0
                        $factura["saldo_restante"] = 0;
                        $factura["status_pagado"] = 1;
                    }

                }

                $factura->update();
            }
        }

        // print_r (json_encode( ["cliente" => $cliente->factura, "totalAbonos"=>$totalAbonos ]));

    }

    function calcularDeudaFacturaCliente($clienteID){
        $cliente = Cliente::find($clienteID);
        $data = array(
            "saldo_restante" => 0,
            "totalFactura" => 0
        );

        $cliente->factura = $cliente->facturas()->where([
            ['status', '=', 1], //1 Activo | 0 Inactivo
            ['tipo_venta', '=', 1], // 1 = Credito | 2 = Contado,
            ['status_pagado', '=', 0] // 0 = en proceso | 1 = Finalizado,
        ])->get();

        // print_r (count($cliente->factura));
        if(count($cliente->factura)>0){

            $totalFactura = 0;
            $saldoRestanteFactura = 0;

            foreach ($cliente->factura as $factura) {
                $saldoRestanteFactura += $factura["saldo_restante"];
                $totalFactura += $factura["monto"];
            }

            $data["saldo_restante"] = $saldoRestanteFactura;
            $data["totalFactura"] = $totalFactura;
        }


        return $data;
    }
