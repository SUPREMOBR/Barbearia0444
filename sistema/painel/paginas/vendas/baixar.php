<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// 1-Executa a atualização do status do pagamento na tabela "receber".
// 2-Marca como "pago" (Sim), atribui o usuário que fez a baixa e define a data de pagamento.
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

echo 'Baixado com Sucesso';
