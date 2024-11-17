<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Consulta para atualizar a tabela 'pagar'.
// A consulta marca o pagamento como 'Sim', registra o usuário que fez a baixa e a data do pagamento como a data atual.
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

echo 'Baixado com Sucesso';
