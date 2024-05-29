<?php 
require_once('../conexao.php');

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$whatsapp = $_POST['whatsapp_sistema'];
$fixo = $_POST['telefone_fixo_sistema'];
$endereco = $_POST['endereco_sistema'];
$tipo_relatorio = $_POST['tipo_relatorio'];
$tipo_comissao = $_POST['tipo_comissao'];

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$caminho = '../img/logo.png';
$imagem_temporaria = @$_FILES['foto-logo']['tmp_name']; 
if(@$_FILES['foto-logo']['name'] != ""){
	$extencao = pathinfo(@$_FILES['foto-logo']['name'], PATHINFO_EXTENSION);   
	if($extencao == 'png'){ 
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão da imagem para a Logo é somente *PNG';
		exit();
	}

}

$caminho = '../img/favicon.ico';
$imagem_temporaria = @$_FILES['foto-icone']['tmp_name']; 
if(@$_FILES['foto-icone']['name'] != ""){
	$extencao = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);   
	if($extencao == 'ico'){ 
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão da imagem para a ícone é somente *ICO';
		exit();
	}

}


$caminho = '../img/logo_rel.jpg';
$imagem_temporaria = @$_FILES['foto-logo-rel']['tmp_name']; 
if(@$_FILES['foto-logo-rel']['name'] != ""){
	$extencao = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($extencao == 'jpg'){ 
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão da imagem para o Relatório é somente *Jpg';
		exit();
	}

}


$query = $pdo->prepare("UPDATE config SET nome = :nome, email = :email, telefone_whatsapp = :whatsapp, telefone_fixo = :telefone_fixo, endereco = :endereco, logo = 'logo.png', icone = 'favicon.ico', logo_relatorio = 'logo_relatorio.jpg', tipo_relatorio = '$tipo_relatorio', tipo_comissao = '$tipo_comissao' ");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":whatsapp", "$whatsapp");
$query->bindValue(":telefone_fixo", "$fixo");
$query->bindValue(":endereco", "$endereco");

$query->execute();

echo 'Editado com Sucesso';
 ?>