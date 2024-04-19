<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\RedsysAPI;

class RedsysController extends Controller
{



    public function generateSignature(Request $request)
{
    $secretKey = base64_decode('sq7HjrUOBfKmC576ILgskD5srU870gJ7');
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
        "DS_MERCHANT_MERCHANTURL" => "http://127.0.0.1:8000/api/redsys/handle-notification",
        "DS_MERCHANT_ORDER" => $order,
        "DS_MERCHANT_TERMINAL" => "1",
        "DS_MERCHANT_TRANSACTIONTYPE" => "0",
        "DS_MERCHANT_URLKO" => "https://localhost:3000/payOk",
        "DS_MERCHANT_URLOK" => "https://localhost:3000/payOk"
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
    Log::info('Inicio del manejo de notificación de Redsys.');

    if (!$request->has(['Ds_SignatureVersion', 'Ds_MerchantParameters', 'Ds_Signature'])) {
        Log::error('Faltan datos en la solicitud de notificación de Redsys.');
        return response()->json(['status' => 'error', 'message' => 'Datos necesarios no proporcionados.'], 400);
    }

    $version = $request->input("Ds_SignatureVersion");
    $params = $request->input("Ds_MerchantParameters");
   $signatureRecibida = $request->input("Ds_Signature");

    $miObj = new \RedsysAPI();
    $miObj->decodeMerchantParameters($params);

    $codigoRespuesta = $miObj->getParameter("Ds_Response");
    if ($codigoRespuesta === null) {
        Log::error('Código de respuesta no disponible.');
        return response()->json(['status' => 'error', 'message' => 'Código de respuesta no disponible.'], 400);
    }

    $clave = env('REDSYS_SECRET_KEY', 'tu_clave_secreta_por_defecto');
    $signatureCalculada = $miObj->createMerchantSignatureNotif($clave, $params);
    if ($signatureCalculada !== $signatureRecibida) {
        Log::error('Firma inválida.', ['signatureCalculada' => $signatureCalculada, 'signatureRecibida' => $signatureRecibida]);
        return response()->json(['status' => 'error', 'message' => 'Firma inválida.'], 400);
    }
    $this->decodeAndProcessMerchantData($params);


    Log::info('Notificación de Redsys procesada correctamente.', ['codigoRespuesta' => $codigoRespuesta]);
    return response()->json(['status' => 'success']);
}

}
