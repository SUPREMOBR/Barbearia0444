<?php
require_once("verificar.php");  // Inclui o arquivo de verificação de autenticação para garantir que o usuário esteja logado
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

$pag = 'agendamentos'; // Define uma variável para a página atual
$data_atual = date('Y-m-d'); // Obtém a data atual no formato 'Y-m-d'

//verificar se ele tem a permissão de estar nessa página
if (@$agendamentos == 'ocultar') {
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>
<!-- Início do HTML -->
<div class="row">
	<div class="col-md-3">
		<button style="margin-bottom:10px" onclick="inserir()" type="button" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Agendamento</button>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<!-- Campo de seleção para escolher o profissional -->
			<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" onchange="mudarFuncionario()">
				<option value="">Selecione um Profissional</option>
				<?php
				// Consulta para selecionar todos os funcionários disponíveis para atendimento
				$query = $pdo->query("SELECT * FROM usuarios01 where atendimento = 'Sim' ORDER BY id desc");
				$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
				$total_registro = @count($resultado);
				// Popula o select com os dados dos funcionários
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

</div>
<!-- Campo oculto para armazenar a data selecionada -->
<input type="hidden" name="data_agenda" id="data_agenda" value="<?php echo date('Y-m-d') ?>">

<div class="row" style="margin-top: 15px">

	<div class="col-md-4 agile-calendar">
		<div class="calendar-widget">
			<!-- Calendário -->
			<!-- grids -->
			<div class="agile-calendar-grid">
				<div class="page">

					<div class="w3l-calendar-left">
						<div class="calendar-heading">

						</div>
						<!-- carrega o calendário -->
						<div class="monthly" id="mycalendar"></div>
					</div>

					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
	</div>
	<!--  lista os agendamentos -->
	<div class="col-xs-12 col-md-8 bs-example widget-shadow" style="padding:10px 5px; margin-top: 0px;" id="listar">

	</div>
</div>

<!-- Modal para adicionar novo agendamento -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="titulo_inserir"></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário para o novo agendamento -->
			<form method="post" id="form-text">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Cliente</label>
								<!-- Campo de seleção para escolher o cliente -->
								<select class="form-control sel3" id="cliente" name="cliente" style="width:100%;" required>

									<?php
									// Consulta para obter todos os clientes
									$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									// Popula o select com os dados dos clientes
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

						<div class="col-md-6">
							<div class="form-group">
								<label>Funcionário </label>
								<!-- Campo de seleção para escolher o funcionário -->
								<select class="form-control sel2" id="funcionario_modal" name="funcionario" style="width:100%;" onchange="mudarFuncionarioModal()">
									<option value="">Selecione um Funcionário</option>
									<?php

									$query = $pdo->query("SELECT * FROM usuarios01 where atendimento = 'Sim' ORDER BY id desc");
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

					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Serviço</label>
								<!-- Campo de seleção para escolher o serviço -->
								<select class="form-control sel3" id="servico" name="servico" style="width:100%;" required>

								</select>
							</div>
						</div>

						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Data </label>
								<!-- Campo para selecionar a data -->
								<input type="date" class="form-control" name="data" id="data-modal" onchange="mudarData()">
							</div>
						</div>

					</div>

					<hr>
					<div class="row">

						<div class="col-md-12" id="nasc">
							<div class="form-group">
								<div id="listar-horarios">
									<!-- Mensagem padrão antes de selecionar o funcionário -->
									<small>Selecionar Funcionário</small>
								</div>
							</div>
						</div>

					</div>
					<hr>

					<div class="col-md-12">
						<div class="form-group">
							<label>OBS <small>(Máx 100 Caracteres)</small></label>
							<!-- Campo para inserir observações -->
							<input maxlength="100" type="text" class="form-control" name="obs" id="obs">
						</div>
					</div>

					<br>
					<!-- Campos ocultos para armazenar dados adicionais -->
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_funcionario" id="id_funcionario">
					<small>
						<!-- Div para exibir mensagens de feedback -->
						<div id="mensagem" align="center" class="mt-3"></div>
					</small>

				</div>
				<div class="modal-footer">
					<!-- Botão para salvar o agendamento -->
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>

			</form>

		</div>
	</div>
</div>

<!-- Modal de Serviço -->
<div class="modal fade" id="modalServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Serviço: <span id="titulo_servico"></span> </h4>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário do modal para inserir dados do serviço -->
			<form method="post" id="form-servico">
				<div class="modal-body">
					<!-- Seção para selecionar o funcionário que prestará o serviço -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Funcionário</label>
								<select class="form-control sel4" id="funcionario_agd" name="funcionario_agd" style="width:100%;" required>

									<?php
									// Consulta para listar os funcionários que podem prestar o serviço
									$query = $pdo->query("SELECT * FROM usuarios01 where atendimento = 'Sim' ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										// Preenche o select com a lista de funcionários
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

					</div>

					<!-- Seção para definir valores do serviço e data do pagamento -->
					<div class="row">
						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Valor (Falta Pagar)</label>
								<input type="text" class="form-control" name="valor_serv_agd" id="valor_serv_agd">
							</div>
						</div>


						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Data PGTO</label>
								<input type="date" class="form-control" name="data_pagamento" id="data_pagamento" value="<?php echo $data_atual ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma pagamento</label>
								<select class="form-control" id="pagamento" name="pagamento" style="width:100%;" required>

									<?php
									// Consulta para listar as formas de pagamento disponíveis
									$query = $pdo->query("SELECT * FROM formas_pagamento");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										// Preenche o select com as formas de pagamento
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
					</div>

					<!-- Seção para valor e data do pagamento restante -->
					<div class="row">
						<div class="col-md-4" id="">
							<div class="form-group">
								<label>Valor Restante </label>
								<input type="text" class="form-control" name="valor_serv_agd_restante" id="valor_serv_agd_restante" placeholder="Mais de uma forma PGTO">
							</div>
						</div>


						<div class="col-md-4" id="">
							<div class="form-group">
								<label>Data PGTO Restante</label>
								<input type="date" class="form-control" name="data_pagamento_restante" id="data_pagamento_restante" value="">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma pagamento Restante</label>
								<select class="form-control" id="pagamento_restante" name="pagamento_restante" style="width:100%;">
									<option value="">Selecionar pagamento</option>
									<?php
									// Consulta para listar as formas de pagamento novamente para o pagamento restante
									$query = $pdo->query("SELECT * FROM formas_pagamento");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										// Preenche o select com as formas de pagamento
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
					</div>

					<!-- Seção para observações adicionais -->
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label>Observações </label>
								<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2">
							</div>
						</div>

					</div>

					<br>
					<!-- Campos ocultos para enviar dados adicionais -->
					<input type="hidden" name="id_agd" id="id_agd">
					<input type="hidden" name="cliente_agd" id="cliente_agd">
					<input type="hidden" name="servico_agd" id="servico_agd">
					<input type="hidden" name="descricao_serv_agd" id="descricao_serv_agd">

					<small>
						<div id="mensagem-servico" align="center" class="mt-3"></div>
					</small>

				</div>

				<!-- Rodapé do modal com o botão de salvar -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>



			</form>

		</div>
	</div>
</div>

<!-- Scripts relacionados ao calendário e AJAX -->
<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>

<!-- calendar -->
<script type="text/javascript" src="js/monthly.js"></script>
<script type="text/javascript">
	$(window).load(function() {
		// Inicializa o calendário mensal
		$('#mycalendar').monthly({
			mode: 'event',

		});

		$('#mycalendar2').monthly({
			mode: 'picker',
			target: '#mytarget',
			setWidth: '250px',
			startHidden: true,
			showTrigger: '#mytarget',
			stylePast: true,
			disablePast: true
		});

		switch (window.location.protocol) {
			case 'http:':
			case 'https:':
				// running on a server, should be good.
				break;
			case 'file:':
				alert('Just a heads-up, events will not work when run locally.');
		}

	});
</script>
<!-- //calendar -->

<script type="text/javascript">
	$(document).ready(function() {

		$('.sel3').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>


<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({

		});
	});
</script>

<!-- Script para selecionar o funcionário no modal e carregar informações -->
<script type="text/javascript">
	$(document).ready(function() {

		$('.sel4').select2({
			dropdownParent: $('#modalServico')
		});
	});
</script>

<script>
	// Envia o formulário "#form-text" via AJAX quando ele é submetido
	$("#form-text").submit(function() {
		// Exibe a mensagem "Carregando..." enquanto aguarda a resposta
		$('#mensagem').text('Carregando...');
		// Impede o envio padrão do formulário (recarregar página)
		event.preventDefault();

		// Cria um objeto FormData com os dados do formulário
		var formData = new FormData(this);
		// Realiza a requisição AJAX
		$.ajax({
			url: 'paginas/' + pag + "/inserir.php", // URL onde os dados do formulário serão enviados
			type: 'POST', // Método da requisição
			data: formData, // Dados enviados

			// Função a ser chamada após a resposta da requisição
			success: function(mensagem) {
				// Limpa a mensagem de carregamento
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				// Se a resposta for "Salvo com Sucesso", fecha o modal e atualiza os dados
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar').click(); // Fecha o modal
					listar(); // Atualiza a lista de agendamentos
					listarHorarios(); // Atualiza os horários disponíveis
				} else {
					// Caso haja erro, exibe a mensagem de erro
					$('#mensagem').addClass('text-danger')
					$('#mensagem').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	// Função para listar os agendamentos com base no funcionário e na data
	function listar() {
		// Obtém o funcionário selecionado
		var funcionario = $('#funcionario_modal').val();
		// Obtém a data selecionada
		var data = $("#data_agenda").val();
		$("#data-modal").val(data); // Atualiza o valor da data no modal

		// Envia a requisição AJAX para listar os dados com base no funcionário e data
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST', // Método da requisição
			data: {
				// Dados a serem enviados
				data,
				funcionario
			},
			dataType: "text", // Tipo de resposta esperada

			success: function(result) {
				// Atualiza a lista de agendamentos no frontend com a resposta
				$("#listar").html(result);
			}
		});
	}
</script>

<script type="text/javascript">
	// Função para limpar os campos do formulário
	function limparCampos() {
		$('#id').val(''); // Limpa o campo 'id'
		$('#obs').val(''); // Limpa o campo 'obs'
		$('#hora').val(''); // Limpa o campo 'hora'
		$('#data').val('<?= $data_atual ?>'); // Define a data atual no campo 'data

	}
</script>

<script type="text/javascript">
	// Função para mudar o funcionário selecionado
	function mudarFuncionario() {
		var funcionario = $('#funcionario').val(); // Obtém o funcionário selecionado
		$('#id_funcionario').val(funcionario); // Define o 'id_funcionario'
		$('#funcionario_modal').val(funcionario).change(); // Atualiza o valor no modal

		// Atualiza a lista de agendamentos, horários e serviços com base no novo funcionário
		listar();
		listarHorarios();
		listarServicos(funcionario);

	}
</script>

<script type="text/javascript">
	// Função para mudar o funcionário no modal
	function mudarFuncionarioModal() {
		var func = $('#funcionario_modal').val(); // Obtém o funcionário do modal
		listar(); // Atualiza a lista de agendamentos
		listarHorarios(); // Atualiza os horários disponíveis
		listarServicos(func); // Atualiza os serviços do funcionário
	}
</script>

<script type="text/javascript">
	// Função para mudar a data no modal
	function mudarData() {
		var data = $('#data-modal').val(); // Obtém a data selecionada
		$('#data_agenda').val(data).change(); // Atualiza o campo de data no formulário
		// Atualiza a lista de agendamentos e horários com base na nova data
		listar();
		listarHorarios();

	}
</script>

<script type="text/javascript">
	// Função para listar os horários disponíveis com base no funcionário e data
	function listarHorarios() {

		var funcionario = $('#funcionario_modal').val(); // Obtém o funcionário selecionado
		var data = $('#data_agenda').val(); // Obtém a data selecionada

		// Envia a requisição AJAX para listar os horários disponíveis
		$.ajax({
			url: 'paginas/' + pag + "/listar-horarios.php",
			method: 'POST', // Método da requisição
			data: {
				// Dados a serem enviados
				funcionario,
				data
			},
			dataType: "text", // Tipo de resposta esperada

			success: function(result) {
				// Atualiza a lista de horários no frontend
				$("#listar-horarios").html(result);
			}
		});
	}
</script>

<script>
	// Envia o formulário de serviço via AJAX
	$("#form-servico").submit(function() {
		event.preventDefault();

		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/inserir-servico.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-servico').text('');
				$('#mensagem-servico').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar-servico').click();
					listar();
				} else {
					$('#mensagem-servico').addClass('text-danger')
					$('#mensagem-servico').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	// Função para listar os serviços com base no funcionário selecionado
	function listarServicos(func) {
		var serv = $("#servico").val();

		$.ajax({
			url: 'paginas/' + pag + "/listar-servicos.php",
			method: 'POST',
			data: {
				func
			},
			dataType: "text",

			success: function(result) {
				$("#servico").html(result);
			}
		});
	}
</script>