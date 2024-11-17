<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como grupos
$pag = 'grupos';

//verificar se ele tem a permissão de estar nessa página
if (@$grupos == 'ocultar') {
	// Se a permissão for "ocultar", redireciona para a página inicial
	echo "<script>window.location='../index.php'</script>";
	exit();
}
?>

<div class="">
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Grupo</a>
</div>

<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<!-- Modal para inserção de novo grupo -->
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário de inserção do grupo -->
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
					<!-- Campo oculto que  será usado para passar o ID do grupo ao salvar -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<div id="mensagem" align="center"></div>
					</small>
				</div>
			</form>

		</div>
	</div>
</div>

<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<script src="js/ajax.js"></script>