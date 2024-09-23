<?php 
require_once("../sistema/conexao.php");
@session_start();
$usuario = @$_SESSION['id'];

$funcionario = @$_POST['funcionario'];
$data = @$_POST['data'];
$hora_rec = @$_POST['hora'];

$hoje = date('Y-m-d');
$hora_atual = date('H:i:s');

if(strtotime($data) < strtotime($hoje)){
	echo '000';
	exit();
}


if($funcionario == ""){
	
	exit();
}


$diasemana = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado");
$diasemana_numero = date('w', strtotime($data));
$dia_procurado = $diasemana[$diasemana_numero];

//percorrer os dias da semana que ele trabalha
$query = $pdo->query("SELECT * FROM dias where funcionario = '$funcionario' and dia = '$dia_procurado'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($resultado) == 0){
		echo 'Este Funcionário não trabalha neste Dia!';
	exit();
}

?>
<div class="row">

	<?php 
	$query = $pdo->query("SELECT * FROM horarios where funcionario = '$funcionario' ORDER BY horario asc");
	$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
	$total_registro = @count($resultado);
	if($total_registro > 0){
		for($i=0; $i < $total_registro; $i++){
			foreach ($resultado[$i] as $key => $value){}
				$hora = $resultado[$i]['horario'];
				$horaFormatada = date("H:i", strtotime($hora));


				//validar horario
$query2 = $pdo->query("SELECT * FROM agendamentos where data = '$data' and hora = '$hora' and funcionario = '$funcionario'");
$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$total_registro2 = @count($resultado2);
if($total_registro2 == 0 || strtotime($hora_rec) == strtotime($hora)){
	$hora_agendada = '';
	$texto_hora = '';

	if(strtotime($hora_rec) == strtotime($hora)){
		$checado = 'checked';
	}else{
		$checado = '';
	}

	if(strtotime($hora) < strtotime($hora_atual) and strtotime($data) == strtotime($hoje)){
		$esconder = 'disabled';
	}else{
		$esconder = '';
	}


	if(strtotime($dataHora) != strtotime($data) and $dataHora != "" and $dataHora != "null"){
		continue;
	}	

				?>

				<div class="col-3">
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="hora" value="<?php echo $hora ?>" <?php echo $hora_agendada ?> style="width:17px; height: 17px" required <?php echo $checado ?> <?php echo $esconder ?>>
					  <label class="form-check-label <?php echo $texto_hora ?>" for="flexRadioDefault1">
					    <?php echo $horaFormatada ?>
					  </label>
					</div>
				</div> 

				<?php 
				
		}

	}
	}
	?>


</div>