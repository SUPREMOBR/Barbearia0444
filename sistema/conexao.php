<?php
$banco = 'barbearia01';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

date_default_timezone_set('America/Sao_Paulo');

 try {
      $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $erro) {
echo 'Não conectado ao Banco de Dados! <br><br>'  .$erro;
}



//VARIAVEIS DO SISTEMA
$nome_sistema = 'Salão Lima';
$email_sistema = 'nagatabrisa.05@gmail.com';

?>