<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'receber';  // Define o nome da tabela no banco de dados
$data_hoje = date('Y-m-d'); // Define a data atual

@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$usuario_logado = @$_SESSION['id']; // Obtém o ID do usuário logado na sessão.

$dataInicial = @$_POST['dataInicial']; // Obtém a data inicial do filtro
$dataFinal = @$_POST['dataFinal']; // Obtém a data final do filtro
$status = '%' . @$_POST['status'] . '%'; // Obtém o status do filtro (ex.: "Pendente", "Pago", etc.)

$total_pago = 0; // Inicializa o total pago
$total_a_pagar = 0; // Inicializa o total a pagar

// Consulta as contas que estão no intervalo de datas e com o status especificado, que são do tipo "Serviço" e do funcionário logado
$query = $pdo->query("SELECT * FROM $tabela where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago LIKE '$status' 
and tipo = 'Serviço' and funcionario = '$usuario_logado' ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

// Se houver registros, gera a tabela com os dados
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th class="esc">Valor</th> 
	<th class="esc">Funcionário</th>
	<th class="esc">Data Serviço</th>		
	<th class="esc">Vencimento</th>	
	<th class="esc">Cliente</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Percorre os registros encontrados e exibe os dados na tabela
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
		$funcionario = $resultado[$i]['funcionario'];
		$obs = $resultado[$i]['obs'];

		$pago = $resultado[$i]['pago'];
		$servico = $resultado[$i]['servico'];

		// Se houver uma comanda associada, altera o valor
		$comanda = $resultado[$i]['comanda'];
		$valor2 = $resultado[$i]['valor2'];

		if ($comanda > 0) {
			$valor = $valor2;
		}

		// Formata o valor e as datas para exibição
		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		// Consulta as informações do cliente associado à conta
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

		// Consulta as informações do usuário que fez a baixa do pagamento
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}

		// Consulta as informações do usuário que lançou a conta
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}

		// Consulta as informações do funcionário associado
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_funcionario = $resultado2[0]['nome'];
		} else {
			$nome_funcionario = 'Sem Referência!';
		}

		// Se a data de pagamento for '0000-00-00', significa que ainda não foi pago
		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger';
			$data_pagamentoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor;
			$japago = 'ocultar';
		} else {
			$classe_alerta = 'verde';
			$visivel = 'ocultar';
			$total_pago += $valor;
			$japago = '';
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

		// Se o vencimento for anterior à data de hoje e o pagamento ainda não foi feito, aplica a classe de débito
		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';
		} else {
			$classe_debito = '';
		}

		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$nome_funcionario}</td>
<td class="esc">{$data_lancamentoF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$nome_pessoa}</td>
<td>
		

		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancamentoF}', '{$data_vencimentoF}',  '{$data_pagamentoF}', 
		'{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}', '{$obs}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>


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

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a title="Baixar Conta" href="#" class="dropdown-toggle {$visivel}" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-check-square verde"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}')"><span class="verde">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

		<big><a class="{$japago}" href="#" onclick="gerarComprovante('{$id}')" title="Gerar Comprovante"><i class="fa fa-file-pdf-o text-primary"></i></a></big>

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
<div align="right">Total Recebido: <span class="verde">R$ {$total_pagoF}</span> </div>
<div align="right">Total à Receber: <span class="text-danger">R$ {$total_a_pagarF}</span> </div>

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
	function editar(id, produto, pessoa, valor, data_vencimento, data_pagamento, foto) {
		$('#id').val(id);
		$('#produto').val(produto).change();
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_vencimento').val(data_vencimento);
		$('#data_pagamento').val(data_pagamento);

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

		$('#target').attr('src', 'img/contas/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#valor_serv').val('');
		$('#data_pagamento').val('<?= $data_hoje ?>');

		$('#valor_serv_agd_restante').val('');
		$('#data_pagamento_restante').val('');
		$('#pagamento_restante').val('').change();


	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, telefone, obs) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancamento_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);

		$('#obs_dados').text(obs);


		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function saida(id, nome, estoque) {

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);

		$('#modalSaida').modal('show');
	}
</script>

<script type="text/javascript">
	function entrada(id, nome, estoque) {

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);

		$('#modalEntrada').modal('show');
	}
</script>

<script type="text/javascript">
	function gerarComprovante(id) {
		let a = document.createElement('a');
		a.target = '_blank';
		a.href = 'rel/comprovante.php?id=' + id;
		a.click();
	}
</script>