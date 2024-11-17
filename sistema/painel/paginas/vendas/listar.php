<?php
@session_start(); // Inicia a sessão ou retoma a sessão ativa.
$nivel_usuario = @$_SESSION['nivel']; // Obtém o nível do usuário logado.
$id_usuario1 = @$_SESSION['id']; // Obtém o ID do usuário logado.
require_once("../../../conexao.php"); // Conecta ao banco de dados.

$tabela = 'receber'; // Define a tabela 'receber' onde estão os dados das contas a receber.
$data_hoje = date('Y-m-d'); // Obtém a data atual no formato 'YYYY-MM-DD'.

// Recebe as variáveis passadas via POST: intervalo de datas e status do pagamento.
$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%' . @$_POST['status'] . '%'; // Prepara a busca com o status do pagamento (usando LIKE para buscar padrões).

// Inicializa as variáveis para somar os totais pagos e a pagar.
$total_pago = 0;
$total_a_pagar = 0;

// Se o usuário for administrador, realiza a consulta sem filtro de usuário.
if ($nivel_usuario == "Administrador") {
	$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' and data_vencimento <= '$dataFinal' and pago 
	LIKE '$status' and produto != 0 ORDER BY pago asc, data_vencimento asc");
} else {
	// Caso contrário, realiza a consulta filtrando pelas contas lançadas pelo usuário logado.
	$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' and data_vencimento <= '$dataFinal' and pago 
	LIKE '$status' and produto != 0 and usuario_lancouou = '$id_usuario1' ORDER BY pago asc, data_vencimento asc");
}

$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Se houver registros, exibe a tabela com os dados.
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th class="esc">Valor</th> 	
	<th class="esc">Vencimento</th> 	
	<th class="esc">Data Pagamento</th> 
	<th class="esc">Vendido Por</th>
	<th class="esc">Cliente</th>	
	<th class="esc">Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id']; // ID do registro.
		$descricao = $resultado[$i]['descricao']; // Descrição do produto.
		$valor = $resultado[$i]['valor']; // Valor da conta.
		$data_lancamento = $resultado[$i]['data_lancamento']; // Data de lançamento da conta.
		$data_pagamento = $resultado[$i]['data_pagamento']; // Data de pagamento da conta.
		$data_vencimento = $resultado[$i]['data_vencimento']; // Data de vencimento da conta.
		$usuario_lancou = $resultado[$i]['usuario_lancou']; // Usuário que lançou a conta.
		$usuario_baixa = $resultado[$i]['usuario_baixa']; // Usuário que deu baixa no pagamento.
		$foto = $resultado[$i]['foto']; // Foto associada ao pagamento ou documento.
		$pessoa = $resultado[$i]['pessoa']; // ID da pessoa associada à conta.
		$produto = $resultado[$i]['produto']; // ID do produto.
		$pago = $resultado[$i]['pago']; // Status de pagamento.
		$funcionario = $resultado[$i]['funcionario']; // ID do funcionário que fez a venda.
		$valor2 = $resultado[$i]['valor2']; // Valor alternativo para o pagamento (se houver).

		// Se o valor for menor ou igual a zero, usa o valor alternativo.
		if ($valor <= 0) {
			$valor = $valor2;
		}

		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		// Consulta para obter o nome e telefone do cliente associado à conta.
		$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_pessoa = $resultado2[0]['nome']; // Nome do cliente.
			$telefone_pessoa = $resultado2[0]['telefone']; // Telefone do cliente.
		} else {
			$nome_pessoa = 'Nenhum!'; // Caso não exista cliente.
			$telefone_pessoa = 'Nenhum'; // Caso não exista telefone.
		}

		// Consultas para obter o nome dos usuários responsáveis pelas ações de baixa, lançamento e venda.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome']; // Nome do usuário que deu baixa.
		} else {
			$nome_usuario_pagamento = 'Nenhum!'; // Caso não exista.
		}


		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_vendedor = $resultado2[0]['nome']; // Nome do vendedor (funcionário)
		} else {
			$nome_vendedor = '';
		}

		// Consulta para obter o nome do usuário que lançou a conta.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome']; // Nome do usuário que lançou a conta.
		} else {
			$nome_usuario_lancou = 'Sem Referência!'; // Caso não exista referência.
		}

		// Se a data de pagamento for '0000-00-00', significa que o pagamento ainda não foi feito.
		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger'; // Classe para destacar contas pendentes.
			$data_pagamentoF = 'Pendente'; // Exibe 'Pendente' no campo de data de pagamento.
			$visivel = ''; // Torna visível o status de pendência.
			$total_a_pagar += $valor; // Soma ao total a pagar.
			$japago = 'ocultar'; // Esconde a opção de pagamento.
		} else {
			$classe_alerta = 'verde'; // Classe para contas pagas.
			$visivel = 'ocultar'; // Torna invisível a opção de pendência.
			$total_pago += $valor; // Soma ao total pago.
			$japago = ''; // Exibe a opção de pagamento.
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

		// Se a data de vencimento for menor que a data de hoje e o pagamento ainda não foi feito, destaca a linha em vermelho.
		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';
		} else {
			$classe_debito = '';
		}



		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$data_pagamentoF}</td>
<td class="esc">{$nome_vendedor}</td>
<td class="esc">{$nome_pessoa}</td>
<td><a href="img/contas/{$foto}" target="_blank"><img src="img/contas/{$tumb_arquivo}" width="27px" class="mr-2"></a></td>
<td>
		

		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancamentoF}', '{$data_vencimentoF}',  '{$data_pagamentoF}', '{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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
		$('#pessoa').val(0).change();
		$('#valor').val('');
		$('#data_pagamento').val('');
		$('#data_vencimento').val('<?= $data_hoje ?>');
		$('#foto').val('');
		$('#quantidade').val('1');

		$('#target').attr('src', 'img/contas/sem-foto.jpg');
		calcular()
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, telefone) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancamento_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);

		$('#link_mostrar').attr('href', 'img/contas/' + link);
		$('#target_mostrar').attr('src', 'img/contas/' + foto);

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
		a.href = 'rel/comprovante_venda.php?id=' + id;
		a.click();
	}
</script>