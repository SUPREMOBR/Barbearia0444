<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

if(@$_POST['id_usuario'] != ""){
	$usuario_logado = $_POST['id_usuario'];
}else{
	@session_start();
$usuario_logado = @$_SESSION['id'];
}


$cliente = $_POST['cliente_agd'];
$data_pagamento = $_POST['data_pagamento'];
$id_agd = @$_POST['id_agd'];
$valor_serv = $_POST['valor_serv_agd'];
$descricao = $_POST['descricao_serv_agd'];
$funcionario = $_POST['funcionario_agd'];
$servico = $_POST['servico_agd'];
$obs = $_POST['obs'];
$pagamento = $_POST['pagamento'];

$valor_serv_restante = $_POST['valor_serv_agd_restante'];
$pagamento_restante = $_POST['pagamento_restante'];
$data_pagamento_restante = $_POST['data_pagamento_restante'];

if($valor_serv_restante == ""){
	$valor_serv_restante = 0;
}

$valor_total_servico = $valor_serv + $valor_serv_restante;


$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$descricao = $resultado[0]['nome'];
$descricao2 = 'Comissão - '.$resultado[0]['nome'];

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao'];

if($comissao_funcionario > 0){
	$comissao = $comissao_funcionario;
}

if($tipo_comissao == 'Porcentagem'){
	$valor_comissao = ($comissao * $valor_total_servico) / 100;
}else{
	$valor_comissao = $comissao;
}

$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $resultado[0]['taxa'];

if($valor_taxa > 0 and strtotime($data_pagamento) <=  strtotime($data_atual)){
	if($taxa_sistema == 'Cliente'){
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	}else{
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
	
}

$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento_restante'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa'];

if($valor_taxa > 0 and strtotime($data_pagamento_restante) <=  strtotime($data_atual)){
	if($taxa_sistema == 'Cliente'){
		$valor_serv_restante = $valor_serv_restante + $valor_serv_restante * ($valor_taxa / 100);
	}else{
		$valor_serv_restante = $valor_serv_restante - $valor_serv_restante * ($valor_taxa / 100);
	}
	
}

if(strtotime($data_pagamento) <=  strtotime($data_atual)){
	$pago = 'Sim';
	$data_pagamento2 = $data_pagamento;
	$usuario_baixa = $usuario_logado;


	//lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento',
	 data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario',
	  servico = '$servico', cliente = '$cliente'");

}else{
	$pago = 'Não';
	$data_pagamento2 = '';
	$usuario_baixa = 0;

	if($lancamento_comissao == 'Sempre'){
		//lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento',
	 data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario',
	  servico = '$servico', cliente = '$cliente'");
	}
}

if($valor_serv_restante > 0){
if(strtotime($data_pagamento_restante) <=  strtotime($data_atual)){
	$pago_restante = 'Sim';
	$data_pagamento2_restante = $data_pagamento;
	$usuario_baixa_restante = $usuario_logado;
}else{
	$pago_restante = 'Não';
	$data_pagamento2_restante = '';
	$usuario_baixa_restante = 0;
}

//lançar o restante
$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv_restante', data_lancamento = curDate(),
 data_vencimento = '$data_pagamento_restante', data_pagamento = '$data_pagamento2_restante', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa',
  foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago_restante', servico = '$servico', funcionario = '$funcionario', obs = '$obs',
   pagamento = '$pagamento_restante'");	
}

//dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone'];
$nome_cliente = $resultado2[0]['nome'];


$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(),
 data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa',
foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario', obs = '$obs', pagamento = '$pagamento'");

$pdo->query("UPDATE agendamentos SET status = 'Concluído' where id = '$id_agd'");


echo 'Salvo com Sucesso'; 

?>