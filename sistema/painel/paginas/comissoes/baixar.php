<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado
@session_start(); // Inicia a sessão ou retoma a sessão ativa
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; // Recebe o ID do registro a ser atualizado via POST.

// A consulta atualiza o registro na tabela 'pagar' com o ID fornecido:
// 1. O campo 'pago' é alterado para 'Sim', indicando que o pagamento foi feito.
// 2. O campo 'usuario_baixa' recebe o ID do usuário logado, identificando quem fez a baixa do pagamento.
// 3. O campo 'data_pagamento' recebe a data atual (curDate()), marcando quando o pagamento foi realizado.
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() 
WHERE id = '$id'");

echo 'Baixado com Sucesso';
