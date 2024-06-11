<?php 
@session_start();
require_once("verificar.php");
require_once("../conexao.php");

$pag = 'acessos';

//verificar se ele tem a permissão de estar nessa página
if(@$acessos == 'ocultar'){
    echo "<script>window.location='../index.php'</script>";
    exit();
}

?>

<div class="">      
	<a class="btn btn-success" onclick="inserir()" class="btn btn-success btn-flat btn-pri"><i class="fa fa-plus" aria-hidden="true"></i> Novo Acesso</a>
</div>

<div class="bs-example widget-shadow" style="padding:15px" id="listar">
	
</div>




<!-- Modal Inserir-->
<div class="modal fade" id="modalform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
					<span aria-hidden="true" >&times;</span>
				</button>
			</div>
			<form id="form">
			<div class="modal-body">

					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="exampleInputEmail1">Nome</label>
								<input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>    
							</div> 	
						</div>
						<div class="col-md-4">

							<div class="form-group">
								<label for="exampleInputEmail1">Chave</label>
								<input type="text" class="form-control" id="chave" name="chave" placeholder="chave" >    
							</div> 	
						</div>
					</div>

					<div class="col-md-4">
							
							<div class="form-group">
								<label for="exampleInputEmail1">Grupo</label>
								<select class="form-control sel2" id="grupo" name="grupo" style="width:100%;" > 
                                     
								    <option value="0">Nenhum Grupo</option>

									<?php 
									$query = $pdo->query("SELECT * FROM grupo_acessos ORDER BY id desc");
									$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
									$total_registro = @count($resultado);
									if($total_registro > 0){
										for($i=0; $i < $total_registro; $i++){
										foreach ($resultado[$i] as $key => $value){}
										echo '<option value="'.$resultado[$i]['id'].'">'.$resultado[$i]['nome'].'</option>';
										}
									}
									 ?>
									

								</select>   
							</div> 	
						</div>

					
						<input type="hidden" name="id" id="id">

					<br>
					<small><div id="mensagem" align="center"></div></small>
				</div>

				<div class="modal-footer">      
					<button type="submit" class="btn btn-success">Salvar</button>
				</div>
			</form>

			
		</div>
	</div>
</div>


<script type="text/javascript">var pag = "<?=$pag?>"</script>
<script src="js/ajax.js"></script>


<script type="text/javascript">
	$(document).ready(function() {
    $('.sel2').select2({
    	dropdownParent: $('#modalform')
    });
});
</script>

