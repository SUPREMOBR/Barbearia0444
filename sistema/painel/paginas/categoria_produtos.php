<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php" que provavelmente contém funções de verificação de permissão ou autenticação do usuário
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para que as operações de banco estejam disponíveis

// Define a variável $pag com o valor 'categoria_produtos', usada para indicar a página atual
$pag = 'categoria_produtos';

// Verifica se o usuário tem permissão para acessar esta página
if (@$cat_produtos == 'ocultar') {
	// Redireciona o usuário para a página principal caso ele não tenha permissão
	echo "<script>window.location='../index.php'</script>";
	exit(); // Interrompe a execução do script
}
?>

<div class="">
	<!-- Cria um botão que chama a função JavaScript `inserir()` para abrir o modal de inserção de uma nova categoria de produto -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Nova Categoria</a>
</div>

<!-- Div que  será usada para listar as categorias de produtos, sendo carregada  com AJAX -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">
</div>

<!-- Modal para Inserir Nova Categoria -->
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
						<div class="col-md-9">
							<div class="form-group">
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o Nome" required>
								<!-- Campo de entrada de texto onde o usuário insere o nome da nova categoria -->
							</div>
						</div>
						<div class="col-md-3">
							<!-- Botão para salvar o formulário -->
							<button type="submit" class="btn btn-primary">Salvar</button>
						</div>
					</div>
					<!-- Campo oculto para armazenar o ID da categoria, usado em edições -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<!-- Div para exibir mensagens de feedback, como erros ou confirmações -->
						<div id="mensagem" align="center"></div>
					</small>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>"; // Passa a variável PHP $pag para o JavaScript, facilitando o uso da página atual em scripts AJAX
</script>
<script src="js/ajax.js"></script> <!-- Inclui o arquivo JavaScript com as funções AJAX necessárias -->