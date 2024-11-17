<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber'; // Define o nome da tabela no banco de dado
$data_atual = date('Y-m-d'); // Define a data atual

$id = $_POST['id'];  // Recebe o ID do cliente via POST.
$id_cli = $_POST['id']; // Redefine a variável id_cli com o valor de 'id'.

// Verifica se a página atual está definida; se não, define como 0.
if (@$_POST['pagina'] == "") {
	@$_POST['pagina'] = 0;
}

// Realiza uma consulta para buscar todas as contas do cliente que não foram pagas.
$query = $pdo->query("SELECT * FROM $tabela where pessoa = '$id' and pago != 'Sim' ORDER BY data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número total de registros retornados.
// Verifica se há registros a serem exibidos.
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th class="">Descrição Conta</th>
	<th>Data Vencimento</th>	
	<th class="">Valor</th>
	<th class="">Dar Baixa</th>	
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Variáveis para armazenar totais de valores.
	$total = 0;
	$total_pagar = 0;
	$total_vencido = 0;
	$totalF = 0;
	$total_pagarF = 0;
	$total_vencidoF = 0;
	// Itera pelos registros e exibe as informações de cada conta.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$descricao = $resultado[$i]['descricao'];
		$tipo = $resultado[$i]['tipo'];
		$valor = $resultado[$i]['valor'];
		$data_lancamento = $resultado[$i]['data_lancamento'];
		$data_pagamento = $resultado[$i]['data_pagamento'];
		$data_vencimento = $resultado[$i]['data_vencimento'];
		$usuario_lancou = $resultado[$i]['usuario_lancou'];
		$usuario_baixa = $resultado[$i]['usuario_baixa'];
		$foto = $resultado[$i]['foto'];
		$pessoa = $resultado[$i]['pessoa'];
		$pago = $resultado[$i]['pago'];

		// Formata o valor e as datas para exibição.
		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		$total += $valor; // Soma o valor total.
		$totalF = number_format($total, 2, ',', '.'); // Formata o total.

		// Verifica se a data de vencimento passou da data atual.
		$classe_data = '';
		if (strtotime($data_vencimento) < strtotime($data_atual)) {
			$classe_data = 'text-danger';
			$total_vencido += $valor; // Soma o total vencido.
		} else {
			$total_pagar += $valor; // Soma o total a pagar.
		}

		$total_pagarF = number_format($total_pagar, 2, ',', '.');
		$total_vencidoF = number_format($total_vencido, 2, ',', '.');
		// Exibe a linha com as informações da conta.
		echo <<<HTML
<tr class="">
<td>{$descricao}</td>
<td class="{$classe_data}">{$data_vencimentoF}</td>
<td class="text-danger">R$ {$valor}</td>
<td>
	<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a title="Baixar Conta" href="#" class="dropdown-toggle " data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-check-square verde"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}', '{$id_cli}')"><span class="verde">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>
</td>
</tr>
HTML;
	}
	// Exibe os totais de valores no final da tabela.
	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir-baixar"></div></small>
</table>
<div align="right"><span style="margin-right: 20px; ">Total Vencido <span style="color:red">R$ {$total_vencidoF}</span></span>
<span style="margin-right: 20px; ">Total à Vencer <span style="color:blue">R$ {$total_pagarF}</span></span>
<span >Total Pagar <span style="color:green">R$ {$totalF}</span></span>
</div>
</small>

HTML;
} else {
	// Exibe uma mensagem caso não haja contas pendentes.
	echo '<small>Este Cliente não possui pagamento pendente!</small>';
}
