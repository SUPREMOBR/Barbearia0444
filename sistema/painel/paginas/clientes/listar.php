<?php
require_once("../../../conexao.php"); // Conecta ao banco de dados.
$tabela = 'clientes'; // Define o nome da tabela no banco de dado
$data_atual = date('Y-m-d'); // Define a data atual

// Define o parâmetro de busca, permitindo busca parcial com %.
$busca = '%' . @$_POST['busca'] . '%';

// Verifica se a página atual está definida; se não, define como 0.
if (@$_POST['pagina'] == "") {
	@$_POST['pagina'] = 0;
}

$pagina = intval(@$_POST['pagina']); // Converte a página atual em inteiro.
$limite = $pagina * $itens_pag;  // Define o limite para paginação.

// Consulta ao banco para buscar clientes que contenham o termo de busca no nome, telefone ou CPF.
$query = $pdo->query("SELECT * FROM $tabela ORDER BY id DESC LIMIT $limite, $itens_pag");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado); // Conta o número de registros retornados.
// Se houver registros, começa a exibir a tabela.
if ($total_registro > 0) {
	// Exibe a tabela com os usuários e suas informações
	echo <<<HTML
	<small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th>Nome</th>	
	<th class="esc">Telefone</th> 
	<th class="esc">CPF</th> 	
	<th class="esc">Cadastro</th> 	
	<th class="esc">Nascimento</th> 
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;
	// Loop para exibir cada cliente retornado na busca.
	for ($i = 0; $i < $total_registro; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
		$id = $resultado[$i]['id'];
		$nome = $resultado[$i]['nome'];
		$data_nascimento = $resultado[$i]['data_nascimento'];
		$data_cadastro = $resultado[$i]['data_cadastro'];
		$telefone = $resultado[$i]['telefone'];
		$endereco = $resultado[$i]['endereco'];
		$ultimo_servico = $resultado[$i]['ultimo_servico'];
		$cpf = $resultado[$i]['cpf'];

		$data_cadastroF = implode('/', array_reverse(@explode('-', $data_cadastro)));
		$data_nascimentoF = implode('/', array_reverse(@explode('-', $data_nascimento)));

		if ($data_nascimentoF == '00/00/0000') { // Verifica se a data de nascimento é inválida.
			$data_nascimentoF = 'Sem Lançamento';
		}

		// Preparação do número de telefone para ser usado em um link do WhatsApp.
		$whats = '55' . preg_replace('/[ ()-]+/', '', $telefone);

		// Consulta ao banco para buscar o nome do último serviço associado ao cliente.
		$query2 = $pdo->query("SELECT * FROM servicos where id = '$ultimo_servico'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$nome_servico = $resultado2[0]['nome'];
		} else {
			$nome_servico = 'Nenhum!';
		}

		// Consulta ao banco para buscar o último pagamento do cliente
		$query2 = $pdo->query("SELECT * FROM receber where pessoa = '$id' order by id desc limit 1");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if (@count($resultado2) > 0) {
			$obs_servico = $resultado2[0]['obs'];
			$valor_servico = $resultado2[0]['valor'];
			$data_servico = $resultado2[0]['data_lanc'];
			$valor_servico = number_format($valor_servico, 2, ',', '.'); // Formata o valor do serviço.
			$data_servico = implode('/', array_reverse(@explode('-', $data_servico)));  // Formata a data do serviço.
		} else {
			$obs_servico = '';
			$valor_servico = '';
			$data_servico = '';
		}
		// Consulta ao banco para contar o total de registros compatíveis com o termo de busca.
		$query2 = $pdo->query("SELECT * FROM $tabela where nome LIKE '$busca' or telefone LIKE '$busca' or cpf LIKE '$busca'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);

		// Calcula o número total de páginas com base no número de registros e no limite por página.
		$num_paginas = ceil($total_registro2 / $itens_pag);


		echo <<<HTML
<tr class="">
<td>{$nome}</td>
<td class="esc">{$telefone}</td>
<td class="esc">{$cpf}</td>
<td class="esc">{$data_cadastroF}</td>
<td class="esc">{$data_nascimentoF}</td>
<td>
	    <!-- Ícone para editar dados do cliente -->
		<big><a href="#" onclick="editar('{$id}','{$nome}', '{$telefone}', '{$endereco}', '{$data_nascimento}', '{$cpf}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>
        <!-- Ícone para visualizar mais detalhes do cliente -->
		<big><a href="#" onclick="mostrar('{$id}','{$nome}', '{$telefone}', '{$data_cadastroF}', '{$data_nascimentoF}', '{$endereco}', '{$nome_servico}', '{$obs_servico}', '{$valor_servico}', '{$data_servico}')" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>

        <!-- Ícone de exclusão com menu suspenso para confirmar a ação -->
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

        <!-- Link para abrir o WhatsApp com o número do cliente pré-preenchido -->
		<big><a href="http://api.whatsapp.com/send?1=pt_BR&phone=$whats&text=" target="_blank" title="Abrir Whatsapp"><i class="fa fa-whatsapp verde"></i></a></big>

        <!-- Ícone para abrir o contrato de serviço do cliente -->
		<big><a class="" href="#" onclick="contrato('{$id}','{$nome}')" title="Contrato de Serviço"><i class="fa fa-file-pdf-o text-primary"></i></a></big>

		<!-- Ícone para gerar PDF com os últimos serviços do cliente -->
		<big><a class="" href="rel/rel_servicos_clientes_class.php?id={$id}" target="_blank" title="Últimos Serviços"><i class="fa fa-file-pdf-o text-danger"></i></a></big>

		</td>
</tr>
HTML;
	}

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>
</small>



<hr>
   <div class="row" align="center">
     <nav aria-label="Page navigation example">
          <ul class="pagination">
			<!-- Botão para página anterior -->
            <li class="page-item">
              <a onclick="listarClientes(0)" class="paginador" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
                <span class="sr-only">Previous</span>
              </a>
            </li>
HTML;
	// Paginação com controle para exibir páginas ao redor da página atual
	for ($i = 0; $i < $num_paginas; $i++) {
		$estilo = "";
		// Mostra páginas próximas da atual
		if ($pagina >= ($i - 2) and $pagina <= ($i + 2)) {
			if ($pagina == $i)
				$estilo = "active"; // Destaca a página atual

			$pag = $i + 1;
			$ultimo_registro = $num_paginas - 1;

			echo <<<HTML

             <li class="page-item {$estilo}">
              <a onclick="listarClientes({$i})" class="paginador " href="#" >{$pag}
                
              </a></li>
HTML;
		}
	}
	// Botão para a próxima página
	echo <<<HTML

            <li class="page-item">
              <a onclick="listarClientes({$ultimo_registro})" class="paginador" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
                <span class="sr-only">Next</span>
              </a>
            </li>
          </ul>
        </nav>
      </div> 

HTML;
} else {
	// Mensagem caso não haja registros
	echo '<small>Não possui nenhum registro Cadastrado!</small>';
}

?>
<!-- Configuração do DataTable para a tabela -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#tabela').DataTable({
			"ordering": false,
			"stateSave": true
		});
		$('#tabela_filter label input').focus();
	});
</script>

<!-- Função para abrir o modal de edição com os dados do cliente -->
<script type="text/javascript">
	function editar(id, nome, telefone, endereco, data_nascimento, cpf) {
		$('#id').val(id);
		$('#nome').val(nome);
		$('#telefone').val(telefone);
		$('#endereco').val(endereco);
		$('#data_nascimento').val(data_nascimento);
		$('#cpf').val(cpf);


		$('#titulo_inserir').text('Editar Registro');
		$('#modalForm').modal('show');

	}

	function limparCampos() {
		$('#id').val('');
		$('#nome').val('');
		$('#telefone').val('');
		$('#endereco').val('');
		$('#data_nascimento').val('');
		$('#cpf').val('');
	}
</script>

<!-- Função para mostrar detalhes do cliente no modal -->
<script type="text/javascript">
	function mostrar(id, nome, telefone, data_cadastro, data_nascimento, endereco, servico, obs, valor, data) {

		$('#nome_dados').text(nome);
		$('#data_cadastro_dados').text(data_cadastro);
		$('#data_nascimento_dados').text(data_nascimento);
		$('#telefone_dados').text(telefone);
		$('#endereco_dados').text(endereco);
		$('#servico_dados').text(servico);

		$('#obs_dados_tab').text(obs);
		$('#servico_dados_tab').text(servico);
		$('#data_dados_tab').text(data);
		$('#valor_dados_tab').text(valor);

		$('#modalDados').modal('show');
		listarDebitos(id)
	}
</script>

<!-- Função para abrir o modal do contrato -->
<script type="text/javascript">
	function contrato(id, nome) {
		$('#titulo_contrato').text(nome);
		$('#id_contrato').val(id);
		$('#modalContrato').modal('show');
		listarTextoContrato(id);

	}
</script>