<?php
// Inclui o arquivo de conexão ao banco de dados.
require_once("conexao.php");

// Obtém o e-mail enviado pelo formulário de recuperação de senha.
$email = $_POST['email'];

// Consulta o banco de dados para verificar se existe um usuário com o e-mail fornecido.
$query = $pdo->query("SELECT * from usuarios01 where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Armazena o resultado da consulta em um array associativo.
$total_registro = @count($resultado); // Conta o número de registros encontrados (usuários).
if ($total_registro > 0) { // 1-Se um usuário com esse e-mail for encontrado.
    // 2-Recupera a senha do primeiro registro encontrado.
    $senha = $resultado[0]['senha'];

    // Configuração para o envio do e-mail
    $destinatario = $email;  // Define o destinatário como o e-mail fornecido.
    $assunto = $nome_sistema . ' - Recuperação de Senha'; // Define o assunto do e-mail.
    $mensagem = 'Sua senha é ' . $senha; // Mensagem que será enviada ao usuário.
    $cabecalhos = "From: " . $email_sistema; // Cabeçalhos do e-mail, incluindo o remetente.

    // Envia o e-mail de recuperação.
    @mail($destinatario, $assunto, $mensagem, $cabecalhos);
    // Mensagem de sucesso após o envio do e-mail.
    echo 'Recuperado com Sucesso';
} else {
    // Mensagem de erro se o e-mail não estiver cadastrado.
    echo 'Esse email não está Cadastrado!';
}
