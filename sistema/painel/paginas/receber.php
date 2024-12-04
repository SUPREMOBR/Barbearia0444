<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como receber
$pag = 'receber';

//verificar se ele tem a permissão de estar nessa página
if (@$receber == 'ocultar') {
	echo "<script>window.location='../index.php'</script>";
	exit();
}

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
	<!-- Botão para abrir o formulário de inserção de uma nova conta -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Nova Conta</a>
</div>

<!-- Container principal com padding e sombra para organizar os filtros e a listagem -->
<div class="bs-example widget-shadow" style="padding:15px">
	<!-- Linha de filtros por data de vencimento -->
	<div class="row">
		<!-- Filtro de data inicial -->
		<div class="col-md-5" style="margin-bottom:5px;">
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:20px">
				<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_inicio_mes ?>" required>
			</div>
			<!-- Filtro de data final -->
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:30px">
				<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_final_mes ?>" required>
			</div>
		</div>
		<!-- Links para filtros rápidos (ontem, hoje, mês) -->
		<div class="col-md-2" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
					<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
					<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
				</small>
			</div>
		</div>
		<!-- Links para filtros de status das contas (todas, pendentes, pagas) -->
		<div class="col-md-3" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Todas as Contas" class="text-muted" href="#" onclick="buscarContas('')"><span>Todas</span></a> /
					<a title="Contas Pendentes" class="text-muted" href="#" onclick="buscarContas('Não')"><span>Pendentes</span></a> /
					<a title="Contas Pagas" class="text-muted" href="#" onclick="buscarContas('Sim')"><span>Pagas</span></a>
				</small>
			</div>
		</div>

		<input type="hidden" id="buscar-contas">

	</div>

	<hr>
	<!-- Linha para exibir a lista de contas -->
	<div id="listar">

	</div>

</div>

<!-- Modal para inserir/editar uma conta -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário para inserir ou editar conta -->
			<form id="form">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="exampleInputEmail1">Descrição</label>
								<input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição da Conta">
							</div>
						</div>

						<div class="col-md-6">

							<div class="form-group">
								<label for="exampleInputEmail1">Cliente</label>
								<select class="form-control sel2" id="pessoa" name="pessoa" style="width:100%;">

									<?php
									// Consulta para listar clientes
									$query = $pdo->query("SELECT * FROM clientes ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);

									echo '<option value="0">Selecione um Cliente</option>';
									// Lista todos os clientes na seleção
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

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Valor</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required>
							</div>
						</div>

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Vencimento</label>
								<input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="<?php echo $data_hoje ?>">
							</div>
						</div>

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Pago Em</label>
								<input type="date" class="form-control" id="data_pagamento" name="data_Pagamento">
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Arquivo</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImg">
								<img src="img/contas/sem-foto.jpg" width="80px" id="target">
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
					<!-- Botão de salvar do formulário -->
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
						<span><b>Data Pagamento: </b></span>
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

					<div class="col-md-8">
						<span><b>Cliente: </b></span>
						<span id="pessoa_dados"></span>
					</div>

				</div>

				<div class="row">
					<div class="col-md-12" align="center">
						<a id="link_mostrar" target="_blank" title="Clique para abrir o arquivo!">
							<img width="250px" id="target_mostrar">
						</a>
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
	// Função para definir datas de início e fim e listar resultados com base nesses valores
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar(); // Chama a função 'listar' para atualizar a lista de resultados com as novas datas
	}
</script>

<script type="text/javascript">
	// Quando o campo de data inicial muda, executa a função 'listar' para atualizar os resultados
	$('#data-inicial-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});
	// Quando o campo de data final muda, executa a função 'listar' para atualizar os resultados
	$('#data-final-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar();
	});
</script>

<script type="text/javascript">
	// Função para listar os dados com base nos filtros de data e status
	function listar() {
		// Captura os valores dos filtros de data e status
		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();

		// Envia uma requisição AJAX para obter a listagem de conta
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST', // Método de envio de dados
			data: {
				dataInicial, // Envia a data inicial selecionada
				dataFinal, // Envia a data final selecionada
				status // Envia o status selecionado (ex.: todas, pagas, pendentes)
			},
			dataType: "html",

			// Função executada em caso de sucesso da requisição AJAX
			success: function(result) {
				$("#listar").html(result); // Insere o resultado retornado pelo servidor no elemento com id "listar"
				$('#mensagem-excluir').text(''); // Limpa qualquer mensagem de erro exibida anteriormente
			}
		});
	}
</script>

<script type="text/javascript">
	// Função para definir o status de busca e listar os dados com o novo status
	function buscarContas(status) {
		$('#buscar-contas').val(status); // Define o valor do campo oculto '#buscar-contas' com o status selecionado
		listar(); // Chama a função 'listar' para atualizar a lista de acordo com o novo status
	}
</script>

<script type="text/javascript">
	// Função para marcar uma conta como "baixada"
	function baixar(id) {

		// Envia uma requisição AJAX para marcar a conta como baixada no banco de dados
		$.ajax({
			url: 'paginas/' + pag + "/baixar.php",
			method: 'POST',
			data: {
				// Envia o ID da conta para ser baixada
				id
			},
			dataType: "text",

			success: function(mensagem) {
				// Se a conta foi baixada com sucesso, chama 'listar' para atualizar a listagem
				if (mensagem.trim() == "Baixado com Sucesso") {
					listar();
				} else {
					// Caso contrário, exibe a mensagem de erro em vermelho
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}

			},

		});
	}
</script>