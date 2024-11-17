<?php

if ($api == "menuia") {
  $data_mensagem_obj = new DateTime($data_mensagem);
  $data_mensagem_obj->modify("-$antAgendamento hours");
  $data_mensagem = $data_mensagem_obj->format('Y-m-d H:i:s');

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
      'to' => $telefone,
      'message' => $mensagem,
      'licence' => 'xxx',
      'agendamento' => $data_mensagem
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  $response = json_decode($response, true);
  $hash = $response['id'];
} else {
  $url = "";

  $data = array(
    'instance' => $instancia,
    'to' => $telefone,
    'token' => $token,
    'message' => $mensagem,
    'data' => $data_mensagem
  );



  $options = array('http' => array(
    'method' => 'POST',
    'content' => http_build_query($data)
  ));

  $stream = stream_context_create($options);

  $result = @file_get_contents($url, false, $stream);
  $resultado = json_decode($result, true);
  $hash = @$resultado['message']['hash'];
}
//echo $hash;
