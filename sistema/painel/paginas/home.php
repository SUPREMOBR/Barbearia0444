<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

//verificar se ele tem a permissão de estar nessa página
if (@$home == 'ocultar') {
    // Se a permissão for "ocultar", redireciona para a página inicial
    echo "<script>window.location='../index.php'</script>";
    exit();
}

$data_hoje = date('Y-m-d'); // Define a data de hoje
$data_ontem = date('Y-m-d', strtotime("-1 days", strtotime($data_hoje))); // Calcula a data de ontem com base na data de hoje

// Extrai o mês atual e o ano atual
$mes_atual = Date('m');
$ano_atual = Date('Y');
// Define o primeiro dia do mês atual
$data_inicio_mes = $ano_atual . "-" . $mes_atual . "-01";

// Define o último dia do mês atual, considerando a quantidade de dias em cada mês
if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
    $dia_final_mes = '30'; // Meses de 30 dias
} else if ($mes_atual == '2') {
    $dia_final_mes = '28'; // Fevereiro (não considera ano bissexto aqui)
} else {
    $dia_final_mes = '31'; // Meses de 31 dias
}
// Concatena ano, mês e dia final para definir a data final do mês atual
$data_final_mes = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

// Consulta para obter todos os clientes e conta o número de registros
$query = $pdo->query("SELECT * FROM clientes ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_clientes = @count($resultado);

// Consulta para obter contas a pagar com vencimento hoje e que não foram pagas
$query = $pdo->query("SELECT * FROM pagar where data_vencimento = curDate() and pago != 'Sim' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_pagar_hoje = @count($resultado);

// Consulta para obter contas a receber com vencimento hoje, que não foram pagas e possuem valor maior que zero
$query = $pdo->query("SELECT * FROM receber where data_vencimento = curDate() and pago != 'Sim' and valor > 0 ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$contas_receber_hoje = @count($resultado);

// Consulta para obter todos os produtos e calcula quantos estão com estoque baixo
$query = $pdo->query("SELECT * FROM produtos");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
$estoque_baixo = 0;
if ($total_registro > 0) {
    for ($i = 0; $i < $total_registro; $i++) {
        foreach ($resultado[$i] as $key => $value) {
        }
        $estoque = $resultado[$i]['estoque'];
        $nivel_estoque = $resultado[$i]['nivel_estoque'];

        // Verifica se o estoque está abaixo ou igual ao nível de alerta
        if ($nivel_estoque >= $estoque) {
            $estoque_baixo += 1;
        }
    }
}

// Consulta para contar agendamentos marcados para hoje
$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_agendamentos_hoje = @count($resultado);

// Consulta para contar agendamentos concluídos hoje
$query = $pdo->query("SELECT * FROM agendamentos where data = curDate() and status = 'Concluído'");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_agendamentos_concluido_hoje = @count($resultado);

// Calcula a porcentagem de agendamentos concluídos em relação ao total de agendamentos de hoje
if ($total_agendamentos_concluido_hoje > 0 and $total_agendamentos_hoje > 0) {
    $porcentagemAgendamentos = ($total_agendamentos_concluido_hoje / $total_agendamentos_hoje) * 100;
} else {
    $porcentagemAgendamentos = 0;
}

// Consulta para contar serviços lançados hoje
$query = $pdo->query("SELECT * FROM receber where data_lancamento = curDate() and tipo = 'Serviço'  ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_servicos_hoje = @count($resultado);

// Consulta para contar serviços pagos lançados hoje
$query = $pdo->query("SELECT * FROM receber where data_lancamento = curDate() and tipo = 'Serviço' and pago = 'Sim'  ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_servicos_pago_hoje = @count($resultado);

// Calcula a porcentagem de serviços pagos em relação ao total de serviços lançados hoje
if ($total_servicos_pago_hoje > 0 and $total_servicos_hoje > 0) {
    $porcentagemServicos = ($total_servicos_pago_hoje / $total_servicos_hoje) * 100;
} else {
    $porcentagemServicos = 0;
}

// Consulta para contar comissões do mês
$query = $pdo->query("SELECT * FROM pagar where data_lancamento >= '$data_inicio_mes' and data_lancamento <= '$data_final_mes' and tipo = 'Comissão' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_comissoes_mes = @count($resultado);

// Consulta para contar comissões pagas do mês
$query = $pdo->query("SELECT * FROM pagar where data_lancamento >= '$data_inicio_mes' and data_lancamento <= '$data_final_mes' and tipo = 'Comissão' and pago = 'Sim' ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_comissoes_mes_pagas = @count($resultado);

// Calcula a porcentagem de comissões pagas em relação ao total de comissões do mês
if ($total_comissoes_mes_pagas > 0 and $total_comissoes_mes > 0) {
    $porcentagemComissoes = ($total_comissoes_mes_pagas / $total_comissoes_mes) * 100;
} else {
    $porcentagemComissoes = 0;
}

// Totaliza as contas a pagar do dia
$total_debitos_dia = 0;
// Seleciona débitos pagos hoje
$query = $pdo->query("SELECT * FROM pagar where data_pagamento = curDate()");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se há registros
if (@count($resultado) > 0) {
    for ($i = 0; $i < @count($resultado); $i++) {
        foreach ($resultado[$i] as $key => $value) {
        }
        // Soma o valor de cada débito do dia
        $total_debitos_dia += $resultado[$i]['valor'];
    }
}

// Totaliza as contas a receber do dia
$total_ganhos_dia = 0;
// Seleciona ganhos recebidos hoje
$query = $pdo->query("SELECT * FROM receber where data_pagamento = curDate() and valor > 0 ");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
// Verifica se há registros
if (@count($resultado) > 0) {
    for ($i = 0; $i < @count($resultado); $i++) {
        foreach ($resultado[$i] as $key => $value) {
        }
        // Soma o valor de cada ganho do dia
        $total_ganhos_dia += $resultado[$i]['valor'];
    }
}

$saldo_total_dia = $total_ganhos_dia - $total_debitos_dia; // Calcula o saldo total do dia, considerando ganhos e débitos
$saldo_total_diaF = number_format($saldo_total_dia, 2, ',', '.'); // Formata o saldo para exibição

// Define a classe CSS de acordo com o saldo do dia (positivo ou negativo)
if ($saldo_total_dia < 0) {
    $classe_saldo_dia = 'user1'; // Classe CSS para saldo negativo
} else {
    $classe_saldo_dia = 'dollar2'; // Classe CSS para saldo positivo
}

// Dados para o gráfico mensal
$dados_meses_despesas =  '';
$dados_meses_servicos =  '';
$dados_meses_vendas =  '';
// Alimenta dados para o gráfico ao longo dos 12 meses do ano atual
for ($i = 1; $i <= 12; $i++) {
    // Formata o número do mês com dois dígitos
    if ($i < 10) {
        $mes_atual = '0' . $i;
    } else {
        $mes_atual = $i;
    }
    // Define o último dia do mês (considerando 28, 30 ou 31 dias)
    if ($mes_atual == '4' || $mes_atual == '6' || $mes_atual == '9' || $mes_atual == '11') {
        $dia_final_mes = '30';
    } else if ($mes_atual == '2') {
        $dia_final_mes = '28';
    } else {
        $dia_final_mes = '31';
    }

    // Define o início e o fim do mês atual para o filtro de data
    $data_mes_inicio_grafico = $ano_atual . "-" . $mes_atual . "-01";
    $data_mes_final_grafico = $ano_atual . "-" . $mes_atual . "-" . $dia_final_mes;

    // Calcula as despesas do mês
    $total_mes_despesa = 0;
    $query = $pdo->query("SELECT * FROM pagar where pago = 'Sim' and tipo = 'Conta' and data_pagamento >= '$data_mes_inicio_grafico' 
    and data_pagamento <= '$data_mes_final_grafico' ORDER BY id asc");
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_registro = @count($resultado);
    // Soma os valores das despesas do mês
    if ($total_registro > 0) {
        for ($i2 = 0; $i2 < $total_registro; $i2++) {
            foreach ($resultado[$i2] as $key => $value) {
            }
            $total_mes_despesa +=  $resultado[$i2]['valor'];
        }
    }
    // Armazena para o gráfico
    $dados_meses_despesas = $dados_meses_despesas . $total_mes_despesa . '-';

    // Calcula as vendas do mês
    $total_mes_vendas = 0;
    $query = $pdo->query("SELECT * FROM receber where pago = 'Sim' and tipo = 'Venda' and data_pagamento >= '$data_mes_inicio_grafico' 
    and data_pagamento <= '$data_mes_final_grafico' and valor > 0 ORDER BY id asc");
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_registro = @count($resultado);
    // Soma os valores das vendas do mês
    if ($total_registro > 0) {
        for ($i2 = 0; $i2 < $total_registro; $i2++) {
            foreach ($resultado[$i2] as $key => $value) {
            }
            $total_mes_vendas +=  $resultado[$i2]['valor'];
        }
    }
    // Armazena para o gráfico
    $dados_meses_vendas = $dados_meses_vendas . $total_mes_vendas . '-';

    // Calcula os serviços do mês
    $total_mes_servicos = 0;
    $query = $pdo->query("SELECT * FROM receber where pago = 'Sim' and tipo = 'Serviço' and data_pagamento >= '$data_mes_inicio_grafico' 
    and data_pagamento <= '$data_mes_final_grafico'  ORDER BY id asc");
    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
    $total_registro = @count($resultado);
    // Soma os valores dos serviços do mês
    if ($total_registro > 0) {
        for ($i2 = 0; $i2 < $total_registro; $i2++) {
            foreach ($resultado[$i2] as $key => $value) {
            }
            $valor_do_serv = $resultado[$i2]['valor'];
            if ($valor_do_serv == 0) { // Considera o campo 'valor2' se 'valor' for zero
                $valor_do_serv = $resultado[$i2]['valor2'];
            }
            $total_mes_servicos += $valor_do_serv;
        }
    }
    // Armazena para o gráfico
    $dados_meses_servicos = $dados_meses_servicos . $total_mes_servicos . '-';
}

// HTML com campos ocultos para passar dados ao gráfico
?>

<input type="hidden" id="dados_grafico_despesa">
<input type="hidden" id="dados_grafico_venda">
<input type="hidden" id="dados_grafico_servico">
<div class="main-page">

    <?php if ($ativo_sistema == '') { ?>
        <div style="background: #ffc341; color:#3e3e3e; padding:10px; font-size:14px; margin-bottom:10px">
            <div><i class="fa fa-info-circle"></i> <b>Aviso: </b> Prezado Cliente, não identificamos o pagamento de sua última mensalidade, entre em contato conosco o mais rápido possivel para regularizar o pagamento, caso contário seu acesso ao sistema será desativado.</div>
        </div>
    <?php } ?>

    <div class="col_3">
        <!-- Link para a página de clientes -->
        <a href="clientes">
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-users icon-rounded"></i>
                    <div class="stats">
                        <!-- Total de clientes -->
                        <h5><strong><big><big><?php echo $total_clientes ?></big></big></strong></h5>

                    </div>
                    <hr style="margin-top:10px">
                    <div align="center"><span>Total de Clientes</span></div>
                </div>
            </div>
        </a>
        <!-- Link para a página de contas a pagar -->
        <a href="pagar">
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-money user1 icon-rounded"></i>
                    <div class="stats">
                        <!-- Contas a pagar hoje -->
                        <h5><strong><big><big><?php echo $contas_pagar_hoje ?></big></big></strong></h5>

                    </div>
                    <hr style="margin-top:10px">
                    <div align="center"><span>Contas à Pagar Hoje</span></div>
                </div>
            </div>
        </a>
        <!-- Link para a página de contas a receber -->
        <a href="receber">
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-money dollar2 icon-rounded"></i>
                    <div class="stats">
                        <!-- Contas a receber hoje -->
                        <h5><strong><big><big><?php echo $contas_receber_hoje ?></big></big></strong></h5>

                    </div>
                    <hr style="margin-top:10px">
                    <div align="center"><span>Contas à Receber Hoje</span></div>
                </div>
            </div>
        </a>
        <!-- Link para a página de estoque -->
        <a href="estoque">
            <div class="col-md-3 widget widget1">
                <div class="r3_counter_box">
                    <i class="pull-left fa fa-pie-chart dollar1 icon-rounded"></i>
                    <div class="stats">
                        <!-- Produtos em estoque baixo -->
                        <h5><strong><big><big><?php echo $estoque_baixo ?></big></big></strong></h5>

                    </div>
                    <hr style="margin-top:10px">
                    <div align="center"><span>Produtos Estoque Baixo</span></div>
                </div>
            </div>
        </a>
        <!-- Exibição do saldo do dia -->
        <div class="col-md-3 widget">
            <div class="r3_counter_box">
                <i class="pull-left fa fa-usd <?php echo $classe_saldo_dia ?> icon-rounded"></i>
                <div class="stats">
                    <h5><strong><big><?php echo @$saldo_total_diaF ?></big></strong></h5>

                </div>
                <hr style="margin-top:10px">
                <div align="center"><span>R$ Saldo do Dia</span></div>
            </div>
        </div>
        <div class="clearfix"> </div>
    </div>
    <!-- Estatísticas adicionais: Agendamentos do dia, serviços pagos hoje, comissões pagas no mês -->
    <div class="row" style="margin-top: 20px">
        <!-- Agendamentos do dia -->
        <div class="col-md-4 stat stat2">
            <div class="content-top-1">
                <div class="col-md-7 top-content">
                    <h5>Agendamentos Dia</h5>
                    <label><?php echo $total_agendamentos_hoje  ?>+</label>
                </div>
                <div class="col-md-5 top-content1">
                    <!-- Gráfico de pizza -->
                    <div id="demo-pie-1" class="pie-title-center" data-percent="<?php echo $porcentagemAgendamentos ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <!-- Serviços pagos hoje -->
        <div class="col-md-4 stat">
            <div class="content-top-1">
                <div class="col-md-7 top-content">
                    <h5>Serviços Pagos Hoje</h5>
                    <label><?php echo $total_servicos_hoje ?>+</label>
                </div>
                <div class="col-md-5 top-content1">
                    <!-- Gráfico de pizza -->
                    <div id="demo-pie-2" class="pie-title-center" data-percent="<?php echo $porcentagemServicos ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>
        <!-- Comissões pagas no mês -->
        <div class="col-md-4 stat">
            <div class="content-top-1">
                <div class="col-md-7 top-content">
                    <h5>Comissões Pagas Mês</h5>
                    <label><?php echo $total_comissoes_mes ?>+</label>
                </div>
                <div class="col-md-5 top-content1">
                    <!-- Gráfico de pizza -->
                    <div id="demo-pie-3" class="pie-title-center" data-percent="<?php echo $porcentagemComissoes ?>"> <span class="pie-value"></span> </div>
                </div>
                <div class="clearfix"> </div>
            </div>
        </div>

    </div>
    <!-- Gráfico de linha para o demonstrativo financeiro -->
    <div class="row-one widgettable">
        <div class="col-md-12 content-top-2 card">

            <div class="agileinfo-cdr">
                <div class="card-header">
                    <h3>Demonstrativo Financeiro</h3>
                </div>

                <div id="Linegraph" style="width: 98%; height: 350px">
                </div>

            </div>

        </div>

        <div class="clearfix"> </div>
    </div>



    <!-- for amcharts js -->
    <script src="js/amcharts.js"></script>
    <script src="js/serial.js"></script>
    <script src="js/export.min.js"></script>
    <link rel="stylesheet" href="css/export.css" type="text/css" media="all" />
    <script src="js/light.js"></script>
    <!-- for amcharts js -->

    <script src="js/index1.js"></script>


</div>
<div class="clearfix"> </div>
</div>
<div class="clearfix"> </div>

</div>

</div>







<!-- for index page weekly sales java script -->
<script src="js/SimpleChart.js"></script>
<script>
    $('#dados_grafico_despesa').val('<?= $dados_meses_despesas ?>');
    var dados = $('#dados_grafico_despesa').val();
    saldo_mes = dados.split('-');


    $('#dados_grafico_venda').val('<?= $dados_meses_vendas ?>');
    var dados_venda = $('#dados_grafico_venda').val();
    saldo_mes_venda = dados_venda.split('-');


    $('#dados_grafico_servico').val('<?= $dados_meses_servicos ?>');
    var dados_servico = $('#dados_grafico_servico').val();
    saldo_mes_servico = dados_servico.split('-');



    var graphdata1 = {
        linecolor: "#e32424",
        title: "Despesas",
        values: [{
                X: "Janeiro",
                Y: parseFloat(saldo_mes[0])
            },
            {
                X: "Fevereiro",
                Y: parseFloat(saldo_mes[1])
            },
            {
                X: "Março",
                Y: parseFloat(saldo_mes[2])
            },
            {
                X: "Abril",
                Y: parseFloat(saldo_mes[3])
            },
            {
                X: "Maio",
                Y: parseFloat(saldo_mes[4])
            },
            {
                X: "Junho",
                Y: parseFloat(saldo_mes[5])
            },
            {
                X: "Julho",
                Y: parseFloat(saldo_mes[6])
            },
            {
                X: "Agosto",
                Y: parseFloat(saldo_mes[7])
            },
            {
                X: "Setembro",
                Y: parseFloat(saldo_mes[8])
            },
            {
                X: "Outubro",
                Y: parseFloat(saldo_mes[9])
            },
            {
                X: "Novembro",
                Y: parseFloat(saldo_mes[10])
            },
            {
                X: "Dezembro",
                Y: parseFloat(saldo_mes[11])
            },

        ]
    };

    var graphdata2 = {
        linecolor: "#109447",
        title: "Vendas",
        values: [{
                X: "Janeiro",
                Y: parseFloat(saldo_mes_venda[0])
            },
            {
                X: "Fevereiro",
                Y: parseFloat(saldo_mes_venda[1])
            },
            {
                X: "Março",
                Y: parseFloat(saldo_mes_venda[2])
            },
            {
                X: "Abril",
                Y: parseFloat(saldo_mes_venda[3])
            },
            {
                X: "Maio",
                Y: parseFloat(saldo_mes_venda[4])
            },
            {
                X: "Junho",
                Y: parseFloat(saldo_mes_venda[5])
            },
            {
                X: "Julho",
                Y: parseFloat(saldo_mes_venda[6])
            },
            {
                X: "Agosto",
                Y: parseFloat(saldo_mes_venda[7])
            },
            {
                X: "Setembro",
                Y: parseFloat(saldo_mes_venda[8])
            },
            {
                X: "Outubro",
                Y: parseFloat(saldo_mes_venda[9])
            },
            {
                X: "Novembro",
                Y: parseFloat(saldo_mes_venda[10])
            },
            {
                X: "Dezembro",
                Y: parseFloat(saldo_mes_venda[11])
            },

        ]
    };


    var graphdata3 = {
        linecolor: "#0e248a",
        title: "Serviços",
        values: [{
                X: "Janeiro",
                Y: parseFloat(saldo_mes_servico[0])
            },
            {
                X: "Fevereiro",
                Y: parseFloat(saldo_mes_servico[1])
            },
            {
                X: "Março",
                Y: parseFloat(saldo_mes_servico[2])
            },
            {
                X: "Abril",
                Y: parseFloat(saldo_mes_servico[3])
            },
            {
                X: "Maio",
                Y: parseFloat(saldo_mes_servico[4])
            },
            {
                X: "Junho",
                Y: parseFloat(saldo_mes_servico[5])
            },
            {
                X: "Julho",
                Y: parseFloat(saldo_mes_servico[6])
            },
            {
                X: "Agosto",
                Y: parseFloat(saldo_mes_servico[7])
            },
            {
                X: "Setembro",
                Y: parseFloat(saldo_mes_servico[8])
            },
            {
                X: "Outubro",
                Y: parseFloat(saldo_mes_servico[9])
            },
            {
                X: "Novembro",
                Y: parseFloat(saldo_mes_servico[10])
            },
            {
                X: "Dezembro",
                Y: parseFloat(saldo_mes_servico[11])
            },

        ]
    };




    $(function() {


        $("#Linegraph").SimpleChart({
            ChartType: "Line",
            toolwidth: "50",
            toolheight: "25",
            axiscolor: "#E6E6E6",
            textcolor: "#6E6E6E",
            showlegends: true,
            data: [graphdata3, graphdata2, graphdata1],
            legendsize: "30",
            legendposition: 'bottom',
            xaxislabel: 'Meses',
            title: '',
            yaxislabel: 'Totais R$',

        });

    });
</script>
<!-- //for index page weekly sales java script -->