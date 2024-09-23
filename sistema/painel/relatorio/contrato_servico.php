<?php 
include('../../conexao.php');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
$data_hoje = utf8_encode(strftime('%A, %d de %B de %Y', strtotime('today')));

$id = $_GET['id'];

$query = $pdo->query("SELECT * FROM clientes where id = '$id'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){
$id = $resultado[0]['id'];
	$nome = $resultado[0]['nome'];	
	$data_nascimento = $resultado[0]['data_nascimento'];
	$data_cadastro = $resultado[0]['data_cadastro'];	
	$telefone = $resultado[0]['telefone'];
	$endereco = $resultado[0]['endereco'];
	$cpf = $resultado[0]['cpf'];
}

if($cpf == ""){
	echo 'Preencha o CPF do cliente';
	exit();
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Contrato de Serviços</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">


<style>

		@page {
			margin: 15px;

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

	<div class="titulo_cab titulo_img"><u>Contrato de Prestação de Serviços </u></div>	
	<div class="data_img"><?php echo mb_strtoupper($data_hoje) ?></div>

	<img class="imagem" src="<?php echo $url_sistema ?>/sistema/img/logo_rel.jpg" width="150px">

	
	<br><br><br>
	<div class="cabecalho" style="border-bottom: solid 1px #0340a3">
	</div>

	<div class="mx-2" style="font-size:13px">

<p>Pelo presente instrumento, de um lado, a CONTRATADA, <?php echo mb_strtoupper($nome_sistema) ?>, CNPJ: <?php echo $cnpj_sistema ?>, com sede na <?php echo $endereco_sistema ?>, e de outro lado, CONTRATANTE, <?php echo mb_strtoupper($nome) ?> CPF: <?php echo $cpf ?>, partes qualificadas acima, tem entre si, justo e contratado este instrumento e com cláusulas e condições que seguem: 
	</p>


	<p><b>I-OBJETO</b></p> 
 
 <p>
Cláusula, 1°- Por este instrumento, a CONTRATADA, através de profissionais, regularmente habilitados obriga-se a prestar serviços para prótese capilar à CONTRATANTE, através do(s) tratamentos abaixo descriminado(s). </p>
 
 <p>
Parágrafo Primeiro - A CONTRATADA obriga-se a prestar serviços de prótese capilar à CONTRATANTE, com aplicação de métodos e equipamentos, próprios, objetivando o tratamento
CONTRATANTE, nos termos das condições gerais contidas nesse instrumento. 
</p>


<p>
Parágrafo Segundo - Os serviços de prótese capilar contratados compreendem na realização do número de manutenção contratadas nas datas e horários de acordo com agendamento prévio. </p>
 
  <p>
Parágrafo Terceiro - Caso haja necessidade de alteração nos horários e datas em anexo, decorrentes de algum imprevisto que impossibilite a CONTRATANTE de comparecer no horário pré-estabelecido, a mesma deverá avisar a CONTRATADA com no mínimo 12 horas de antecedência, assim será reagendada. </p>
 
<p>
Parágrafo Quarto - Em caso da desmarcação de sessão com menos de 12 horas de antecedência ou não comparecimento, acarretará na perda da manutenção. 
</p>
 
 <p>
Parágrafo Quinto - Ocorrendo a hipótese do parágrafo terceiro, o reagendamento dependerá da disponibilidade da CONTRATADA. 
</p>
 
  <p>
Parágrafo Sexto - Caso a CONTRATANTE não compareça nas datas e horários pré-definidos a CONTRATADA exime-se de qualquer responsabilidade no que diz respeito a resultados esperados dos procedimentos, restando rescindido o presente contrato de pleno direito, sem necessidade de qualquer outra formalidade, sendo devido pagamento os valores contratados a CONTRATADA em sua integralidade como forma de compensação por perdas e danos. 
 </p>



<p><b>II- DO PREÇO</b></p> 

<p>
Cláusula 1°- R$ ____________, valor por extenso ________________________________________________________ foi devidamente convencionado entre as partes. </p>

<p>
Cláusula 2°- O preço livremente ajustado para a realização dos procedimentos descritos na clausula 1°.
</p>

 <p><b>III-CONDIÇÕES GERAIS </b></p> 
 
 <p>

Cláusula 3°- A CONTRATANTE declara ter sido previamente informada sobre todos os benefícios, risco, indicações, contraindicações, principais efeito colaterais e advertências gerais, relacionadas aos procedimentos, ora contratados, sendo que referidas informações foram suficientes esclarecidas, claras e elucidativas. 
</p>

 <p>
Cláusula 4°- A CONTRATANTE declara que todos os termos técnicos foram explicados, bem como todas as dúvidas foram-lhe sanadas. 
</p>

 <p> 
Cláusula 5°- A CONTRATANTE compromete-se a seguir todas as orientações e, havendo necessidade, fazer uso de produtos contidos em sua prescrição domiciliar, respeitando indicados de utilização sem o que os resultados almejados poderão não ser alcançados. 

 <p> 
Cláusula 6°- A CONTRATANTE declara ter plena ciência de que os resultados dos procedimentos estão condicionados a rigorosa fazer as manutenção subscrita, sendo todos estes fatos externos e independente do controle da contratada. 
 
  <p>
Cláusula 7°- O prazo deste instrumento inicia-se na data da primeira colocação da prótese capilares agendada, descrita acima, e seu término dar-se-á de acordo com o indicado no protocolo, caso não haja nenhum erro ou acidente de responsabilidade da CONTRATADA, que resultem em prorrogação do prazo previsto. 
 
  <p>
Cláusula 8°- A CONTRATANTE não poderá rescindir o presente contrato alegando insatisfação com o resultado. 
 
  <p>
Cláusula 9°- Ocorrendo atraso por parte da CONTRATATE para apresentação para manutenção agendada no estabelecimento da CONTRATADA, o tempo das manutenção serão reduzidos, na mesma medida do tempo de atraso da CONTRATANTE. 
 
  <p>
Clausula 10°- A CONTRATANTE declara ter plena ciência de que o serviço contratado neste instrumento não poderá ser trocado por outros serviços oferecidos pela empresa no setor 
 <p><br><br><br>

<div align="center">
_______________________________________________________________________________<br>
Assinatura do Cliente

<br><br><br>
<?php echo mb_strtoupper($cidade_sistema) ?>, <?php echo $data_hoje ?>
</div>
	</div>



	

</body>
</html>