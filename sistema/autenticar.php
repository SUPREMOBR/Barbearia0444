<?php 
@session_start();
require_once("conexao.php");


$email = $_POST['email'];
$senha = $_POST['senha'];
$senha_crip = md5($senha);

$query = $pdo->prepare("SELECT * from usuarios01 where (email = :email or cpf = :email) and senha_crip = :senha");
$query->bindValue(":email", "$email");
$query->bindValue(":senha", "$senha_crip");
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

$total_registro = @count($resultado);
if($total_registro > 0){
	$ativo = $resultado[0]['ativo'];


	if($ativo == 'Sim'){

		$_SESSION['id'] = $resultado[0]['id'];
		$_SESSION['nivel'] = $resultado[0]['nivel'];
		$_SESSION['nome'] = $resultado[0]['nome'];
	
		//ir para o painel
		echo "<script>window.location='painel'</script>";
	}else{
		echo "<script>window.alert('Seu usuário foi desativado, contate o administrador!')</script>";
	echo "<script>window.location='index.php'</script>";
	}
	
}else{
	echo "<script>window.alert('Usuário ou Senha Incorretos!')</script>";
	echo "<script>window.location='index.php'</script>";
}

 ?>