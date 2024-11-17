<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$data_atual = date('Y-m-d'); // Define a data atual

$id = $_POST['id']; // Obtém o valor do ID que foi passado pelo formulário

// Busca os detalhes do registro no banco de dados usando o ID
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$funcionario = $resultado[0]['funcionario'];
$servico = $resultado[0]['servico'];
$cliente = $resultado[0]['pessoa'];
$descricao = 'Comissão - ' . $resultado[0]['descricao'];
$valor_conta = $resultado[0]['valor'];
$pagamento = $resultado[0]['pagamento'];

// Obter detalhes do serviço para cálculo da comissão
$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$nome_servico = $resultado[0]['nome'];

// Obter a taxa de comissão do funcionário
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$comissao_funcionario = $resultado[0]['comissao'];

// Substitui a comissão pelo valor específico do funcionário
if ($comissao_funcionario > 0) {
	$comissao = $comissao_funcionario;
}

// Calcula a comissão com base no tipo especificado
if ($tipo_comissao == 'Porcentagem') {
	$valor_comissao = ($comissao * $valor_conta) / 100;
} else {
	$valor_comissao = $comissao;
}

// Obtém a taxa de pagamento e ajusta o valor da taxa conforme configurado no sistema
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa'];

if ($valor_taxa > 0) {
	if ($taxa_sistema == 'Cliente') {
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	} else {
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
}

// Atualiza o registro para marcar como pago
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate() where id = '$id'");

// Condicional para registrar comissão como despesa
if ($lancamento_comissao != 'Sempre') {
	//lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = curDate(), 
	data_vencimento = curDate(), usuario_lancou = '$id_usuario', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', servico = '$servico', cliente = '$cliente'");
}

echo 'Baixado com Sucesso';

// Buscar os dados do cliente para notificação
$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente' order by id desc limit 2");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$telefone = $resultado2[0]['telefone'];
$nome_cliente = $resultado2[0]['nome'];

// Verifica agendamentos do cliente e executa uma exclusão de hash se presente
$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$cliente'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	for ($i = 0; $i < $total_registro; $i++) {
		$hash = $resultado[$i]['hash'];
		if ($hash != "") {
			require('../../../../ajax/api-excluir.php'); // Exclui registros de agendamento relacionados
		}
	}
}
