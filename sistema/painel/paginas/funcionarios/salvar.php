<?php 
require_once("../../../conexao.php");
$tabela = 'usuarios01';

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

if($cargo == 0){
	echo 'Cadastre um Cargo para o Usuário';
	exit();
}

//validar email
$query = $pdo->query("SELECT * from $tabela where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0 and $id != $resultado[0]['id']){
	echo 'Email já Cadastrado, escolha outro';
	exit();
}


//validar cpf
$query = $pdo->query("SELECT * from $tabela where cpf = '$cpf'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0 and $id != $resultado[0]['id']){
	echo 'CPF já Cadastrado, escolha outro';
	exit();
}


//validar troca da foto
$query = $pdo->query("SELECT * FROM $tabela where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
	$foto = $resultado[0]['foto'];
}else{
	$foto = 'sem-foto.jpg';
}


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../img/perfil/' .$nome_img;

$imagem_temporaria = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$extencao = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($extencao == 'png' or $extencao == 'jpg' or $extencao == 'jpeg' or $extencao == 'gif'){ 
	
			//EXCLUO A FOTO ANTERIOR
			if($foto != "sem-foto.jpg"){
				@unlink('../../img/perfil/'.$foto);
			}

			$foto = $nome_img;
		
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}




if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, senha = '$senha', senha_crip = '$senha_crip', nivel = '$cargo', data = curDate(), ativo = 'Sim', telefone = :telefone, endereco = :endereco, foto = '$foto', atendimento = '$atendimento', tipo_chave = '$tipo_chave', chave_pix = :chave_pix");
}else{
	$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, nivel = '$cargo', telefone = :telefone, endereco = :endereco, foto = '$foto', atendimento = '$atendimento', tipo_chave = '$tipo_chave', chave_pix = :chave_pix WHERE id = '$id'");
}

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":chave_pix", "$chave_pix");
$query->execute();

echo 'Salvo com Sucesso';