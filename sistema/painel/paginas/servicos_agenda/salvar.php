<?php
$tabela = 'receber'; // Define o nome da tabela no banco de dados
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$data_atual = date('Y-m-d'); // Define a data atual

@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$usuario_logado = @$_SESSION['id']; // Obtém o ID do usuário logado na sessão

$cliente = $_POST['cliente']; // Obtém o ID do cliente enviado pelo formulário via POST.
$data_pagamento = $_POST['data_pagamento']; // Obtém a data de pagamento enviada pelo formulário.
$id = @$_POST['id']; // Obtém o ID (se existir) enviado pelo formulário.
$valor_serv = $_POST['valor_serv']; // Obtém o valor do serviço.
$valor_serv = str_replace(',', '.', $valor_serv); // Converte o valor do serviço, substituindo a vírgula por ponto para garantir que seja tratado como float.
$funcionario = $_POST['funcionario']; // Obtém o ID do funcionário que executou o serviço.
$servico = $_POST['servico']; // Obtém o ID do serviço prestado.
$obs = $_POST['obs']; // Obtém observações sobre o serviço.
$pagamento = @$_POST['pagamento']; // Obtém a forma de pagamento selecionada.

$valor_serv_restante = $_POST['valor_serv_agd_restante']; // Obtém o valor restante do serviço, caso haja algum parcelamento.
$pagamento_restante = $_POST['pagamento_restante']; // Obtém a forma de pagamento para o restante do serviço.
$data_pagamento_restante = $_POST['data_pagamento_restante']; // Obtém a data de pagamento do restante, se houver.


if ($valor_serv_restante == "") {
	$valor_serv_restante = 0; // Se não houver valor restante, define como zero
}

// Calcula o valor total do serviço, somando o valor atual com o restante.
$valor_total_servico = $valor_serv + $valor_serv_restante;

// Consulta os dados do serviço.
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor']; // Obtém o valor do serviço.
$comissao = $resultado[0]['comissao']; // Obtém a comissão associada ao serviço.
$descricao = $resultado[0]['nome']; // Obtém o nome do serviço.
$descricao2 = 'Comissão - ' . $resultado[0]['nome']; // Cria a descrição para a comissão do serviço.
$nome_servico = $resultado[0]['nome']; // Nome do serviço.

// Consulta os dados do cliente.
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone']; // Obtém o telefone do cliente.
$nome_cliente = $resultado2[0]['nome']; // Obtém o nome do cliente.

// Consulta os dados do funcionário que executou o serviço.
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao']; // Obtém a comissão do funcionário.

if ($comissao_funcionario > 0) {
	// Se o funcionário tiver uma comissão específica, usa o valor dele.
	$comissao = $comissao_funcionario;
}

if ($tipo_comissao == 'Porcentagem') {
	// Calcula a comissão do funcionário em porcentagem sobre o valor total do serviço
	$valor_comissao = ($comissao * $valor_total_servico) / 100;
} else {
	$valor_comissao = $comissao; // Caso contrário, usa o valor fixo de comissão.
}

// Consulta os dados da forma de pagamento.
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = $resultado[0]['taxa']; // Obtém a taxa associada à forma de pagamento.

if ($valor_taxa > 0 and strtotime($data_pagamento) <= strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		// Se a taxa for do cliente, aumenta o valor do serviço.
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	} else {
		// Se a taxa for do sistema, diminui o valor do serviço.
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
}

// Consulta a forma de pagamento do restante.
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento_restante'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa']; // Obtém a taxa para o restante

if ($valor_taxa > 0 and strtotime($data_pagamento_restante) <= strtotime($data_atual)) {
	if ($taxa_sistema == 'Cliente') {
		// Aplica a taxa do cliente ao restante.
		$valor_serv_restante = $valor_serv_restante + $valor_serv_restante * ($valor_taxa / 100);
	} else {
		// Aplica a taxa do sistema ao restante.
		$valor_serv_restante = $valor_serv_restante - $valor_serv_restante * ($valor_taxa / 100);
	}
}


if (strtotime($data_pagamento) <=  strtotime($data_atual)) {
	$pago = 'Sim'; // Marca como pago.
	$data_pagamento2 = $data_pagamento; // Define a data de pagamento.
	$usuario_baixa = $usuario_logado; // Define o usuário responsável pela baixa do pagamento.

	// Insere uma comissão de pagamento para o funcionário no banco de dados.
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
	data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
	servico = '$servico', cliente = '$cliente'");

	// Formata o telefone do cliente para o formato internacional.
	$telefone = '55' . preg_replace('/[ ()-]+/', '', $telefone);

	// Se a mensagem de agendamento for via API, envia uma mensagem agendada.
	if ($msg_agendamento == 'Api') {
		//agendar mensagem de retorno
		$mensagem = '*Olá tudo bem ' . $nome_cliente . '! Nós ' . $nome_sistema . ', queremos ouvir você!*  %0A %0A';
		$mensagem .= 'Como foi seu último serviço de ' . $nome_servico . ' conosco? Você teria alguma sugestão de melhoria? Você é muito importante pra gente!%0A %0A Faz um tempo que não nós vemos você aqui. Quando você vai dar aquele tapa no visual? Você merece o que há de melhor, conheça nossos pacotes de desconto. *Promoção especial apenas hoje!*';
		$data_mensagem = $data_atual . ' 12:15:00';
		require('../../../../ajax/api-agendar.php'); // Envia a mensagem agendada usando a API.
	}
} else {
	$pago = 'Não'; // Marca como não pago.
	$data_pagamento2 = ''; // Deixa a data de pagamento em branco.
	$usuario_baixa = 0; // Define que o pagamento não foi realizado.


	if ($lancamento_comissao == 'Sempre') {
		// Se o lançamento de comissão for 'Sempre', insere a comissão a pagar mesmo que não tenha sido pago ainda.
		$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', 
		data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
		servico = '$servico', cliente = '$cliente'");
	}
}



if ($valor_serv_restante > 0) {
	if (strtotime($data_pagamento_restante) <=  strtotime($data_atual)) {
		$pago_restante = 'Sim'; // Marca o pagamento restante como realizado.
		$data_pagamento2_restante = $data_pagamento;
		$usuario_baixa_restante = $usuario_logado;
	} else {
		$pago_restante = 'Não'; // Marca o pagamento restante como pendente.
		$data_pagamento2_restante = ''; // Deixa a data de pagamento do restante em branco.
		$usuario_baixa_restante = 0;
	}

	// Insere o restante do pagamento na tabela "receber".
	$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv_restante', data_lancamento = curDate(), 
	data_vencimento = '$data_pagamento_restante', data_pagamento = '$data_pagamento2_restante', usuario_lancou = '$usuario_logado', 
	usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago_restante', servico = '$servico', 
	funcionario = '$funcionario', obs = '$obs', pagamento = '$pagamento_restante'");
}


// Insere o pagamento do serviço principal na tabela "receber".
$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_servico = $resultado2[0]['nome']; // Obtém o nome do serviço novamente.

//dados do cliente


$pdo->query("INSERT INTO $tabela SET descricao = '$nome_servico', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(), 
data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', 
foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario', obs = '$obs', pagamento = '$pagamento'");

echo 'Salvo com Sucesso';
