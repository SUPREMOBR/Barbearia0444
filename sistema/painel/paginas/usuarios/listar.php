<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'usuarios01'; // Define o nome da tabela no banco de dados

// Realiza a consulta para buscar todos os registros da tabela e ordena por ID de forma decrescente.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se há registros a serem exibidos.
if ($total_registro > 0) {
	// Inicia a criação da tabela.
	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Email</th> 	
	<th class="esc">Senha</th> 	
	<th class="esc">Nível</th> 	
	<th class="esc">Cadastro</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Itera sobre os registros retornados.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		// Armazena os valores de cada campo em variáveis específicas para facilitar o uso.
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

		// Formata a data para o formato brasileiro
		$dataF = implode('/', array_reverse(explode('-', $data)));

		// Verifica o nível do usuário. Se for "Administrador", a senha será ocultada.
		if ($nivel == 'Administrador') {
			$senhaF = '******'; // Esconde a senha por razões de segurança.
		} else {
			$senhaF = $senha; // Exibe a senha diretamente 
		}

		// Configura o estado do item com base no campo "ativo".
		if ($ativo == 'Sim') {
			$icone = 'fa-check-square'; // Ícone que indica que o item está ativo.
			$titulo_link = 'Desativar Item'; // Tooltip para desativar.
			$acao = 'Não'; // Próxima ação será desativar.
			$classe_linha = '';
		} else {
			$icone = 'fa-square-o'; // Ícone que indica que o item está inativo.
			$titulo_link = 'Ativar Item'; // Tooltip para ativar.
			$acao = 'Sim'; // Próxima ação será ativar.
			$classe_linha = 'text-muted';
		}


		echo <<<HTML
<tr class="{$classe_linha}">
<td>
<img src="img/perfil/{$foto}" width="27px" class="mr-2">
{$nome}
</td>
<td class="esc">{$email}</td>
<td class="esc">{$senhaF}</td>
<td class="esc">{$nivel}</td>
<td class="esc">{$dataF}</td>
<td>
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$email}', '{$telefone}', '{$cpf}', '{$nivel}', '{$endereco}', '{$foto}', '{$atendimento}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>

		<big><a href="#" onclick="mostrar('{$nome}', '{$email}', '{$cpf}', '{$senhaF}', '{$nivel}', '{$dataF}', '{$ativo}', '{$telefone}', '{$endereco}', '{$foto}', '{$atendimento}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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

		<big><a href="#" onclick="permissoes('{$id}', '{$nome}')" title="Definir Permissões"><i class="fa fa-lock " style="color:blue; margin-left:3px"></i></a></big>


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
	function editar(id, nome, email, telefone, cpf, nivel, endereco, foto, atendimento) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#email').val(email);
		$('#telefone').val(telefone);
		$('#cpf').val(cpf);
		$('#cargo').val(nivel).change();
		$('#endereco').val(endereco);
		$('#atendimento').val(atendimento).change();


		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');
		$('#foto').val('');
		$('#target').attr('src', 'img/perfil/' + foto);
	}

	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#email').val('');
		$('#cpf').val('');
		$('#endereco').val('');
		$('#foto').val('');
		$('#target').attr('src', 'img/perfil/sem-foto.jpg');
	}
</script>



<script type="text/javascript">
	function mostrar(nome, email, cpf, senha, nivel, data, ativo, telefone, endereco, foto, atendimento) {

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

		$('#target_mostrar').attr('src', 'img/perfil/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function permissoes(id, nome) {
		$('#id-usuario').val(id);
		$('#nome-usuario').text(nome);
		$('#modalPermissoes').modal('show');
		$('#mensagem-permissao').text('');
		listarPermissoes(id);
	}
</script>