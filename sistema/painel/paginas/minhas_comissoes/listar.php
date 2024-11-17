<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dados
$data_hoje = date('Y-m-d'); // Define a data atual

@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$id_usuario = $_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$dataInicial = @$_POST['dataInicial']; // Obtém a data inicial do filtro do formulário
$dataFinal = @$_POST['dataFinal']; // Obtém a data final do filtro do formulário
$status = '%' . @$_POST['status'] . '%'; // Obtém o status da comissão (ex: Paga ou Pendente)
$funcionario = $id_usuario; // Define o ID do funcionário logado como parâmetro

// Consulta informações do usuário (funcionário)
$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
if ($total_registro2 > 0) {
	$nome_funcionario2 = $resultado2[0]['nome']; // Obtém o nome do funcionário
} else {
	$nome_funcionario2 = 'Sem Referência!';
}

// Inicializa as variáveis de totalização dos pagamentos
$total_pago = 0;
$total_a_pagar = 0;
$total_pendente = 0;

// Consulta as comissões do funcionário no intervalo de datas
$query = $pdo->query("SELECT * FROM $tabela where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago LIKE '$status' 
and funcionario = '$funcionario' and tipo = 'Comissão' ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th class="esc">Valor</th> 	
	<th class="esc">Data Serviço</th>		
	<th class="esc">Vencimento</th>	
	<th class="esc">Paga Em</th>
	<th class="esc">Cliente</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Itera sobre os registros retornados da consulta
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
		$usuario_lanc = $resultado[$i]['usuario_lanc'];
		$usuario_baixa = $resultado[$i]['usuario_baixa'];
		$foto = $resultado[$i]['foto'];
		$pessoa = $resultado[$i]['pessoa'];
		$funcionario = $resultado[$i]['funcionario'];
		$cliente = $resultado[$i]['cliente'];

		$pago = $resultado[$i]['pago'];
		$servico = $resultado[$i]['servico'];

		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		// Obtém informações do cliente
		$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa = $resultado2[0]['telefone'];
		} else {
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = 'Nenhum';
		}

		// Obtém informações do usuário que fez o pagamento (baixa)
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}

		// Obtém informações do cliente final (quem recebeu o serviço)
		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_cliente = $resultado2[0]['nome'];
		} else {
			$nome_cliente = 'Nenhum!';
		}

		// Obtém informações do usuário que registrou a comissão (lancou)
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}

		// Obtém o nome do funcionário associado
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_funcinario = $resultado2[0]['nome']; //nome_func
		} else {
			$nome_funcionario = 'Sem Referência!';
		}

		// Obtém informações sobre o serviço
		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_serv = $resultado2[0]['nome']; // Nome do serviço
		} else {
			$nome_serv = 'Sem Referência!';
		}

		// Verifica o status de pagamento (Pendente ou Pago)
		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger'; // Classe para visualização de pendência
			$data_pagamentoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor; // Somatório do valor a pagar
			$total_pendente += 1; // Contador de pendentes
		} else {
			$classe_alerta = 'verde'; // Classe para pagamento efetuado
			$visivel = 'ocultar'; // Esconde informações de pagamento
			$total_pago += $valor; // Somatório do valor pago
		}

		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else {
			$tumb_arquivo = $foto;
		}

		// Verifica se o pagamento está atrasado (não pago e vencido)
		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro'; // Classe de alerta para débito vencido
		} else {
			$classe_debito = '';  // Sem débito
		}

		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$nome_serv}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$data_lancamentoF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$data_pagamentoF}</td>
<td class="esc">{$nome_cliente}</td>
<td>
		
		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancamentoF}', '{$data_vencimentoF}',  '{$data_pagamentoF}', '{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}', '{$nome_funcionario}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

		</td>
</tr>
HTML;
	}

	$total_pagoF = number_format($total_pago, 2, ',', '.');
	$total_a_pagarF = number_format($total_a_pagar, 2, ',', '.');

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>

<br>	
<div align="right">Total Pago: <span class="verde">R$ {$total_pagoF}</span> </div>
<div align="right">Total à Pagar: <span class="text-danger">R$ {$total_a_pagarF}</span> </div>

</small>
HTML;
} else {
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {

		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, telefone, func) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancamento_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);
		$('#nome_funcionario_dados').text(func);

		$('#link_mostrar').attr('href', 'img/contas/' + link);
		$('#target_mostrar').attr('src', 'img/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>