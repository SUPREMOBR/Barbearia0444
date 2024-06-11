<?php 
$tabela = 'receber';
require_once("../../../conexao.php");
$data_atual = date('Y-m-d');

@session_start();
$usuario_logado = @$_SESSION['id'];

$cliente = $_POST['cliente_agd'];
$data_pagamento = $_POST['data_pagamento'];
$id_agd = @$_POST['id_agd'];
$valor_serv = $_POST['valor_serv_agd'];
$descricao = $_POST['descricao_serv_agd'];
$funcionario = $_POST['funcionario_agd'];
$servico = $_POST['servico_agd'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$valor = $resultado[0]['valor'];
$comissao = $resultado[0]['comissao'];
$descricao = $resultado[0]['nome'];
$descricao2 = 'Comissão - '.$resultado[0]['nome'];

if($tipo_comissao == 'Porcentagem'){
	$valor_comissao = ($comissao * $valor) / 100;
}else{
	$valor_comissao = $comissao;
}

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

$pdo->query("INSERT INTO $tabela SET descricao = '$descricao', tipo = 'Serviço', valor = '$valor_serv', data_lancamento = curDate(), data_vencimento = '$data_pagamento', data_pagamento = '$data_pagamento2', usuario_lancou = '$usuario_logado', usuario_baixa = '$usuario_baixa', foto = 'sem-foto.jpg', pessoa = '$cliente', pago = '$pago', servico = '$servico', funcionario = '$funcionario'");
$pdo->query("UPDATE agendamentos SET status = 'Concluído' where id = '$id_agd'");

echo 'Salvo com Sucesso'; 

?>