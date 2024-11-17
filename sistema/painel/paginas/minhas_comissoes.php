<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como minhas_comissoes
$pag = 'minhas_comissoes';

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

$id_func = $_SESSION['id'];
?>

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

		<div class="col-md-2" align="center">
			<div>
				<!-- Link para definir a data como ontem --> <!-- Link para definir a data como hoje --> <!-- Link para definir a data como o mês atual -->
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

		<div class="col-md-2" align="center">
			<div>
				<!-- Formulário para gerar relatório de comissões -->
				<form action="rel/rel_comissoes_class.php" target="_blank" method="POST">
					<!-- Campos ocultos que são enviados ao servidor com os dados do relatório -->
					<input type="hidden" name="dataInicial" id="dataInicial">
					<input type="hidden" name="dataFinal" id="dataFinal">
					<input type="hidden" name="pago" id="pago_rel">
					<input type="hidden" name="funcionario" value="<?php echo $id_func ?>">

					<!-- O botão envia o formulário e abre o relatório PDF gerado. O ícone indica que é um arquivo PDF. -->
					<button type="submit" class="text-danger link-botao"><i class="fa fa-file-pdf-o" class="text-danger"></i> <span class="text-primary">Relatório</span></button>

				</form>
			</div>
		</div>
		<!-- Campo oculto para buscar contas -->
		<input type="hidden" id="buscar-contas">

	</div>

	<hr>
	<div id="listar">
		<!-- Este é o contêiner onde os resultados da busca serão listados -->
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
						<span><b>Fornecedor: </b></span>
						<span id="pessoa_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Funcionário: </b></span>
						<span id="nome_funcionario_dados"></span>
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
		listar(); // Chama a função 'listar' após definir as datas
	}
</script>

<script type="text/javascript">
	$('#data-inicial-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar(); // Chama a função listar() sempre que a data inicial for alterada
	});

	$('#data-final-caixa').change(function() {
		//$('#tipo-busca').val('');
		listar(); // Chama a função listar() sempre que a data final for alterada
	});
</script>

<script type="text/javascript">
	function listar() {

		// Pega os valores dos campos de data e status
		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();

		// Atualiza os valores em campos ocultos (inputs) para enviar esses dados via formulário
		$('#dataInicial').val(dataInicial);
		$('#dataFinal').val(dataFinal);
		$('#pago_rel').val(status);

		// Faz uma requisição AJAX para o servidor
		$.ajax({
			url: 'paginas/' + pag + "/listar.php",
			method: 'POST',
			data: {
				dataInicial, // Passa a data inicial para o servidor
				dataFinal, // Passa a data final para o servidor
				status // Passa o status de busca para o servidor
			},
			dataType: "html",

			// Função que é chamada quando a requisição é bem-sucedida
			success: function(result) {
				// Atualiza o conteúdo da div com o id 'listar' com o resultado retornado
				$("#listar").html(result);
				// Limpa qualquer mensagem de erro ou aviso na div 'mensagem-excluir'
				$('#mensagem-excluir').text('');
			}
		});
	}
</script>

<script type="text/javascript">
	function buscarContas(status) {
		$('#buscar-contas').val(status); // Define o valor do campo #buscar-contas com o status fornecido
		listar(); // Chama a função listar() para atualizar a lista de dados
	}
</script>