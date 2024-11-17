<?php
$tabela = 'receber'; // Define o nome da tabela no banco de dados
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$data_atual = date('Y-m-d'); // Define a data atual

// Verifica se existe um 'id_usuario' enviado via POST, se sim, atribui à variável $usuario_logado. Caso contrário, usa o valor armazenado na sessão
if (@$_POST['id_usuario'] != "") {
	$usuario_logado = $_POST['id_usuario']; // Atribui o ID do usuário enviado
} else {
	@session_start(); // Inicia a sessão ou retoma a sessão já iniciada
	$usuario_logado = @$_SESSION['id']; // Atribui o ID do usuário logado na sessão
}

// Obtém os dados enviados via POST
$cliente = @$_POST['cliente']; // ID do cliente
$data_pagamento = $_POST['data_pagamento']; // Data de pagamento
$valor_serv = $_POST['valor_serv']; // Valor do serviço
$valor_serv = str_replace(',', '.', $valor_serv); // Substitui vírgula por ponto para garantir a formatação numérica correta
$funcionario = $usuario_logado; // O funcionário que está lançando a conta é o usuário logado
$servico = $_POST['servico']; // ID do serviço
$pagamento = @$_POST['pagamento']; // Forma de pagamento
$obs = @$_POST['obs']; // Observações

// Obtém informações do pagamento restante, se houver
$valor_serv_restante = $_POST['valor_serv_agd_restante'];
$pagamento_restante = $_POST['pagamento_restante'];
$data_pagamento_restante = $_POST['data_pagamento_restante'];

// Se o valor do serviço restante for vazio, define como 0
if ($valor_serv_restante == "") {
	$valor_serv_restante = 0;
}

// Calcula o valor total do serviço (valor do serviço + valor restante)
$valor_total_servico = $valor_serv + $valor_serv_restante;

// Verifica se o cliente foi selecionado. Caso não, exibe uma mensagem e interrompe a execução
if (@$cliente == "") {
	echo 'Selecione um Cliente!';
	exit();
}

// Consulta os dados do serviço (valor, nome e comissão)
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor']; // Valor do serviço
$nome_servico = $resultado[0]['nome']; // Nome do serviço
$comissao = $resultado[0]['comissao']; // Comissão do serviço
$descricao = $resultado[0]['nome']; // Descrição do serviço (nome)
$descricao2 = 'Comissão - ' . $resultado[0]['nome']; // Descrição para a comissão

// Consulta os dados do cliente
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone']; // Telefone do cliente
$nome_cliente = $resultado2[0]['nome']; // Nome do cliente

// Consulta os dados do funcionário (comissão, etc)
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao']; // Comissão do funcionário

// Se o funcionário tem comissão definida, usa o valor da comissão dele ao invés da comissão padrão do serviço
if ($comissao_funcionario > 0) {
	$comissao = $comissao_funcionario;
}

// Se a comissão for em porcentagem, calcula o valor da comissão com base no total do serviço
if ($tipo_comissao == 'Porcentagem') {
	$valor_comissao = ($comissao * $valor_total_servico) / 100;
} else {
	$valor_comissao = $comissao; // Caso a comissão seja um valor fixo
}

// Consulta as taxas associadas à forma de pagamento escolhida
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $resultado[0]['taxa']; // Taxa da forma de pagamento

// Se a forma de pagamento tem uma taxa e a data de pagamento é hoje ou anterior, ajusta o valor do serviço com a taxa
if ($valor_taxa > 0 and strtotime($data_pagamento) <=  strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100); // Se a taxa for para o cliente, adiciona ao valor do serviço
	} else {
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100); // Se a taxa for para o sistema, subtrai do valor do serviço
	}
}

// Consulta as taxas associadas ao pagamento restante, se houver
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento_restante'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa']; // Taxa da forma de pagamento para o restante

// Se a forma de pagamento do restante tem uma taxa e a data de pagamento é hoje ou anterior, ajusta o valor restante
if ($valor_taxa > 0 and strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv_restante = $valor_serv_restante + $valor_serv_restante * ($valor_taxa / 100);
	} else {
		$valor_serv_restante = $valor_serv_restante - $valor_serv_restante * ($valor_taxa / 100);
	}
}

// Se a data de pagamento for anterior ou igual à data atual, marca como pago
if (strtotime($data_pagamento) <=  strtotime($data_atual)) {
	$pago = 'Sim';
	$data_pagamento2 = $data_pagamento; // Atualiza a data de pagamento
	$usuario_baixa = $usuario_logado; // Define o usuário que registrou o pagamento

	// Registra a comissão do funcionário como uma conta a pagar
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
	data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
	servico = '$servico', cliente = '$cliente'");

	// Formata o telefone
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);
} else {
	$pago = 'Não'; // Marca como não pago se a data de pagamento for futura
	$data_pagamento2 = ''; // Não há data de pagamento
	$usuario_baixa = 0; // Não há usuário que registrou o pagamento

	if ($lancamento_comissao == 'Sempre') {
		// Registra a comissão como uma conta a pagar mesmo se o pagamento não foi realizado
		$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
		data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
		servico = '$servico', cliente = '$cliente'");
	}
}

// Se houver valor restante, registra o pagamento restante
if ($valor_serv_restante > 0) {
	if (strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
		$pago_restante = 'Sim'; // Marca como pago se a data de pagamento restante for anterior ou igual à data atual
		$data_pagamento2_restante = $data_pagamento; // Define a data de pagamento
		$usuario_baixa_restante = $usuario_logado; // Define o usuário que registrou o pagamento restante
	} else {
		$pago_restante = 'Não'; // Marca como não pago se a data for futura
		$data_pagamento2_restante = ''; // Não há data de pagamento restante
		$usuario_baixa_restante = 0; // Não há usuário que registrou o pagamento restante
	}

	// Registra o pagamento restante na tabela
	$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv_restante', data_lancamento = curDate(), 
	data_vencimento = '$data_pagamento_restante', data_pagamento = '$data_pagamento2_restante', usuario_lancou = '$usuario_logado', 
	usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago_restante', servico = '$servico', 
	funcionario = '$funcionario', obs = '$obs', pagamento = '$pagamento_restante'");
}

# Consulta para buscar o nome do serviço na tabela servicos, dado o seu ID ($servico). O nome é então armazenado na variável $nome_servico,
$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = $resultado2[0]['nome'];

//aki fica os dados do cliente
// Registra o pagamento inicial do serviço
$pdo->query("INSERT INTO $tabela SET descricao = '$nome_servico', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(), 
data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', 
foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario', pagamento = '$pagamento', obs = '$obs'");


echo 'Salvo com Sucesso';
