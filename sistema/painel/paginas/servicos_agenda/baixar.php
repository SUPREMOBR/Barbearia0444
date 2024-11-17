<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$data_atual = date('Y-m-d'); // Define a data atual

$id = $_POST['id']; // Recebe o ID do registro que será atualizado

// Consulta o banco de dados para obter os detalhes do registro baseado no ID fornecido
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Obtém os dados do registro
$funcionario = $resultado[0]['funcionario']; // ID do funcionário
$servico = $resultado[0]['servico']; // ID do serviço
$cliente = $resultado[0]['pessoa']; // ID do cliente
$descricao = 'Comissão - ' . $resultado[0]['descricao']; // Descrição (inclui "Comissão")
$valor_conta = $resultado[0]['valor']; // Valor da conta
$pagamento = $resultado[0]['pagamento']; // Método de pagamento

// Consulta a tabela de serviços para obter o valor e comissão
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor']; // Valor do serviço
$comissao = $resultado[0]['comissao']; // Comissão padrão do serviço
$nome_servico = $resultado[0]['nome']; // Nome do serviço

// Consulta a tabela de usuários para obter a comissão do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao']; // Comissão do funcionário

// Se o funcionário tem uma comissão específica, substitui a comissão padrão
if ($comissao_funcionario > 0) {
	$comissao = $comissao_funcionario;
}

// Calcula o valor da comissão
if ($tipo_comissao == 'Porcentagem') {
	$valor_comissao = ($comissao * $valor_conta) / 100; // Comissão em porcentagem
} else {
	$valor_comissao = $comissao; // Comissão fixa
}

// Consulta a tabela de formas de pagamento para obter a taxa associada
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa']; // Taxa associada ao método de pagamento

// Ajusta o valor do serviço caso haja uma taxa
if ($valor_taxa > 0) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100); // Adiciona taxa ao valor se for do cliente
	} else {
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100); // Subtrai taxa ao valor se for do sistema
	}
}

// Marca a conta como paga e registra o usuário que fez a baixa
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

// Se o tipo de comissão não for 'Sempre', cria uma conta a pagar para o funcionário
if ($lanc_comissao != 'Sempre') {
	// Lança a comissão como uma conta a pagar para o funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = curDate(), 
    data_vencimento = curDate(), usuario_lancou = '$id_usuario', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', 
    servico = '$servico', cliente = '$cliente'");
}

echo 'Baixado com Sucesso';

// Dados do cliente para atualizar a agenda, se necessário
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone']; // Telefone do cliente
$nome_cliente = $resultado2[0]['nome']; // Nome do cliente

// Consulta os agendamentos do cliente
$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Se houver agendamentos para o cliente, exclui os registros com hash (se existir)
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$hash = $resultado[$i]['hash'];
		if ($hash != "") {
			require('../../../../ajax/api-excluir.php'); // Exclui o agendamento se o hash estiver presente
		}
	}
}
