<?php 
include('../../conexao.php');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$id = $_GET['id'];

$query2 = $pdo->query("SELECT * FROM clientes where id = '$id'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
$nome_cliente = @$resultado2[0]['nome'];

?>

<!DOCTYPE html>
<html>
<head>
	<title>Serviços do Cliente</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


<style>

		@page {
			margin: 0px;

		}

		body{
			margin-top:5px;
			font-family:Times, "Times New Roman", Georgia, serif;
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
			font-family:Times, "Times New Roman", Georgia, serif;
		}

		.titulo_cab{
			color:#0340a3;
			font-size:20px;
		}

		
		
		.titulo{
			margin:0;
			font-size:28px;
			font-family:Arial, Helvetica, sans-serif;
			color:#6e6d6d;

		}

		.subtitulo{
			margin:0;
			font-size:12px;
			font-family:Arial, Helvetica, sans-serif;
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

	<div class="titulo_cab titulo_img"><u>Últimos Serviços Cliente: <?php echo $nome_cliente ?></u></div>	
	<div class="data_img"><?php echo mb_strtoupper($data_hoje) ?></div>

	<img class="imagem" src="<?php echo $url_sistema ?>/sistema/img/logo_rel.jpg" width="150px">

	
	<br><br><br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>

	<div class="mx-2" >

		<?php 
		$total_entradas = 0;
		$query = $pdo->query("SELECT * FROM receber where tipo = 'Serviço' and pago = 'Sim' and pessoa = '$id' ORDER BY data_pagamento desc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if($total_registro > 0){
		 ?>

	<table class="table table-striped borda" cellpadding="6">
  <thead>
    <tr align="center">
      <th scope="col">Descrição</th>      
      <th scope="col">Valor</th>
      <th scope="col">Data PGTO</th>
      <th scope="col">Recebido Por</th>     
      <th scope="col">Forma Pagamento</th>
     
    </tr>
  </thead>
  <tbody>

  	<?php 
  	for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];	
	$descricao = $resultado[$i]['descricao'];
	$tipo = $resultado[$i]['tipo'];
	$valor = $resultado[$i]['valor'];
	$data_lancamento = $resultado[$i]['data_lancamento'];
	$data_pagamento = $resultado[$i]['data_pagamento'];
	$data_vencimento = $resultado[$i]['data_vencimento'];
	$usuario_lancou = $resultado[$i]['usuario_lancou'];
	$usuario_baixa = $resultado[$i]['usuario_baixa'];
	$foto = $resultado[$i]['foto'];
	$pessoa = $resultado[$i]['pessoa'];
	$pago = $resultado[$i]['pago'];
	$servico = $resultado[$i]['servico'];
	$pgto = $resultado[$i]['pgto'];

	$total_entradas += $valor;
	
	$valorFormatado = number_format($valor, 2, ',', '.');
	$total_entradasFormatado = number_format($total_entradas, 2, ',', '.');
	$data_lancamentoFormatado = implode('/', array_reverse(explode('-', $data_lancamento)));
	$data_pagamentoFormatado = implode('/', array_reverse(explode('-', $data_pagamento)));
	$data_vencimentoFormatado = implode('/', array_reverse(explode('-', $data_vencimaneto)));
	

		$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa = $resultado2[0]['telefone'];
			$classe_whats = '';
		}else{
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = '';
			$classe_whats = 'ocultar';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		}else{
			$nome_usuario_pagamento = 'Nenhum!';
		}



		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_usuario_lancou = $resultado2[0]['nome'];
		}else{
			$nome_usuario_lancou = 'Sem Referência!';
		}


		$query2 = $pdo->query("SELECT * FROM servicos where id = '$servico'");
	$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	$total_registro2 = @count($resultado2);
	if($total_registro2 > 0){
		$nome_servico = $resultado2[0]['nome'];
	}else{
		$nome_servico = '';
	}

		
  	 ?>

    <tr align="center" class="">
      <td align="left">
<?php echo $descricao ?>
</td>

<td class="esc">R$ <?php echo $valorFormatado ?></td>
<td class="esc"><?php echo $data_pagamentoFormatado ?></td>
<td class="esc"><?php echo $nome_usuario_pagamento ?></td>
<td class="esc"><?php echo $pagamento ?></td>

    </tr>

<?php } ?>
  
  </tbody>
</table>

<?php }else{
echo 'Não possuem registros para serem exibidos!';
exit();
} ?>

	</div>



	<div class="col-md-12 p-2">
		<div class="" align="right" style="margin-right: 20px">

			<span class=""> <small><small><small><small>TOTAL DE RECEBIMENTOS</small> : <?php echo @$total_registro ?></small></small></small>  </span>

		<span class="text-success"> <small><small><small><small>TOTAL R$</small> : <?php echo @$total_entradasFormatado ?></small></small></small>  </span>


				
		</div>
	</div>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>



	<div class="footer"  align="center">
		<span style="font-size:10px"><?php echo $nome_sistema ?> Whatsapp: <?php echo $whatsapp_sistema ?></span> 
	</div>

</body>
</html>