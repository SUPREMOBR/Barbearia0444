<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$cliente = $_POST['cliente'];
$data_pagamento = $_POST['data_pagamento'];
$id = @$_POST['id'];
$valor_serv = $_POST['valor_serv'];
$funcionario = $usuario_logado;
$servico = $_POST['servico'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$descricao = $resultado[0]['nome'];
$descricao2 = 'Comissão - '.$resultado[0]['nome'];

if(strtotime($data_pagamento) <=  strtotime($data_atual)){
	$pago = 'Sim';
	$data_pagamento2 = $data_pagamento;
	$usuario_baixa = $usuario_logado;

    //lançar a conta a pagar para a comissão do funcionário
	$pdo->query("INSERT INTO pagar SET descricao = '$descricao2', tipo = 'Comissão', valor = '$valor_comissao', data_lancamento = '$data_pagamento', data_vencimento = '$data_pagamento', usuario_lancou = '$usuario_logado', foto = 'sem-foto.jpg', pago = 'Não', funcionario = '$funcionario', servico = '$servico', cliente = '$cliente'");

}else{
	$pago = 'Não';
	$data_pagamento2 = '';
	$usuario_baixa = 0;
}


$pdo->query("INSERT INTO $tabela SET descricao = '$nome_servico', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(), data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario'");


echo 'Salvo com Sucesso';
 ?>