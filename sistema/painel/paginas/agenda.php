<?php
require_once("verificar.php");  // Inclui o arquivo "verificar.php" para controle de acesso
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

// Define o nome da página e obtém a data atual
$pag = 'agenda';
$data_atual = date('Y-m-d');

?>
<!-- Interface para o botão de novo agendamento -->
<div class="row">
	<div class="col-md-3">
		<button style="margin-bottom:10px" onclick="inserir()" type="button" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Agendamento</button>
	</div>

</div>
<!-- Campo oculto para armazenar a data do agendamento -->
<input type="hidden" name="data_agenda" id="data_agenda" value="<?php echo date('Y-m-d') ?>">

<!-- Estrutura da agenda e área de listagem -->
<div class="row" style="margin-top: 15px">

	<div class="col-md-4 agile-calendar">
		<div class="calendar-widget">

			<!-- grids -->
			<div class="agile-calendar-grid">
				<div class="page">

					<div class="w3l-calendar-left">
						<div class="calendar-heading">

						</div>
						<!-- Componente de calendário -->
						<div class="monthly" id="mycalendar"></div>
					</div>

					<div class="clearfix"> </div>
				</div>
			</div>
		</div>
	</div>
	<!-- Área de listagem de agendamentos -->
	<div class="col-xs-12 col-md-8 bs-example widget-shadow" style="padding:10px 5px; margin-top: 0px;" id="listar">

	</div>
</div>

<!-- Modal para criar/editar um agendamento -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="titulo_inserir"></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário para cadastro de agendamento -->
			<form method="post" id="form-text">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-5">
							<!-- Seleção do cliente -->
							<div class="form-group">
								<label>Cliente</label>
								<select class="form-control sel3" id="cliente" name="cliente" style="width:100%;" required>

									<?php
									// Consulta para listar os clientes
									$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									// Itera e cria uma opção para cada cliente
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

						<div class="col-md-4">
							<!-- Seleção do serviço -->
							<div class="form-group">
								<label>Serviço</label>
								<select class="form-control sel3" id="servico" name="servico" style="width:100%;" required>

									<?php
									// Consulta para listar os serviços disponíveis para o usuário logado
									$query = $pdo->query("SELECT * FROM servicos_funcionarios where funcionario = '$id_usuario' ");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									if (@count($resultado) > 0) {
										for ($i = 0; $i < @count($resultado); $i++) {
											$serv = $resultado[$i]['servico'];

											$query2 = $pdo->query("SELECT * FROM servicos where id = '$serv' and ativo = 'Sim' ");
											$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
											$nome_funcionario = $resultado2[0]['nome'];

											echo '<option value="' . $serv . '">' . $nome_funcionario . '</option>';
										}
									} else {
										echo '<option value="">Nenhum Serviço</option>';
									}
									?>

								</select>
							</div>
						</div>

						<div class="col-md-3" id="nasc">
							<!-- Seleção da data do agendamento -->
							<div class="form-group">
								<label>Data </label>
								<input type="date" class="form-control" name="data" id="data-modal" onchange="mudarData()">
							</div>
						</div>

					</div>

					<hr>
					<!-- Listagem dinâmica dos horários disponíveis -->
					<div class="row">
						<div class="col-md-12" id="nasc">
							<div class="form-group">
								<div id="listar-horarios">

								</div>
							</div>
						</div>

					</div>
					<hr>
					<!-- Campo para observações adicionais -->
					<div class="col-md-12">
						<div class="form-group">
							<label>OBS <small>(Máx 100 Caracteres)</small></label>
							<input maxlength="100" type="text" class="form-control" name="obs" id="obs">
						</div>
					</div>

					<br>
					<!-- Campos ocultos para informações adicionais do agendamento -->
					<input type="hidden" name="id" id="id">
					<input type="hidden" name="id_funcionario" id="id_funcionario">
					<small>
						<div id="mensagem" align="center" class="mt-3"></div>
					</small>

				</div>
				<!-- Botão de submissão do formulário -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>

			</form>

		</div>
	</div>
</div>

<!-- Modal para detalhes do serviço -->
<div class="modal fade" id="modalServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Serviço: <span id="titulo_servico"></span></h4>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form method="post" id="form-servico">
				<div class="modal-body">
					<!-- Formulário de informações do serviço -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Funcionário</label>
								<select class="form-control sel4" id="funcionario_agd" name="funcionario_agd" style="width:100%;" required>

									<?php
									// Consulta para listar funcionários
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

					</div>
					<!-- Campos adicionais para valor e pagamento -->
					<div class="row">
						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Valor (Falta Pagar)</label>
								<input type="text" class="form-control" name="valor_serv_agd" id="valor_serv_agd">
							</div>
						</div>

						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Data Pagamento</label>
								<input type="date" class="form-control" name="data_pagamento" id="data_pgto" value="<?php echo $data_atual ?>">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma pagamento</label>
								<select class="form-control" id="pagamento" name="pagamento" style="width:100%;" required>

									<?php
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
					</div>
					<!-- Campos para informações adicionais de pagamento -->
					<div class="row">
						<div class="col-md-4" id="">
							<div class="form-group">
								<label>Valor Restante </label>
								<input type="text" class="form-control" name="valor_serv_agd_restante" id="valor_serv_agd_restante" placeholder="Mais de uma forma PGTO">
							</div>
						</div>

						<div class="col-md-4" id="">
							<div class="form-group">
								<label>Data pagamento Restante</label>
								<input type="date" class="form-control" name="data_pagamento_restante" id="data_pagamento_restante" value="">
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label>Forma pagamento Restante</label>
								<select class="form-control" id="pagamento_restante" name="pagamento_restante" style="width:100%;">
									<option value="">Selecionar pagamento</option>
									<?php
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
					</div>
					<!-- Campo para observações do serviço -->
					<div class="row">

						<div class="col-md-12">
							<div class="form-group">
								<label>Observações </label>
								<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2">
							</div>
						</div>

					</div>

					<br>
					<!-- Campos ocultos para informações adicionais do serviço -->
					<input type="hidden" name="id_agd" id="id_agd">
					<input type="hidden" name="cliente_agd" id="cliente_agd">
					<input type="hidden" name="servico_agd" id="servico_agd">
					<input type="hidden" name="descricao_serv_agd" id="descricao_serv_agd">

					<!-- Mensagem para feedback do usuário -->
					<small>
						<div id="mensagem-servico" align="center" class="mt-3"></div>
					</small>

				</div>

				<!-- Botão de submissão do formulário de serviço -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>



			</form>

		</div>
	</div>
</div>


<script type="text/javascript">
	// Define a variável `pag` para acessar o caminho atual da página.
	var pag = "<?= $pag ?>"
</script>
<!-- Importa o arquivo de script Ajax personalizado -->
<script src="js/ajax.js"></script>

<!-- Configuração do calendário -->
<script type="text/javascript" src="js/monthly.js"></script>
<script type="text/javascript">
	$(window).load(function() {
		// Inicializa o calendário com modo de exibição de eventos
		$('#mycalendar').monthly({
			mode: 'event',

		});
		// Inicializa o calendário de seleção de data com algumas opções
		$('#mycalendar2').monthly({
			mode: 'picker',
			target: '#mytarget',
			setWidth: '250px',
			startHidden: true,
			showTrigger: '#mytarget',
			stylePast: true,
			disablePast: true
		});
		// Exibe um alerta caso o protocolo seja "file:", informando que eventos locais não serão exibidos
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

<!-- Configuração de campos select com Select2 -->
<script type="text/javascript">
	$(document).ready(function() {
		// Lista os horários ao carregar a página
		listarHorarios();

		$('.sel3').select2({
			dropdownParent: $('#modalForm') // Modal de agendamento
		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({

		});
	});
</script>

<script type="text/javascript">
	$(document).ready(function() {

		$('.sel4').select2({
			dropdownParent: $('#modalServico') // Modal de serviço
		});
	});
</script>

<!-- Envio do formulário de agendamento via Ajax -->
<script>
	$("#form-text").submit(function() {
		$('#mensagem').text('Carregando...');
		event.preventDefault();

		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/inserir.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {
					$('#btn-fechar').click();
					listar();
					listarHorarios();
				} else {
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

<!-- Função para listar os agendamentos filtrados -->
<script type="text/javascript">
	function listar() {

		var funcionario = $('#funcionario').val();

		var data = $("#data_agenda").val();
		$("#data-modal").val(data);


		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				data,
				funcionario
			},
			dataType: "text",

			success: function(result) {
				$("#listar").html(result);
			}
		});
	}
</script>

<!-- Função para limpar os campos do formulário -->
<script type="text/javascript">
	function limparCampos() {
		$('#id').val('');
		$('#obs').val('');
		$('#hora').val('');
		$('#data').val('<?= $data_atual ?>');

	}
</script>

<!-- Função para atualizar a lista de agendamentos ao mudar o funcionário -->
<script type="text/javascript">
	function mudarFuncionario() {
		var funcionario = $('#funcionario').val();
		$('#id_funcionario').val(funcionario);
		listar();
		listarHorarios();
	}
</script>

<!-- Função para atualizar a data e listar novamente os agendamentos -->
<script type="text/javascript">
	function mudarData() {
		var data = $('#data-modal').val();
		$('#data_agenda').val(data).change();

		listar();
		listarHorarios();

	}
</script>

<!-- Função para listar os horários disponíveis para o agendamento -->
<script type="text/javascript">
	function listarHorarios() {

		var funcionario = $('#funcionario').val();
		var data = $('#data_agenda').val();


		$.ajax({
			url: 'paginas/' + pag + "/listar-horarios.php",
			method: 'POST',
			data: {
				funcionario,
				data
			},
			dataType: "text",

			success: function(result) {
				$("#listar-horarios").html(result);
			}
		});
	}
</script>

<!-- Envio do formulário de serviço via Ajax -->
<script>
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