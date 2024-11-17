<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'servicos'; // Define o nome da tabela no banco de dados

//consulta para buscar todos os registros da tabela 'servicos', ordenados por ID de forma decrescente
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);

// Verifica se há registros retornados
if ($total_registro > 0) {
	// Verifica se o tipo de comissão é 'Porcentagem', e ajusta o símbolo para '%'
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
	<th class="esc">Tempo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para percorrer todos os registros retornados
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Armazena os dados de cada serviço
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$ativo = $resultado[$i]['ativo'];
		$categoria = $resultado[$i]['categoria'];
		$valor = $resultado[$i]['valor'];
		$foto = $resultado[$i]['foto'];
		$comissao = $resultado[$i]['comissao'];
		$tempo = $resultado[$i]['tempo'];

		// Formata o valor com duas casas decimais e vírgula como separador decimal
		$valorF = number_format($valor, 2, ',', '.');

		// Verifica se o serviço está ativo ou não e ajusta os ícones, título e classe da linha
		if ($ativo == 'Sim') {
			$icone = 'fa-check-square'; // Ícone para ativo
			$titulo_link = 'Desativar Item'; // Título do link para desativar
			$acao = 'Não'; // Define a ação para desativar
			$classe_linha = '';
		} else {
			$icone = 'fa-square-o'; // Ícone para inativo
			$titulo_link = 'Ativar Item'; // Título do link para ativar
			$acao = 'Sim'; // Define a ação para ativar
			$classe_linha = 'text-muted';
		}

		//consulta para obter o nome da categoria do serviço
		$query2 = $pdo->query("SELECT * FROM categoria_servicos where id = '$categoria'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		// Verifica se a categoria foi encontrada
		if ($total_registro2 > 0) {
			// Se encontrada, armazena o nome da categoria
			$nome_categoria = $resultado2[0]['nome'];
		} else {
			// Se não encontrada, define o nome da categoria como 'Sem Referência!'
			$nome_categoria = 'Sem Referência!';
		}

		// Formata a comissão com o símbolo '%' ou com o prefixo 'R$' dependendo do tipo de comissão
		if ($tipo_comissao == '%') {
			// Se for porcentagem, formata como um valor com '%' no final
			$comissaoF = number_format($comissao, 0, ',', '.') . '%';
		} else {
			// Se for valor em R$, formata com o prefixo 'R$'
			$comissaoF = 'R$ ' . number_format($comissao, 2, ',', '.');
		}


		echo <<<HTML
<tr class="{$classe_linha}">
<td>
<img src="img/servicos/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$nome_categoria}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$comissaoF}</td>
<td class="esc">{$tempo} Minutos</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$valor}', '{$categoria}', '{$foto}', '{$comissao}', '{$tempo}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$valorF}', '{$nome_categoria}', '{$ativo}', '{$foto}', '{$comissaoF}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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
	function editar(id, nome, valor, categoria, foto, comissao, tempo) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#valor').val(valor);
		$('#categoria').val(categoria).change();
		$('#comissao').val(comissao);
		$('#tempo').val(tempo);

		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
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
		$('#tempo').val('');
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