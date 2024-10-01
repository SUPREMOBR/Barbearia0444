<?php 
require_once("../sistema/conexao.php");
@session_start();
$telefone = $_POST['telefone'];
$nome = $_POST['nome'];
$funcionario = $_POST['funcionario'];
$hora = @$_POST['hora'];
$servico = $_POST['servico'];
$obs = $_POST['obs'];
$data = $_POST['data'];
$data_agd = $_POST['data'];
$id = @$_POST['id'];

$hash = "";

$telefone_cliente = $_POST['telefone'];

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$intervalo = $resultado[0]['intervalo'];

$query = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$tempo = $resultado[0]['tempo'];


$hora_minutos = strtotime("+$tempo minutes", strtotime($hora));			
$hora_final_servico = date('H:i:s', $hora_minutos);

$nova_hora = $hora;



$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) == 0){
		echo 'Este Funcionário não trabalha neste Dia!';
	exit();
}else{
	$inicio = $resultado[0]['inicio'];
	$final = $resultado[0]['final'];
	$inicio_almoco = $resultado[0]['inicio_almoco'];
	$final_almoco = $resultado[0]['final_almoco'];
}

while (strtotime($nova_hora) < strtotime($hora_final_servico)){
		
		$hora_minutos = strtotime("+$intervalo minutes", strtotime($nova_hora));			
		$nova_hora = date('H:i:s', $hora_minutos);		
		
		//VERIFICAR NA TABELA HORARIOS AGD SE TEM O HORARIO NESSA DATA
		$query_agd = $pdo->query("SELECT * FROM horarios_agd where data = '$data' and funcionario = '$funcionario' and horario = '$nova_hora'");
		$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($resultado_agd) > 0){
			echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
			exit();
		}



		//VERIFICAR NA TABELA AGENDAMENTOS SE TEM O HORARIO NESSA DATA e se tem um intervalo entre o horario marcado e o proximo agendado nessa tabela
		$query_agd = $pdo->query("SELECT * FROM agendamentos where data = '$data' and funcionario = '$funcionario' and hora = '$nova_hora'");
		$resultado_agd = $query_agd->fetchAll(PDO::FETCH_ASSOC);
		if(@count($resultado_agd) > 0){
			if($tempo <= $intervalo){

			}else{
				if($hora_final_servico == $resultado_agd[0]['hora']){
					
				}else{
					echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido a outros agendamentos!';
						exit();
				}
				
			}
			
		}


		if(strtotime($nova_hora) > strtotime($inicio_almoco) and strtotime($nova_hora) < strtotime($final_almoco)){
		echo 'Este serviço demora cerca de '.$tempo.' minutos, precisa escolher outro horário, pois neste horários não temos disponibilidade devido ao horário de almoço!';
			exit();
	}

}


@$_SESSION['telefone'] = $telefone;

if($hora == ""){
	echo 'Escolha um Horário para Agendar!';
	exit();
}

if($data < date('Y-m-d')){
	echo 'Escolha uma data igual ou maior que Hoje!';
	exit();
}

//validar horario
$query = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0 and $resultado[0]['id'] != $id){
	echo 'Este horário não está disponível!';
	exit();
}

//Cadastrar o cliente caso não tenha cadastro
$query = $pdo->query("SELECT * FROM clientes where telefone LIKE '$telefone' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) == 0){
	$query = $pdo->prepare("INSERT INTO clientes SET nome = :nome, telefone = :telefone, data_cadastro = curDate(), alertado = 'Não'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");	
	$query->execute();
	$id_cliente = $pdo->lastInsertId();

}else{
	$id_cliente = $resultado[0]['id'];
}






$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

if($not_sistema == 'Sim'){
	$mensagem_not = $nome;
	$titulo_not = 'Novo Agendamento '.$dataFormatada.' - '.$horaFormatada;
	$id_usuario = $funcionario;
	require('../api/notid.php');
} 


if($msg_agendamento == 'Api'){

$query = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_funcionario = $resultado[0]['nome'];
$telefone_funcionario = $resultado[0]['telefone'];  //tel_func

$query = $pdo->query("SELECT * FROM servicos where id = '$servico' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$nome_serv = $resultado[0]['nome'];

$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));

$mensagem = '_Novo Agendamento_ %0A';
$mensagem .= 'Funcionário: *'.$nome_funcionario.'* %0A';
$mensagem .= 'Serviço: *'.$nome_serv.'* %0A';
$mensagem .= 'Data: *'.$dataFormatada.'* %0A';
$mensagem .= 'Hora: *'.$horaFormatada.'* %0A';
$mensagem .= 'Cliente: *'.$nome.'* %0A';
if($obs != ""){
	$mensagem .= 'Obs: *'.$obs.'* %0A';
}

$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);

require('api-texto.php');

if($telefone_funcionario != $whatsapp_sistema){  //tel_func
	$telefone = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_funcionario);  //tel_func
	require('api-texto.php');	
}


//agendar o alerta de confirmação
$hora_atual = date('H:i:s');
$data_atual = date('Y-m-d');
$hora_minutos = strtotime("-$minutos_aviso minutes", strtotime($hora));
$nova_hora = date('H:i:s', $hora_minutos);

if(strtotime($hora_atual) < strtotime($nova_hora) or strtotime($data_atual) != strtotime($data_agd)){
	$mensagem = '*Confirmação de Agendamento* %0A %0A';
	$mensagem .= 'Envie *Sim* para confirmar seu agendamento hoje às '.$horaFormatada;
	$data_mensagem = $data_agd.' '.$nova_hora;
	if($minutos_aviso > 0){
		require('api-agendar.php');
	}
	
}


}


//marcar o agendamento
$query = $pdo->prepare("INSERT INTO agendamentos SET funcionario = '$funcionario', cliente = '$id_cliente', hora = '$hora', data = '$data_agd',
 usuario = '0', status = 'Agendado', obs = :obs, data_lancamento = curDate(), servico = '$servico', hash = '$hash'");

echo 'Agendado com Sucesso';
	

$query->bindValue(":obs", "$obs");
$query->execute();

?>