<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dado
$data_hoje = date('Y-m-d'); // Define a data atual

// Obtém os parâmetros enviados via POST para filtrar os registros
$dataInicial = @$_POST['dataInicial']; // Data inicial do filtro
$dataFinal = @$_POST['dataFinal']; // Data final do filtro
$status = '%' . @$_POST['status'] . '%'; // Status, com % para filtro parcial
$funcionario = @$_POST['funcionario']; // ID do funcionário
$funcionario2 = $_POST['funcionario']; // Outra variável para ID do funcionário


// Consulta para pegar o nome do funcionário baseado no ID
$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario2'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);

// Verifica se o funcionário foi encontrado e armazena seu nome, caso contrário, define uma mensagem
if ($total_registro2 > 0) {
	$nome_funcionario2 = $resultado2[0]['nome'];
} else {
	$nome_funcionario2 = 'Sem Referência!';
}

// Inicializa as variáveis para armazenar o total de valores pagos, a pagar e pendentes
$total_pago = 0;
$total_a_pagar = 0;
$total_pendente = 0;

// Se o ID do funcionário não for informado, consulta todos os registros de acordo com as datas e status
if ($funcionario == "") {
	$query = $pdo->query("SELECT * FROM $tabela where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago 
	LIKE '$status' and tipo = 'Comissão' ORDER BY pago asc, data_vencimento asc");
} else {
	// Caso o ID do funcionário seja informado, filtra também por esse funcionário
	$query = $pdo->query("SELECT * FROM $tabela where data_lancamento >= '$dataInicial' and data_lancamento <= '$dataFinal' and pago 
	LIKE '$status' and funcionario = '$funcionario' and tipo = 'Comissão' ORDER BY pago asc, data_vencimento asc");
}

// Executa a consulta e armazena o resultado
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Se houver registros, exibe a tabela com os dados
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
	// Loop para exibir cada registro encontrado na consulta
	for ($i = 0; $i < $total_registro; $i++) {
		// Acessa cada coluna do registro, embora não seja necessário neste trechos
		foreach ($resultado[$i] as $key => $value) {
		}
		// Atribui os valores do registro a variáveis
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
		$cliente = $resultado[$i]['cliente'];
		$pago = $resultado[$i]['pago'];
		$servico = $resultado[$i]['servico'];

		// Formata o valor e as datas
		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		// Consulta para obter os dados do cliente baseado no ID da pessoa
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

		// Consulta para obter o nome do usuário que fez a baixa do pagamento
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}

		// Consulta para obter o nome do cliente
		$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_cliente = $resultado2[0]['nome'];
		} else {
			$nome_cliente = 'Nenhum!';
		}

		// Consulta para obter o nome do usuário que fez o lançamento
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}

		// Consulta para obter o nome do funcionário baseado no ID
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_funcionario = $resultado2[0]['nome'];
			$chave_pix_funcinario = $resultado2[0]['chave_pix'];
			$tipo_chave_funcionario = $resultado2[0]['tipo_chave'];
		} else {
			$nome_funcionario = 'Sem Referência!';
			$chave_pix_funcinario = '';
			$tipo_chave_funcionario = '';
		}

		// Consulta para obter o nome do serviço
		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_serv = $resultado2[0]['nome'];
		} else {
			$nome_serv = 'Sem Referência!';
		}

		// Verifica se o pagamento está pendente ou já foi pago
		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger'; // Alerta vermelho para pagamento pendente
			$data_pagamentoF = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor; // Atualiza o total a pagar
			$total_pendente += 1; // Atualiza o total de pendentes
		} else {
			$classe_alerta = 'verde'; // Alerta verde para pagamento realizado
			$visivel = 'ocultar'; // Oculta a linha
			$total_pago += $valor; // Atualiza o total pago
		}


		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png'; // Se for PDF, usa uma miniatura do PDF
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';  // Se for RAR ou ZIP, usa uma miniatura de arquivo compactado
		} else {
			$tumb_arquivo = $foto; // Caso contrário, usa a própria foto
		}

		// Verifica se o pagamento está vencido
		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro'; // Alerta vermelho para débito
		} else {
			$classe_debito = ''; // Sem alerta para débito
		}



		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$nome_serv}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$nome_funcionario}</td>
<td class="esc">{$data_lancamentoF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$nome_cliente}</td>
<td>
		
        <!-- Ícone para visualizar mais detalhes do cliente -->
		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancamentoF}', '{$data_vencimentoF}',  '{$data_pagamentoF}', '{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}', '{$nome_funcionario}', '{$tipo_chave_funcionario}', '{$chave_pix_funcionario}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>


         <!-- Ícone de exclusão com menu suspenso para confirmar a ação -->
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

        <!-- Ícone de "Baixar Conta" com menu suspenso para confirmar a baixa do pagamento -->
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

		var func = '<?= $nome_funcionario2 ?>';
		$('#titulo_inserir').text(func);
		$('#total_pagamento').text('<?= $total_a_pagarF ?>');
		$('#total_comissoes').text('<?= $total_pendente ?>');

		$('#id_funcionario').val('<?= $funcionario ?>');
		$('#data_inicial').val('<?= $dataInicial ?>');
		$('#data_final').val('<?= $dataFinal ?>');



		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<!-- Função para mostrar detalhes do cliente no modal -->
<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, telefone, func, tipo_chave, chave_pix) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancamento_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);
		$('#nome_func_dados').text(func);
		$('#tipo_chave_dados').text(tipo_chave);
		$('#chave_pix_dados').text(chave_pix);

		$('#link_mostrar').attr('href', 'img/contas/' + link);
		$('#target_mostrar').attr('src', 'img/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>