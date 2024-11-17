<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dados
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$id = $_POST['id']; //Recebe o ID passado via POST (Formulário)

// Consulta os dados do registro que será baixado (marcado como pago)
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

// Armazena os dados do registro para manipulação
$funcionario = $resultado[0]['funcionario'];
$servico = $resultado[0]['servico'];
$cliente = $resultado[0]['pessoa'];
$descricao = 'Comissão - ' . $resultado[0]['descricao'];
$tipo = $resultado[0]['tipo'];
$pagamento = $resultado[0]['pagamento'];
$valor_serv = $resultado[0]['valor'];

// Se o tipo for 'Serviço', realiza o cálculo da comissão
if ($tipo == 'Serviço') {
	// Busca os dados do serviço associado
	$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	// Obtém o valor do serviço e a comissão
	$valor = $resultado[0]['valor'];
	$comissao = $resultado[0]['comissao'];

	// Verifica o tipo de comissão (porcentagem ou valor fixo)
	if ($tipo_comissao == 'Porcentagem') {
		// Calcula a comissão como uma porcentagem do valor do serviço
		$valor_comissao = ($comissao * $valor_serv) / 100;
	} else {
		// A comissão é um valor fixo
		$valor_comissao = $comissao;
	}

	// Verifica se a comissão será lançada em uma conta a pagar
	if ($lancamento_comissao != 'Sempre') {
		// Lança a comissão na tabela 'pagar' como uma conta a pagar
		$pdo->query("INSERT INTO pagar SET descricao = '$descricao', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = curDate(), 
data_vencimento = curDate(), usuario_lancou = '$id_usuario', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', servico = '$servico', 
cliente = '$cliente'");
	}
}
// Consulta a forma de pagamento associada
$query = $pdo->query("SELECT * FROM formas_pagamento where nome = '$pagamento'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor_taxa = @$resultado[0]['taxa']; // Obtém a taxa associada à forma de pagamento

// Verifica se há taxa a ser aplicada
if ($valor_taxa > 0) {
	// Verifica se a taxa será aplicada ao cliente ou ao serviço
	if ($taxa_sistema == 'Cliente') {
		// Aplica a taxa ao valor do serviço se a taxa for do cliente
		$valor_serv = $valor_serv + $valor_serv * ($valor_taxa / 100);
	} else {
		// Subtrai a taxa do valor do serviço se a taxa for do sistema
		$valor_serv = $valor_serv - $valor_serv * ($valor_taxa / 100);
	}
}

// Atualiza o registro na tabela 'receber' para marcar como pago
$pdo->query("UPDATE $tabela SET pago = 'Sim', usuario_baixa = '$id_usuario', data_pagamento = curDate(), valor = '$valor_serv' where id = '$id'");

echo 'Baixado com Sucesso';
