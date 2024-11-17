<?php
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

$id = @$_POST['id'];

if ($id == "") {
	$id = 0;
}

@session_start();
$usuario_logado = @$_SESSION['id'];


$total_servicos = 0;

$query = $pdo->query("SELECT * FROM $tabela where tipo = 'Serviço' and comanda = '$id' and func_comanda = '$usuario_logado' order by id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($resultado > 0) {

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela_servicos">
	<thead> 
	<tr> 
	<th>Serviço</th>	
	<th class="esc">Valor</th> 
	<th class="esc">Profissional</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	for ($i = 0; $i < $resultado; $i++) {
		foreach ($resultado[$i] as $key => $value) {
		}
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
		$funcionario = $resultado[$i]['funcionario'];
		$obs = $resultado[$i]['obs'];
		$comanda = $resultado[$i]['comanda'];

		$pago = $resultado[$i]['pago'];
		$servico = $resultado[$i]['servico'];

		$valorF = number_format($valor, 2, ',', '.');
		$data_lancamentoF = implode('/', array_reverse(explode('-', $data_lancamento)));
		$data_pagamentoF = implode('/', array_reverse(explode('-', $data_pagamento)));
		$data_vencimentoF = implode('/', array_reverse(explode('-', $data_vencimento)));


		$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$resultado2 = @count($resultado2);
		if ($resultado2 > 0) {
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa = $resultado2[0]['telefone'];
		} else {
			$nome_pessoa = 'Nenhum!';
			$telefone_pessoa = 'Nenhum';
		}


		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_baixa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$resultado2 = @count($resultado2);
		if ($resultado2 > 0) {
			$nome_usuario_pagamento = $resultado2[0]['nome'];
		} else {
			$nome_usuario_pagamento = 'Nenhum!';
		}



		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$usuario_lancou'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$resultado2 = @count($resultado2);
		if ($resultado2 > 0) {
			$nome_usuario_lancou = $resultado2[0]['nome'];
		} else {
			$nome_usuario_lancou = 'Sem Referência!';
		}



		$query2 = $pdo->query("SELECT * FROM usuarios01 where id = '$funcionario'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$resultado2 = @count($resultado2);
		if ($resultado2 > 0) {
			$nome_funcionario = $resultado2[0]['nome'];
		} else {
			$nome_funcionario = 'Sem Referência!';
		}


		if ($data_pagamento == '0000-00-00') {
			$classe_alerta = 'text-danger';
			$data_pagamentoF = 'Pendente';
			$visivel = '';

			$japago = 'ocultar';
		} else {
			$classe_alerta = 'verde';
			$visivel = 'ocultar';

			$japago = '';
		}

		$total_servicos += $valor;


		//extensão do arquivo
		$ext = pathinfo($foto, PATHINFO_EXTENSION);
		if ($ext == 'pdf') {
			$tumb_arquivo = 'pdf.png';
		} else if ($ext == 'rar' || $ext == 'zip') {
			$tumb_arquivo = 'rar.png';
		} else {
			$tumb_arquivo = $foto;
		}


		if ($data_vencimento < $data_hoje and $pago != 'Sim') {
			$classe_debito = 'vermelho-escuro';
		} else {
			$classe_debito = '';
		}



		echo <<<HTML
<tr class="{$classe_debito}">
<td> {$descricao}</td>
<td class="esc">R$ {$valorF}</td>
<td class="esc">{$nome_funcionario}</td>

<td>
		


		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirServico('{$id}', '{$comanda}')"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>
		
	
		</td>
</tr>
HTML;
	}

	$total_servicosF = number_format($total_servicos, 2, ',', '.');

	echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir-servicos"></div></small>
</table>
	
<div align="right">Total Serviços: <span class="verde">R$ {$total_servicosF}</span> </div>

</small>
HTML;
} else {
	echo '<small>Nenhum serviço ainda Lançado!</small>';
}

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#valor_servicos').val("<?= $total_servicos ?>");

	});

	function excluirServico(id, comanda) {
		$.ajax({
			url: 'paginas/' + pag + "/excluir_servico.php",
			method: 'POST',
			data: {
				id
			},
			dataType: "text",

			success: function(result) {
				listarServicos(comanda);
				calcular();
			}
		});

	}
</script>