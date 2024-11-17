<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como dias_bloqueio_func
$pag = 'dias_bloqueio_func';
?>

<!-- Formulário para selecionar e salvar uma data de bloqueio de um funcionário -->
<form id="form-dias">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Data</label>
                <input type="date" name="data" id="data" class="form-control">
            </div>
        </div>

        <div class="col-md-4">
            <!-- Botão de envio do formulário -->
            <button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
        </div>
        <!-- Campo oculto para armazenar o ID do usuário associado -->
        <input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

    </div>
</form>

<small>
    <!-- Div para exibir mensagens de sucesso ou erro -->
    <div id="mensagem-dias"></div>
</small>
<!-- Div para exibir a lista de dias de bloqueio dos funcionários -->
<big>
    <div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">

    </div>
</big>

<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>

<!-- Script para carregar os serviços ao carregar a página -->
<script type="text/javascript">
    $(document).ready(function() {
        var func = $("#id_dias").val();
        listarServicos(func)
    });
</script>

<script type="text/javascript">
    $("#form-dias").submit(function() {

        var func = $("#id_dias").val();
        event.preventDefault();
        var formData = new FormData(this);

        // Faz a requisição AJAX para salvar a data de bloqueio
        $.ajax({
            url: 'paginas/' + pag + "/inserir-servico.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                $('#mensagem-dias').text('');
                $('#mensagem-dias').removeClass()
                // Verifica se a mensagem de retorno foi "Salvo com Sucesso"
                if (mensagem.trim() == "Salvo com Sucesso") {

                    //$('#btn-fechar-horarios').click();
                    listarServicos(func);

                } else {

                    $('#mensagem-dias').addClass('text-danger')
                    $('#mensagem-dias').text(mensagem)
                }

            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>

<!-- Script para listar os dias de bloqueio dos funcionários -->
<script type="text/javascript">
    function listarServicos(func) {

        // Faz uma requisição AJAX para buscar a lista de serviços
        $.ajax({
            url: 'paginas/' + pag + "/listar-servicos.php",
            method: 'POST',
            data: {
                func
            },
            dataType: "html",

            success: function(result) {
                // Exibe o resultado dentro da div "listar-dias"
                $("#listar-dias").html(result);
                $('#mensagem-dias-excluir').text('');
            }
        });
    }
</script>