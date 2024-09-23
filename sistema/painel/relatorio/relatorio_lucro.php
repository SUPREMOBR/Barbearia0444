<?php 
include('../../conexao.php');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$dataInicial = $_GET['dataInicial'];
$dataFinal = $_GET['dataFinal'];

$dataInicialFormatada = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalFormatada = implode('/', array_reverse(explode('-', $dataFinal)));

if($dataInicial == $dataFinal){
	$texto_apuracao = 'APURADO EM '.$dataInicialFormatada;
}else if($dataInicial == '1980-01-01'){
	$texto_apuracao = 'APURADO EM TODO O PERÍODO';
}else{
	$texto_apuracao = 'APURAÇÃO DE '.$dataInicialFormatada. ' ATÉ '.$dataFinalFormatada;
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Demonstrativo de Lucro</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


<style>

		@page {
			margin: 0px;

		}

		body{
			margin-top:5px;
			font-family: TimesNewRoman, Geneva, sans-serif; 
		}		

			.footer {
				margin-top:20px;
				width:100%;
				background-color: #ebebeb;
				padding:5px;
				position:absolute;
				bottom:0;
			}

		

		.cabecalho {    
			padding:10px;
			margin-bottom:30px;
			width:100%;
			font-family: TimesNewRoman, Geneva, sans-serif; 
		}

		.titulo_cab{
			color:#0340a3;
			font-size:20px;
		}

		
		
		.titulo{
			margin:0;
			font-size:28px;
			font-family: TimesNewRoman, Geneva, sans-serif; 
			color:#6e6d6d;

		}

		.subtitulo{
			margin:0;
			font-size:12px;
			font-family: TimesNewRoman, Geneva, sans-serif; 
			color:#6e6d6d;
		}



		hr{
			margin:8px;
			padding:0px;
		}


		
		.area-cab{
			
			display:block;
			width:100%;
			height:10px;

		}

		
		.coluna{
			margin: 0px;
			float:left;
			height:30px;
		}

		.area-tab{
			
			display:block;
			width:100%;
			height:30px;

		}


		.imagem {
			width: 150px;
			position:absolute;
			right:20px;
			top:10px;
		}

		.titulo_img {
			position: absolute;
			margin-top: 10px;
			margin-left: 10px;

		}

		.data_img {
			position: absolute;
			margin-top: 40px;
			margin-left: 10px;
			border-bottom:1px solid #000;
			font-size: 10px;
		}

		.endereco {
			position: absolute;
			margin-top: 50px;
			margin-left: 10px;
			border-bottom:1px solid #000;
			font-size: 10px;
		}

		.verde{
			color:green;
		}



		table.borda {
    		border-collapse: collapse; /* CSS2 */
    		background: #FFF;
    		font-size:12px;
    		vertical-align:middle;
		}
 
		table.borda td {
		    border: 1px solid #dbdbdb;
		}
		 
		table.borda th {
		    border: 1px solid #dbdbdb;
		    background: #ededed;
		    font-size:13px;
		}
				

	</style>


</head>
<body>	

	<div class="titulo_cab titulo_img"><u>Demonstrativo de Lucro </u></div>	
	<div class="data_img"><?php echo mb_strtoupper($data_hoje) ?></div>

	<img class="imagem" src="<?php echo $url_sistema ?>/sistema/img/logo_rel.jpg" width="150px">

	
	<br><br><br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>

	<div class="mx-2" >

		<section class="area-cab">
			
			<div>
				<small><small><small><u><?php echo $texto_apuracao ?></u></small></small></small>
			</div>

	
			</section>

			<br>

		<?php 
		$total_servicos = 0;
		$total_vendas = 0;
		$total_receber = 0;
		$total_pagar = 0;
		$total_compras = 0;
		$total_comissoes = 0;

		$total_entradas = 0;
		$total_saidas = 0;

		$saldo_total = 0;
		
		 ?>

	<table class="table table-striped borda" cellpadding="6">
  <thead>
    <tr align="center">
      <th scope="col">Serviços</th>
      <th scope="col">Vendas</th>
      <th scope="col">Recebimentos</th>
      <th scope="col">Despesas</th>
      <th scope="col">Compras</th>
      <th scope="col">Comissões</th>
    </tr>
  </thead>
  <tbody>

  	<?php
  	//totalizar os serviços 
  	$query = $pdo->query("SELECT * FROM receber where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Serviço' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_servicos += $resultado[$i]['valor'];

	}



	//totalizar os vendas 
  	$query = $pdo->query("SELECT * FROM receber where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Venda' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_vendas += $resultado[$i]['valor'];

	}



	//totalizar contas recebidas
  	$query = $pdo->query("SELECT * FROM receber where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Conta' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_receber += $resultado[$i]['valor'];

	}





	//totalizar contas despesas
  	$query = $pdo->query("SELECT * FROM pagar where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Conta' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_pagar += $resultado[$i]['valor'];

	}




	//totalizar contas compras
  	$query = $pdo->query("SELECT * FROM pagar where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Compra' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_compras += $resultado[$i]['valor'];

	}





	//totalizar contas despesas
  	$query = $pdo->query("SELECT * FROM pagar where data_pagamento >= '$dataInicial' and data_pagamento <= '$dataFinal' and tipo = 'Comissão' and pago = 'Sim' ORDER BY data_pagamento asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);	
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}

		$total_comissoes += $resultado[$i]['valor'];

	}
		

	$total_servicosFormatado = number_format($total_servicos, 2, ',', '.');	
	$total_vendasFormatada = number_format($total_vendas, 2, ',', '.');
	$total_receberFormatado = number_format($total_receber, 2, ',', '.');	
	$total_pagarFormatada = number_format($total_pagar, 2, ',', '.');	
	$total_comprasFormatada = number_format($total_compras, 2, ',', '.');	
	$total_comissoesFormatado = number_format($total_comissoes, 2, ',', '.');

	$total_entradas = $total_servicos + $total_vendas + $total_receber;	
	$total_saidas = $total_pagar + $total_compras + $total_comissoes;

	$total_entradasFormatada = number_format($total_entradas, 2, ',', '.');	
	$total_saidasFormatada = number_format($total_saidas, 2, ',', '.');	

	$saldo_total = $total_entradas - $total_saidas;

	$saldo_totalFormatado = number_format($saldo_total, 2, ',', '.');

	if($saldo_total < 0){
		$classe_saldo = 'text-danger';
		$classe_img = 'negativo.jpg';

	}else{
		$classe_saldo = 'text-success';
		$classe_img = 'positivo.jpg';
	}

  	 ?>

    <tr align="center" class="">

<td class="text-success">R$ <?php echo $total_servicosFormatado ?></td>
<td class="text-success">R$ <?php echo $total_vendasFormatada ?></td>
<td class="text-success">R$ <?php echo $total_receberFormatado ?></td>
<td class="text-danger">R$ <?php echo $total_pagarFormatada ?></td>
<td class="text-danger">R$ <?php echo $total_comprasFormatada ?></td>
<td class="text-danger">R$ <?php echo $total_comissoesFormatado ?></td>

    </tr>


 <tr align="center" class="">
<td style="background: #e6ffe8" colspan="3" scope="col">Total de Entradas / Ganhos</td>
<td style="background: #ffe7e6" colspan="3" scope="col">Total de Saídas / Despesas</td>
</tr>

 <tr align="center" class="">
<td colspan="3" class="text-success"> R$ <?php echo $total_entradasFormatada ?></td>
<td colspan="3" class="text-danger"> R$ <?php echo $total_saidasFormatada ?></td>
</tr>
  
  </tbody>
</table>
	</div>



	<div class="col-md-12 p-2">
		<div class="" align="center" style="margin-right: 20px">

			<img src="<?php echo $url_sistema ?>/sistema/img/<?php echo $classe_img ?>" width="100px">
			<span class="<?php echo $classe_saldo ?>">R$ <?php echo $saldo_totalFormatado ?></span>

				
		</div>
	</div>
	


	<div class="footer"  align="center">
		<span style="font-size:10px"><?php echo $nome_sistema ?> Whatsapp: <?php echo $whatsapp_sistema ?></span> 
	</div>

</body>
</html>