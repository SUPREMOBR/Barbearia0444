<?php
  $url = "";

  $data = array('instance' => $instancia,
                'to' => $telefone,
                'token' => $token,
                'message' => $mensagem,
                'data' => $data_mensagem);

  

  $options = array('http' => array(
                 'method' => 'POST',
                 'content' => http_build_query($data)
  ));

  $stream = stream_context_create($options);

  $result = @file_get_contents($url, false, $stream);
  $resultado = json_decode($result, true);
  $hash = @$resultado['message']['hash'];
  //echo $hash;
?>
  

