<?php
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$cliente = $_POST['cliente'];
$id = @$_POST['id'];
$funcionario = $_POST['funcionario'];
$servico = $_POST['servico'];

if ($id == "") {
	$id = 0;
}


$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$descricao = $resultado[0]['nome'];
$descricao2 = 'Comissão - ' . $resultado[0]['nome'];
$nome_servico = $resultado[0]['nome'];

//dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone'];
$nome_cliente = $resultado2[0]['nome'];

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao'];

if ($comissao_funcionario > 0) {
	$comissao = $comissao_funcionario;
}

if ($tipo_comissao == 'Porcentagem') {
	$valor_comissao = ($comissao * $valor) / 100;
} else {
	$valor_comissao = $comissao;
}



$pdo->query("INSERT INTO $tabela SET descricao = '$nome_servico', tipo = 'Serviço', valor = '$valor', data_lancamento = curDate(), 
data_vencimento = curDate(), usuario_lancou = '$usuario_logado',  foto = 'sem-foto.jpg', pessoa = '$cliente', pago = 'Não', servico = '$servico', 
funcionario = '$funcionario', func_comanda = '$usuario_logado', comanda = '$id', valor2 = '$valor'");
$ult_id = $pdo->lastInsertId();


//lançar a conta a pagar para a comissão do funcionário
$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = curDate(), 
data_vencimento = curDate(), usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
servico = '$servico', cliente = '$cliente', id_ref = '$ult_id'");



echo 'Salvo com Sucesso';
