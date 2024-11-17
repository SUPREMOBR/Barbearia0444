<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Atualiza a tabela 'pagar' marcando o pagamento como "Sim" e registrando o ID do usuário que fez a baixa
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

echo 'Baixado com Sucesso';
