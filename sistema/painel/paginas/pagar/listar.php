<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'pagar'; // Define o nome da tabela no banco de dados
$data_hoje = date('Y-m-d'); // Define a data atual

$dataInicial = @$_POST['dataInicial']; // Obtém a data inicial do filtro
$dataFinal = @$_POST['dataFinal']; // Obtém a data final do filtro
$status = '%' . @$_POST['status'] . '%'; // Obtém o status do filtro (ex.: "Pendente", "Pago", etc.)

$total_pago = 0; // Inicializa o total pago
$total_a_pagar = 0; // Inicializa o total a pagar

// Consulta os pagamentos que estão dentro do intervalo de datas e com o status especificado.
$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' and data_vencimento <= '$dataFinal' and pago LIKE '$status' 
ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta quantos registros foram encontrados.// Conta quantos registros foram encontrados.
if ($total_registro > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Descrição</th>	
	<th class="esc">Valor</th> 	
	<th class="esc">Vencimento</th> 	
	<th class="esc">Data Pagamento</th> 
	<th class="esc">Funcionário</th>
	<th class="esc">Fornecedor</th>	
	<th class="esc">Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Itera sobre todos os registros encontrados.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Extrai os dados de cada pagamento.
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
		$funcionario = $resultado[$i]['funcionario'];

		// Formata o valor para exibição no formato brasileiro (com vírgula e ponto)
		$valorF = @number_format($valor, 2, ',', '.');

		// Converte as datas para o formato brasileiro
		$data_lancamentoF = implode('/', array_reverse(@explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(@explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(@explode('-', $data_vencimento)));

		// Consulta as informações do fornecedor associado ao pagamento.
		$query2 = $pdo->query("SELECT * FROM fornecedores where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Se o fornecedor for encontrado, extrai seus dados.
		if ($total_registro2 > 0) {
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa = $resultado2[0]['telefone'];
			$chave_pix_forn = $resultado2[0]['chave_pix'];
			$tipo_chave_forn = $resultado2[0]['tipo_chave'];
			$classe_whats = ''; // Classe para mostrar o WhatsApp (se disponível).
		} else {
			// Caso contrário, define valores padrão para o fornecedor
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = '';
			$classe_whats = 'ocultar';
			$chave_pix_forn = '';
			$tipo_chave_forn = '';
		}

		// Consulta as informações do funcionário que fez o pagamento.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Se o funcionário for encontrado, extrai seus dados.
		if ($total_registro2 > 0) {
			$nome_funcionario = $resultado2[0]['nome'];
			$telefone_funcionario = $resultado2[0]['telefone'];
			$chave_pix_funcionario = $resultado2[0]['chave_pix'];
			$tipo_chave_funcionario = $resultado2[0]['tipo_chave'];
		} else {
			// Caso contrário, define valores padrão para o funcionário.
			$nome_funcionario = 'Nenhum!';
			$telefone_funcionario = '';
			$chave_pix_funcionario = '';
			$tipo_chave_funcionario = '';
		}

		// Consulta o usuário que efetuou o pagamento (baixa).
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Se o usuário de pagamento for encontrado, extrai o nome.
		if ($total_registro2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}

		// Consulta o usuário que fez o lançamento da transação.
		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Se o usuário que lançou for encontrado, extrai o nome.
		if ($total_registro2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}

		// Se o pagamento ainda não foi realizado, exibe como "Pendente" e adiciona ao total a pagar.
		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger';  // Classe para exibir o alerta em vermelho (pendente).
			$data_pagamentoF = 'Pendente';  // Exibe "Pendente" na coluna de data de pagamento.
			$visivel = '';  // Mostra as opções de ação para pagamento.
			$total_a_pagar += $valor;  // Adiciona o valor ao total a pagar.
		} else {
			// Se o pagamento foi realizado, aplica a classe verde e oculta o botão de ação.
			$classe_alerta = 'verde';
			$visivel = 'ocultar';  // Oculta a opção de ação de pagamento.
			$total_pago += $valor;  // Adiciona o valor ao total pago.
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


		// Verifica se o pagamento está vencido e não foi pago ainda, aplica um estilo especial.
		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';  // Estilo para indicar débito (em vermelho escuro).
		} else {
			$classe_debito = '';  // Sem débito (nenhuma classe adicional).
		}

		// Formata o número do telefone para uso no WhatsApp (formato internacional).
		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone_pessoa);

		// Define qual chave Pix exibir, dependendo se for para fornecedor ou funcionário.
		if ($nome_pessoa == 'Nenhum!' and $nome_funcionario != 'Nenhum!') {
			$chave = 'Pix funcionario : Tipo ' . $tipo_chave_funcionario . ' - Chave ' . $chave_pix_funcionario;
		} else if ($nome_funcionario == 'Nenhum!' and $nome_pessoa != 'Nenhum!') {
			$chave = 'Pix Fornecedor : Tipo ' . $tipo_chave_forn . ' - Chave ' . $chave_pix_forn;
		}



		echo <<<HTML
<tr class="{$classe_debito}">
<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$data_vencimentoF}</td>
<td class="esc">{$data_pagamentoF}</td>
<td class="esc">{$nome_funcionario}</td>
<td class="esc">{$nome_pessoa}</td>
<td><a href="img/contas/{$foto}" target="_blank"><img src="img/contas/{$tumb_arquivo}" width="27px" class="mr-2"></a></td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$descricao}', '{$pessoa}', '{$valor}', '{$data_vencimento}', '{$data_pagamento}', '{$tumb_arquivo}', '{$funcionario}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorF}', '{$data_lancamentoF}', '{$data_vencimentoF}',  '{$data_pagamentoF}', '{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$nome_funcionario}', '{$telefone_funcionario}', '{$chave}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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


		<big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp" class="{$classe_whats}"><i class="fa fa-whatsapp verde"></i></a></big>
	
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
	function editar(id, descricao, pessoa, valor, data_vencimento, data_pagamento, foto, func) {
		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_vencimento').val(data_vencimento);
		$('#data_pagamento').val(data_pagamento);
		$('#funcionario').val(func).change();

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src', 'img/contas/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#descricao').val('');
		$('#valor').val('');
		$('#data_pagamento').val('');
		$('#data_venc').val('<?= $data_hoje ?>');
		$('#foto').val('');

		$('#target').attr('src', 'img/contas/sem-foto.jpg');
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, nome_funcionario, telefone_funcionario, chave) {

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancamento_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#nome_funcionario_dados').text(nome_funcionario);
		$('#telefone_funcionario_dados').text(telefone_funcionario);
		$('#chave_dados').text(chave);

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