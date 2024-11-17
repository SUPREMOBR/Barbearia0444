<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como funcionarios
$pag = 'funcionarios';

//verificar se ele tem a permissão de estar nessa página
if (@$funcionarios == 'ocultar') {
	// Se a permissão for "ocultar", redireciona para a página inicial
	echo "<script>window.location='../index.php'</script>";
	exit();
}
?>

<div class="">
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Funcionário</a>
</div>
<!-- Vai listar os funcionários  -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<!-- Modal para inserir ou editar um funcionário -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<!-- Formulário de dados do funcionário -->
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
							</div>
						</div>
						<div class="col-md-6">

							<div class="form-group">
								<label for="exampleInputEmail1">Email</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Telefone</label>
								<input type="text" class="form-control" id="telefone" name="telefone" placeholder="Telefone">
							</div>
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">CPF</label>
								<input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF">
							</div>
						</div>

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Cargo</label>
								<select class="form-control sel2" id="cargo" name="cargo" style="width:100%;">

									<?php
									// Consulta ao banco de dados para listar os cargos (exceto 'Administrador')
									$query = $pdo->query("SELECT * FROM cargos where nome != 'Administrador' ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									// Loop para exibir os cargos disponíveis no banco
									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									} else {
										// Caso não existam cargos cadastrados
										echo '<option value="0">Cadastre um Cargo</option>';
									}
									?>

								</select>
							</div>
						</div>
					</div>

					<div class="row">

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Atendimento</label>
								<select class="form-control" name="atendimento" id="atendimento">
									<option value="Sim">Sim</option>
									<option value="Não">Não</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Tipo Chave Pix</label>
								<select class="form-control" name="tipo_chave" id="tipo_chave">
									<option value="">Selecionar Chave</option>
									<option value="CPF">CPF</option>
									<option value="Telefone">Telefone</option>
									<option value="Email">Email</option>
									<option value="Código">Código</option>
									<option value="CNPJ">CNPJ</option>
								</select>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Chave Pix</label>
								<input type="text" class="form-control" id="chave_pix" name="chave_pix" placeholder="Chave Pix">
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Endereço</label>
								<input type="text" class="form-control" id="endereco" name="endereco" placeholder="Rua X Número 1 Bairro xxx">
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Intervalo Minutos</label>
								<input type="number" class="form-control" id="intervalo" name="intervalo" placeholder="Intervalo Horários" required>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Comissão <small>(Se for Diferente do Padrão)</small></label>
								<input type="number" class="form-control" id="comissao" name="comissao" placeholder="Valor R$ ou %">
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Foto</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImg">
								<img src="img/perfil/sem-foto.jpg" width="80px" id="target">
							</div>
						</div>

					</div>
					<!-- Campo oculto para armazenar o ID do funcionário durante a edição -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<!-- Exibição de mensagem após a ação (salvar ou erro) -->
						<div id="mensagem" align="center"></div>
					</small>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
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
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-8">
						<span><b>Email: </b></span>
						<span id="email_dados"></span>
					</div>
					<div class="col-md-4">
						<span><b>Senha: </b></span>
						<span id="senha_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>CPF: </b></span>
						<span id="cpf_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Telefone: </b></span>
						<span id="telefone_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Nível: </b></span>
						<span id="nivel_dados"></span>
					</div>
					<div class="col-md-6">
						<span><b>Ativo: </b></span>
						<span id="ativo_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-6">
						<span><b>Cadastro: </b></span>
						<span id="data_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Atendimento: </b></span>
						<span id="atendimento_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-6">
						<span><b>Tipo Chave: </b></span>
						<span id="tipo_chave_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Chave Pix: </b></span>
						<span id="chave_pix_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-12">
						<span><b>Endereço: </b></span>
						<span id="endereco_dados"></span>
					</div>

				</div>

				<div class="row">
					<div class="col-md-12" align="center">
						<img width="250px" id="target_mostrar">
					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<!-- Modal Horarios-->
<div class="modal fade" id="modalHorarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_horarios"></span></h4>
				<button id="btn-fechar-horarios" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<!-- Formulário para inserir os horários -->
				<form id="form-horarios">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Horário</label>
								<input type="time" class="form-control" id="horario" name="horario" required>
							</div>
						</div>

						<div class="col-md-4">
							<button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
						</div>
						<!-- Campo oculto para armazenar o ID do horário -->
						<input type="hidden" name="id" id="id_horarios">

					</div>
				</form>

				<hr>
				<!-- Vai exibir os horários cadastrados -->
				<div class="" id="listar-horarios">

				</div>

				<br>
				<small>
					<div id="mensagem-horarios"></div>
				</small>

			</div>

		</div>
	</div>
</div>

<!-- Modal Dias-->
<div class="modal fade" id="modalDias" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:80%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dias"></span></h4>
				<button id="btn-fechar-dias" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<!-- Formulário para configurar os dias e horários -->
				<form id="form-dias">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label for="exampleInputEmail1">Dia</label>
								<select class="form-control" id="dias" name="dias" required>
									<option value="Segunda-Feira">Segunda-Feira</option>
									<option value="Terça-Feira">Terça-Feira</option>
									<option value="Quarta-Feira">Quarta-Feira</option>
									<option value="Quinta-Feira">Quinta-Feira</option>
									<option value="Sexta-Feira">Sexta-Feira</option>
									<option value="Sábado">Sábado</option>
									<option value="Domingo">Domingo</option>

								</select>
							</div>

						</div>

						<div class="col-md-4" align="center">
							<label for="exampleInputEmail1">(Início) Jornada de Trabalho (Final)</label>
							<div class="row" style="margin-top: 2px">
								<div class="col-md-6">
									<input type="time" name="inicio" class="form-control" id="inicio" required>
								</div>

								<div class="col-md-6">

									<input type="time" name="final" class="form-control" id="final" required>

								</div>
							</div>

						</div>

						<div class="col-md-4" align="center">
							<label for="exampleInputEmail1">(Início) Intervalo de Almoço (Final)</label>
							<div class="row" style="margin-top: 2px">
								<div class="col-md-6">
									<input type="time" name="inicio_almoco" class="form-control" id="inicio_almoco">
								</div>

								<div class="col-md-6">

									<input type="time" name="final_almoco" class="form-control" id="final_almoco">

								</div>
							</div>

						</div>

						<div class="col-md-2">
							<button type="submit" class="btn btn-primary" style="margin-top:22px">Salvar</button>
						</div>
						<!-- Campos ocultos para ID do usuário e ID do dia -->
						<input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

						<input type="hidden" name="id_d" id="id_d">

					</div>
				</form>

				<small>
					<div id="mensagem-dias"></div>
				</small>

				<big>
					<!-- Exibição das configurações de dias cadastradas -->
					<div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">

					</div>
				</big>

			</div>

		</div>
	</div>
</div>

<!-- Modal Servicos-->
<div class="modal fade" id="modalServicos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_servico"></span></h4>
				<button id="btn-fechar-servico" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<!-- Formulário para adicionar ou editar um serviço -->
				<form id="form-servico">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Serviço</label>
								<!-- Dropdown de serviços -->
								<select class="form-control sel3" id="servico" name="servico" style="width:100%;" required>
									<?php
									// Consulta os serviços cadastrados no banco de dados
									$query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if ($total_registro > 0) {
										// Preenche as opções do select com os serviços existentes
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
							<button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
						</div>
						<!-- Campo oculto para armazenar o ID do serviço -->
						<input type="hidden" name="id" id="id_servico">

					</div>
				</form>

				<hr>
				<!-- Vai exibir a lista de serviços -->
				<div class="" id="listar-servicos">

				</div>

				<br>
				<small>
					<div id="mensagem-servicos"></div>
				</small>

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
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];

		var reader = new FileReader();

		reader.onloadend = function() {
			target.src = reader.result;
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>

<script type="text/javascript">
	$("#form-dias").submit(function() {

		event.preventDefault();
		var formData = new FormData(this); // Cria um objeto FormData, que permite enviar o formulário com arquivos e dados.

		$.ajax({
			url: 'paginas/' + pag + "/inserir-dias.php",
			type: 'POST',
			data: formData, // Passa os dados do formulário para o servidor

			// Função de sucesso: executada quando a requisição retorna com sucesso
			success: function(mensagem) {
				$('#mensagem-dias').text(''); // Limpa a mensagem de erro ou sucesso que pode ser exibida
				$('#mensagem-dias').removeClass()

				// Verifica se a mensagem de resposta do servidor indica que os dados foram salvos com sucesso
				if (mensagem.trim() == "Salvo com Sucesso") {

					//$('#btn-fechar-horarios').click();
					$("#id_d").val('');
					listarDias(func); // Chama a função 'listarDias' para atualizar a lista de dias exibida


				} else {
					// Se a mensagem não for de sucesso, exibe uma mensagem de erro
					$('#mensagem-dias').addClass('text-danger')
					$('#mensagem-dias').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	// Função para listar os dias de trabalho de um funcionário, passando o parâmetro 'func'
	function listarDias(func) {

		$.ajax({
			url: 'paginas/' + pag + "/listar-dias.php", // URL para onde a requisição AJAX é enviada
			method: 'POST',
			data: {
				// Dados a serem enviados: id do profissional
				func
			},
			dataType: "html",

			success: function(result) {
				$("#listar-dias").html(result); // Preenche a div #listar-dias com o resultado retornado
				$('#mensagem-dias-excluir').text('');
			}
		});
	}
</script>

<script type="text/javascript">
	$("#form-servico").submit(function() {

		var func = $("#id_servico").val(); // Pega o ID do serviço
		event.preventDefault(); // Impede o comportamento padrão do formulário
		var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário

		$.ajax({
			url: 'paginas/' + pag + "/inserir-servico.php", // URL onde os dados serão enviados
			type: 'POST', // Método de envio
			data: formData, // Dados a serem enviados

			success: function(mensagem) {
				$('#mensagem-servicos').text(''); // Limpa mensagens anteriores
				$('#mensagem-servicos').removeClass()
				if (mensagem.trim() == "Salvo com Sucesso") {

					//$('#btn-fechar-horarios').click();
					listarServicos(func); // Atualiza a lista de serviços

				} else {

					$('#mensagem-servicos').addClass('text-danger')
					$('#mensagem-servicos').text(mensagem) // Exibe a mensagem de erro
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>

<script type="text/javascript">
	function listarServicos(func) {

		$.ajax({
			url: 'paginas/' + pag + "/listar-servicos.php", // URL onde a lista de serviços será carregada
			method: 'POST',
			data: {
				// Envia o ID do usuário ou serviço
				func
			},
			dataType: "html",

			success: function(result) {
				$("#listar-servicos").html(result); // Preenche a div com a lista de serviços
				$('#mensagem-servico-excluir').text(''); // Limpa qualquer mensagem de exclusão
			}
		});
	}
</script>