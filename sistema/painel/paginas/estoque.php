<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como estoque
$pag = 'estoque';
//verificar se ele tem a permissão de estar nessa página
if (@$estoque == 'ocultar') {
	// Se a permissão for "ocultar", redireciona para a página inicial
	echo "<script>window.location='../index.php'</script>";
	exit(); // Interrompe a execução do código
}

?>
<!-- Vai exibir a lista de itens do estoque -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<!-- Modal para exibir os detalhes de um item do estoque -->
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
					<div class="col-md-7">
						<span><b>Categoria: </b></span>
						<span id="categoria_dados"></span>
					</div>
					<div class="col-md-5">
						<span><b>Valor Compra: </b></span>
						<span id="valor_compra_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-7">
						<span><b>Valor Venda: </b></span>
						<span id="valor_venda_dados"></span>
					</div>

					<div class="col-md-5">
						<span><b>Estoque: </b></span>
						<span id="estoque_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">

					<div class="col-md-8">
						<span><b>Alerta Nível Mínimo Estoque: </b></span>
						<span id="nivel_estoque_dados"></span>
					</div>

				</div>

				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-12">
						<span><b>Descrição: </b></span>
						<span id="descricao_dados"></span>
					</div>

				</div>

				<div class="row">
					<!-- Exibe a imagem do produto -->
					<div class="col-md-12" align="center">
						<img width="250px" id="target_mostrar">
					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<!-- Variável JavaScript que armazena o nome da página atual -->
<script type="text/javascript">
	var pag = "<?= $pag ?>" // Define a variável 'pag' com o valor da página atual
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<!-- Função JavaScript para carregar e exibir a imagem selecionada -->
<script type="text/javascript">
	function carregarImg() {
		var target = document.getElementById('target'); // Elemento de imagem para exibição
		var file = document.querySelector("#foto").files[0]; // Obtém o arquivo de imagem selecionado

		// Cria um leitor de arquivo
		var reader = new FileReader();

		// Função que será chamada após o carregamento do arquivo
		reader.onloadend = function() {
			// Define o conteúdo da imagem no elemento 'target'
			target.src = reader.result;
		};
		// Se um arquivo foi selecionado, lê a imagem como URL de dados
		if (file) {
			reader.readAsDataURL(file);

		} else {
			// Se nenhum arquivo foi selecionado, limpa a imagem
			target.src = "";
		}
	}
</script>