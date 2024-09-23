<?php 
require_once("../../../conexao.php");
$tabela = 'usuarios01';

$query = $pdo->query("SELECT * FROM $tabela where nivel != 'Administrador' ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Email</th> 	
	<th class="esc">CPF</th> 	
	<th class="esc">Cargo</th> 	
	<th class="esc">Cadastro</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;


for($i=0; $i < $total_registro; $i++){
	foreach ($resultado[$i] as $key => $value){}
	$id = $resultado[$i]['id'];
	$nome = $resultado[$i]['nome'];
	$email = $resultado[$i]['email'];
	$cpf = $resultado[$i]['cpf'];
	$senha = $resultado[$i]['senha'];
	$nivel = $resultado[$i]['nivel'];
	$data = $resultado[$i]['data'];
	$ativo = $resultado[$i]['ativo'];
	$telefone = $resultado[$i]['telefone'];
	$endereco = $resultado[$i]['endereco'];
	$foto = $resultado[$i]['foto'];
	$atendimento = $resultado[$i]['atendimento'];
	$tipo_chave = $resultado[$i]['tipo_chave'];
	$chave_pix = $resultado[$i]['chave_pix'];

	$dataFormatada = implode('/', array_reverse(explode('-', $data)));
	
	$senha = '*******';

	if($ativo == 'Sim'){
			$icone = 'fa-check-square';
			$titulo_link = 'Desativar Item';
			$acao = 'Não';
			$classe_linha = '';
		}else{
			$icone = 'fa-square-o';
			$titulo_link = 'Ativar Item';
			$acao = 'Sim';
			$classe_linha = 'text-muted';
		}

		$whats = '55'.preg_replace('/[ ()-]+/' , '' , $telefone);
		
		echo <<<HTML
		<tr class="{$classe_linha}">
		<td>
		<img src="img/perfil/{$foto}" width="27px" class="mr-2">
		{$nome}
		</td>
		<td class="esc">{$email}</td>
		<td class="esc">{$cpf}</td>
		<td class="esc">{$nivel}</td>
		<td class="esc">{$dataFormatada}</td>
		<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$email}', '{$telefone}', '{$cpf}', '{$nivel}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$email}', '{$cpf}', '{$senha}', '{$nivel}', '{$dataFormatada}', '{$ativo}', '{$telefone}', '{$endereco}', '{$foto}', '{$atendimento}', '{$tipo_chave}', '{$chave_pix}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>



		<big><a href="#" onclick="ativar('{$id}', '{$acao}')" title="{$titulo_link}"><i class="fa {$icone} text-success"></i></a></big>
        
		<a href="#" onclick="horarios('{$id}', '{$nome}')" title="Lançar Horários}"><i class="fa fa-calendar text-secondary"></i></a>

		<a href="#" onclick="dias('{$id}', '{$nome}')" title="Lançar Dias"><i class="fa fa-calendar text-danger"></i></a>

		<big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp"><i class="fa fa-whatsapp verde"></i></a></big>

		<a href="#" onclick="servico('{$id}', '{$nome}')" title="Definir Serviços"><i class="fa fa-briefcase" style="color:#a60f4b"></i></a>


		</td>
</tr>
HTML;

}

echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>
HTML;


}else{
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready( function () {
    $('#tabela').DataTable({
    		"ordering": false,
			"stateSave": true
    	});
    $('#tabela_filter label input').focus();
} );
</script>


<script type="text/javascript">
	function editar(id, nome, email, telefone, cpf, nivel, endereco, foto, atendimento, tipo_chave, chave_pix){
		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#cpf').val(cpf);
		$('#cargo').val(nivel).change();
		$('#endereco').val(endereco);
        $('#atendimento').val(atendimento).change();
		$('#chave_pix').val(chave_pix);
		$('#tipo_chave').val(tipo_chave).change();
		
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');
		$('#foto').val('');

		$('#target').attr('src','img/perfil/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#email').val('');
		$('#cpf').val('');
		$('#endereco').val('');
		$('#foto').val('');
		$('#chave_pix').val('');
		$('#target').attr('src','img/perfil/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, email, cpf, senha, nivel, data, ativo, telefone, endereco, foto, atendimento, tipo_chave, chave_pix){

		$('#nome_dados').text(nome);
		$('#email_dados').text(email);
		$('#cpf_dados').text(cpf);
		$('#senha_dados').text(senha);
		$('#nivel_dados').text(nivel);
		$('#data_dados').text(data);
		$('#ativo_dados').text(ativo);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#atendimento_dados').text(atendimento);
		$('#tipo_chave_dados').text(tipo_chave);
		$('#chave_pix_dados').text(chave_pix);

		$('#target_mostrar').attr('src','img/perfil/' + foto);

		$('#modalDados').modal('show');
	}
</script>


<script type="text/javascript">
	function horarios(id, nome){

		$('#nome_horarios').text(nome);		
		$('#id_horarios').val(id);		

		$('#modalHorarios').modal('show');
		listarHorarios(id);
	}
</script>


<script type="text/javascript">
	function dias(id, nome){

		$('#nome_dias').text(nome);		
		$('#id_dias').val(id);		

		$('#modalDias').modal('show');
		listarDias(id);
	}
</script>


<script type="text/javascript">
	function servico(id, nome){

		$('#nome_servico').text(nome);		
		$('#id_servico').val(id);		

		$('#modalServicos').modal('show');
		listarServicos(id);
	}
</script>