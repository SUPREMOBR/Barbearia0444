<?php
require_once("../../../conexao.php");
$tabela = 'servicos';

$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

	if ($tipo_comissao == 'Porcentagem') {
		$tipo_comissao = '%';
	}

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Categoria</th> 	
	<th class="esc">Valor</th> 	
	<th class="esc">Comissão <small>({$tipo_comissao})</small></th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$ativo = $resultado[$i]['ativo'];
		$foto = $resultado[$i]['foto'];
		$valor = $resultado[$i]['valor'];
		$categoria = $resultado[$i]['categoria'];
		$comissao = $resultado[$i]['comissao'];

		$valorFormatado = number_format($valor, 2, ',', '.');

		if ($ativo == 'Sim') {
			$icone = 'fa-check-square';
			$titulo_link = 'Desativar Item';
			$acao = 'Não';
			$classe_linha = '';
		} else {
			$icone = 'fa-square-o';
			$titulo_link = 'Ativar Item';
			$acao = 'Sim';
			$classe_linha = 'text-muted';
		}

		$query2 = $pdo->query("SELECT * FROM categoria_servicos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if ($total_registro2 > 0) {
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			$nome_categoria = 'Sem Referência!';
		}


		if ($tipo_comissao == '%') {
			$comissaoFormatada = number_format($comissao, 0, ',', '.') . '%';
		} else {
			$comissaoFormatada = 'R$ ' . number_format($comissao, 2, ',', '.');
		}



		echo <<<HTML
<tr class="{$classe_linha}">
<td>
<img src="img/servicos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$nome_categoria}</td>
<td class="esc">R$ {$valorFormatado}</td>
<td class="esc">{$comissaoFormatada}</td>
<td>
        <big><a href="#" onclick="editar('{$id}','{$nome}', '{$valor}', '{$categoria}', '{$foto}', '{$comissao}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$valorFormatado}', '{$nome_categoria}', '{$ativo}', '{$foto}', '{$comissaoFormatada}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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
} else {
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>


<script type="text/javascript">
	function editar(id, nome, valor, categoria, foto, comissao) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor').val(valor);
		$('#categoria').val(categoria).change();
		$('#comissao').val(comissao);

		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');
		$('#foto').val('');

		$('#target').attr('src', 'img/servicos/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#valor').val('');
		$('#comissao').val('');
		$('#foto').val('');
		$('#target').attr('src', 'img/servicos/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, valor, categoria, ativo, foto, comissao) {

		$('#nome_dados').text(nome);
		$('#valor_dados').text(valor);
		$('#categoria_dados').text(categoria);
		$('#ativo_dados').text(ativo);
		$('#comissao_dados').text(comissao);

		$('#target_mostrar').attr('src', 'img/servicos/' + foto);

		$('#modalDados').modal('show');
	}
</script>