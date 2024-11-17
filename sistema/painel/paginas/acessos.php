<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php");  // Inclui o arquivo que verifica o acesso do usuário
require_once("../conexao.php"); // Inclui o arquivo de conexão com o banco de dados

//verificar se ele tem a permissão de estar nessa página
if (@$acessos == 'ocultar') {
	// Redireciona o usuário para a página inicial se ele não tiver permissão
	echo "<script>window.location='../index.php'</script>";
	exit();
}

// Define o valor da variável para indicar a página atual
$pag = 'acessos';

?>

<div class="">
	<!-- Botão para abrir o modal de inserção de novo acesso -->
	<a class="btn btn-primary" onclick="inserir()" class="btn btn-primary btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Acesso</a>
</div>

<!-- Div onde será listada a tabela com os acessos -->
<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>

<!-- Modal para inserir novos acessos -->
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
			<!-- Formulário dentro do modal -->
			<form id="form">
				<div class="modal-body">

					<div class="row">
						<!-- Campo para inserir o nome do acesso -->
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
							</div>
						</div>
						<div class="col-md-4">
							<!-- Campo para inserir a chave do acesso -->
							<div class="form-group">
								<label for="exampleInputEmail1">Chave</label>
								<input type="text" class="form-control" id="chave" name="chave" placeholder="Chave">
							</div>
						</div>

						<!-- Campo para selecionar o grupo do acesso -->
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Grupo</label>
								<select class="form-control sel2" id="grupo" name="grupo" style="width:100%;">
									<!-- Opção padrão de nenhum grupo -->
									<option value="0">Nenhum Grupo</option>

									<?php
									// Realiza uma consulta para obter os grupos de acesso do banco de dados
									$query = $pdo->query("SELECT * FROM grupo_acessos ORDER BY id asc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									// Verifica se há grupos para exibir
									if ($total_registro > 0) {
										// Laço para exibir os grupos na lista suspensa
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

					<!-- Campo oculto para armazenar o ID do acesso -->
					<input type="hidden" name="id" id="id">

					<br>
					<small>
						<!-- Div para exibir mensagens do formulário -->
						<div id="mensagem" align="center"></div>
					</small>
				</div>
				<!-- Botão para enviar o formulário -->
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Salvar</button>
				</div>
			</form>


		</div>
	</div>
</div>


<!-- Script para definir a variável "pag" com o valor da página atual -->
<script type="text/javascript">
	var pag = "<?= $pag ?>"
</script>
<!-- Inclui o arquivo JavaScript responsável pelas requisições AJAX -->
<script src="js/ajax.js"></script>

<!-- Script para inicializar o Select2 (plugin de dropdown) no modal de formulário -->
<script type="text/javascript">
	$(document).ready(function() {
		$('.sel2').select2({
			dropdownParent: $('#modalForm')
		});
	});
</script>