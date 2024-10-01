<?php
  $url = "";

  $data = array('instance' => "",
                'to' => "",
                'token' => "",
                'message' => "Mensagem a ser Enviada");


  $options = array('http' => array(
                 'method' => 'POST',
                 'content' => http_build_query($data)
  ));

  $stream = stream_context_create($options);

  $result = @file_get_contents($url, false, $stream);

  echo $result;
?>
  