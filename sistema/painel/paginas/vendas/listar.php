<?php 
require_once("../../../conexao.php");
$tabela = 'receber';
$data_hoje = date('Y-m-d');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$status = '%'.@$_POST['status'].'%';


$total_pago = 0;
$total_a_pagar = 0;

$query = $pdo->query("SELECT * FROM $tabela where data_vencimento >= '$dataInicial' and data_vencimento <= '$dataFinal' and pago LIKE '$status' and produto != 0 ORDER BY pago asc, data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){

	echo <<<HTML
	<small>
	<table class="table table-hover" id="tabela">
	<thead> 
	<tr> 
	<th>Produto</th>	
	<th class="esc">Valor</th> 	
	<th class="esc">Vencimento</th> 	
	<th class="esc">Data Pagamento</th> 
	
	<th class="esc">Cliente</th>	
	<th class="esc">Arquivo</th>	
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	
HTML;

	


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
	$produto = $resultado[$i]['produto'];
	$pago = $resultado[$i]['pago'];
	
	$valorFormatado = number_format($valor, 2, ',', '.');
	
	$data_lancamentoFormatado = implode('/', array_reverse(explode('-', $data_lancamento)));
	$data_pagamentoFormatado = implode('/', array_reverse(explode('-', $data_pagamento)));
	$data_vencimentoFormatado = implode('/', array_reverse(explode('-', $data_vencimento)));


	$query2 = $pdo->query("SELECT * FROM clientes where id = '$pessoa'");
		$resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		$total_registro2 = @count($resultado2);
		if($total_registro2 > 0){
			$nome_pessoa = $resultado2[0]['nome'];
			$telefone_pessoa  = $resultado2[0]['nome'];
		}else{
			$nome_pessoa = 'Sem Referência!';
			$telefone_pessoa = '';
		}


	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_baixa'");
	    $resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	    $total_registro2 = @count($resultado2);
	    if($total_registro2 > 0){
		    $nome_usuario_pagamento = $resultado2[0]['nome'];
	    }else{
			$nome_usuario_pagamento = 'Nenhum!';
		}
	

	$query2 = $pdo->query("SELECT * FROM usuarios where id = '$$usuario_lancou'");
	    $resultado2 = $query2->fetchAll(PDO::FETCH_ASSOC);
	    $total_registro2 = @count($resultado2);
	    if($total_registro2 > 0){
		    $nome_usuario_lancou = $resultado2[0]['nome'];
	    }else{
			$nome_usuario_lancou = 'Sem Referência!';
		}



		if($data_pagamento == '0000-00-00'){
			$classe_alerta = 'text-danger';
			$data_pagamentoFormatado = 'Pendente';
			$visivel = '';
			$total_a_pagar += $valor;
		}else{
			$classe_alerta = 'verde';
			$visivel = 'ocultar';
			$total_pago += $valor;
		}
		
    //extensão do arquivo
      $extencao = pathinfo($foto, PATHINFO_EXTENSION);
        if($extencao == 'pdf'){
	       $tumb_arquivo = 'pdf.png';
        }else if($extencao == 'rar' || $extencao == 'zip'){
	       $tumb_arquivo = 'rar.png';
        }else{
	       $tumb_arquivo = $foto;
        }
		

if($data_vencimento < $data_hoje and $pago != 'Sim'){
	$classe_debito = 'vermelho-escuro';
}else{
	$classe_debito = '';
}


		echo <<<HTML
		<tr class="{$classe_debito}">
		<td><i class="fa fa-square {$classe_alerta}"></i> {$descricao}</td>
		<td class="esc">R$ {$valorFormatado}</td>
		<td class="esc">{$data_vencimentoFormatado}</td>
		<td class="esc">{$data_pagamentoFormatado}</td>
		<td class="esc">{$nome_pessoa}</td>
		<td><a href="img/contas/{$foto}" target="_blank"><img src="img/contas/{$tumb_arquivo}" width="27px" class="mr-2"></a></td>
		<td>
        
		<big><a href="#" onclick="mostrar('{$descricao}', '{$valorFormatado}', '{$data_lancamentoFormatado}', '{$data_vencimentoFormatado}',  '{$data_pagamentoFormatado}', '{$nome_usuario_lancou}', '{$nome_usuario_pagamento}', '{$tumb_arquivo}', '{$nome_pessoa}', '{$foto}', '{$telefone_pessoa}'))" title="Ver Dados"><i class="fa fa-info-circle text-secondary"></i></a></big>



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
         

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a title="Baixar Conta" href="#" class="dropdown-toggle {$visivel}" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-check-square verde"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}')"><span class="verde">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>


		</td>
</tr>
HTML;

}


$total_pagoFormatado = number_format($total_pago, 2, ',', '.');
$total_a_pagarFormatado = number_format($total_a_pagar, 2, ',', '.');

echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir"></div></small>
</table>

<br>	
<div align="right">Total Recebido: <span class="verde">R$ {$total_pagoFormatado}</span> </div>
<div align="right">Total à Receber: <span class="text-danger">R$ {$total_a_pagarFormatado}</span> </div>

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
	function editar(id, descricao, pessoa, valor, data_vencimento, data_pagamento, foto){
		$('#id').val(id);
		$('#descricao').val(descricao);
		$('#pessoa').val(pessoa).change();
		$('#valor').val(valor);
		$('#data_vencimento').val(data_vencimento);
		$('#data_pagamento').val(data_pagamento);
								
		$('#titulo_inserir').text('Editar Registro');
		$('#modalform').modal('show');

		$('#target').attr('src','img/contas/' + foto);
	}

	function limparCampos(){
		$('#id').val('');
		$('#descricao').val('');
		$('#valor').val('');
		$('#data_pagamento').val('');
		$('#data_vencimento').val('<?=$data_hoje?>');		
		$('#foto').val('');
		$('#quantidade').val('1');

		$('#target').attr('src','img/contas/sem-foto.jpg');
		calcular()
	}
</script>

<script type="text/javascript">
	function mostrar(descricao, valor, data_lancamento, data_vencimento, data_pagamento, usuario_lancou, usuario_pagamento, foto, pessoa, link, telefone){

		$('#nome_dados').text(descricao);
		$('#valor_dados').text(valor);
		$('#data_lancou_dados').text(data_lancamento);
		$('#data_vencimento_dados').text(data_vencimento);
		$('#data_pagamento_dados').text(data_pagamento);
		$('#usuario_lancou_dados').text(usuario_lancou);
		$('#usuario_baixa_dados').text(usuario_pagamento);
		$('#pessoa_dados').text(pessoa);
		$('#telefone_dados').text(telefone);
		
		$('#link_mostrar').attr('href','img/contas/' + link);
		$('#target_mostrar').attr('src','img/contas/' + foto);

		$('#modalDados').modal('show');
	}
</script>

<script type="text/javascript">
	function saida(id, nome, estoque){

		$('#nome_saida').text(nome);
		$('#estoque_saida').val(estoque);
		$('#id_saida').val(id);		

		$('#modalSaida').modal('show');
	}
</script>


<script type="text/javascript">
	function entrada(id, nome, estoque){

		$('#nome_entrada').text(nome);
		$('#estoque_entrada').val(estoque);
		$('#id_entrada').val(id);		

		$('#modalEntrada').modal('show');
	}
</script>


