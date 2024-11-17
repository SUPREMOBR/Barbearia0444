<?php
@session_start(); // Inicia a sessão PHP, permitindo o uso de variáveis de sessão em toda a aplicação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados.

// Define o nome da página atual como 'comissoes'.
$pag = 'comissoes';

//verificar se ele tem a permissão de estar nessa página
if (@$comissoes == 'ocultar') {
	// Redireciona para a página inicial se o acesso for restrito.
	echo "<script>window.location='../index.php'</script>";
	exit(); // Encerra o script se o acesso for negado.
}

$data_hoje = date('Y-m-d'); // Define a data atual.
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje))); // Define a data de ontem, subtraindo um dia da data atual.

$mes_atual = Date('m'); // Obtém o mês atual.
$ano_atual = Date('Y'); // Obtém o ano atual.
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01"; // Define o início do mês atual.

if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
	$dia_final_mes = '30'; // Define o último dia dos meses com 30 dias.
} else if ($mes_atual == '2') {
	$dia_final_mes = '28'; // Define o último dia de fevereiro.
} else {
	$dia_final_mes = '31'; // Define o último dia para meses com 31 dias.
}

// Define o último dia do mês atual.
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

?>

<div class="bs-example widget-shadow" style="padding:15px">
	<!-- Formulário de seleção de data e funcionário -->
	<div class="row">
		<div class="col-md-5" style="margin-bottom:5px;">
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:20px">
				<!-- Data de início -->
				<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_hoje ?>" required>
			</div>

			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:30px">
				<!-- Data de término -->
				<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_hoje ?>" required>
			</div>
		</div>
		<!-- Seleção de Funcionário -->
		<div class="col-md-3">
			<div class="form-group">
				<select class="form-control sel2" id="funcionario" name="funcionario" style="width:100%;" onchange="listar()">
					<option value="">Filtrar Funcionário</option>
					<?php
					// Carrega a lista de funcionários no dropdown
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
		<!-- Botão para baixar comissões -->
		<div class="col-md-3">
			<button onclick="baixarTudo()" type="button" class="btn btn-success"> Baixar Comissões</button>
		</div>

		<input type="hidden" id="buscar-contas">

	</div>
	<!-- Links para filtros rápidos de data e status -->
	<div class="row">
		<div class="col-md-2" align="center">
			<div>
				<small>
					<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
					<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
					<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
				</small>
			</div>
		</div>

		<div class="col-md-3" align="center">
			<div>
				<small>
					<a title="Todos os Serviços" class="text-muted" href="#" onclick="buscarContas('')"><span>Todos</span></a> /
					<a title="Pendentes" class="text-muted" href="#" onclick="buscarContas('Não')"><span>Pendentes</span></a> /
					<a title="Pagos" class="text-muted" href="#" onclick="buscarContas('Sim')"><span>Pagos</span></a>
				</small>
			</div>
		</div>

	</div>

	<hr>
	<!-- Div onde as comissões serão listadas via AJAX -->
	<div id="listar">

	</div>

</div>

<!-- Modal para confirmação de pagamento -->
<div class="modal fade" id="modalBaixarTudo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Pagar Comissões : <span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form id="form-excluir">
				<div class="modal-body">

					<div class="row">
						<div class="col-md-9">
							<div class="form-group">
								<p>Você confirma o pagamento de R$ <b><span id="total_pagamento"></span></b> reais num total de <span id="total_comissoes"></span> comissões Pendentes.</p>
							</div>
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-primary">Confirmar</button>

						</div>
					</div>

					<input type="hidden" name="id_funcionario" id="id_funcionario">
					<input type="hidden" name="data_inicial" id="data_inicial">
					<input type="hidden" name="data_final" id="data_final">

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
						<span><b>Data PGTO: </b></span>
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

					<div class="col-md-12">
						<span><b>Funcionário: </b></span>
						<span id="nome_funcionario_dados"></span>

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
	var pag = "<?= $pag ?>" // Define a variável da página para ser usada em URLs
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({});
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
	// Função para definir o intervalo de datas e listar comissões
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar(); // Chama a função de listagem após definir as datas
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
	// Função para listar as comissões de acordo com filtros
	function listar() {

		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();
		var funcionario = $('#funcionario').val();

		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial,
				dataFinal,
				status,
				funcionario
			},
			dataType: "html",

			success: function(result) {
				// Exibe o resultado na div "listar"
				$("#listar").html(result);
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

<script type="text/javascript">
	// Função para buscar comissões de acordo com o status
	function buscarContas(status) {
		$('#buscar-contas').val(status);
		listar();
	}
</script>

<script type="text/javascript">
	function baixar(id) {
		$.ajax({
			url: 'paginas/' + pag + "/baixar.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(mensagem) {
				if (mensagem.trim() == "Baixado com Sucesso") {
					listar();
				} else {
					$('#mensagem-excluir').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}

			},

		});
	}
</script>

<script type="text/javascript">
	// Função para abrir o modal de confirmação de pagamento para todas as comissões
	function baixarTudo() {

		var funcionario = $('#funcionario').val();

		if (funcionario === '') {
			alert('Selecione um Funcionário');
			return;
		}

		$('#mensagem').text('');
		$('#modalBaixarTudo').modal('show'); // Abre o modal
		limparCampos();
	}
</script>

<script type="text/javascript">
	// Função para enviar o formulário de exclusão (confirmação de pagamento)
	$("#form-excluir").submit(function() {

		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: 'paginas/' + pag + "/baixar-todas.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem').text('');
				$('#mensagem').removeClass()
				if (mensagem.trim() == "Baixado com Sucesso") {

					$('#btn-fechar').click(); // Fecha o modal
					listar(); // Atualiza a lista

				} else {
					// Exibe a mensagem de erro
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