<?php 
require_once("../../../conexao.php");
$tabela = 'receber';
@session_start();
$id_usuario = $_SESSION['id'];

$id = $_POST['id'];
$produto = $_POST['produto'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$pessoa = $_POST['pessoa'];
$data_vencimento = $_POST['data_vencimento'];
$data_pagamento = $_POST['data_pagamento'];
$quantidade = $_POST['quantidade'];


if($produto == 0){
	echo 'Cadastre um Produto e Depois selecione!';
	exit();
}


$query = $pdo->query("SELECT * FROM produtos where id = '$produto'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$descricao = 'Venda - ('.$quantidade.') '.$resultado[0]['nome'];
$estoque = $resultado[0]['estoque'];


if($data_pagamento != ''){
	$usuario_pagamento = $id_usuario;
	$pago = 'Sim';
}else{
	$usuario_pagamento = 0;
	$pago = 'Não';
}

if($quantidade > $estoque){
	echo 'Você não pode vendar mais do que você possui em estoque! Você tem '.$estoque. ' produtos em estoque!';
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


//atualizar dados do produto
$total_estoque = $estoque - $quantidade;
$pdo->query("UPDATE produtos SET estoque = '$total_estoque' WHERE id = '$produto'");


//SCRIPT PARA SUBIR FOTO NO SERVIDOR
$nome_img = date('d-m-Y H:i:s') .'-'.@$_FILES['foto']['name'];
$nome_img = preg_replace('/[ :]+/' , '-' , $nome_img);

$caminho = '../../img/contas/' .$nome_img;

$imagem_temporaria = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$extencao = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($extencao == 'png' or $extencao == 'jpg' or $extencao == 'jpeg' or $extencao == 'gif'
	or $extencao == 'pdf' or $extencao == 'rar' or $extencao == 'zip'){ 
	
			//EXCLUO A FOTO ANTERIOR
			if($foto != "sem-foto.jpg"){
				@unlink('../../img/contas/'.$foto);
			}

			$foto = $nome_img;
		
		move_uploaded_file($imagem_temporaria, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}




if($id == ""){
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Venda', valor = :valor, data_lancamento = curDate(), data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', foto = '$foto', pessoa = '$pessoa', pago = '$pago', produto = '$produto', quantidade = '$quantidade'");
}else{
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', foto = '$foto', pessoa = $pessoa, produto = '$produto', quantidade = '$quantidade' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
 ?>