<?php

if ($api == "menuia") {
  $mensagem = str_replace("%0A", "\n", $mensagem);
  $mensagem = $mensagem == '' ? '.' : $mensagem;


  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://chatbot.menuia.com/api/create-message',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
      'appkey' => $instancia,
      'authkey' => $token,
      'licence' => 'xxx',
      'to' => $numeros_formatados,
      'message' => $mensagem ?? '.',
      'agendamento' => date('Y-m-d H:i:s'),
      'file' => $url_audio
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  //echo $response;

  //Caso queira pausar o envio, vc pode pegar a hash que está retornando.
  $res_hash = json_decode($response, true);
  $hash = $res_hash['id'];
} else {
  $dados = '{
    "programado": false,
    "delay": ' . $delay . ',
    "numeros": ' . $numeros_formatados . ',
    "instancia": "' . $instancia . '",
    "audio": "' . $url_audio . '"
}';

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => '',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $dados,
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);

  //echo $response;

  //Caso queira pausar o envio, vc pode pegar a hash que está retornando.
  $res_hash = json_decode($response, false);
  $hash3 = $res_hash->hash;
}
