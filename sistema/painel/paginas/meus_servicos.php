<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como meus_servicos
$pag = 'meus_servicos';

$data_hoje = date('Y-m-d'); // Data atual
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje))); // Calcula a data de ontem subtraindo 1 dia de $data_hoje

$mes_atual = Date('m'); // Obtém o mês atual no formato numérico
$ano_atual = Date('Y');  // Obtém o ano atual
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";  // Concatena ano e mês para definir o início do mês atual no formato "YYYY-MM-01"

// Verifica o último dia do mês atual
if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
	$dia_final_mes = '30';
} else if ($mes_atual == '2') {
	$dia_final_mes = '28';
} else {
	$dia_final_mes = '31';
}
// Concatena ano e mês para definir o último dia do mês
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

?>

<div class="">
	<!-- Botão que ativa a função inserir() para abrir o modal de cadastro de um novo serviço -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Serviço</a>
</div>

<div class="bs-example widget-shadow" style="padding:15px">

	<div class="row">
		<div class="col-md-5" style="margin-bottom:5px;">
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:20px">
				<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_hoje ?>" required>
			</div>

			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:30px">
				<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_hoje ?>" required>
			</div>
		</div>

		<!-- Esses links permitem definir rapidamente os filtros de data para "Ontem", "Hoje" e "Mês", facilitando a consulta de serviços.																		 -->
		<div class="col-md-2" style="margin-top:5px;" align="center">
			<div>
				<small>
					<!-- Link para definir a data como ontem --> <!-- Link para definir a data como hoje --> <!-- Link para definir a data como o mês atual -->
					<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
					<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
					<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
				</small>
			</div>
		</div>

		<div class="col-md-3" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Todos os Serviços" class="text-muted" href="#" onclick="buscarContas('')"><span>Todos</span></a> /
					<a title="Pendentes" class="text-muted" href="#" onclick="buscarContas('Não')"><span>Pendentes</span></a> /
					<a title="Pagos" class="text-muted" href="#" onclick="buscarContas('Sim')"><span>Pagos</span></a>
				</small>
			</div>
		</div>

		<input type="hidden" id="buscar-contas">

	</div>

	<hr>
	<div id="listar">

	</div>

</div>

<!-- Modal Inserir-->
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
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Clientes</label>
								<!-- Campo para selecionar o cliente -->
								<select class="form-control sel2" id="cliente" name="cliente" style="width:100%;" required>
									<?php
									// Busca todos os clientes no banco de dados
									$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									// Itera sobre os clientes e cria uma opção no select para cada cliente
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
								<label>Serviço</label>
								<select class="form-control sel2" id="servico" name="servico" style="width:100%;" required>
									<!-- Campo para selecionar o serviço -->
									<?php
									// Busca serviços disponíveis para o funcionário logado
									$query = $pdo->query("SELECT * FROM servicos_funcionarios where funcionario = '$id_usuario' ");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									// Verifica se há registros de serviços para o funcionário logado
									if (@count($resultado) > 0) {
										// Itera sobre os serviços e os exibe como opções se ativos
										for ($i = 0; $i < @count($resultado); $i++) {
											$serv = $resultado[$i]['servico'];
											// Realiza uma nova consulta para verificar se o serviço está ativo e obter seu nome
											$query2 = $pdo->query("SELECT * FROM servicos where id = '$serv' and ativo = 'Sim' ");
											$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
											// Se o serviço está ativo, extrai o nome e cria uma opção no campo de seleção
											$nome_funcionario = $resultado2[0]['nome']; // Armazena o nome do serviço

											// Exibe o serviço como uma opção no select, usando o ID como valor e o nome como texto visível
											echo '<option value="' . $serv . '">' . $nome_funcionario . '</option>';
										}
									} else {
										// Caso não existam serviços para o funcionário, exibe uma opção indicando "Nenhum Serviço"
										echo '<option value="">Nenhum Serviço</option>';
									}
									?>

								</select>
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-3" id="nasc">
							<div class="form-group">
								<label>Valor </label>
								<input type="text" class="form-control" name="valor_serv" id="valor_serv" required>
							</div>
						</div>

						<div class="col-md-4" id="nasc">
							<div class="form-group">
								<label>Data Pagamento</label>
								<input type="date" class="form-control" name="data_pagamento" id="data_pagamento" value="<?php echo date('Y-m-d') ?>">
							</div>
						</div>

						<div class="col-md-5">
							<div class="form-group">
								<label>Forma pagamento</label>
								<select class="form-control" id="pagamento" name="pagamento" style="width:100%;" required>

									<?php
									// Busca as formas de pagamento disponíveis no banco de dados
									$query = $pdo->query("SELECT * FROM formas_pagamento");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado); // Conta o total de registros obtidos da consulta
									// Verifica se existem registros de formas de pagamento
									if ($total_registro > 0) {
										// Itera sobre as formas de pagamento e cria uma opção para cada uma
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											// Cria uma opção de forma de pagamento no select, onde o valor da opção é o nome da forma de pagamento
											echo '<option value="' . $resultado[$i]['nome'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									}
									?>

								</select>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3" id="">
								<div class="form-group">
									<label>Valor Restante </label>
									<input type="text" class="form-control" name="valor_serv_agd_restante" id="valor_serv_agd_restante">
								</div>
							</div>

							<div class="col-md-4" id="">
								<div class="form-group">
									<label>Data pagamento Restante</label>
									<input type="date" class="form-control" name="data_pagamento_restante" id="data_pagamento_restante" value="">
								</div>
							</div>

							<div class="col-md-5">
								<div class="form-group">
									<label>Forma pagamento Restante</label>
									<select class="form-control" id="pagamento_restante" name="pagamento_restante" style="width:100%;">
										<option value="">Selecionar pagamento</option>
										<?php
										// Realiza uma consulta ao banco de dados para obter todas as formas de pagamento disponíveis
										$query = $pdo->query("SELECT * FROM formas_pagamento");
										$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
										$total_registro = @count($resultado); // Conta o total de registros obtidos da consulta
										// Verifica se há registros na tabela de formas de pagamento
										if ($total_registro > 0) {
											// Itera sobre cada registro encontrado
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

						<div class="col-md-12">
							<div class="form-group">
								<label>Observações </label>
								<input maxlength="1000" type="text" class="form-control" name="obs" id="obs2">
							</div>
						</div>

					</div>

					<input type="hidden" name="id" id="id">

					<br>
					<small>
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
					<div class="col-md-6">
						<span><b>Valor : </b></span>
						<span id="valor_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Data Lançamento: </b></span>
						<span id="data_lancamento_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Data Vencimento: </b></span>
						<span id="data_vencimento_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Data pagamento: </b></span>
						<span id="data_pagamento_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-6">
						<span><b>Usuário Lançou: </b></span>
						<span id="usuario_lancou_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Usuário Baixa: </b></span>
						<span id="usuario_baixa_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-6">
						<span><b>Cliente: </b></span>
						<span id="pessoa_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Telefone: </b></span>
						<span id="telefone_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-6">
						<span><b>OBS: </b></span>
						<span id="obs_dados"></span>
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
		calcular()

		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];

		var arquivo = file['name'];
		resultado = arquivo.split(".", 2);

		if (resultado[1] === 'pdf') {
			$('#target').attr('src', "img/pdf.png");
			return;
		}

		if (resultado[1] === 'rar' || resultado[1] === 'zip') {
			$('#target').attr('src', "img/rar.png");
			return;
		}

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
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar(); // Chama a função para buscar as contas filtradas
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
	// Função que inicia o processo de listar itens de acordo com os filtros aplicados
	function listar() {
		// Captura os valores dos filtros para data e status
		var dataInicial = $('#data-inicial-caixa').val(); // Obtém a data inicial do campo de data inicial
		var dataFinal = $('#data-final-caixa').val(); // Obtém a data final do campo de data final
		var status = $('#buscar-contas').val(); // Obtém o status selecionado (ex: 'Pagos' ou 'Pendentes')

		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial, // Data inicial para o filtro
				dataFinal, // Data final para o filtro
				status // Status do filtro (ex: 'Pagos', 'Pendentes' ou vazio para todos)
			},
			// Tipo de dados esperados como resposta
			dataType: "html",

			success: function(result) {
				$("#listar").html(result); // Insere o HTML retornado no elemento com id "listar" (exibindo a lista filtrada)
				$('#mensagem-excluir').text(''); // Limpa qualquer mensagem de exclusão exibida anteriormente
			}
		});
	}
</script>

<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status); // Define o status de pagamento no campo oculto (ex: 'Sim', 'Não')
		listar(); // Chama a função para listar as contas de acordo com o status
	}
</script>

<script type="text/javascript">
	// Função que inicia o processo de baixar um item
	function baixar(id) {
		$.ajax({
			url: 'paginas/' + pag + "/baixar.php",
			method: 'POST',
			data: {
				// Dados enviados para o servidor (o ID do item que será baixado)
				id
			},
			dataType: "text",

			success: function(mensagem) {
				if (mensagem.trim() == "Baixado com Sucesso") {
					listar(); // Chama a função listar() para atualizar a lista de itens
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}

			},

		});
	}
</script>


<script type="text/javascript">
	// Função que inicia o processo de cálculo
	function calcular() {

		var quant = $('#quantidade').val(); // Obtém o valor do campo "quantidade"
		var produto = $('#produto').val(); // Obtém o valor do campo "produto"

		$.ajax({
			url: 'paginas/' + pag + "/calcular.php",
			method: 'POST',
			data: {
				// Dados enviados para o servidor (produto e quantidade)
				produto,
				quant
			},
			dataType: "text",

			success: function(mensagem) {
				// Exibe a resposta (valor calculado) no campo com id "valor"
				$('#valor').val(mensagem)
			},

		});
	}
</script>