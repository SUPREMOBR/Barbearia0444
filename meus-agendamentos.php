<?php
// Inicia a sessão
session_start();
// Obtém o número de telefone da sessão atual
$telefone = $_SESSION['telefone'];
// Inclui o cabeçalho da página
require_once("cabecalho.php");
// Define a data atual no formato 'Y-m-d'
$data_atual = date('Y-m-d');

// Verifica se o telefone está vazio
if ($telefone == '') {
	// Exibe um alerta e redireciona o usuário para a página de agendamentos
	//echo "<script>window.alert('Você precisa inserir seu Telefone')</script>";
	//echo "<script>window.location='agendamentos.php'</script>";

}
// Busca o cliente pelo telefone
$query = $pdo->query("SELECT * FROM clientes where telefone = '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Armazena o ID do cliente
$id_cliente = $resultado[0]['id'];

?>
<style type="text/css">
	.sub_page .hero_area {
		min-height: auto;
	}
</style>

</div>

<div class="footer_section" style="background: #FFF;">
	<div class="container">
		<div class="footer_content ">

			<?php
			// Busca agendamentos do cliente com status 'Agendado', ordenados por data
			$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and status = 'Agendado' ORDER BY data asc");
			$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			// Conta o número total de agendamentos
			$total_registro = @count($resultado);
			// Se houver agendamentos
			if ($total_registro > 0) {
				// Percorre cada agendamento
				for ($i = 0; $i < $total_registro; $i++) {
					foreach ($resultado[$i] as $key => $value) {
					}
					// Armazena informações do agendamento atual
					$id = $resultado[$i]['id'];
					$funcionario = $resultado[$i]['funcionario'];
					$cliente = $resultado[$i]['cliente'];
					$hora = $resultado[$i]['hora'];
					$data = $resultado[$i]['data'];
					$usuario = $resultado[$i]['usuario'];
					$data_lancamento = $resultado[$i]['data_lancamento'];
					$obs = $resultado[$i]['obs'];
					$status = $resultado[$i]['status'];
					$servico = $resultado[$i]['servico'];
					$ref_pix = $resultado[$i]['ref_pix'];

					// Formata a data e a hora
					$dataF = implode('/', array_reverse(explode('-', $data)));
					$horaF = date("H:i", strtotime($hora));

					// Define a classe da linha conforme o status
					if ($status == 'Concluído') {
						$classe_linha = '';
					} else {
						$classe_linha = 'text-muted';
					}


					// Define a imagem e classe de status conforme o status do agendamento
					if ($status == 'Agendado') {
						$imagem = 'icone-relogio.png';
						$classe_status = '';
					} else {
						$imagem = 'icone-relogio-verde.png';
						$classe_status = 'ocultar';
					}

					// Busca o nome do usuário responsável pelo agendamento
					$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_usuario = $resultado2[0]['nome'];
					} else {
						$nome_usuario = 'Sem Usuário';
					}
					// Busca o nome do funcionário responsável pelo serviço
					$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_funcionario = $resultado2[0]['nome'];
					} else {
						$nome_funcionario = 'Sem Usuário';
					}

					// Busca o nome e valor do serviço
					$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_serv = $resultado2[0]['nome'];
						$valor_serv = $resultado2[0]['valor'];
					} else {
						$nome_serv = 'Não Lançado';
						$valor_serv = '';
					}

					// Busca o nome do cliente
					$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_cliente = $resultado2[0]['nome'];
					} else {
						$nome_cliente = 'Sem Cliente';
					}

					//retirar aspas do texto do obs
					$obs = str_replace('"', "**", $obs);

			?>

					<div class="list-group">

						<div class="list-group-item list-group-item-action flex-column align-items-start " style="margin-bottom: 10px">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1"><small> <i class="fa fa-calendar" aria-hidden="true"></i> Data: <?php echo $dataF ?> <i class="fa fa-clock-o text-success" aria-hidden="true" style="margin-left: 10px"></i> Hora: <?php echo $horaF ?></small></h5>
								<small><a href="#" onclick="excluir('<?php echo $id ?>', '<?php echo $nome_cliente ?>', '<?php echo $dataF ?>', '<?php echo $horaF ?>', '<?php echo $nome_serv ?>', '<?php echo $nome_funcionario ?>')"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i> </a></small>
							</div>
							<p class="mb-1"><small>Funcionário: <?php echo $nome_funcionario ?></small></p>
							<small><b>Serviço:</b> <?php echo $nome_serv ?> <b>Valor:</b> R$ <?php echo $valor_serv ?></small>
						</div>

					</div>



			<?php
				}
			} else {
				// Exibe mensagem se não houver agendamentos
				echo 'Nenhum horário para essa Data!';
			}

			?>


			<br>


		</div>


	</div>





</div>


<?php require_once("rodape.php") // Inclui o rodapé da página 
?>


<!-- Modal Excluir -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><small>Excluir Agendamento</small></h5>
				<!-- Botão para fechar o modal -->
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar-excluir">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário para exclusão de agendamento -->
			<form id="form-excluir">
				<div class="modal-body">

					Deseja Excluir o Agendamento?

					<!-- Mensagem de status da exclusão -->
					<span id="msg-excluir"></span>

					<!-- Campos ocultos para armazenar dados do agendamento a ser excluído -->
					<input type="hidden" name="id" id="nome_excluir">
					<input type="hidden" name="id" id="data_excluir">
					<input type="hidden" name="id" id="hora_excluir">
					<input type="hidden" name="id" id="servico_excluir">
					<input type="hidden" name="id" id="func_excluir">
					<input type="hidden" name="id" id="id_excluir">

					<br>
					<small>
						<!-- Exibe mensagens de status no centro -->
						<div id="mensagem-excluir" align="center"></div>
					</small>
					<!-- Botão para enviar o formulário e excluir o agendamento -->
					<div align="right"><button type="submit" class="btn btn-danger">Excluir</button></div>
				</div>


			</form>

		</div>
	</div>
</div>


<!-- Script para capturar o telefone e exibir no modal -->
<script type="text/javascript">
	$(document).ready(function() {
		// Armazena o telefone do cliente
		var telefone = "<?= $telefone ?>";
	});
</script>

<!-- Função para abrir o modal e preencher os campos do agendamento -->
<script type="text/javascript">
	function excluir(id, nome, data, hora, servico, func) {


		$('#id_excluir').val(id);

		$('#nome_excluir').val(nome);
		$('#data_excluir').val(data);
		$('#hora_excluir').val(hora);
		$('#servico_excluir').val(servico);
		$('#func_excluir').val(func);

		// Exibe o modal para exclusão
		$('#modalExcluir').modal('show');

	}
</script>


<!-- Script para enviar o formulário de exclusão via AJAX -->
<script>
	$("#form-excluir").submit(function() {
		// Impede o envio padrão do formulário
		event.preventDefault();
		// Cria um FormData com os dados do formulário
		var formData = new FormData(this);

		$.ajax({
			url: "ajax/excluir.php", // Envia a requisição para o arquivo PHP responsável pela exclusão
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-excluir').text('');
				$('#mensagem-excluir').removeClass()
				// Verifica se a exclusão foi bem-sucedida
				if (mensagem.trim() == "Cancelado com Sucesso") {
					// Fecha o modal
					$('#btn-fechar-excluir').click();
					// Exibe a mensagem de sucesso
					$('#mensagem').text(mensagem)
					// Armazena as informações do agendamento excluído
					var id_cliente = $('#id_excluir').val();
					var nome = $('#nome_excluir').val();
					var dataFormatada = $('#data_excluir').val();
					var horaFormatada = $('#hora_excluir').val();
					var nome_serv = $('#servico_excluir').val();
					var nome_funcionario = $('#func_excluir').val();

					// Redireciona para a página de agendamentos
					window.location = "agendamentos.php";

					var msg_agendamento = "<?= $msg_agendamento ?>";

					// Envia mensagem pelo WhatsApp se estiver habilitado
					if (msg_agendamento == 'Sim') {

						let a = document.createElement('a');
						a.target = '_blank';
						a.href = 'http://api.whatsapp.com/send?1=pt_BR&phone=<?= $telefone_whatsapp ?>&text= *Atenção:* _Agendamento Cancelado_ %0A Funcionário: *' + nome_funcionario + '* %0A Serviço: *' + nome_serv + '* %0A Data: *' + dataFormatada + '* %0A Hora: *' + horaFormatada + '* %0A Cliente: *' + nome + '*';
						a.click();
						return;

					}

				} else {
					//$('#mensagem').addClass('text-danger')
					// Exibe mensagem de erro caso a exclusão falhe
					$('#mensagem-excluir').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>