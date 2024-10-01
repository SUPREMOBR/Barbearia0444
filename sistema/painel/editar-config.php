<?php
require_once('../conexao.php');

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$whatsapp = $_POST['whatsapp_sistema'];
$fixo = $_POST['telefone_fixo_sistema'];
$endereco = $_POST['endereco_sistema'];
$tipo_relatorio = $_POST['tipo_relatorio'];
$tipo_comissao = $_POST['tipo_comissao'];
$texto_rodape = $_POST['texto_rodape'];
$texto_sobre = $_POST['texto_sobre'];
$mapa = $_POST['mapa'];
$texto_agendamento = $_POST['texto_agendamento'];
$msg_agendamento = $_POST['msg_agendamento'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$cidade_sistema = $_POST['cidade_sistema'];
$agendamento_dias = $_POST['agendamento_dias'];
$itens_pag = $_POST['itens_pag'];
$minutos_aviso = $_POST['minutos_aviso'];
$token = $_POST['token'];
$instancia = $_POST['instancia'];
$instagram = $_POST['instagram_sistema']; 
$taxa_sistema = $_POST['taxa_sistema']; 


if ($minutos_aviso == "") {
	$minutos_aviso = 0;
}

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$caminho = '../img/logo.png';
$imagem_temporaria = @$_FILES['foto-logo']['tmp_name'];
if (@$_FILES['foto-logo']['name'] != "") {
	$extensao = pathinfo(@$_FILES['foto-logo']['name'], PATHINFO_EXTENSION);
	if ($extensao == 'png') {
		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão da imagem para a Logo é somente *PNG';
		exit();
	}
}

$caminho = '../img/favicon.ico';
$imagem_temporaria = @$_FILES['foto-icone']['tmp_name'];
if (@$_FILES['foto-icone']['name'] != "") {
	$extensao = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);
	if ($extensao == 'ico') {
		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão da imagem para a ícone é somente *ICO';
		exit();
	}
}


$caminho = '../img/logo_relatorio.jpg';
$imagem_temporaria = @$_FILES['foto-logo-rel']['tmp_name'];
if (@$_FILES['foto-logo-rel']['name'] != "") {
	$extensao = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);
	if ($extensao == 'jpg') {
		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão da imagem para o Relatório é somente *Jpg';
		exit();
	}
}

$query = $pdo->query("SELECT * FROM config");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$img_banner_index = $resultado[0]['img_banner_index'];

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = @$_FILES['foto-banner-index']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/' . $nome_img;

$imagem_temporaria = @$_FILES['foto-banner-index']['tmp_name'];

if (@$_FILES['foto-banner-index']['name'] != "") {
	$extensao = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($extensao == 'png' or $extensao == 'jpg' or $extensao == 'jpeg' or $extensao == 'gif') {

		$img_banner_index = $nome_img;

		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}



//validar troca da foto
$query = $pdo->query("SELECT * FROM config");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$img_sobre = $resultado[0]['imagem_sobre'];

//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = @$_FILES['foto-sobre']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);

$caminho = '../../images/' . $nome_img;

$imagem_temporaria = @$_FILES['foto-sobre']['tmp_name'];

if (@$_FILES['foto-sobre']['name'] != "") {
	$extensao = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($extensao == 'png' or $extensao == 'jpg' or $extensao == 'jpeg' or $extensao == 'gif') {

		$img_sobre = $nome_img;

		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}





$caminho = '../../images/favicon.png';
$imagem_temporaria = @$_FILES['foto-icone-site']['tmp_name'];
if (@$_FILES['foto-icone-site']['name'] != "") {
	$extensao = pathinfo(@$_FILES['foto-icone-site']['name'], PATHINFO_EXTENSION);
	if ($extensao == 'png') {
		move_uploaded_file($imagem_temporaria, $caminho);
	} else {
		echo 'Extensão da imagem para a ícone é somente *PNG';
		exit();
	}
}


$query = $pdo->prepare("UPDATE config SET nome = :nome, email = :email, telefone_whatsapp = :whatsapp, telefone_fixo = :telefone_fixo, 
endereco = :endereco, logo = 'logo.png', icone = 'favicon.png', logo_relatorio = 'logo_relatorio.jpg', 
tipo_relatorio = '$tipo_relatorio', tipo_comissao = '$tipo_comissao', texto_rodape = :texto_rodape, img_banner_index = '$img_banner_index', 
icone_site = 'favicon.png', imagem_sobre = '$img_sobre', texto_sobre = :texto_sobre, mapa = :mapa, 
texto_agendamento = :texto_agendamento, msg_agendamento = :msg_agendamento, texto_agendamento = :texto_agendamento, 
msg_agendamento = :msg_agendamento, cnpj = :cnpj, cidade = :cidade, agendamento_dias = '$agendamento_dias', itens_pag = '$itens_pag',
 minutos_aviso = '$minutos_aviso', token = :token, instancia = :instancia, instagram = :instagram, taxa_sistema = :taxa_sistema "); 

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":whatsapp", "$whatsapp");
$query->bindValue(":telefone_fixo", "$fixo");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":texto_rodape", "$texto_rodape");
$query->bindValue(":texto_sobre", "$texto_sobre");
$query->bindValue(":mapa", "$mapa");
$query->bindValue(":texto_agendamento", "$texto_agendamento");
$query->bindValue(":msg_agendamento", "$msg_agendamento");
$query->bindValue(":cnpj", "$cnpj_sistema");
$query->bindValue(":cidade", "$cidade_sistema");
$query->bindValue(":token", "$token");
$query->bindValue(":instancia", "$instancia");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":taxa_sistema", "$taxa_sistema");

$query->execute();

echo 'Editado com Sucesso';
