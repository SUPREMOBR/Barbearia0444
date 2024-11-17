<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define a variável para identificar a página atual, usada em scripts e referências.
$pag = 'compras';

//verificar se ele tem a permissão de estar nessa página
if (@$compras == 'ocultar') {
	// Redireciona para a página inicial se não tiver permissão.
	echo "<script>window.location='../index.php'</script>";
	exit();
}
// Define datas para uso nos filtros de período da página
$data_hoje = date('Y-m-d'); // Data atual
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje))); // Data de ontem.

$mes_atual = Date('m'); // Mês atual.
$ano_atual = Date('Y'); // Ano atual.
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01"; // Define o início do mês atual.

// Define o dia final do mês atual, considerando meses com 28, 30 ou 31 dias.
if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
	$dia_final_mes = '30';
} else if ($mes_atual == '2') {
	$dia_final_mes = '28';
} else {
	$dia_final_mes = '31';
}
// Define a data de fim do mês atual.
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

?>

<div class="">
	<!-- Botão para abrir modal de nova compra. -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Nova Compra</a>
</div>

<div class="bs-example widget-shadow" style="padding:15px">

	<div class="row">
		<div class="col-md-5" style="margin-bottom:5px;">
			<!-- Filtro de Data de Vencimento Inicial -->
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Inicial" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:20px">
				<input type="date" class="form-control " name="data-inicial" id="data-inicial-caixa" value="<?php echo $data_inicio_mes ?>" required>
			</div>
			<!-- Filtro de Data de Vencimento Final -->
			<div style="float:left; margin-right:10px"><span><small><i title="Data de Vencimento Final" class="fa fa-calendar-o"></i></small></span></div>
			<div style="float:left; margin-right:30px">
				<input type="date" class="form-control " name="data-final" id="data-final-caixa" value="<?php echo $data_final_mes ?>" required>
			</div>
		</div>
		<!-- Links para seleção rápida de datas: ontem, hoje e mês -->
		<div class="col-md-2" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Conta de Ontem" class="text-muted" href="#" onclick="valorData('<?php echo $data_ontem ?>', '<?php echo $data_ontem ?>')"><span>Ontem</span></a> /
					<a title="Conta de Hoje" class="text-muted" href="#" onclick="valorData('<?php echo $data_hoje ?>', '<?php echo $data_hoje ?>')"><span>Hoje</span></a> /
					<a title="Conta do Mês" class="text-muted" href="#" onclick="valorData('<?php echo $data_inicio_mes ?>', '<?php echo $data_final_mes ?>')"><span>Mês</span></a>
				</small>
			</div>
		</div>
		<!-- Links para filtrar por status: Todas, Pendentes, e Pagas -->
		<div class="col-md-3" style="margin-top:5px;" align="center">
			<div>
				<small>
					<a title="Todas as Contas" class="text-muted" href="#" onclick="buscarContas('')"><span>Todas</span></a> /
					<a title="Contas Pendentes" class="text-muted" href="#" onclick="buscarContas('Não')"><span>Pendentes</span></a> /
					<a title="Contas Pagas" class="text-muted" href="#" onclick="buscarContas('Sim')"><span>Pagas</span></a>
				</small>
			</div>
		</div>
		<!-- Input oculto para armazenar o status selecionado. -->
		<input type="hidden" id="buscar-contas">

	</div>

	<hr>
	<!-- Local onde serão exibidas as contas filtradas -->
	<div id="listar">

	</div>

</div>

<!-- Modal para Inserir Nova Compra -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<!-- Botão para fechar o modal. -->
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

					<div class="row">
						<!-- Campo de seleção de Produto -->
						<div class="col-md-5">
							<div class="form-group">
								<label for="exampleInputEmail1">Produto</label>
								<select class="form-control sel2" id="produto" name="produto" style="width:100%;">

									<?php
									// Consulta para listar produtos disponíveis.
									$query = $pdo->query("SELECT * FROM produtos ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);

									if ($total_registro > 0) {
										for ($i = 0; $i < $total_registro; $i++) {
											foreach ($resultado[$i] as $key => $value) {
											}
											echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
										}
									} else {
										echo '<option value="0">Cadastre um Produto</option>';
									}
									?>

								</select>
							</div>
						</div>
						<!-- Campo de seleção de Fornecedor -->
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Fornecedor</label>
								<select class="form-control sel2" id="pessoa" name="pessoa" style="width:100%;">

									<?php
									// Consulta para listar fornecedores.
									$query = $pdo->query("SELECT * FROM fornecedores ORDER BY nome asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);

									echo '<option value="0">Selecione um Fornecedor</option>';

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
						<!-- Campo para a quantidade -->
						<div class="col-md-3">

							<div class="form-group">
								<label for="exampleInputEmail1">Quantidade</label>
								<input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade" required>
							</div>
						</div>

					</div>
					<!-- Campo para o valor total da compra -->
					<div class="row">

						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Valor Total Compra</label>
								<input type="text" class="form-control" id="valor" name="valor" placeholder="Valor" required>
							</div>
						</div>
						<!-- Campo para data de vencimento -->
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Vencimento</label>
								<input type="date" class="form-control" id="data_vencimento" name="data_vencimento" value="<?php echo $data_hoje ?>">
							</div>
						</div>
						<!-- Campo para data da compra -->
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Pago Em</label>
								<input type="date" class="form-control" id="data_pagamento" name="data_pagamento">
							</div>
						</div>

					</div>

					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Arquivo (Nota Fiscal)</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImg">
								<img src="img/contas/sem-foto.jpg" width="80px" id="target">
							</div>
						</div>

					</div>
					<!-- Input oculto para armazenar o ID da compra ao editar. -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<div id="mensagem" align="center"></div>
					</small>
				</div>

				<div class="modal-footer">
					<!-- Botão de salvar a compra. -->
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

					<div class="col-md-6">
						<span><b>Fornecedor: </b></span>
						<span id="pessoa_dados"></span>
					</div>

					<div class="col-md-6">
						<span><b>Telefone: </b></span>
						<span id="telefone_dados"></span>
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
	var pag = "<?= $pag ?>" // Define a variável "pag" com o valor da variável PHP "$pag" para uso em URLs de scripts Ajax.
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		// Inicializa o plugin Select2 para os campos com a classe "sel2", que melhora a usabilidade dos campos de seleção.
		$('.sel2').select2({
			// Define o "modalForm" como o elemento pai do dropdown para garantir que o Select2 funcione dentro do modal.
			dropdownParent: $('#modalForm')
		});
	});
</script>

<script type="text/javascript">
	// Função para carregar e exibir a imagem ou ícone conforme o tipo de arquivo selecionado.
	function carregarImg() {
		var target = document.getElementById('target'); // Elemento onde a imagem será exibida.
		var file = document.querySelector("#foto").files[0]; // Obtém o arquivo selecionado no input "foto".


		var arquivo = file['name'];
		resultado = arquivo.split(".", 2); // Divide o nome do arquivo e a extensão.

		// Exibe um ícone específico se o arquivo for PDF, RAR ou ZIP.
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
			target.src = reader.result; // Exibe a imagem carregada.
		};

		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = ""; // Limpa o "src" se nenhum arquivo estiver selecionado.
		}
	}
</script>

<script type="text/javascript">
	// Função para atualizar os campos de data e chamar a função "listar" para carregar as informações conforme o filtro.
	function valorData(dataInicio, dataFinal) {
		$('#data-inicial-caixa').val(dataInicio);
		$('#data-final-caixa').val(dataFinal);
		listar(); // Carrega as informações com as novas datas.
	}
</script>

<script type="text/javascript">
	// Detecta mudanças nos campos de data para recarregar a lista automaticamente.
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
	// Função para carregar e exibir as contas conforme os filtros de data e status.
	function listar() {

		var dataInicial = $('#data-inicial-caixa').val();
		var dataFinal = $('#data-final-caixa').val();
		var status = $('#buscar-contas').val();

		$.ajax({
			url: 'paginas/' + pag + "/listar.php", // URL da página que processará a listagem.
			method: 'POST',
			data: {
				// Envia as datas e o status como parâmetros.
				dataInicial,
				dataFinal,
				status
			},
			dataType: "html",

			success: function(result) {
				$("#listar").html(result); // Exibe os resultados no elemento com id "listar".
				$('#mensagem-excluir').text(''); // Limpa mensagens anteriores.
			}
		});
	}
</script>

<script type="text/javascript">
	// Função para buscar e filtrar contas conforme o status selecionado (todas, pendentes, pagas).
	function buscarContas(status) {
		$('#buscar-contas').val(status); // Atualiza o valor do campo oculto de status.
		listar(); // Atualiza a listagem de acordo com o status.
	}
</script>

<script type="text/javascript">
	// Função para marcar uma conta como "baixada" (ou seja, paga ou processada).
	function baixar(id) {
		$.ajax({
			url: 'paginas/' + pag + "/baixar.php", // URL do script que processa o pagamento/baixa da conta.
			method: 'POST',
			data: {
				// Envia o ID da conta como parâmetro.
				id
			},
			dataType: "text",

			success: function(mensagem) {
				if (mensagem.trim() == "Baixado com Sucesso") {
					listar(); // Recarrega a listagem após o sucesso.
				} else {
					$('#mensagem-excluir').addClass('text-danger') // Exibe mensagem de erro em vermelho.
					$('#mensagem-excluir').text(mensagem)
				}

			},

		});
	}
</script>