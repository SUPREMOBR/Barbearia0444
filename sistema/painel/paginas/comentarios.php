<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Inclui o arquivo conexao.php, que provavelmente faz a conexão com o banco de dados.

// Define o nome da página como 'comentarios'.
$pag = 'comentarios';

// Verifica se o usuário tem permissão para acessar a página de comentários.
// Se a variável $comentarios estiver definida como 'ocultar', redireciona para a página inicial.
if (@$comentarios == 'ocultar') {
	echo "<script>window.location='../index.php'</script>";
	exit();
}
?>

<div class="">
	<!-- Botão para inserir um novo comentário. Chama a função JavaScript 'inserir()' ao ser clicado -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Comentário</a>
</div>
<!-- Div onde serão listados os comentários -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<!-- Modal de Inserção de Comentário -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<!-- Botão para fechar o modal -->
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">
					<!-- Formulário para inserir nome e texto do comentário -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
							</div>
						</div>
						<div class="col-md-12">

							<div class="form-group">
								<label for="exampleInputEmail1">Texto <small>(Até 500 Caracteres)</small></label>
								<input maxlength="500" type="text" class="form-control" id="texto" name="texto" placeholder="Texto Comentário">
							</div>
						</div>
					</div>
					<!-- Campo para fazer upload de uma foto do comentário -->
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label>Foto</label>
								<input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
							</div>
						</div>
						<div class="col-md-4">
							<div id="divImg">
								<img src="img/comentarios/sem-foto.jpg" width="80px" id="target">
							</div>
						</div>

					</div>
					<!-- Campo oculto para o ID do comentário, usado para edição -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<div id="mensagem" align="center"></div>
					</small>
				</div>

				<div class="modal-footer">
					<!-- Botão para salvar o comentário -->
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>

		</div>
	</div>
</div>

<!-- Modal para exibir dados de um comentário -->
<div class="modal fade" id="modalDados" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- Título do modal com o nome do autor do comentário -->
				<h4 class="modal-title" id="exampleModalLabel"><span id="nome_dados"></span></h4>
				<button id="btn-fechar-perfil" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body">
				<!-- Exibição do texto do comentário -->
				<div class="row" style="border-bottom: 1px solid #cac7c7;">
					<div class="col-md-12">
						<span><b>Texto Comentário: </b></span>
						<span id="texto_dados"></span>
					</div>

				</div>
				<!-- Exibição da imagem associada ao comentário -->
				<div class="row">
					<div class="col-md-12" align="center">
						<img width="100px" id="target_mostrar">
					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>" // Define a variável de página como 'comentarios' para utilização nos scripts.
</script>
<script src="js/ajax.js"></script>

<script type="text/javascript">
	// Inicializa o plugin select2, aplicando-o ao modal de formulário.
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>

<script type="text/javascript">
	// Função para pré-visualizar a imagem carregada no input file.
	function carregarImg() {
		var target = document.getElementById('target');
		var file = document.querySelector("#foto").files[0];

		var reader = new FileReader();
		// Define a imagem como a pré-visualização do arquivo carregado.
		reader.onloadend = function() {
			target.src = reader.result;
		};
		// Caso o arquivo exista, faz a leitura. Caso contrário, limpa a pré-visualização.
		if (file) {
			reader.readAsDataURL(file);

		} else {
			target.src = "";
		}
	}
</script>