<?php
// Inclui o arquivo de conexão com o banco de dados
require_once('../conexao.php');

// Recebe os dados enviados via POST e os armazena em variáveis
$api = $_POST['api'];
$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$whatsapp = $_POST['whatsapp_sistema'];
$fixo = $_POST['telefone_fixo_sistema'];
$endereco = $_POST['endereco_sistema'];
$tipo_rel = $_POST['tipo_rel'];
$instagram = $_POST['instagram_sistema'];
$tipo_comissao = $_POST['tipo_comissao'];
$texto_rodape = $_POST['texto_rodape'];
$texto_sobre = $_POST['texto_sobre'];
$texto_agendamento = $_POST['texto_agendamento'];
$msg_agendamento = $_POST['msg_agendamento'];
$cnpj_sistema = $_POST['cnpj_sistema'];
$cidade_sistema = $_POST['cidade_sistema'];
$agendamento_dias = $_POST['agendamento_dias'];
$itens_pag = $_POST['itens_pag'];
$token = $_POST['token'];
$minutos_aviso = $_POST['minutos_aviso'];
$instancia = $_POST['instancia'];
$taxa_sistema = $_POST['taxa_sistema'];
$lancamento_comissao = $_POST['lancamento_comissao'];
$porc_servico = $_POST['porc_servico'];
$pagamento_api = $_POST['pagamento_api'];

// Define o valor padrão para $minutos_aviso caso ele esteja vazio
if ($minutos_aviso == "") {
	$minutos_aviso = 0;
}

// Script para fazer upload de imagem logo no servidor
$caminho = '../img/logo.png';
$imagem_temp = @$_FILES['foto-logo']['tmp_name'];
if (@$_FILES['foto-logo']['name'] != "") {
	$ext = pathinfo(@$_FILES['foto-logo']['name'], PATHINFO_EXTENSION);
	if ($ext == 'png') {
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão da imagem para a Logo é somente *PNG';
		exit();
	}
}

// Script para fazer upload de ícone no servidor
$caminho = '../img/favicon.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name'];
if (@$_FILES['foto-icone']['name'] != "") {
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);
	if ($ext == 'png') {
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão da imagem para a ícone é somente *ICO';
		exit();
	}
}

// Script para fazer upload da logo do relatório
$caminho = '../img/logo_rel.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name'];
if (@$_FILES['foto-logo-rel']['name'] != "") {
	$ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);
	if ($ext == 'jpg') {
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão da imagem para o Relatório é somente *Jpg';
		exit();
	}
}

// Consulta dados da configuração atual
$query = $pdo->query("SELECT * FROM config");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$img_banner_index = $resultado[0]['img_banner_index'];

// Script para fazer upload do banner principal
$nome_img = @$_FILES['foto-banner-index']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../../images/' . $nome_img;
$imagem_temp = @$_FILES['foto-banner-index']['tmp_name'];
if (@$_FILES['foto-banner-index']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {
		$img_banner_index = $nome_img;
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// Validação e upload da imagem sobre
$query = $pdo->query("SELECT * FROM config");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$img_sobre = $resultado[0]['imagem_sobre'];
$nome_img = @$_FILES['foto-sobre']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../../images/' . $nome_img;
$imagem_temp = @$_FILES['foto-sobre']['tmp_name'];
if (@$_FILES['foto-sobre']['name'] != "") {
	$ext = pathinfo($nome_img, PATHINFO_EXTENSION);
	if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif') {
		$img_sobre = $nome_img;
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

// Upload de ícone do site
$caminho = '../../images/favicon.png';
$imagem_temp = @$_FILES['foto-icone-site']['tmp_name'];
if (@$_FILES['foto-icone-site']['name'] != "") {
	$ext = pathinfo(@$_FILES['foto-icone-site']['name'], PATHINFO_EXTENSION);
	if ($ext == 'png') {
		move_uploaded_file($imagem_temp, $caminho);
	} else {
		echo 'Extensão da imagem para a ícone é somente *PNG';
		exit();
	}
}

// Atualiza a tabela 'config' no banco de dados com os novos dados
$query = $pdo->prepare("UPDATE config SET nome = :nome, email = :email, api = :api, telefone_whatsapp = :whatsapp, telefone_fixo = :telefone_fixo, 
endereco = :endereco, logo = 'logo.png', icone = 'favicon.png', logo_relatorio = 'logo_relatorio.jpg', tipo_rel = '$tipo_rel', 
instagram = :instagram, tipo_comissao = '$tipo_comissao', texto_rodape = :texto_rodape, img_banner_index = '$img_banner_index', icone_site = 'favicon.png', 
imagem_sobre = '$img_sobre', texto_sobre = :texto_sobre, texto_agendamento = :texto_agendamento, msg_agendamento = :msg_agendamento, 
cnpj = :cnpj, cidade = :cidade, agendamento_dias = '$agendamento_dias', itens_pag = '$itens_pag', token = :token, minutos_aviso = '$minutos_aviso', 
instancia = :instancia, taxa_sistema = :taxa_sistema, lancamento_comissao = :lancamento_comissao, porc_servico = :porc_servico, pagamento_api = :pagamento_api");

// Vincula as variáveis aos parâmetros da consulta para evitar SQL Injection
$query->bindValue(":api", "$api");
$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":whatsapp", "$whatsapp");
$query->bindValue(":telefone_fixo", "$fixo");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":texto_rodape", "$texto_rodape");
$query->bindValue(":texto_sobre", "$texto_sobre");
$query->bindValue(":texto_agendamento", "$texto_agendamento");
$query->bindValue(":msg_agendamento", "$msg_agendamento");
$query->bindValue(":cnpj", "$cnpj_sistema");
$query->bindValue(":cidade", "$cidade_sistema");
$query->bindValue(":token", "$token");
$query->bindValue(":instancia", "$instancia");
$query->bindValue(":taxa_sistema", "$taxa_sistema");
$query->bindValue(":lancamento_comissao", "$lancamento_comissao");
$query->bindValue(":porc_servico", "$porc_servico");
$query->bindValue(":pagamento_api", "$pagamento_api");
$query->execute();

// Exibe mensagem de sucesso ao final da execução
echo 'Editado com Sucesso';
