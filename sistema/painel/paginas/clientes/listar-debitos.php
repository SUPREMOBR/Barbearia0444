<?php
require_once("../../../conexao.php");
$tabela = 'receber';
$data_atual = date('Y-m-d');

$id = $_POST['id'];
$id_cli = $_POST['id'];

// pegar a pagina atual
if (@$_POST['pagina'] == "") {
    @$_POST['pagina'] = 0;
}


$query = $pdo->query("SELECT * FROM $tabela where pessoa = '$id' and pago != 'Sim' ORDER BY data_vencimento asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {

    echo <<<HTML
	<small>
	<table class="table table-hover">
	<thead> 
	<tr> 
	<th class="">Descrição Conta</th>
	<th>Data Vencimento</th>	
	<th class="">Valor</th>
	<th class="">Dar Baixa</th>	
	</tr> 
	</thead> 
	<tbody>	
HTML;
    $total = 0;
    $total_pagar = 0;
    $total_vencido = 0;
    $totalFormatado = 0;
    $total_pagarFormatado = 0;
    $total_vencidoFormatado = 0;
    for ($i = 0; $i < $total_registro; $i++) {
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
        $pago = $resultado[$i]['pago'];

        $valorFormatado = number_format($valor, 2, ',', '.');
        $data_lancamentoFormatado = implode('/', array_reverse(explode('-', $data_lancamento)));
        $data_pagamentoFormatado = implode('/', array_reverse(explode('-', $data_pagamento)));
        $data_vencimentoFormatado = implode('/', array_reverse(explode('-', $data_vencimento)));

        $total += $valor;
        $totalFormatado = number_format($total, 2, ',', '.');

        $classe_data = '';
        if (strtotime($data_vencimento) < strtotime($data_atual)) {
            $classe_data = 'text-danger';
            $total_vencido += $valor;
        } else {
            $total_pagar += $valor;
        }

        $total_pagarFormatado = number_format($total_pagar, 2, ',', '.');
        $total_vencidoFormatado = number_format($total_vencido, 2, ',', '.');
        echo <<<HTML
<tr class="">
<td>{$descricao}</td>
<td class="{$classe_data}">{$data_vencimentoFormatado}</td>
<td class="text-danger">R$ {$valor}</td>
<td>
	<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a title="Baixar Conta" href="#" class="dropdown-toggle " data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-check-square verde"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Baixa na Conta? <a href="#" onclick="baixar('{$id}', '{$id_cli}')"><span class="verde">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>
</td>
</tr>
HTML;
    }

    echo <<<HTML
</tbody>
<small><div align="center" id="mensagem-excluir-baixar"></div></small>
</table>
<div align="right"><span style="margin-right: 20px; ">Total Vencido <span style="color:red">R$ {$total_vencidoFormatado}</span></span>
<span style="margin-right: 20px; ">Total à Vencer <span style="color:blue">R$ {$total_pagarFormatado}</span></span>
<span >Total Pagar <span style="color:green">R$ {$totalFormatado}</span></span>
</div>
</small>

HTML;
} else {
    echo '<small>Este Cliente não possui pagamento pendente!</small>';
}
