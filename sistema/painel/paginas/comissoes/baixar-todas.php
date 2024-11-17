<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$dataInicial = @$_POST['data_inicial']; // Recebe a data inicial do período via POST.
$dataFinal = @$_POST['data_final']; // Recebe a data final do período via POST.
$funcionario = @$_POST['id_funcionario']; // Recebe o ID do funcionário via POST.

// A consulta atualiza os registros na tabela 'pagar' onde:
// 1. A data de lançamento está dentro do intervalo fornecido (dataInicial e dataFinal).
// 2. O pagamento ainda não foi feito (pago = 'Não').
// 3. O funcionário corresponde ao ID fornecido.
// 4. O tipo de pagamento é 'Comissão'.
// O pagamento é marcado como 'Sim', a data de pagamento é definida como a data atual (curDate()), e o usuário responsável pela baixa é registrado.
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where data_lancamento >= '$dataInicial' and 
data_lancamento <= '$dataFinal' and pago = 'Não' and funcionario LIKE '$funcionario' and tipo = 'Comissão'");

echo 'Baixado com Sucesso';
