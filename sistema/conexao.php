<?php
$banco = 'barbearia01';
$usuario = 'root';
$senha = '';
$servidor = 'localhost';

date_default_timezone_set('America/Sao_Paulo');

 try {
      $pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $erro) {
echo 'Não conectado ao Banco de Dados! <br><br>'  .$erro;
}



//VARIAVEIS DO SISTEMA
$nome_sistema = 'Salão Lima';
$email_sistema = 'nagatabrisa.05@gmail.com';
$whatsapp_sistema = '(96) 99182-7077';



$query = $pdo->query("SELECT * from config ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro == 0){
	$pdo->query("INSERT INTO config SET nome = '$nome_sistema', email = '$email_sistema', telefone_whatsapp = '$whatsapp_sistema', logo = 'logo.png', icone = 'favicon.ico', logo_relatorio = 'logo_relatorio.jpg', tipo_relatorio = 'pdf', tipo_comissao = 'Porcentagem'");
}else{
	$nome_sistema = $resultado[0]['nome'];
	$email_sistema = $resultado[0]['email'];
	$whatsapp_sistema = $resultado[0]['telefone_whatsapp'];     
	$tipo_relatorio = $resultado[0]['tipo_relatorio'];
	$telefone_fixo_sistema = $resultado[0]['telefone_fixo'];
	$endereco_sistema = $resultado[0]['endereco'];
	$logo_relatorio = $resultado[0]['logo_relatorio'];
	$logo_sistema = $resultado[0]['logo'];
	$icone_sistema = $resultado[0]['icone'];
	$tipo_comissao = $resultado[0]['tipo_comissao'];


	$telefone_whatsapp = '55'.preg_replace('/[ ()-]+/' , '' , $whatsapp_sistema);
	
	
}

?>