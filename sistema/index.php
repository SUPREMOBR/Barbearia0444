<?php
// Conecta ao banco de dados.
require_once("conexao.php");

//INSERIR UM USUÁRIO ADM CASO N EXISTA
$senha = '123'; // Define uma senha padrão para o administrador.
$senha_crip = md5($senha); // Criptografa a senha usando MD5.

// Verifica se já existe um usuário com nível 'Administrador'.
$query = $pdo->query("SELECT * from usuarios01 where nivel = 'administrador'");
$resultado = $query->fetchALL(PDO::FETCH_ASSOC); // Recupera todos os registros do resultado da consulta.
$total_registro = @count($resultado); // Conta o número de registros encontrados.
if ($total_registro == 0) {
	// Se não existir nenhum administrador. Insere um administrador padrão com as informações abaixo.
	$pdo->query("INSERT INTO usuarios01 SET nome = 'Sousa Lima', email = '$email_sistema', cpf = '000.000.000-00', senha = '$senha',
	 senha_crip = '$senha_crip', nivel = 'Administrador', data = curDate(), ativo = 'Sim', foto = 'sem-foto.jpg'");
}
// VERIFICA SE EXISTEM CARGOS, SE NÃO EXISTIR, INSERE UM PADRÃO
$query = $pdo->query("SELECT * from cargos"); // Verifica a existência de registros na tabela `cargos`.
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Recupera todos os registros da tabela `cargos`.
$total_registro = @count($resultado); // Conta o número de registros.
if ($total_registro == 0) {
	// 1-Se não existir nenhum cargo. 2-Insere o cargo "Administrador" como padrão.
	$pdo->query("INSERT INTO cargos SET nome = 'Administrador'");
}

//EXCLUIR HORÁRIOS TEMPORÁRIOS
// Remove registros antigos na tabela `horarios` com data anterior à data atual.
$pdo->query("DELETE FROM horarios where data < curDate() and data != '' ");

//APAGAR AGENDAMENTOS ANTERIORES
// Pega a data atual
$data_atual = date('Y-m-d');
// Calcula a data limite para manter os agendamentos ativos.
$data_anterior = date('Y-m-d', strtotime("-$agendamento_dias days", strtotime($data_atual)));

// Seleciona agendamentos com data anterior à data limite.
$query = $pdo->query("SELECT * FROM agendamentos WHERE data < '$data_anterior'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC); // Recupera todos os registros do resultado da consulta.
$total_registro = @count($resultado); // Conta o número de registros encontrados.
if ($total_registro > 0) {
	// 1-Se existirem agendamentos antigos.
	for ($i = 0; $i < $total_registro; $i++) {
		// 2-Percorre cada registro.
		foreach ($resultado[$i] as $key => $value) {
		}
		// Armazena o ID do agendamento.
		$id = $resultado[$i]['id'];
		// Exclui o agendamento com o ID selecionado.
		$pdo->query("DELETE FROM agendamentos WHERE id = '$id'");
	}
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $nome_sistema ?></title>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/estilo-login.css"> <!-- Estilos personalizados para o formulário de login -->
	<link rel="icon" type="image/png" href="img/favicon.ico"> <!-- Ícone da aba do navegador -->
	<!-- Tema opcional -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Última versão JavaScript compilada e minificada -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>
	<!-- Estrutura HTML para o formulário de login -->
	<div class="container ">
		<div class="row vertical-offset-100">
			<div class="col-md-4 col-md-offset-4">
				<div class="panel panel-sucess form-login" style="opacity:0.9; border-radius: 20px">
					<div class="panel-heading" align="center" style="border-top-right-radius: 20px; border-top-left-radius: 20px">
						<img src="img/logo.png" width="100px">
					</div>
					<div class="panel-body">
						<!-- Formulário de login que envia dados para "autenticar.php" -->
						<form accept-charset="UTF-8" role="form" action="autenticar.php" method="post">
							<fieldset>
								<div class="form-group">
									<input class="form-control" placeholder="E-mail ou CPF" name="email" type="text">
								</div>
								<div class="form-group">
									<input class="form-control" placeholder="Senha" name="senha" type="password" value="">
								</div>
								<!-- Botão de envio do formulário -->
								<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
							</fieldset>
							<!-- Link para recuperação de senha -->
							<p class="recuperar"><a title="Clique para recuperar a senha" href="" data-toggle="modal" data-target="#exampleModal">Recuperar Senha</a></p>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>

</html>

<!-- Modal para recuperação de senha -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Recuperar Senha</h5>
				<button id="btn-fechar-recuperar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!-- Formulário para envio do e-mail de recuperação -->
			<form method="post" id="form-recuperar">
				<div class="modal-body">

					<input placeholder="Digite seu Email" class="form-control" type="email" name="email" id="email-recuperar" required>

					<br>
					<small>
						<!-- Mensagem de status da recuperação -->
						<div id="mensagem-recuperar" align="center"></div>
					</small>
				</div>
				<div class="modal-footer">
					<!-- Botão de envio para recuperação de senha -->
					<button type="submit" class="btn btn-success">Recuperar</button>
				</div>
			</form>
		</div>
	</div>
</div>



<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<script type="text/javascript">
	// JavaScript para processar o formulário de recuperação de senha via AJAX
	$("#form-recuperar").submit(function() {
		// Impede o comportamento padrão do formulário.
		event.preventDefault();
		// Cria um objeto FormData com os dados do formulário.
		var formData = new FormData(this);

		$.ajax({
			url: "recuperar-senha.php", // Envia os dados para "recuperar-senha.php".
			type: 'POST',
			data: formData,

			// Processa a resposta do servidor.
			success: function(mensagem) {
				$('#mensagem-recuperar').text('');
				$('#mensagem-recuperar').removeClass()
				// Se a resposta for positiva...
				if (mensagem.trim() == "Recuperado com Sucesso") {
					//$('#btn-fechar-recuperar').click();
					// Limpa o campo de e-mail.					
					$('#email-recuperar').val('');
					$('#mensagem-recuperar').addClass('text-success')
					$('#mensagem-recuperar').text('Sua Senha foi enviada para o Email')

				} else {
					// Se houve erro, exibe a mensagem de erro.
					$('#mensagem-recuperar').addClass('text-danger')
					$('#mensagem-recuperar').text(mensagem)
				}


			},

			cache: false,
			contentType: false,
			processData: false,

		});

	});
</script>