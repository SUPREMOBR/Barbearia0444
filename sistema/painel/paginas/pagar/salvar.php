<?php 
require_once("../../../conexao.php");
$tabela = 'pagar';
@session_start();
$id_usuario = $_SESSION['id'];

$id = $_POST['id'];
$descricao = $_POST['descricao'];
$valor = $_POST['valor'];
$valor = str_replace(',', '.', $valor);
$pessoa = $_POST['pessoa'];
$data_vencimento = $_POST['data_vencimento'];
$data_pagamento = $_POST['data_pagamento'];
$funcionario = $_POST['funcionario'];

if($descricao == ""){
	echo 'Insira uma descrição!';
	exit();
}


if($data_pagamento != ''){
	$usuario_pagamento = $id_usuario;
	$pago = 'Sim';
}else{
	$usuario_pagamento = 0;
	$pago = 'Não';
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

$caminho = '../../img/contas/' .$nome_img;

$imagem_temporaria = @$_FILES['foto']['tmp_name']; 

if(@$_FILES['foto']['name'] != ""){
	$extensao = pathinfo($nome_img, PATHINFO_EXTENSION);   
	if($extensao == 'png' or $extensao == 'jpg' or $extensao == 'jpeg' or $extensao == 'gif'
	or $extensao == 'pdf' or $extensao == 'rar' or $extensao == 'zip'){ 
	
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
	$query = $pdo->prepare("INSERT INTO $tabela SET descricao = :descricao, tipo = 'Conta', valor = :valor, data_lancamento = curDate(), data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', usuario_lancou = '$id_usuario', usuario_baixa = '$usuario_pagamento', foto = '$foto', pessoa = '$pessoa', pago = '$pago', funcionario = '$funcionario'");
}else{
	$query = $pdo->prepare("UPDATE $tabela SET descricao = :descricao, valor = :valor, data_vencimento = '$data_vencimento', data_pagamento = '$data_pagamento', foto = '$foto', pessoa = '$pessoa', funcionario = '$funcionario' WHERE id = '$id'");
}

$query->bindValue(":descricao", "$descricao");
$query->bindValue(":valor", "$valor");
$query->execute();

echo 'Salvo com Sucesso';
 ?>