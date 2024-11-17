<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01'; // Define o nome da tabela no banco de dado

// Recebe os dados enviados pelo formulário via POST
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$cargo = $_POST['cargo'];
$endereco = $_POST['endereco'];
$atendimento = $_POST['atendimento'];
$tipo_chave = $_POST['tipo_chave'];
$chave_pix = $_POST['chave_pix'];
$senha = '123';
$senha_crip = md5($senha);
$intervalo = $_POST['intervalo'];
$comissao = $_POST['comissao'];

// Verifica se um cargo foi selecionado
if ($cargo == "0") {
	echo 'Cadastre um Cargo para o Usuáriox';
	exit();
}

// Validação de email único
$query = $pdo->query("SELECT * from $tabela where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'Email já Cadastrado, escolha outro!!';
	exit();
}

// Validação de CPF único
$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if (@count($resultado) > 0 and $id != $resultado[0]['id']) {
	echo 'CPF já Cadastrado, escolha outro!!';
	exit();
}

//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$foto = $resultado[0]['foto'];
} else {
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../img/perfil/' . $nome_img; // Caminho onde a imagem será armazenada

$imagem_temp = @$_FILES['foto']['tmp_name'];

if (@$_FILES['foto']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {

		//EXCLUO A FOTO ANTERIOR
		if ($foto != "sem-foto.jpg") {
			@unlink('../../img/perfil/' . $foto);
		}

		// Define o novo nome da foto
		$foto = $nome_img;

		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// INSERT ou UPDATE de acordo com a presença de ID
if ($id == "") {
	// Insere novo registro
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, senha = '$senha', senha_crip = '$senha_crip', 
	nivel = '$cargo', data = curDate(), ativo = 'Sim', telefone = :telefone, endereco = :endereco, foto = '$foto', atendimento = '$atendimento', 
	tipo_chave = '$tipo_chave', chave_pix = :chave_pix, intervalo = '$intervalo', comissao = '$comissao'");
} else {
	// Atualiza registro existente
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, nivel = '$cargo', telefone = :telefone, endereco = :endereco, 
	foto = '$foto', atendimento = '$atendimento', tipo_chave = '$tipo_chave', chave_pix = :chave_pix, intervalo = '$intervalo', 
	comissao = '$comissao' WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":chave_pix", "$chave_pix");
$query->execute();

echo 'Salvo com Sucesso';
