<?php
// Inicia a sessão (ou continua a sessão existente) para acessar as variáveis de sessão
@session_start();

// Verifica se a variável de sessão 'id' está vazia, o que indica que o usuário não está logado
if (@$_SESSION['id'] == "") {
	// Se 'id' estiver vazio, redireciona o usuário para a página de login
	echo "<script>window.location='../index.php'</script>";
	exit(); // Encerra a execução do script para impedir acesso não autorizado
}
