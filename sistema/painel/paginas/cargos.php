<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", que pode conter uma lógica de verificação de acesso ou autenticação
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados para que as funções de acesso ao banco fiquem disponíveis

$pag = 'cargos'; // Define a variável $pag com o valor 'cargos', usada para definir o contexto ou a página atual

// Verifica se o usuário tem permissão para acessar esta página
if (@$cargos == 'ocultar') {
	// Redireciona o usuário para a página principal caso ele não tenha permissão
	echo "<script>window.location='../index.php'</script>";
	exit(); // Interrompe a execução do script
}

?>

<div class="">
	<!-- Cria um botão que, ao ser clicado, chama a função JavaScript `inserir()` para abrir o formulário de inserção de um novo cargo -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Cargo</a>
</div>

<!-- Div onde serão listados os cargos -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">
</div>

<!-- Modal para Inserir Novo Cargo -->
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
							</div>
						</div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-primary">Salvar</button>
						</div>
					</div>
					<!-- Campo oculto para armazenar o ID do cargo a ser editado -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<!-- Div para exibir mensagens de feedback, como sucesso ou erro -->
						<div id="mensagem" align="center"></div>
					</small>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>"; // Passa a variável PHP $pag para JavaScript para que possa ser usada no script AJAX
</script>
<script src="js/ajax.js"></script> <!-- Inclui o arquivo JavaScript para funcionalidades AJAX -->