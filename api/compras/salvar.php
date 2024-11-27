<?php
require_once("../../sistema/conexao.php");

$tabela = 'pagar';

$foto = $_POST['nome_foto'];
$id_usuario = $_POST['id_usuario'];
$id = $_POST['id'];
$produto = $_POST['produto'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$pessoa = $_POST['pessoa'];
$data_vencimento = $_POST['data_vencimento'];
$data_pagamento = $_POST['data_pagamento'];
$quantidade = $_POST['quantidade'];


if ($produto == 0 || $produto == "") {
	echo 'Cadastre um Produto e Depois selecione!';
	exit();
}



$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = 'Compra - (' . $quantidade . ') ' . $resultado[0]['nome'];
$estoque = $resultado[0]['estoque'];

if ($data_pagamento != '') {
	$usuario_pagamento = $id_usuario;
	$pago = 'Sim';
} else {
	$usuario_pagamento = 0;
	$pago = 'Não';
}


//atualizar dados do produto
$valor_unitario = $valor / $quantidade;
$total_estoque = $estoque + $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque', valor_compra = '$valor_unitario' WHERE id = '$produto'");



$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Compra', valor = :valor, data_lancamento = curDate(), 
data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', usuario_lanc = '$id_usuario', usuario_baixa = '$usuario_pagamento', 
foto = '$foto', pessoa = '$pessoa', pago = '$pago', produto = '$produto', quantidade = '$quantidade'");

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo';

/*
//enviar notificação
$mensagem_not = 'Usuário '.$nome;
$titulo_not = 'Novo Usuário Cadastrado!';
require("../not.php");
*/
