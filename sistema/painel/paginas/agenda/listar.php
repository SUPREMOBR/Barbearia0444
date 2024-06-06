<?php 
require_once("../../../conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$funcionario = @$_SESSION['id'];
$data = @$_POST['data'];

if($data == ""){
	$data = date('Y-m-d');
}


echo <<<HTML
<small>
HTML;
$query = $pdo->query("SELECT * FROM agendamentos where funcionario = '$funcionario' and data = '$data' ORDER BY hora asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
$id = $resultado[$i]['id'];
$funcionario = $resultado[$i]['funcionario'];
$cliente = $resultado[$i]['cliente'];
$hora = $resultado[$i]['hora'];
$data = $resultado[$i]['data'];
$usuario = $resultado[$i]['usuario'];
$data_lancamento = $resultado[$i]['data_lancamento'];
$obs = $resultado[$i]['obs'];
$status = $resultado[$i]['status'];
$servico = $resultado[$i]['servico'];

$dataFormatada = implode('/', array_reverse(explode('-', $data)));
$horaFormatada = date("H:i", strtotime($hora));


if($status == 'Concluído'){		
	$classe_linha = '';
}else{		
	$classe_linha = 'text-muted';
}



if($status == 'Agendado'){
	$imagem = 'icone-relogio.png';
	$classe_status = '';	
}else{
	$imagem = 'icone-relogio-verde.png';
	$classe_status = 'ocultar';
}

$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado2) > 0){
	$nome_usu = $resultado2[0]['nome'];
}else{
	$nome_usu = 'Sem Usuário';
}


$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado2) > 0){
	$nome_serv = $resultado2[0]['nome'];
	$valor_serv = $resultado2[0]['valor'];
}else{
	$nome_serv = 'Não Lançado';
	$valor_serv = '';
}


$query2 = $pdo->query("SELECT * FROM clientes where id = '$cliente'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado2) > 0){
	$nome_cliente = $resultado2[0]['nome'];
}else{
	$nome_cliente = 'Sem Cliente';
}

//retirar aspas do texto do obs
$obs = str_replace('"', "**", $obs);

echo <<<HTML
			<div class="col-xs-12 col-md-4 widget cardTarefas">
        		<div class="r3_counter_box">     		
        		
        		

				<li class="dropdown head-dpdn2" style="list-style-type: none;">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		<button type="button" class="close" title="Excluir agendamento" style="margin-top: -10px">
					<span aria-hidden="true"><big>&times;</big></span>
				</button>
				</a>

		<ul class="dropdown-menu" style="margin-left:-30px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}', '{$horaFormatada}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>


		<div class="row">
        		<div class="col-md-3">
        			 <img class="icon-rounded-vermelho" src="img/{$imagem}" width="45px" height="45px">
        		</div>
        		<div class="col-md-9">
        			<h5><strong>{$horaFormatada}</strong> <a href="#" onclick="fecharServico('{$id}', '{$cliente}', '{$servico}', '{$valor_serv}', '{$funcionario}', '{$nome_serv}')" title="Finalizar Serviço" class="{$classe_status}"> <img class="icon-rounded-vermelho" src="img/check-square.png" width="15px" height="15px"></a></h5>

        			
        		</div>
        		</div>

				
        		<div class="row">
        		<div class="col-md-3">
        			 <img class="icon-rounded-vermelho" src="img/{$imagem}" width="45px" height="45px">
        		</div>
        		<div class="col-md-9">
        			<h5><strong>{$horaF}</strong> <a href="#" onclick="fecharServico('{$id}', '{$cliente}', '{$servico}', '{$valor_serv}', '{$funcionario}', '{$nome_serv}')" title="Finalizar Serviço" class="{$classe_status}"> <img class="icon-rounded-vermelho" src="img/check-square.png" width="15px" height="15px"></a></h5>

        			
        		</div>
        		</div>
        		
        					
        		<hr style="margin-top:-2px; margin-bottom: 3px">                    
                    <div class="stats esc" align="center">
                      <span>
                      
                       <small>{$nome_cliente} (<i><span style="color:#061f9c">{$nome_serv}</span></i>)</small></span>
                    </div>
                </div>
        	</div>
HTML;
}
}else{
	echo 'Nenhum horário para essa Data';
}

?>


<script type="text/javascript">
	function fecharServico(id, cliente, servico, valor_servico, funcionario, nome_serv){
	
		$('#id_agd').val(id);
		$('#cliente_agd').val(cliente);		
		$('#servico_agd').val(servico);	
		$('#valor_serv_agd').val(valor_servico);	
		$('#funcionario_agd').val(funcionario).change();	
		$('#titulo_servico').text(nome_serv);	
		$('#descricao_serv_agd').val(nome_serv);	

		$('#modalServico').modal('show');
	}
</script>





