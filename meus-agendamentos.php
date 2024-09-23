<?php
session_start();
$telefone = $_SESSION['telefone'];
require_once("cabecalho.php");
$data_atual = date('Y-m-d');


echo $_SESSION['telefone'] . 'tel';
if ($telefone == '') {
	//echo "<script>window.alert('Você precisa inserir seu Telefone')</script>";
	//echo "<script>window.location='agendamentos.php'</script>";

}

$query = $pdo->query("SELECT * FROM clientes where telefone = '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
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
			$query = $pdo->query("SELECT * FROM agendamentos where cliente = '$id_cliente' and status = 'Agendado' ORDER BY data asc");
			$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
			$total_registro = @count($resultado);
			if ($total_registro > 0) {
				for ($i = 0; $i < $total_registro; $i++) {
					foreach ($resultado[$i] as $key => $value) {
					}
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

					$dataFormatada = implode('/', array_reverse(explode('-', $data)));
					$horaFormatada = date("H:i", strtotime($hora));


					if ($status == 'Concluído') {
						$classe_linha = '';
					} else {
						$classe_linha = 'text-muted';
					}



					if ($status == 'Agendado') {
						$imagem = 'icone-relogio.png';
						$classe_status = '';
					} else {
						$imagem = 'icone-relogio-verde.png';
						$classe_status = 'ocultar';
					}

					$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_usuario = $resultado2[0]['nome'];
					} else {
						$nome_usuario = 'Sem Usuário';
					}

					$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_funcionario = $resultado2[0]['nome'];
					} else {
						$nome_funcionario = 'Sem Usuário';
					}


					$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
					$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
					if (@count($resultado2) > 0) {
						$nome_serv = $resultado2[0]['nome'];
						$valor_serv = $resultado2[0]['valor'];
					} else {
						$nome_serv = 'Não Lançado';
						$valor_serv = '';
					}


					//retirar aspas do texto do obs
					$obs = str_replace('"', "**", $obs);

			?>

					<div class="list-group">

						<div class="list-group-item list-group-item-action flex-column align-items-start " style="margin-bottom: 10px">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1"><small> <i class="fa fa-calendar" aria-hidden="true"></i> Data: <?php echo $dataFormatada ?> <i class="fa fa-clock-o text-success" aria-hidden="true" style="margin-left: 10px"></i> Hora: <?php echo $horaFormatada ?></small></h5>
								<small><a href="#" onclick="excluir('<?php echo $id ?>', '<?php echo $nome_cliente ?>', '<?php echo $dataFormatada ?>', '<?php echo $horaFormatada ?>', '<?php echo $nome_serv ?>', '<?php echo $nome_funcionario ?>')"><i class="fa fa-trash-o text-danger" aria-hidden="true"></i> </a></small>
							</div>
							<p class="mb-1"><small>Funcionário: <?php echo $nome_funcionario ?></small></p>
							<small><b>Serviço:</b> <?php echo $nome_serv ?> <b>Valor:</b> R$ <?php echo $valor_serv ?></small>
						</div>

					</div>



			<?php
				}
			} else {
				echo 'Nenhum horário para essa Data!';
			}

			?>


			<br>



		</div>


	</div>





</div>




<?php require_once("rodape.php") ?>






<!-- Modal Excluir -->
<div class="modal fade" id="modalExcluir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><small>Excluir Agendamento</small></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px" id="btn-fechar-excluir">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form id="form-excluir">
				<div class="modal-body">

					<span id="msg-excluir"></span>

					<input type="hidden" name="id" id="nome_excluir">
					<input type="hidden" name="id" id="data_excluir">
					<input type="hidden" name="id" id="hora_excluir">
					<input type="hidden" name="id" id="servico_excluir">
					<input type="hidden" name="id" id="funcionario_excluir">
					<input type="hidden" name="id" id="id_excluir">

					<br>
					<small>
						<div id="mensagem-excluir" align="center"></div>
					</small>

					<div align="right"><button type="submit" class="btn btn-danger">Excluir</button></div>
				</div>


			</form>

		</div>
	</div>
</div>



<script type="text/javascript">
	function excluir(id, nome, data, hora, servico, funcionario) {


		$('#id_excluir').val(id);

		$('#nome_excluir').val(nome);
		$('#data_excluir').val(data);
		$('#hora_excluir').val(hora);
		$('#servico_excluir').val(servico);
		$('#funcionario_excluir').val(funcionario); //func

		$('#modalExcluir').modal('show');

	}
</script>



<script>
	$("#form-excluir").submit(function() {
		event.preventDefault();
		var formData = new FormData(this);

		$.ajax({
			url: "ajax/excluir.php",
			type: 'POST',
			data: formData,

			success: function(mensagem) {
				$('#mensagem-excluir').text('');
				$('#mensagem-excluir').removeClass()
				if (mensagem.trim() == "Cancelado com Sucesso") {
					$('#btn-fechar-excluir').click();
					$('#mensagem').text(mensagem)

					var id_cliente = $('#id_excluir').val();
					var nome = $('#nome_excluir').val();
					var dataFormatada = $('#data_excluir').val();
					var horaFormatada = $('#hora_excluir').val();
					var nome_serv = $('#servico_excluir').val();
					var nome_funcionario = $('#funcionario_excluir').val();

					window.location = "agendamentos.php";

					var msg_agendamento = "<?= $msg_agendamento ?>";

					if (msg_agendamento == 'Sim') {

						let a = document.createElement('a');
						a.target = '_blank';
						a.href = 'http://api.whatsapp.com/send?1=pt_BR&phone=<?= $telefone_whatsapp ?>&text= *Atenção:* _Agendamento Cancelado_ %0A Funcionário: *' + nome_funcionario + '* %0A Serviço: *' + nome_serv + '* %0A Data: *' + dataFormatada + '* %0A Hora: *' + horaFormatada + '* %0A Cliente: *' + nome + '*';
						a.click();
						return;

					}

				} else {
					//$('#mensagem').addClass('text-danger')
					$('#mensagem-excluir').text(mensagem)
				}

			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>