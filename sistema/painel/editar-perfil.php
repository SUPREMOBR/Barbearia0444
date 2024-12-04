<?php
// Conecta ao bd
require_once('../conexao.php');

// Recebe os dados enviados via POST e os armazena em variáveis
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$conf_senha = $_POST['conf_senha'];
$endereco = $_POST['endereco'];
$senha_crip = md5($senha); // Criptografa a senha usando MD5
$atendimento = $_POST['atendimento'];
$intervalo = $_POST['intervalo'];
$foto = ''; // Variável para armazenar o nome da foto

// Verifica se as senhas coincidem
if ($senha != $conf_senha) {
	echo 'As senhas são diferentes!!';
	exit();
}

// Valida se o e-mail já está cadastrado no banco de dados
$query = $pdo->query("SELECT * from usuarios01 where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Email já Cadastrado, escolha outro!!';
	exit();
}

// Valida se o CPF já está cadastrado no banco de dados
$query = $pdo->query("SELECT * from usuarios01 where cpf = '$cpf'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'CPF já Cadastrado, escolha outro!!';
	exit();
}



// Consulta a foto atual do usuário para verificar se será trocada
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	// Armazena a foto atual
	$foto = $resultado[0]['foto'];
} else {
	// Define uma foto padrão se não houver uma foto existente
	$foto = 'sem-foto.jpg';
}


// Script para fazer upload da foto do perfil do usuário
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img); // Substitui espaços e caracteres especiais
// Caminho para salvar a imagem
$caminho = 'img/perfil/' . $nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name'];

// Verifica se uma imagem foi enviada
if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		//EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('img/perfil/' . $foto);
		}
		// Atualiza o nome da foto para a nova imagem
		$foto = $nome_img;
		// Move a nova imagem para o caminho especificado
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


// Prepara a consulta para atualizar os dados do usuário no banco de dados
$query = $pdo->prepare("UPDATE usuarios01 SET nome = :nome, email = :email, telefone = :telefone, cpf = :cpf, senha = :senha, senha_crip = '$senha_crip', 
endereco = :endereco, foto = '$foto', atendimento = '$atendimento', intervalo = '$intervalo' WHERE id = '$id'");

// Vincula as variáveis aos parâmetros da consulta para evitar SQL Injection
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":senha", "$senha");
$query->bindValue(":endereco", "$endereco");
$query->execute();

// Exibe mensagem de sucesso ao final da execução
echo 'Editado com Sucesso';
