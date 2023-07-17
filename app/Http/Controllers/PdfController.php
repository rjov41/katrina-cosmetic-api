<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Models\Factura;
use App\Models\Producto;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Factura_Detalle;
use App\Models\TazaCambioFactura;
use App\Models\Categoria;
use App\Models\FacturaHistorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;

class PdfController extends Controller
{
    public function facturaPago($id, Request $request)
    {
        $response = [];
        $regaloList = [];
        $status = 400;
        $facturaEstado = 1; // Activo

        if (is_numeric($id)) {

            $factura =  Factura::with('cliente')->where([
                ['id', '=', $id],
                // ['estado', '=', $facturaEstado],
            ])->first();

            /// datos del vendedor

            $factura->user_data =  User::where([
                ['id', '=', $factura->user_id],
                // ['estado', '=', $facturaEstado],
            ])->first();

            $factura_detalle = Factura_Detalle::where([
                ['estado', '=', 1],
                ['factura_id', '=', $factura->id],
            ])->get();

            if (count($factura_detalle) > 0) {
                foreach ($factura_detalle as $productoDetalle) {
                    $producto = Producto::find($productoDetalle["producto_id"]);
                    // dd($productoDetalle["id"]);
                    $productoDetalle["marca"]       = $producto->marca;
                    $productoDetalle["modelo"]      = $producto->modelo;
                    // $productoDetalle["stock"]       = $producto->stock;
                    // $productoDetalle["precio"]      = $producto->precio;
                    $productoDetalle["linea"]       = $producto->linea;
                    $productoDetalle["descripcion"] = $producto->descripcion;
                    // $productoDetalle["estado"]      = $producto->estado;

                    foreach ($productoDetalle->regaloFacturado as $regaloF) {
                        $regaloF->regalo;
                        $regaloF->detalle_regalo =  Producto::where([
                            ['id', '=', $regaloF->regalo->id_producto_regalo],
                        ])->first();
                    }
                    array_push($regaloList, ...$productoDetalle->regaloFacturado);
                }

                // dd(json_encode($regaloList));
            }

            $taza = TazaCambioFactura::where("factura_id", $factura->id)->first();
            if (!is_null($taza)) { // si la factura tiene taza de cambio utilizo esa taza para convertir los montos

                // dd("1");
                $factura->monto = decimal($factura->monto) * decimal($taza->monto);
                $factura->saldo_restante = decimal($factura->saldo_restante) * decimal($taza->monto);

                $detalleFactura = [];
                foreach ($factura_detalle  as $detalle) {
                    $detalle->precio = decimal($detalle->precio) * decimal($taza->monto);
                    $detalle->precio_unidad = decimal($detalle->precio_unidad) * decimal($taza->monto);

                    array_push($detalleFactura, $detalle);
                }
                $factura->factura_detalle = $detalleFactura;
            } else {
                // dd(json_encode($factura_detalle));
                // dd(convertTazaCambio(1));
                $factura->monto = convertTazaCambio($factura->monto);
                $factura->saldo_restante = convertTazaCambio($factura->saldo_restante);

                $detalleFactura = [];
                foreach ($factura_detalle  as $detalle) {
                    $detalle->precio = convertTazaCambio($detalle->precio);
                    $detalle->precio_unidad = convertTazaCambio($detalle->precio_unidad);

                    array_push($detalleFactura, $detalle);
                }
                $factura->factura_detalle = $detalleFactura;
            }

            // print_r(json_encode($factura));
            // dd(json_encode($factura));
            if ($factura) {
                $response = $factura;
                $status = 200;
            } else {
                $response[] = "La factura no existe o fue eliminado.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
        }


        $data = [
            'data' => $response,
            'regalos' => $regaloList
        ];

        $archivo = PDF::loadView('pdf', $data);
        $pdf = PDF::loadView('pdf', $data)->output();

        Storage::disk('public')->put('factura.pdf', $pdf);


        return $archivo->download('factura_' . $response->id . '.pdf');
    }

    public function estadoCuenta(Request $request)
    {
        $all_datos = queryEstadoCuenta($request->id);
        $response['cliente'] = Cliente::find($request->id);

        $response['estado_cuenta'] = array_chunk($all_datos['estado_cuenta'], 30);

        $data = [
            'data' => $response

        ];


        $archivo = PDF::loadView('estado_cuenta', $data);
        $pdf = $archivo->output();

        Storage::disk('public')->put('estado_cuenta.blade.pdf', $pdf);


        return $archivo->download('estado_cuenta_' . $request->id . '.pdf');
    }

    public function SendMail($id)
    {


        $response = [];
        $status = 400;
        $facturaEstado = 1; // Activo

        if (is_numeric($id)) {

            // if($request->input("estado") != null) $facturaEstado = $request->input("estado");
            // dd($productoEstado);

            $factura =  Factura::with('factura_detalle', 'cliente', 'factura_historial')->where([
                ['id', '=', $id],
                // ['estado', '=', $facturaEstado],
            ])->first();

            if (count($factura->factura_detalle) > 0) {
                foreach ($factura->factura_detalle as $key => $productoDetalle) {
                    $producto = Producto::find($productoDetalle["producto_id"]);
                    // dd($productoDetalle["id"]);
                    $productoDetalle["marca"]       = $producto->marca;
                    $productoDetalle["modelo"]      = $producto->modelo;
                    // $productoDetalle["stock"]       = $producto->stock;
                    // $productoDetalle["precio"]      = $producto->precio;
                    $productoDetalle["linea"]       = $producto->linea;
                    $productoDetalle["descripcion"] = $producto->descripcion;
                    // $productoDetalle["estado"]      = $producto->estado;
                }
            }

            if (count($factura->factura_historial) > 0) {
                foreach ($factura->factura_historial as $key => $itemHistorial) {
                    $user = User::find($itemHistorial["user_id"]);

                    $itemHistorial["name"]      = $user->name;
                    $itemHistorial["apellido"]  = $user->apellido;
                }
            }



            if ($factura) {
                $response = $factura;
                $status = 200;
            } else {
                $response[] = "La factura no existe o fue eliminado.";
            }
        } else {
            $response[] = "El Valor de Id debe ser numerico.";
        }


        $data = [
            'data' => $response
        ];

        $archivo = PDF::loadView('pdf', $data);
        $pdf = PDF::loadView('pdf', $data)->output();

        Storage::disk('public')->put('factura.pdf', $pdf);

        $msg = $id;




        /*  Mail::to('rjov41@gmail.com')->send($correo); */

        // Mail::to('rjov41@gmail.com')->queue(new PdfMail($msg));

        return 'Mail enviado';
    }

    function generar(Request $request)
    {
        dd($request);
        // aqui colocar lo mismo que en facturaPago
    }

    function cartera(Request $request)
    {
        // dd([$request->dateFin, $request->allDates, $request->roleName]);

        $fullName = 'Todos';
        $data = carteraQuery($request);

        $data['facturas'] = array_chunk(json_decode(json_encode($data['factura'])), 11);

        if ($request->userId != 0) {
            $datosCliente =  User::find($request->userId);
            $data['fullname'] = $datosCliente->name . ' ' . $datosCliente->apellido;
        } else {
            $data['fullname'] = $fullName;
        }


        $data = [
            'data' => $data['facturas'],
            'fullname' => $data['fullname'],
            'total' => $data['total']
        ];



        $archivo = PDF::loadView('cartera', $data);
        $pdf = PDF::loadView('cartera', $data)->output();

        Storage::disk('public')->put('cartera.pdf', $pdf);


        return $archivo->download('cartera.pdf');
    }

    public function inventario()
    {

        $data["productos"] = Producto::where([
            ['estado', '=', 1],
        ])->get();

        $data['productos'] = array_chunk(json_decode(json_encode($data['productos'])), 34);


        $data = [
            'data' => $data['productos'],
            'total' => count($data['productos'])
        ];
        // dd(json_encode($data));


        $archivo = PDF::loadView('inventario_producto', $data);
        $pdf = PDF::loadView('inventario_producto', $data)->output();

        Storage::disk('public')->put('inventario_producto.pdf', $pdf);


        return $archivo->download('inventario_producto.pdf');
    }

    public function productosVendidos(Request $request)
    {

        $dataQuery = productosVendidos($request);

        $data['productos'] = array_chunk(json_decode(json_encode($dataQuery['productos'])), 34);


        $data = [
            'data' => $data['productos'],
            'total' => $dataQuery['totalProductos']
        ];
        // dd(json_encode($data));


        $archivo = PDF::loadView('productos_vendidos', $data);
        $pdf = PDF::loadView('productos_vendidos', $data)->output();

        Storage::disk('public')->put('productos_vendidos.pdf', $pdf);


        return $archivo->download('inventario_producto.pdf');
    }

    public function registro_cliente(Request $request)
    {

        $response = $this->registroClienteQuery($request);

        // print_r(json_encode($response));
        // $response['data'] =
        $data = [
            'data' =>  array_chunk(json_decode(json_encode($response)), 25),
            'cantidad' => count($response),
        ];

        // dd(json_encode($data));

        $archivo = PDF::loadView('registro_clientes', $data);
        $pdf = $archivo->output();

        Storage::disk('public')->put('registro_clientes.blade.pdf', $pdf);

        return $archivo->download('registro_clientes_' . $request->id . '.pdf');
    }

    public function registro_cliente_csv(Request $request)
    {
        $dataCSV = [];
        $fileName = "registro_clientes_" . Carbon::now('utc')->toDateTimeString() . ".csv";
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array(
            'Codigo_Cliente',
            'Nombre_Completo',
            'Dirección',
            'Celular',
            'Saldo_Actual',
            'Ultima_Fecha_de_Pago',
            'Dias de Cobro',
        );

        $response = $this->registroClienteQuery($request, false);
        foreach ($response as $cliente) {

            // $diasCobro = explode(",", $cliente->dias_cobro);

            // $strCobrosHtml = "";
            // foreach ($diasCobro as $dia) {
            //     $strCobrosHtml .= "- " . ucwords($dia) . "<br>";
            // }
            // $cliente->dias_cobro = $strCobrosHtml;


            $dataCSV[] = array(
                'Codigo_Cliente' => $cliente->id,
                'Nombre_Completo' => $cliente->nombreCompleto,
                'Dirección' => $cliente->direccion_casa,
                'Celular' => $cliente->celular,
                'Saldo_Actual' => $cliente->saldo,
                'Ultima_Fecha_de_Pago' => ($cliente->ultimoAbono) ? Carbon::parse($cliente->ultimoAbono->created_at)->format('j-m-Y') : "No posee abonos",
                'Dias_de_Cobro' => $cliente->dias_cobro,
            );
        }

        $callback = function () use ($dataCSV, $columns) {
            $file = fopen('php://output', 'w');
            //si no quieren que el csv muestre el titulo de columnas omitan la siguiente línea.
            fputcsv($file, $columns);
            foreach ($dataCSV as $item) {
                fputcsv($file, $item);
            }
            fclose($file);
        };

        //Esto hace que Laravel exponga el archivo como descarga
        return response()->stream($callback, 200, $headers);
    }

    public function registro_cliente_excell(Request $request)
    {
        $fileName = "registro_clientes_" . Carbon::now('utc')->toDateTimeString() . ".xlsx";

        $columns = array(
            'Codigo_Cliente',
            'Nombre_Completo',
            'Dirección',
            'Celular',
            'Saldo_Actual',
            'Ultima_Fecha_de_Pago',
            'Dias_de_Cobro',
        );

        $response = $this->registroClienteQuery($request, false);
        $dataExcell = [
            $columns
        ];
        foreach ($response as $cliente) {
            $dataExcell[] = array(
                $cliente->id,
                $cliente->nombreCompleto,
                $cliente->direccion_casa,
                $cliente->celular,
                $cliente->saldo,
                ($cliente->ultimoAbono) ? Carbon::parse($cliente->ultimoAbono->created_at)->format('j-m-Y') : "No posee abonos",
                $cliente->dias_cobro,
            );
        }

        $export = new ClientExport([
            $dataExcell
        ]);

        return Excel::download($export, $fileName);
    }

    private function registroClienteQuery($request, $formatDiasCobro = true)
    {
        $parametros = [["estado", 1]];

        // DB::enableQueryLog();

        $clientes =  Cliente::where($parametros);

        // ** Filtrado por userID
        $clientes->when($request->userId && $request->userId != 0, function ($q) use ($request) {
            $query = $q;
            // vendedor
            // supervisor
            // administrador

            $user = User::select("*")
                // ->where('estado', 1)
                ->where('id', $request->userId)
                ->first();

            if (!$user) {
                return $query;
            }

            return $query->where('user_id', $user->id);
        });

        $clientes->when($request->diasCobros, function ($q) use ($request) {
            $query = $q;
            $dias = explode(",", $request->diasCobros);
            $condicionDiasCobro = [];
            foreach ($dias as $dia) {
                array_push($condicionDiasCobro, ['dias_cobro', 'LIKE', '%' . $dia . '%', "or"]);
            }
            return $query->where($condicionDiasCobro);
        });

        $clientes->when($request->categoriaId && $request->categoriaId != 0, function ($q) use ($request) {
            $query = $q;

            $categoria = Categoria::select("*")
                ->where('estado', 1)
                ->where('id', $request->categoriaId)
                ->first();

            if (!$categoria) {
                return $query;
            }

            return $query->where('categoria_id', $categoria->id);
        });

        // filtrados para campos numericos
        $clientes->when($request->filter && is_numeric($request->filter), function ($q) use ($request) {
            $query = $q;
            // id de recibos 
            $filterSinNumeral = str_replace("#", "", $request->filter);

            $query = $query->where('id', 'LIKE', '%' . $filterSinNumeral . '%');

            return $query;
        }); // Fin Filtrado


        // ** Filtrado para string
        $clientes->when($request->filter && !is_numeric($request->filter), function ($q) use ($request) {
            $query = $q;

            // nombre cliente y empresa
            $query = $query->Where('nombreCompleto', 'LIKE', '%' . $request->filter . '%')
                ->orWhere('nombreEmpresa', 'LIKE', '%' . $request->filter . '%')
                ->orWhere('direccion_casa', 'LIKE', '%' . $request->filter . '%');

            return $query;
        }); // Fin Filtrado por cliente

        $clientes = $clientes->get();

        // dd(DB::getQueryLog());


        if (count($clientes) > 0) {
            foreach ($clientes as $cliente) {
                // dd($cliente->frecuencias);
                // validarStatusPagadoGlobal($cliente->id);
                $cliente->frecuencia = $cliente->frecuencia;
                $cliente->categoria = $cliente->categoria;
                $cliente->facturas = $cliente->facturas;
                $cliente->usuario = $cliente->usuario;
                // $cliente->saldo = calcularDeudaFacturasGlobal($cliente->id);

                $saldoCliente = calcularDeudaFacturasGlobal($cliente->id);

                if ($saldoCliente > 0) {
                    $cliente->saldo = number_format(-(float) $saldoCliente, 2);
                }

                if ($saldoCliente == 0) {
                    $cliente->saldo = $saldoCliente;
                }

                if ($saldoCliente < 0) {
                    // $cliente->saldo = number_format((float) str_replace("-", "", $saldoCliente), 2);
                    $saldo_sin_guion = str_replace("-", "", $saldoCliente);
                    $cliente->saldo = decimal(filter_var($saldo_sin_guion, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
                }

                if ($formatDiasCobro) {
                    $diasCobro = explode(",", $cliente->dias_cobro);

                    $strCobrosHtml = "";
                    foreach ($diasCobro as $dia) {
                        $strCobrosHtml .= "- " . ucwords($dia) . "<br>";
                    }
                    $cliente->dias_cobro = $strCobrosHtml;
                }

                $cliente->ultimoAbono = FacturaHistorial::where(
                    [
                        ["cliente_id", $cliente->id],
                    ]
                )->orderBy('created_at', 'desc')->first();
            }
            // dd(json_encode($clientes));

            // $response[] = $clientes;
        }

        return $clientes;
    }

    public function productos_vendedor(Request $request)
    {
        $response = $this->productosVendedorQuery($request);
        if($request->userId == 0){
            $username = 'Todos';
            
        }else{
            $usertemp = User::find($request->userId);
            
            $username = $usertemp->name .' '. $usertemp->apellido;
        }
        //dd(json_decode(json_encode($response->{'original'})));
        $data = [
            'data' =>  array_chunk(json_decode(json_encode($response->{'original'})), 10),
            'cantidad' => count(json_decode(json_encode($response->{'original'}))),
            'usuario' => $username
        ];
        

        $archivo = PDF::loadView('productos_vendedor', $data);
        $pdf = $archivo->output();

        Storage::disk('public')->put('productos_vendedor.blade.pdf', $pdf);

        return $archivo->download('productos_vendedor_' . $request->userId . '.pdf');
    }

    public function productosVendedorQuery(Request $request)
    {
        
        $response = [];
        $status = 200;
        // $facturaEstado = 1; // Activo
        $parametros = [["facturas.status", 1]];

        if (empty($request->dateIni)) {
            $dateIni = Carbon::now();
        } else {
            $dateIni = Carbon::parse($request->dateIni);
        }

        if (empty($request->dateFin)) {
            $dateFin = Carbon::now();
        } else {
            $dateFin = Carbon::parse($request->dateFin);
        }

        // DB::enableQueryLog();

        $Facturas =  Factura::where($parametros);

        // ** Filtrado por rango de fechas Meta 
        $Facturas->when($request->allDates && $request->allDates == "false", function ($q) use ($dateIni, $dateFin) {
            return $q->whereBetween('facturas.created_at', [$dateIni->toDateString() . " 00:00:00",  $dateFin->toDateString() . " 23:59:59"]);
        });
        

        // ** Stado Pagado Factura 
        $Facturas->when($request->status_pagado && $request->status_pagado != "3", function ($q) use ($request) {
            return $q->where('facturas.status_pagado', $request->status_pagado);
        });
        
        // ** userId por Factura 
        $Facturas->when($request->userId && $request->userId != 0, function ($q) use ($request) {
            return $q->where('facturas.user_id', $request->userId);
        });

        $Facturas->select(DB::raw('COUNT(factura_detalles.cantidad) AS cantidad_total,facturas.id, facturas.cliente_id,factura_detalles.producto_id, facturas.user_id'))
            ->join('factura_detalles', 'factura_detalles.factura_id', '=', 'facturas.id')
            ->groupBy("facturas.cliente_id", "factura_detalles.producto_id","facturas.user_id","facturas.id");
        $Facturas =  $Facturas->get();

        // SELECT COUNT(factura_detalles.cantidad), facturas.cliente_id,factura_detalles.producto_id, facturas.user_id
        // FROM facturas
        // INNER JOIN factura_detalles ON factura_detalles.factura_id = facturas.id
        // WHERE facturas.created_at BETWEEN DATE("2023-04-05 19:26:17") AND DATE("2023-08-05 19:26:17")
        // GROUP BY facturas.cliente_id, factura_detalles.producto_id, facturas.user_id

        if (count($Facturas) > 0) {
            foreach ($Facturas as $Factura) {
                $Factura->cliente = Cliente::find($Factura->cliente_id);  
                $Factura->usuario = User::find($Factura->user_id); 
                $Factura->producto = Producto::find($Factura->producto_id); 
            }

            // $response[] = $ProductosFacturados;
        }

        $response = $Facturas;


        return response()->json($response, $status);
    }
}
