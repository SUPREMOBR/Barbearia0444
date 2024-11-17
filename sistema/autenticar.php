<?php
// Inicia a sessão para armazenar dados do usuário logado
@session_start();
// Inclui a conexão com o banco de dados
require_once("conexao.php");

// Recebe os dados de login do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];
// Criptografa a senha com o algoritmo MD5
$senha_crip = md5($senha);

// Prepara a consulta para verificar se o email ou CPF e senha correspondem a um usuário ativo
$query = $pdo->prepare("SELECT * from usuarios01 where (email = :email or cpf = :email) and senha_crip = :senha");
$query->bindValue(":email", "$email"); // Substitui o valor de ":email" pela variável $email
$query->bindValue(":senha", "$senha_crip"); // Substitui o valor de ":senha" pela senha criptografada
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta quantos registros foram encontrados

// Verifica se o usuário foi encontrado
if ($total_registro > 0) {
	$ativo = $resultado[0]['ativo'];


	if ($ativo == 'Sim') {
		// Armazena as informações do usuário na sessão
		$_SESSION['id'] = $resultado[0]['id'];
		$_SESSION['nivel'] = $resultado[0]['nivel'];
		$_SESSION['nome'] = $resultado[0]['nome'];

		// Redireciona para o painel do usuário
		echo "<script>window.location='painel'</script>";
	} else {
		// Exibe uma mensagem caso o usuário esteja desativado
		echo "<script>window.alert('Seu usuário foi desativado, contate o administrador!')</script>";
		// Redireciona para a página de login
		echo "<script>window.location='index.php'</script>";
	}
} else {
	// Exibe uma mensagem de erro caso o login falhe
	echo "<script>window.alert('Usuário ou Senha Incorretos!')</script>";
	// Redireciona para a página de login
	echo "<script>window.location='index.php'</script>";
}
