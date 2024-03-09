public function handleNotification(Request $request)
    {
        /* Log::info('Inicio del manejo de notificación de Redsys.');

        if (!$request->has(['Ds_SignatureVersion', 'Ds_MerchantParameters', 'Ds_Signature'])) {
            Log::error('Faltan datos en la solicitud de notificación de Redsys.');
            return response()->json(['status' => 'error', 'message' => 'Datos necesarios no proporcionados.'], 400);
        } */

        //$version = $request->input("Ds_SignatureVersion");
        
       // $signatureRecibida = $request->input("Ds_Signature");

        //Log:info('Params', ['params'=>$params]);

        /* $miObj = new \RedsysAPI();
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
        } */

        $this->decodeAndProcessMerchantData($params);

      /*   Log::info('Notificación de Redsys procesada correctamente.', ['codigoRespuesta' => $codigoRespuesta]);
        return response()->json(['status' => 'success']); */
    }
