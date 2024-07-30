<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RedsysAPI;

class RedsysController extends Controller
{

    public function generateSignature(Request $request)
{
    $secretKey = base64_decode('uRoktylrzTI0bSrdFYyj3Sn9UgbSKEb8');
    $amount = $request->amount;
    $order = $request->order;

    // Convertir el monto a un formato adecuado (eliminar el punto decimal)
    $amount = str_replace('.', '', $amount);
    $amount = intval($amount);

    Log::info('request generate signature', ['amount'=>$amount, 'order'=>$order]);

    $json = [
        "DS_MERCHANT_AMOUNT" => $amount,
        "DS_MERCHANT_CURRENCY" => "978",
        "DS_MERCHANT_MERCHANTCODE" => "347790438",
        "DS_MERCHANT_MERCHANTURL" => "http://server.usuriaga.com/api/redsys/handle-notification",
        "DS_MERCHANT_ORDER" => $order,
        "DS_MERCHANT_TERMINAL" => "1",
        "DS_MERCHANT_TRANSACTIONTYPE" => "0",
        "DS_MERCHANT_URLKO" => "http://localhost:3000/paymentOk",
        "DS_MERCHANT_URLOK" => "http://localhost:3000/paymentOk",
    ];
    $json = base64_encode(json_encode($json));

    $key = $this->encrypt3DES($order, $secretKey);
    $signature = hash_hmac('sha256', $json, $key, true);
    $signature = base64_encode($signature);

    return response()->json([
        'signature' => $signature,
        'jsonData' => $json
    ], 200);
}


    private function encrypt3DES($message, $key)
    {
        $l = ceil(strlen($message) / 8) * 8;
        return substr(openssl_encrypt($message . str_repeat("\0", $l - strlen($message)), 'des-ede3-cbc', $key, OPENSSL_RAW_DATA, "\0\0\0\0\0\0\0\0"), 0, $l);
    }


    protected function decodeAndProcessMerchantData($params)
{
    // Decodificar los parámetros de Redsys desde Base64
    $paramsDecoded = base64_decode($params);
    Log::info('Params Decoded', ['paramsDecoded' => $paramsDecoded]);

    // Convertir los parámetros decodificados de JSON a un array PHP
    $paramsArray = json_decode($paramsDecoded, true);

    // Comprobar si json_decode ha encontrado algún error
    if (json_last_error() !== JSON_ERROR_NONE) {
        Log::error('Error al decodificar JSON de params', ['error' => json_last_error_msg()]);
        return; // Terminar la ejecución si hay un error al decodificar
    }

    // Asegurar que Ds_Order está presente en el array
    if (isset($paramsArray['Ds_Order'])) {
        $dsOrder = $paramsArray['Ds_Order'];
        Log::info('Ds_Order', ['Ds_Order' => $dsOrder]);
    } else {
        Log::error('Ds_Order no está presente en los parámetros.');
    }
}


public function handleNotification(Request $request)
{
    $params = $request->input('Ds_MerchantParameters');
    $signature = $request->input('Ds_Signature');
    $version = $request->input('Ds_SignatureVersion');

    // Decodificar los parámetros
    $decodedParams = base64_decode($params);
    $decodedParams = json_decode($decodedParams, true);

    // Extraer el ID del pedido y otros parámetros si es necesario
    $orderId = $decodedParams['DS_MERCHANT_ORDER'] ?? null;

    return response()->json(['message' => 'Confirmado', 'order_id' => $orderId]);
}


}
