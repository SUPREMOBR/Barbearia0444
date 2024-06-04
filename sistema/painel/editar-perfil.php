<?php 
require_once('../conexao.php');

$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$conf_senha = $_POST['conf_senha'];
$endereco = $_POST['endereco'];
$senha_crip = md5($senha);
$atendimento = $_POST['atendimento'];

$foto = '';

if($senha != $conf_senha){
	echo 'As senhas são diferentes!!';
	exit();
}

//validar email
$query = $pdo->query("SELECT * from usuarios01 where email = '$email'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0 and $id != $resultado[0]['id']){
	echo 'Email já Cadastrado, escolha outro!!';
	exit();
}

//validar cpf
$query = $pdo->query("SELECT * from usuarios01 where cpf = '$cpf'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) > 0 and $id != $resultado[0]['id']){
	echo 'CPF já Cadastrado, escolha outro!!';
	exit();
}



//validar troca da foto
$query = $pdo->query("SELECT * FROM usuarios01 where id = '$id'");
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

$caminho = 'img/perfil/' .$nome_img;

$imagem_temporaria = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$extencao = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($extencao == 'png' or $extencao == 'jpg' or $extencao == 'jpeg' or $extencao == 'gif'){ 
	
			//EXCLUO A FOTO ANTERIOR
			if($foto != "sem-foto.jpg"){
				@unlink('img/perfil/'.$foto);
			}

			$foto = $nome_img;
		
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}




$query = $pdo->prepare("UPDATE usuarios01 SET nome = :nome, email = :email, telefone = :telefone, cpf = :cpf, senha = :senha, senha_crip = '$senha_crip', endereco = :endereco, foto = '$foto', atendimento = '$atendimento' WHERE id = '$id'");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":cpf", "$cpf");
$query->bindValue(":senha", "$senha");
$query->bindValue(":endereco", "$endereco");
$query->execute();

echo 'Editado com Sucesso';
 ?>