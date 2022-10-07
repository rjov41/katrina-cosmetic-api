<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClientesReactivados;
use App\Models\DevolucionFactura;
use App\Models\DevolucionProducto;
use App\Models\Factura;
use App\Models\FacturaHistorial;
use App\Models\Meta;
use App\Models\Recibo;
use App\Models\User;
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
        ]);

        if ($validation->fails()) {
            return response()->json(["message" => "Error, no hay usuarios seleccionados."], 400);
        } else {

            // dd($clienteEstado);

            DB::beginTransaction();
            try {
                $userTo = ['user_id' => $request->userTo];

                // dump(json_encode(Cliente::where("user_id", $request->userFrom)->get()));
                $clientes = Cliente::where("user_id", $request->userFrom);
                $clientes->update($userTo);
                // dd(json_encode(Cliente::where("id", 349)->get()));

                $clientesReactivados = ClientesReactivados::where("user_id", $request->userFrom);
                $clientesReactivados->update($userTo);

                $devolucionFactura = DevolucionFactura::where("user_id", $request->userFrom);
                $devolucionFactura->update($userTo);

                $devolucionProductos = DevolucionProducto::where("user_id", $request->userFrom);
                $devolucionProductos->update($userTo);

                $facturas = Factura::where("user_id", $request->userFrom);
                $facturas->update($userTo);

                $facturaHistorials = FacturaHistorial::where("user_id", $request->userFrom);
                $facturaHistorials->update($userTo);

                $meta = Meta::where("user_id", $request->userFrom);
                $meta->update($userTo);

                $recibos = Recibo::where("user_id", $request->userFrom);
                $recibos->update($userTo);

                DB::commit();
                // DB::rollback();
                $response["mensaje"] = 'Proceso completado';
                $status = 200;

            } catch (Exception $e) {
                DB::rollback();
                // dd($e);
                // // print_r(json_encode($e));
                return response()->json(["mensaje" => json_encode($e)], 400);
            }

            // if(count($cliente) > 0){
            //     $response[] = $cliente;
            // }

            return response()->json($response, $status);
        }
    }
}
