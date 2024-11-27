<?php
require_once("../../sistema/conexao.php");

$tabela = 'receber';

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
$pagamento = $_POST['pagamento'];

if ($produto == 0 || $produto == "") {
	echo 'Cadastre um Produto e Depois selecione!';
	exit();
}



$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = 'Venda - (' . $quantidade . ') ' . $resultado[0]['nome'];
$estoque = $resultado[0]['estoque'];

if ($data_pagamento != '') {
	$usuario_pagamento = $id_usuario;
	$pago = 'Sim';
} else {
	$usuario_pagamento = 0;
	$pago = 'Não';
}


if ($quantidade > $estoque) {
	echo 'Você não pode vendar mais do que você possui em estoque! Você tem ' . $estoque . ' produtos em estoque!';
	exit();
}


//atualizar dados do produto
$total_estoque = $estoque - $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");





if ($id == "") {
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Venda', valor = :valor, data_lancamento = curDate(), 
	data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', 
	foto = '$foto', pessoa = '$pessoa', pago = '$pago', produto = '$produto', quantidade = '$quantidade', pagamento = '$pagamento'");
} else {

	//tratamento para trocar a foto e apagar a antiga
	$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_reg = @count($resultado);
	$foto_antiga = $resultado[0]['foto'];

	if ($foto_antiga != "sem-foto.jpg" and $foto != $foto_antiga) {
		unlink("../../sistema/painel/img/contas/" . $foto_antiga);
	}


	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', 
	data_pagamento = '$data_pagamento', foto = '$foto', pessoa = '$pessoa', produto = '$produto', quantidade = '$quantidade' WHERE id = '$id'");
}

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
