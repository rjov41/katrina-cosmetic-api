<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClientesReactivados;
use App\Models\DevolucionFactura;
use App\Models\DevolucionProducto;
use App\Models\Factura;
use App\Models\Factura_Detalle;
use App\Models\FacturaHistorial;
use App\Models\Recibo;
use App\Models\ReciboHistorial;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConfiguracionController extends Controller
{
    public function migracion(Request $request)
    {
        $response = [];
        $status = 400;

        $validation = Validator::make($request->all(), [
            'userTo' => 'required|numeric',
            'userFrom' => 'required|numeric',
            'idclientes' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(["message" => "Error, no hay usuarios seleccionados."], 400);
        } else {
            DB::beginTransaction();
            // print_r(json_encode($request->idclientes));
            // DB::enableQueryLog();

            try {
                foreach ($request->idclientes as $clienteID) {

                    $userTo = ['user_id' => $request->userTo];

                    // 1- Busco la info del cliente
                    $cliente = Cliente::where("id", $clienteID);

                    // 2 - cambio de usuario si tiene cliente reactivados
                    $clientesReactivados = ClientesReactivados::where("cliente_id", $clienteID);
                    $clientesReactivados->update($userTo);


                    //////  3 - Operaciones con facturas //// 
                    $facturas = Factura::where("cliente_id", $clienteID)->get();

                    if (count($facturas) > 0) {
                        foreach ($facturas as  $factura) {
                            // 4 - Inicio de revision factura devuelta

                            // $factura->devolucion_factura = $factura->devolucion_factura()->where([
                            //     ['factura_id', '=', $factura->id],
                            // ])->get();

                            $devolucionFactura = DevolucionFactura::where([
                                ['factura_id', '=', $factura->id],
                            ])->get();

                            if (count($devolucionFactura) > 0) {
                                // dd(json_encode($factura));

                                // 5 - Si la factura tiene devolucion modifico el usuario que realizo la devolucion
                                foreach ($devolucionFactura as  $devolucion) {
                                    DevolucionFactura::where("factura_id", $devolucion->factura_id)->update($userTo);
                                }
                            }

                            // fin revision factura devuelta

                            // 6- Inicio de revision productos devueltos

                            // $factura->factura_detalle = $factura->factura_detalle()->where([
                            //     ['factura_id', '=', $factura->id], 
                            // ])->get();

                            $facturaDetalle = Factura_Detalle::where([
                                ['factura_id', '=', $factura->id],
                            ])->get();

                            if (count($facturaDetalle) > 0) {
                                // dd(json_encode($factura));
                                foreach ($facturaDetalle as $producto) {
                                    // print_r(json_encode(DevolucionProducto::where("factura_detalle_id", $producto->id)->get()));
                                    // 7 - Si la factura tiene devolucion de producto modifico el usuario que realizo la devolucion
                                    DevolucionProducto::where("factura_detalle_id", $producto->id)->update($userTo);
                                    // print_r(json_encode(DevolucionProducto::where("factura_detalle_id", $producto->id)->get()));
                                }
                            }

                            // fin revision producto devuelta

                            // envio la factura al nuevo cliente
                            $factura->update($userTo);
                        }
                    }

                    // DB::enableQueryLog();

                    // Inicio de modificacion de Abonos clientes
                    $abonos = FacturaHistorial::where("cliente_id", $clienteID);
                    $abonosQuery = $abonos->get();
                    if (count($abonosQuery) > 0) {
                        $recibo = Recibo::where("user_id", (int) $request->userTo)->first();

                        // print_r(json_encode($abonosQuery));
                        foreach ($abonosQuery as $abono) {
                            if ($recibo) {
                                // Inicio de modificacion de recibos
                                // dd(json_encode($recibo));
                                $recibosHistorial = ReciboHistorial::where("factura_historial_id", $abono->id);
                                $recibosHistorial->update(['recibo_id' => $recibo->id]);
                            }else{
                                // dd($recibo);
                                throw new Exception("El usuario al que desea hacerle la migracion no tiene recibos registrados.");

                            }

                            // Fin de modificacion de recibos
                        }

                    }
                    $abonos->update($userTo);

                    $query = DB::getQueryLog();
                    // return response()->json($query, $status);
                    $cliente->update($userTo);


                    // DB::commit();
                    
                    // print_r(json_encode($facturas));
                }
                
                // $query = DB::getQueryLog();
                // dd(json_encode($query));
                $response["mensaje"] =  "Completado con exito";
                $status = 200;
                
                // return response()->json($query, $status);
                // DB::rollback();
                DB::commit();
                return response()->json($response, $status);
                // DB::rollback();
            } catch (Exception $e) {
                $response["mensaje"] =  $e->getMessage();
                // print_r(json_encode(["error" => $e->getMessage()]));
                DB::rollback();
                // dd($e);
                // // print_r(json_encode($e));
                // return response()->json(["mensaje" => json_encode($e)], 400);
            }


            // dd($clienteEstado);


            return response()->json($response, $status);
        }
    }
}
