<?php
$tabela = 'receber'; // Define o nome da tabela no banco de dado
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$data_atual = date('Y-m-d'); // Obtém a data atual

// Verifica se o ID do usuário foi enviado via POST, caso contrário, usa o ID da sessão
if (@$_POST['id_usuario'] != "") {
	$usuario_logado = $_POST['id_usuario'];
} else {
	@session_start();
	$usuario_logado = @$_SESSION['id'];
}

// Recebe os dados do formulário
$cliente = $_POST['cliente_agd'];
$data_pagamento = $_POST['data_pagamento'];
$id_agd = @$_POST['id_agd'];
$valor_serv = $_POST['valor_serv_agd'];
$descricao = $_POST['descricao_serv_agd'];
$funcionario = $_POST['funcionario_agd'];
$servico = $_POST['servico_agd'];
$obs = $_POST['obs'];
$pagamento = $_POST['pagamento'];

// Dados para o valor e pagamento restante
$valor_serv_restante = $_POST['valor_serv_agd_restante'];
$pagamento_restante = $_POST['pagamento_restante'];
$data_pagamento_restante = $_POST['data_pagamento_restante'];

// Define o valor original do serviço
$valor_serv_original = $_POST['valor_serv_agd'];

// Verifica se já existe um registro de recebimento para o agendamento
$query = $pdo->query("SELECT * FROM receber where referencia = '$id_agd'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$agendamento_conta = @count($resultado);
$valor_recebido = @$resultado[0]['valor'];

// Calcula o novo valor do serviço com base no valor já recebido
$novo_valor_servico = $valor_recebido + $valor_serv;

// Define o valor do serviço restante como zero, caso esteja vazio
if ($valor_serv_restante == "") {
	$valor_serv_restante = 0;
}

// Calcula o valor total do serviço incluindo o valor recebido e o valor restante
$valor_total_servico = $valor_serv + $valor_serv_restante + $valor_recebido;

// Obtém detalhes do serviço para calcular comissão
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$descricao = $resultado[0]['nome'];
$descricao2 = 'Comissão - ' . $resultado[0]['nome'];
$nome_servico = $resultado[0]['nome'];

// Consulta para obter a comissão do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao'];

// Verifica se o funcionário possui uma comissão específica
if ($comissao_funcionario > 0) {
	$comissao = $comissao_funcionario;
}

// Calcula o valor da comissão com base no tipo (percentual ou fixo)
if ($tipo_comissao == 'Porcentagem') {
	$valor_comissao = ($comissao * $valor_total_servico) / 100;
} else {
	$valor_comissao = $comissao;
}

// Consulta para obter a taxa da forma de pagamento e ajusta o valor do serviço conforme a taxa
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $resultado[0]['taxa'];

if ($valor_taxa > 0 and strtotime($data_pagamento) <=  strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	} else {
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
}

// Repete o ajuste para a taxa do pagamento restante
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento_restante'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa'];

if ($valor_taxa > 0 and strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv_restante = $valor_serv_restante + $valor_serv_restante * ($valor_taxa / 100);
	} else {
		$valor_serv_restante = $valor_serv_restante - $valor_serv_restante * ($valor_taxa / 100);
	}
}

// Verifica se o pagamento é feito na data atual ou antes para definir se está pago ou não
if (strtotime($data_pagamento) <=  strtotime($data_atual)) {
	$pago = 'Sim';
	$data_pagamento2 = $data_pagamento;
	$usuario_baixa = $usuario_logado;


	//lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
	data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
	servico = '$servico', cliente = '$cliente'");
} else {
	$pago = 'Não';
	$data_pagamento2 = '';
	$usuario_baixa = 0;

	if ($lancamento_comissao == 'Sempre') {
		//lançar a conta a pagar para a comissão do funcionário
		$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
		data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
		servico = '$servico', cliente = '$cliente'");
	}
}

// Lança o valor restante a receber
if ($valor_serv_restante > 0) {
	if (strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
		$pago_restante = 'Sim';
		$data_pagamento2_restante = $data_pagamento;
		$usuario_baixa_restante = $usuario_logado;
	} else {
		$pago_restante = 'Não';
		$data_pagamento2_restante = '';
		$usuario_baixa_restante = 0;
	}

	//lançar o restante
	$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv_restante', data_lancamento = curDate(), 
	data_vencimento = '$data_pagamento_restante', data_pagamento = '$data_pagamento2_restante', usuario_lancou = '$usuario_logado', 
	usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago_restante', servico = '$servico', 
	funcionario = '$funcionario', obs = '$obs', pagamento = '$pagamento_restante'");
}

//$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
//$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
//$dias_retorno = $resultado2[0]['dias_retorno'];


// Consulta para obter dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone'];
$nome_cliente = $resultado2[0]['nome'];

// Verifica se o agendamento já tem um registro e insere ou atualiza conforme o caso
if ($valor_serv_original != 0) {
	if ($agendamento_conta == 0) {
		$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(), 
		data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', 
		foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario', obs = '$obs', 
		pagamento = '$pagamento'");
	} else {
		$pdo->query("UPDATE $tabela SET valor = '$novo_valor_servico', data_pagamento = curDate(), usuario_baixa = '$usuario_baixa', 
		foto = 'sem-foto.jpg', pagamento = '$pagamento' where referencia = '$id_agd'");
	}
}

// Atualiza o status do agendamento para "Concluído"
$pdo->query("UPDATE agendamentos SET status = 'Concluído' where id = '$id_agd'");

echo 'Salvo com Sucesso';
