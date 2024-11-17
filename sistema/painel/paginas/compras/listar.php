<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar';  // Define o nome da tabela no banco de dado
$data_hoje = date('Y-m-d'); // Define a data atual

// Obtém os parâmetros enviados via POST para filtrar os registros
$dataInicial = @$_POST['dataInicial']; // Data inicial do filtro
$dataFinal = @$_POST['dataFinal']; // Data final do filtro
$status = '%' . @$_POST['status'] . '%'; // Status, com % para filtro parcial


$total_pago = 0; // Inicializa a variável para armazenar o total pago.
$total_a_pagar = 0; // Inicializa a variável para armazenar o total a pagar.

# Consulta ao banco de dados para pegar os registros filtrados.	
$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' and data_vencimento <= '$dataFinal' and pago 
LIKE '$status' and produto != 0 ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

# Se houver registros, começa a exibição dos dados em formato de tabela.
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
	
	<th class="esc">Fornecedor</th>	
	<th class="esc">Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	# Loop para percorrer todos os registros retornados e exibir os dados.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		# Acessa os dados de cada registro.
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
		$produto = $resultado[$i]['produto'];
		$pago = $resultado[$i]['pago'];

		# Formata o valor e as datas para exibição.
		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));

		# Consulta o fornecedor associado ao pagamento.
		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa = $resultado2[0]['telefone'];
		} else {
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = 'Nenhum';
		}

		# Consulta o usuário que fez o pagamento.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}

		# Consulta o usuário que lançou o pagamento.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}

		# Verifica se o pagamento foi realizado ou está pendente, e ajusta a aparência da linha.
		if ($pago != 'Sim') {
			$classe_alerta = 'text-danger'; # Marca a linha como pendente (vermelha).
			$data_pagamentoF = 'Pendente';  # Exibe 'Pendente' na coluna de Data de Pagamento.
			$visivel = ''; # Ações de baixar não estão visíveis.
			$total_a_pagar += $valor;  # Soma o valor ao total a pagar.
		} else {
			$classe_alerta = 'verde'; # Marca a linha como paga (verde).
			$visivel = 'ocultar'; # Ações de baixar estão ocultas.
			$total_pago += $valor;  # Soma o valor ao total pago.
		}

		# Verifica a extensão do arquivo e define o ícone para visualização.
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';  # Exibe o ícone do PDF.
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';  # Exibe o ícone para arquivos comprimidos.
		} else {
			$tumb_arquivo = $foto;  # Caso contrário, exibe a própria imagem.
		}

		# Verifica se o vencimento já passou e o pagamento ainda não foi feito.
		if ($data_vencimento < $data_hoje && $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro'; # Marca como débito em atraso (escuro).
		} else {
			$classe_debito = '';  # Sem alteração de classe se não estiver atrasado.
		}

		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$data_pagamentoF}</td>

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
		$('#data_venc').val('<?= $data_hoje ?>');
		$('#foto').val('');
		$('#quantidade').val('1');
		$('#target').attr('src', 'img/contas/sem-foto.jpg');
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