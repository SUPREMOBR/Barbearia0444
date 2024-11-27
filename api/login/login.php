<?php
require_once("../../sistema/conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];
$senha_crip = md5($senha);

$query = $pdo->prepare("SELECT * from usuarios01 where (email = :email or cpf = :email) and senha_crip = :senha");
$query->bindValue(":email", "$email");
$query->bindValue(":senha", "$senha_crip");
$query->execute();
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
	$nome = $resultado[0]['nome'];
	$email = $resultado[0]['email'];
	$senha = $resultado[0]['senha'];
	$nivel = $resultado[0]['nivel'];
	$data = $resultado[0]['data'];
	$id = $resultado[0]['id'];
	$foto = $resultado[0]['foto'];
	$ativo = $resultado[0]['ativo'];

	$dados = array(
		'nome' => $nome,
		'email' => $email,
		'id' => $id,
		'nivel' => $nivel,
		'foto' => $foto,
		'nomeSistema' => $nome_sistema,
	);

	if ($ativo != 'Sim') {

		$dados = array(
			'msg' => 'Seu usuário foi desativado, contate o administrador!',
		);
	}
} else {
	$dados = array(
		'msg' => 'Usuário ou Senha Incorretos!!',
	);
}


$result = json_encode($dados);
echo $result;
