<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui um arquivo para verificar a sessão e a autorização do usuário.
require_once("../conexao.php");  // Conecta ao banco de dados para obter informações necessárias.

// Define a variável para identificar a página atual.
$pag = 'comanda';

//verificar se ele tem a permissão de estar nessa página
if (@$comanda == 'ocultar') {
	// Redireciona para a página principal se não tiver permissão.
	echo "<script>window.location='../index.php'</script>";
	exit();
}

// Define datas úteis para filtros e operações no sistema.
$data_hoje = date('Y-m-d'); // Data de hoje.
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje))); // Data de ontem.

$mes_atual = Date('m');  // Mês atual.
$ano_atual = Date('Y'); // Ano atual.
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";  // Define o início do mês atual.

// Define o último dia do mês com base no mês atual.
if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
	$dia_final_mes = '30';
} else if ($mes_atual == '2') {
	$dia_final_mes = '28';
} else {
	$dia_final_mes = '31';
}
// Data final do mês atual.
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

?>

<div class="">
	<!-- Botão para criar uma nova comanda -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Nova Comanda</a>
</div>

<div class="bs-example" style="padding:15px">
	<!-- Formulário para filtragem das comandas por data e status -->
	<div class="row" style="margin-top: -20px">
		<div class="col-md-5" style="margin-bottom:5px;">
			<!-- Campo para a data inicial da busca -->
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:20px">
				<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_hoje ?>" required>
			</div>
			<!-- Campo para a data final da busca -->
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:30px">
				<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_hoje ?>" required>
			</div>
		</div>
		<!-- Links rápidos para selecionar datas (Ontem, Hoje, Mês) -->
		<div class="col-md-2" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
					<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
					<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
				</small>
			</div>
		</div>
		<!-- Links rápidos para filtro de comandas por status (Todas, Abertas, Fechadas) -->
		<div class="col-md-3" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Todas as Comandas" class="text-muted" href="#" onclick="buscarContas('')"><span>Todas</span></a> /
					<a title="Abertas" class="text-muted" href="#" onclick="buscarContas('Aberta')"><span>Abertas</span></a> /
					<a title="Fechadas" class="text-muted" href="#" onclick="buscarContas('Fechada')"><span>Fechadas</span></a>
				</small>
			</div>
		</div>
		<!-- Define o valor padrão de busca como "Aberta" -->
		<input type="hidden" id="buscar-contas" value="Aberta">

	</div>

	<!-- Local para exibir a lista de comandas após a busca -->
	<div id="listar">

	</div>

</div>

<!-- Modal para inserção de uma nova comanda -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" style="">
	<div class="modal-dialog modal-lg" role="document" style="width:80%; " id="modal_scrol">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_comanda">Nova Comanda</span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form_salvar">
				<div class="modal-body">
					<!-- Formulário de seleção de cliente e observações -->
					<div class="row">
						<div class="col-md-8" style="border-right: 1px solid #6e6d6d; overflow: scroll; height:auto; max-height: 350px; scrollbar-width: thin;">
							<div class="col-md-6">
								<div class="form-group">
									<label>Cliente</label>
									<select class="form-control sel2" id="cliente" name="cliente" style="width:100%;" required>
										<option value="">Selecionar Cliente</option>
										<?php
										// Loop para listar todos os clientes do banco de dados
										$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
										$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_registro = @count($resultado);
										if ($total_registro > 0) {
											for ($i = 0; $i < $total_registro; $i++) {
												foreach ($resultado[$i] as $key => $value) {
												}
												echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
											}
										}
										?>

									</select>
								</div>
							</div>
							<!-- Observações sobre a comanda -->
							<div class="col-md-6">
								<div class="form-group">
									<label>Observações </label>
									<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2">
								</div>
							</div>
							<!-- Campos para adicionar serviços e produtos à comanda -->
							<div class="col-md-12" style="border-top: 1px solid #cecece; margin-bottom: 5px;">
								<!-- Seleção de serviço -->
								<div class="col-md-5" style="margin-left: -17px; margin-top: 10px">
									<div class="form-group">
										<label>Serviço</label>
										<select class="form-control sel2" id="servico" name="servico" style="width:100%;" required>

											<?php
											// Loop para listar todos os serviços do banco de dados
											$query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
											$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_registro = @count($resultado);
											if ($total_registro > 0) {
												for ($i = 0; $i < $total_registro; $i++) {
													foreach ($resultado[$i] as $key => $value) {
													}
													echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
												}
											}
											?>

										</select>
									</div>
								</div>
								<!-- Seleção do profissional para o serviço -->
								<div class="col-md-5" style="margin-top: 10px">
									<div class="form-group">
										<label>Profissional</label>
										<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" required onchange="">

											<?php
											// Loop para listar profissionais disponíveis
											$query = $pdo->query("SELECT * FROM usuarios01 where atendimento = 'Sim' ORDER BY nome asc");
											$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_registro = @count($resultado);
											if ($total_registro > 0) {
												for ($i = 0; $i < $total_registro; $i++) {
													foreach ($resultado[$i] as $key => $value) {
													}
													echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
												}
											}
											?>

										</select>
									</div>
								</div>

								<div class="col-md-2" style="margin-top: 30px">
									<!-- Botão para adicionar um serviço à comanda -->
									<a href="#" onclick="inserirServico()" class="btn btn-primary"><i class="fa fa-plus"></i></a>
								</div>
								<!-- Este div servirá para listar os serviços adicionados à comanda -->
								<div class="col-md-12" style="border: 1px solid #5c5c5c; margin-bottom: 5px; margin-left: -17px;" id="listar_servicos">

								</div>

								<div class="col-md-5" style="margin-top: 10px;margin-left: -17px;">
									<div class="form-group">
										<label>Produtos</label>
										<!-- Seleção de produtos, mostrando apenas produtos com estoque maior que 0 -->
										<select class="form-control sel2" id="produto" name="produto" style="width:100%;" required onchange="listarServicos()">

											<?php
											// Consulta para obter todos os produtos com estoque positivo
											$query = $pdo->query("SELECT * FROM produtos where estoque > 0 ORDER BY nome asc");
											$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_registro = @count($resultado);
											if ($total_registro > 0) {
												for ($i = 0; $i < $total_registro; $i++) {
													foreach ($resultado[$i] as $key => $value) {
													}
													echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
												}
											}
											?>

										</select>
									</div>
								</div>

								<div class="col-md-2" id="" style="margin-top: 10px;">
									<div class="form-group">
										<label>Quantidade </label>
										<!-- Campo para inserir a quantidade do produto -->
										<input type="number" class="form-control" name="quantidade" id="quantidade" value="1">
									</div>
								</div>

								<div class="col-md-4" style="margin-top: 10px">
									<div class="form-group">
										<label>Profissional</label>
										<!-- Seleção de profissional para o serviço -->
										<select class="form-control sel2" id="funcionario2" name="funcionario2" style="width:100%;" required onchange="listarServicos()">
											<option value="0">Nenhum</option>
											<?php
											// Obtém os profissionais que não são administradores
											$query = $pdo->query("SELECT * FROM usuarios01 where nivel != 'Administrador' ORDER BY nome asc");
											$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
											$total_registro = @count($resultado);
											if ($total_registro > 0) {
												for ($i = 0; $i < $total_registro; $i++) {
													foreach ($resultado[$i] as $key => $value) {
													}
													echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
												}
											}
											?>

										</select>
									</div>
								</div>

								<div class="col-md-1" style="margin-top: 30px">
									<!-- Botão para adicionar um produto à comanda -->
									<a href="#" onclick="inserirProduto()" class="btn btn-primary"><i class="fa fa-plus"></i></a>
								</div>
								<!-- Este div será utilizado para listar os produtos adicionados à comanda -->
								<div class="col-md-12" style="border: 1px solid #5c5c5c; margin-bottom: 5px; margin-left: -17px;" id="listar_produtos">

								</div>

							</div>

						</div>

						<div class="col-md-4">
							<div class="col-md-5" id="nasc">
								<div class="form-group">
									<label><small>Valor</small> </label>
									<!-- Campo para inserir o valor do serviço -->
									<input type="text" class="form-control inputs_form" name="valor_serv" id="valor_serv">
								</div>
							</div>

							<div class="col-md-7" id="nasc">
								<div class="form-group">
									<label><small>Data Pagamento</small></label>
									<!-- Campo para inserir a data de pagamento do serviço -->
									<input type="date" class="form-control inputs_form" name="data_pagamento" id="data_pagamento" value="<?php echo date('Y-m-d') ?>">
								</div>
							</div>

							<div class="col-md-12" style="border-bottom: 1px solid #a8a7a7">
								<div class="form-group">
									<label><small>Forma pagamento</small></label>
									<!-- Seleção de forma de pagamento -->
									<select class="form-control inputs_form" id="pagamento" name="pagamento" style="width:100%;" required>

										<?php
										// Obtém as formas de pagamento disponíveis
										$query = $pdo->query("SELECT * FROM formas_pagamento");
										$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_registro = @count($resultado);
										if ($total_registro > 0) {
											for ($i = 0; $i < $total_registro; $i++) {
												foreach ($resultado[$i] as $key => $value) {
												}
												echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
											}
										}
										?>

									</select>
								</div>
							</div>

							<div class="col-md-5" id="" style="margin-top: 10px">
								<div class="form-group">
									<label><small>Valor Restante</small> </label>
									<!-- Campo para inserir valor restante, atualizando automaticamente -->
									<input type="text" class="form-control inputs_form" name="valor_serv_agd_restante" id="valor_serv_agd_restante" onkeyup="abaterValor()">
								</div>
							</div>

							<div class="col-md-7" id="" style="margin-top: 10px">
								<div class="form-group">
									<label><small>Data Pagamento Restante</small></label>
									<!-- Campo para inserir a data de pagamento do valor restante -->
									<input type="date" class="form-control inputs_form" name="data_pagamento_restante" id="data_pagamento_restante" value="">
								</div>
							</div>

							<div class="col-md-12" style="border-bottom: 1px solid #a8a7a7">
								<div class="form-group">
									<label><small>Forma pagamento Restante</small></label>
									<!-- Seleção de forma de pagamento para o valor restante -->
									<select class="form-control inputs_form" id="pagamento_restante" name="pagamento_restante" style="width:100%;">
										<option value="">Selecionar pagamento</option>
										<?php
										// Obtém novamente as formas de pagamento
										$query = $pdo->query("SELECT * FROM formas_pagamento");
										$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_registro = @count($resultado);
										if ($total_registro > 0) {
											for ($i = 0; $i < $total_registro; $i++) {
												foreach ($resultado[$i] as $key => $value) {
												}
												echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
											}
										}
										?>

									</select>
								</div>
							</div>

							<div class="col-md-12" id="" style="margin-top: 10px">
								<div class="form-group">
									<label><small>Fechar comanda ao Salvar</small></label>
									<!-- Seleção para decidir se a comanda será fechada ao salvar -->
									<select class="form-control inputs_form" id="salvar_comanda" name="salvar_comanda" style="width:100%;">
										<option value="">Não</option>
										<option value="Sim">Sim</option>
									</select>
								</div>
							</div>

							<div class="col-md-12" align="right">
								<!-- Botão para fechar a comanda e salvar -->
								<a href="#" id="btn_fechar_comanda" onclick="fecharComanda()" class="btn btn-success">Fechar Comanda</a>

								<button type="submit" class="btn btn-primary">Salvar</button>
							</div>

						</div>

					</div>
					<!-- Campos ocultos para enviar dados adicionais com o formulário -->
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="valor_servicos" id="valor_servicos">
					<input type="hidden" name="valor_produtos" id="valor_produtos">

					<br>
					<small>
						<div id="mensagem" align="center"></div>
					</small>
				</div>

			</form>

		</div>
	</div>
</div>

<!-- Modal Dados-->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel">Informações da Comanda</h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Corpo do Modal: Exibe informações da comanda -->
			<div class="modal-body">
				<!-- Linha de informações do cliente e valor -->
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-8">
						<!-- Exibe o nome do cliente -->
						<span><b>Cliente: </b></span>
						<span id="cliente_dados"></span>
					</div>
					<div class="col-md-4">
						<!-- Exibe o valor total da comanda -->
						<span><b>Valor: </b></span>
						<span id="valor_dados"></span>
					</div>

				</div>
				<!-- Linha com informações sobre quem abriu a comanda e data de abertura -->
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-8">
						<!-- Exibe o nome do funcionário que abriu a comanda -->
						<span><b>Aberta Por: </b></span>
						<span id="func_dados"></span>
					</div>
					<div class="col-md-4">
						<!-- Exibe a data em que a comanda foi aberta -->
						<span><b>Data: </b></span>
						<span id="data_dados"></span>
					</div>

				</div>
				<!-- Seção para listar serviços e produtos associados à comanda -->
				<div class="row">
					<!-- Aki serão listados os serviços associados à comanda -->
					<div class="col-md-12" style="border: 1px solid #5c5c5c; margin-bottom: 5px;" id="listar_servicos_dados">

					</div>
				</div>

				<div class="row">
					<!-- Aki serão listados os produtos associados à comanda -->
					<div class="col-md-12" style="border: 1px solid #5c5c5c; margin-bottom: 5px; " id="listar_produtos_dados">

					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		var id = $("#id").val();
		listarServicos(id) // Lista os serviços associados à comanda
		listarProdutos(id) // Lista os produtos associados à comanda
		calcular() // Calcula o valor total da comanda

		// Inicializa o select2 para filtros com dropdown
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<script type="text/javascript">
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar();

	}
</script>

<script type="text/javascript">
	$('#data-inicial-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});

	$('#data-final-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});
</script>

<script type="text/javascript">
	function listar() {

		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();

		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial,
				dataFinal,
				status
			},
			dataType: "html",

			success: function(result) {
				// Atualiza a lista com os resultados obtidos
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar(); // Recarrega a lista com o novo status
	}
</script>

<script type="text/javascript">
	function calcular() {

		setTimeout(function() {
			var produtos = $('#valor_produtos').val();
			var servicos = $('#valor_servicos').val();

			var total = parseFloat(produtos) + parseFloat(servicos);
			$('#valor_serv').val(total.toFixed(2)); // Exibe o total calculado

			// Chama a função para ajustar o valor após pagamento
			abaterValor();

		}, 500)

	}
</script>

<script type="text/javascript">
	function inserirServico() {
		$("#mensagem").text('');
		var servico = $("#servico").val();
		var funcionario = $("#funcionario").val();
		var cliente = $("#cliente").val();
		var id = $("#id").val();

		if (cliente == "") {
			alert("Selecione um Cliente")
			return;
		}

		if (servico == "") {
			alert("Selecione um Serviço")
			return;
		}
		$.ajax({
			url: 'paginas/' + pag + "/inserir_servico.php",
			method: 'POST',
			data: {
				servico,
				funcionario,
				cliente,
				id
			},
			dataType: "text",

			success: function(result) {
				if (result.trim() === 'Salvo com Sucesso') {
					listarServicos(id)
					calcular();
				} else {
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>

<script type="text/javascript">
	function listarServicos(id) {

		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				$("#listar_servicos").html(result);
			}
		});
	}
</script>

<script type="text/javascript">
	function inserirProduto() {
		$("#mensagem").text('');
		var produto = $("#produto").val();
		var funcionario = $("#funcionario2").val();
		var cliente = $("#cliente").val();
		var quantidade = $("#quantidade").val();
		var id = $("#id").val();

		if (produto == "") {
			alert("Selecione um Produto")
			return;
		}
		$.ajax({
			url: 'paginas/' + pag + "/inserir_produto.php",
			method: 'POST',
			data: {
				produto,
				funcionario,
				cliente,
				quantidade,
				id
			},
			dataType: "text",

			success: function(result) {
				if (result.trim() === 'Salvo com Sucesso') {
					listarProdutos(id);
					calcular();
					$("#quantidade").val('1');
				} else {
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>

<script type="text/javascript">
	function listarProdutos(id) {

		$.ajax({
			url: 'paginas/' + pag + "/listar_produtos.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				$("#listar_produtos").html(result);
			}
		});
	}
</script>

<script type="text/javascript">
	function fecharComanda() {
		// Coleta dados da comanda
		var cliente = $("#cliente").val();
		var valor = $("#valor_serv").val();
		var valor_restante = $("#valor_serv_agd_restante").val();
		var data_pagamento = $("#data_pagamento").val();
		var data_pagamento_restante = $("#data_pagamento_restante").val();
		var pagamento_restante = $("#pagamento_restante").val();
		var pagamento = $("#pagamento").val();
		var id = $("#id").val();

		// Valida se os campos de pagamento restante estão preenchidos
		if (valor_restante > 0) {
			if (data_pagamento_restante == "" || pagamento_restante == "") {
				alert('Preencha a Data de Pagamento Restante e o tipo de Pagamento Restante');
				return;
			}
		}

		$.ajax({
			url: 'paginas/' + pag + "/fechar_comanda.php",
			method: 'POST',
			data: {
				id,
				valor,
				valor_restante,
				data_pagamento,
				data_pagamento_restante,
				pagamento_restante,
				pagamento,
				cliente
			},
			dataType: "text",

			success: function(result) {

				if (result.trim() === 'Salvo com Sucesso') {
					$('#btn-fechar').click();
					listar();

					$('#data_pagamento').val('<?= $data_hoje ?>');
					$('#valor_serv_agd_restante').val('');
					$('#data_pagamento_restante').val('');
					$('#pagamento_restante').val('').change();

				} else {
					$("#mensagem").text(result);
				}
			}
		});
	}
</script>

<script type="text/javascript">
	function listarProdutosDados(id) {

		$.ajax({
			url: 'paginas/' + pag + "/listar_produtos_dados.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				$("#listar_produtos_dados").html(result);
			}
		});
	}
</script>

<script type="text/javascript">
	function listarServicosDados(id) {

		$.ajax({
			url: 'paginas/' + pag + "/listar_servicos_dados.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				$("#listar_servicos_dados").html(result);
			}
		});
	}
</script>

<script type="text/javascript">
	$("#form_salvar").submit(function() {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/salvar.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				var msg = mensagem.split("*");
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (msg[0].trim() == "Salvo com Sucesso") {

					var salvar = $('#salvar_comanda').val();

					if (salvar == 'Sim') {
						$("#id").val(msg[1]);
						fecharComanda();
					}
					$('#btn-fechar').click();
					listar();

				} else {

					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(msg[0])
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	function abaterValor() {

		var produtos = $('#valor_produtos').val();
		var servicos = $('#valor_servicos').val();

		var total_valores = parseFloat(produtos) + parseFloat(servicos);

		var valor = $("#valor_serv").val();
		var valor_rest = $("#valor_serv_agd_restante").val();

		if (valor == "") {
			valor = 0;
		}

		if (valor_rest == "") {
			valor_rest = 0;
		}

		var total = parseFloat(total_valores) - parseFloat(valor_rest);
		$('#valor_serv').val(total.toFixed(2));

	}
</script>