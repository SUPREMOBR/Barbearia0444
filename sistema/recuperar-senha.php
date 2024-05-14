<?php
require_once("conexao.php");

$email = $_POST['email'];

$query = $pdo->query("SELECT * from usuarios01 where email = '$email'");	
$resultado = $query->fetchALL(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
    $senha = $resultado [0]['senha'];
    
    //envio do email
    $destinatario = $email;
    $assunto = $nome_sistema . ' - Recuperação de Senha';
    $mensagem = 'Sua senha é ' .$senha;
    $cabecalhos = "From: ".$email_sistema;
     
    @mail($destinatario, $assunto, $mensagem, $cabecalhos);

    echo 'Recuperado com Sucesso';
}else{
    echo 'Esse email não está Cadastrado ):';
}

 ?>