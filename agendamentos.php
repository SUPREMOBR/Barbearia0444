<?php
// Inclui o cabeçalho da página e define a data atual no formato 'Y-m-d' (Ano-Mês-Dia).
require_once("cabecalho.php");
$data_atual = date('Y-m-d');
?>
<style type="text/css">
	/* Define estilos para a área de herói da subpágina */
	.sub_page .hero_area {
		min-height: auto;
	}

	/* Define o estilo para os campos de entrada do formulário de agenda */
	.inputs_agenda {
		background: transparent !important;
		border: none;
		border-bottom: 1px solid #FFF !important;
		font-size: 14px !important;
		color: #FFF !important;
		padding: 0 !important;
		margin: 0px !important;
		margin-bottom: 5px !important;
	}
</style>

</div>

<!-- Seção do rodapé com fundo escuro -->
<div class="footer_section" style="background: #292929; ">
	<div class="container">
		<div class="footer_content ">
			<!-- Formulário de agendamento com método POST -->
			<form id="form-agenda" method="post" style="margin-top: -25px !important">
				<div class="footer_form footer-col">
					<div class="row" style="margin-top: -40px">
						<div class="col-6">
							<!-- Campo para telefone com função buscarNome ao digitar -->
							<input onkeyup="buscarNome()" class="inputs_agenda cor_place" type="text" name="telefone" id="telefone" placeholder="Telefone com DDD" required />
						</div>

						<div class="col-6">
							<!-- Campo de data com valor padrão como a data atual -->
							<input style="color:#FFF !important;" onchange="mudarFuncionario()" class="inputs_agenda" type="date" name="data" id="data" value="<?php echo $data_atual ?>" required />

						</div>



						<div class="col-12">
							<!-- Campo de texto para nome do usuário -->
							<input onclick="buscarNome()" class="inputs_agenda cor_place" type="text" name="nome" id="nome" placeholder="Seu Nome" required />
						</div>

					</div>

					<!-- Dropdown para selecionar serviço -->
					<div class="form-group" style="margin-top: 10px">
						<select onchange="mudarServico()" class="form-control sel2" id="servico" name="servico" style="width:100%; height:28px" required>
							<option value="">Selecione um Serviço</option>

							<?php
							// Consulta para buscar serviços disponíveis do banco de dados
							$query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
							$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
							$total_registro = @count($resultado);
							// Exibe os serviços como opções no dropdown
							if ($total_registro > 0) {
								for ($i = 0; $i < $total_registro; $i++) {
									foreach ($resultado[$i] as $key => $value) {
									}
									$valor = $resultado[$i]['valor'];
									$valorF = number_format($valor, 2, ',', '.');

									echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . ' - R$ ' . $valorF . '</option>';
								}
							}
							?>


						</select>
					</div>

					<!-- Dropdown para selecionar funcionário -->
					<div class="form-group">
						<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" onchange="mudarFuncionario()" required>
							echo '<option value=""><?php echo $texto_agendamento ?></option>';

						</select>
					</div>

					<!-- Campo para exibir os horários disponíveis -->
					<div class="form-group">
						<small>
							<div id="listar-horarios">

							</div>
						</small>
					</div>

					<!-- Campo para observações adicionais -->
					<div class="form-group">
						<input maxlength="100" type="text" class="inputs_agenda cor_place" name="obs" id="obs" placeholder="Observações caso exista alguma.">
					</div>
					<!-- Botão para confirmar o agendamento -->
					<button onclick="salvar()" class="botao-verde" type="submit" style="width:100%;" id="btn_agendar">
						<span id='botao_salvar'>Confirmar Agendamento</span>

					</button>
					<!-- Link para ver agendamentos -->
					<a href="meus-agendamentos.php" class="botao-azul" id='botao_editar' style="width:100%; text-align: center; margin-top: 5px">
						Ver Agendamentos
					</a>


					<br><br>
					<small>
						<div id="mensagem" align="center"></div>
					</small>
					<!-- Campos ocultos para armazenar dados temporários -->
					<input type="text" id="data_oculta" style="display: none">
					<input type="hidden" id="id" name="id">
					<input type="hidden" id="hora_rec" nome="hora_rec">
					<input type="hidden" id="nome_funcionario" nome="nome_funcionario">
					<input type="hidden" id="data_rec" nome="data_rec">
					<input type="hidden" id="nome_serv" nome="nome_serv">

				</div>

			</form>

		</div>

	</div>

</div>


<?php require_once("rodape.php") ?>


<!-- Modal para confirmar exclusão de agendamento -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Excluir Agendamento
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar-excluir">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form id="form-excluir">
				<div class="modal-body">

					<span id="msg-excluir"></span>

					<input type="hidden" name="id" id="id_excluir">

					<br>
					<small>
						<div id="mensagem-excluir" align="center"></div>
					</small>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-danger">Excluir</button>
				</div>
			</form>

		</div>
	</div>
</div>


<!-- Scripts para o seletor select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
	.select2-selection__rendered {
		line-height: 45px !important;
		font-size: 16px !important;
		color: #000 !important;

	}

	.select2-selection {
		height: 45px !important;
		font-size: 16px !important;
		color: #000 !important;

	}
</style>



<script type="text/javascript">
	// Configurações do select2 e inicialização dos funcionários
	$(document).ready(function() {
		document.getElementById("botao_editar").style.display = "none";
		$('.sel2').select2({

		});

		listarFuncionarios();
	});
</script>


<script type="text/javascript">
	// Função para atualizar o funcionário e listar horários
	function mudarFuncionario() {
		var funcionario = $('#funcionario').val();
		var data = $('#data').val();
		var hora = $('#hora_rec').val();

		listarHorarios(funcionario, data, hora);
		listarFuncionario();

	}
</script>



<script type="text/javascript">
	// Função para listar horários disponíveis
	function listarHorarios(funcionario, data, hora) {

		$.ajax({
			url: "ajax/listar-horarios.php",
			method: 'POST',
			data: {
				funcionario,
				data,
				hora
			},
			dataType: "text",

			success: function(result) {
				if (result.trim() === '000') {
					alert('Selecione uma data igual ou maior que hoje!');

					var dt = new Date();
					var dia = String(dt.getDate()).padStart(2, '0');
					var mes = String(dt.getMonth() + 1).padStart(2, '0');
					var ano = dt.getFullYear();
					dataAtual = ano + '-' + mes + '-' + dia;
					$('#data').val(dataAtual);
					return;
				} else {
					$("#listar-horarios").html(result);
				}

			}
		});
	}
</script>



<script type="text/javascript">
	// Função para buscar o nome do cliente com base no telefone
	function buscarNome() {
		var telefone = $('#telefone').val();


		$.ajax({
			url: "ajax/listar-nome.php",
			method: 'POST',
			data: {
				telefone
			},
			dataType: "text",

			success: function(result) {
				var split = result.split("*");
				console.log(split[3])

				if (split[2] == "" || split[2] == undefined) {

				} else {
					$("#funcionario").val(parseInt(split[2])).change();
				}


				if (split[5] == "" || split[5] == undefined) {
					document.getElementById("botao_editar").style.display = "none";
				} else {
					$("#servico").val(parseInt(split[5])).change();
					document.getElementById("botao_editar").style.display = "block";
					$("#botao_salvar").text('Novo Agendamento');
				}

				$("#nome").val(split[0]);


				$("#msg-excluir").text('Deseja Realmente excluir esse agendamento feito para o dia ' + split[7] + ' às ' + split[4]);


				mudarFuncionario()



			}
		});




	}
</script>


<script type="text/javascript">
	// Função para redefinir o valor do campo 'id' ao salvar
	function salvar() {
		$('#id').val('');
	}
</script>


<script>
	// Função executada ao enviar o formulário de agendamento
	$("#form-agenda").submit(function() {

		// Evita o comportamento padrão de envio do formulário
		event.preventDefault();

		// Oculta o botão de agendar e exibe uma mensagem de carregamento
		$('#btn_agendar').hide();
		$('#mensagem').text('Carregando!');

		// Cria um FormData com os dados do formulário
		var formData = new FormData(this);

		$.ajax({
			url: "ajax/agendar_temp.php", // Envia para o script PHP que processa o agendamento
			type: 'POST',
			data: formData,

			success: function(mensagem) {

				// Separa a mensagem e o ID do agendamento
				var msg = mensagem.split('*');
				var id_agd = msg[1];

				// Limpa e exibe a mensagem de retorno
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (msg[0].trim() == "Pré Agendado") {
					$('#mensagem').text(msg[0])
					buscarNome() // Atualiza o campo de nome do cliente

					// Redireciona para a página de pagamento com o ID do agendamento
					window.location = "pagamento/" + id_agd + "/100";

				} else {
					//$('#mensagem').addClass('text-danger')
					// Exibe a mensagem de erro, se houver
					$('#mensagem').text(msg[0])
				}
				// Exibe novamente o botão de agendar
				$('#btn_agendar').show();

			},
			// Configurações adicionais para o envio do FormData
			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>


<script type="text/javascript">
	// Função para buscar e preencher o nome do funcionário com base no ID
	function listarFuncionario() {
		var func = $("#funcionario").val();

		$.ajax({
			url: "ajax/listar-funcionario.php",
			method: 'POST',
			data: {
				func
			},
			dataType: "text",

			success: function(result) {
				// Define o valor do nome do funcionário no campo oculto
				$("#nome_funcionario").val(result);
			}
		});
	}
</script>


<script type="text/javascript">
	// Função para atualizar a lista de funcionários ao mudar o serviço
	function mudarServico() {
		listarFuncionarios() // Atualiza a lista de funcionários com base no serviço
		var serv = $("#servico").val();

		$.ajax({
			url: "ajax/listar-servico.php",
			method: 'POST',
			data: {
				serv
			},
			dataType: "text",

			success: function(result) {
				// Define o valor do nome do serviço no campo oculto
				$("#nome_serv").val(result);
			}
		});
	}
</script>


<script type="text/javascript">
	// Função para listar os funcionários disponíveis para o serviço selecionado
	function listarFuncionarios() {
		var serv = $("#servico").val();

		$.ajax({
			url: "ajax/listar-funcionarios.php",
			method: 'POST',
			data: {
				serv
			},
			dataType: "text",

			success: function(result) {
				// Atualiza o campo de seleção com a lista de funcionários disponíveis
				$("#funcionario").html(result);
			}
		});
	}
</script>