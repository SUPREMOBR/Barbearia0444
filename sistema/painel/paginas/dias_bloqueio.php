<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como dias_bloqueio
$pag = 'dias_bloqueio';

//verificar se ele tem a permissão de estar nessa página
if (@$dias_bloqueio == 'ocultar') {
    echo "<script>window.location='../index.php'</script>";
    exit();
}

?>

<!-- Formulário para seleção de data para bloqueio de funcionário -->
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
        <!-- Campo oculto que armazena o ID do usuário para uso em ações -->
        <input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

    </div>
</form>

<small>
    <!--  exibir mensagens de sucesso ou erro após envio -->
    <div id="mensagem-dias"></div>
</small>

<big>
    <!--  exibir a lista de dias bloqueados -->
    <div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">

    </div>
</big>

<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>

<!-- Script para carregar a lista de serviços ao iniciar a página -->
<script type="text/javascript">
    $(document).ready(function() {
        var func = $("#id_dias").val(); // Obtém o ID do usuário
        listarServicos(func) // Chama a função para listar serviços
    });
</script>

<!-- Script para manipular o envio do formulário usando AJAX -->
<script type="text/javascript">
    $("#form-dias").submit(function() {

        var func = $("#id_dias").val(); // Obtém o ID do usuário
        event.preventDefault(); // Evita o comportamento padrão de envio do formulário
        var formData = new FormData(this); // Cria um FormData com os dados do formulário

        // Requisição AJAX para enviar dados e salvar o serviço no servidor
        $.ajax({
            url: 'paginas/' + pag + "/inserir-servico.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                $('#mensagem-dias').text(''); // Limpa a mensagem anterior
                $('#mensagem-dias').removeClass() // Remove classes de estilo da mensagem

                // Verifica se o retorno é "Salvo com Sucesso"
                if (mensagem.trim() == "Salvo com Sucesso") {

                    //$('#btn-fechar-horarios').click();
                    listarServicos(func); // Atualiza a lista de serviços

                } else {

                    $('#mensagem-dias').addClass('text-danger') // Adiciona classe de erro
                    $('#mensagem-dias').text(mensagem) // Exibe a mensagem de erro
                }


            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>

<!-- Script para listar serviços usando AJAX -->
<script type="text/javascript">
    function listarServicos(func) {

        // Requisição AJAX para buscar a lista de serviços para o usuário
        $.ajax({
            url: 'paginas/' + pag + "/listar-servicos.php",
            method: 'POST',
            data: {
                func
            },
            dataType: "html",

            success: function(result) { // Exibe o resultado dentro da div "listar-dias"
                $('#mensagem-dias-excluir').text(''); // Limpa a mensagem de exclusão, se houver
            }
        });
    }
</script>