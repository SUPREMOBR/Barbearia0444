<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01'; // Define o nome da tabela no banco de dados

// Recebe os dados enviados pelo formulário via POST.
$id = $_POST['id']; // ID do usuário (caso já exista ou seja um novo cadastro).
$nome = $_POST['nome']; // Nome do usuário.
$email = $_POST['email']; // Email do usuário.
$telefone = $_POST['telefone']; // Telefone do usuário.
$cpf = $_POST['cpf']; // CPF do usuário.
$cargo = $_POST['cargo']; // Cargo do usuário (deve ser preenchido).
$endereco = $_POST['endereco']; // Endereço do usuário.
$atendimento = $_POST['atendimento']; // Atendimento do usuário.
$senha = '123'; // Senha padrão, pode ser alterada futuramente.
$senha_crip = md5($senha); // Criptografa a senha usando MD5.

if ($cargo == "0") { // Verifica se o campo cargo foi preenchido.
	echo 'Cadastre um Cargo para o Usuário'; // Se o campo "cargo" for "0", exibe um erro.
	exit(); // Encerra o script aqui, impedindo que o restante do código seja executado.
}

// Validação de email único no banco de dados.
$query = $pdo->query("SELECT * from $tabela where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se o email já existe (mas não é o email do próprio usuário sendo editado).
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Email já Cadastrado, escolha outro!!'; // Exibe erro se o email já estiver registrado.
	exit();
}

// Validação de CPF único no banco de dados
$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se o CPF já existe (mas não é o CPF do próprio usuário sendo editado).
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'CPF já Cadastrado, escolha outro!!'; // Exibe erro se o CPF já estiver registrado.
	exit();
}

// Verifica se o usuário já tem uma foto cadastrada no banco de dados.
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	// Se o usuário já existir, pega a foto atual.
	$foto = $resultado[0]['foto'];
} else {
	// Se não existir, define a foto padrão como 'sem-foto.jpg'.
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/perfil/' . $nome_img;

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION); // Obtém a extensão da imagem (png, jpg, jpeg, gif).
	// Verifica se a extensão é permitida.
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		// Exclui a foto anterior do usuário, caso exista
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/perfil/' . $foto);
		}

		$foto = $nome_img;

		move_uploaded_file($imagem_temp, $caminho);
	} else {
		// Exibe erro caso a extensão da imagem não seja permitida
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}




if ($id == "") {
	// Se o ID estiver vazio, significa que é um novo cadastro, então usa o comando INSERT.
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, senha = '$senha', senha_crip = '$senha_crip', 
	nivel = '$cargo', data = curDate(), ativo = 'Sim', telefone = :telefone, endereco = :endereco, foto = '$foto', atendimento = '$atendimento'");
} else {
	// Caso contrário, é uma atualização de usuário existente, então usa o comando UPDATE.
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, nivel = '$cargo', telefone = :telefone, 
	endereco = :endereco, foto = '$foto', atendimento = '$atendimento' WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->execute();

echo 'Salvo com Sucesso';
