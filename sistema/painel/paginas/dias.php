<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como funcionarios
$pag = 'funcionarios';
?>

<!-- Formulário para definir a jornada de trabalho e intervalo de almoço de um funcionário -->
<form id="form-dias">
    <div class="row">
        <!-- Campo para selecionar o dia da semana -->
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Dia</label>
                <select class="form-control" id="dias" name="dias" required>
                    <option value="Segunda-Feira">Segunda-Feira</option>
                    <option value="Terça-Feira">Terça-Feira</option>
                    <option value="Quarta-Feira">Quarta-Feira</option>
                    <option value="Quinta-Feira">Quinta-Feira</option>
                    <option value="Sexta-Feira">Sexta-Feira</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>

                </select>
            </div>

        </div>

        <!-- Campo para definir o horário de início e término da jornada de trabalho -->
        <div class="col-md-4" align="center">
            <label for="exampleInputEmail1">(Início) Jornada de Trabalho (Final)</label>
            <div class="row">
                <!-- Horário de início da jornada -->
                <div class="col-md-6">
                    <input type="time" name="inicio" class="form-control" id="inicio" required>
                </div>

                <!-- Horário de término da jornada -->
                <div class="col-md-6">
                    <input type="time" name="final" class="form-control" id="final" required>

                </div>
            </div>

        </div>

        <!-- Campo para definir o horário de intervalo de almoço -->
        <div class="col-md-4" align="center">
            <label for="exampleInputEmail1">(Início) Intervalo de Almoço (Final)</label>
            <div class="row">
                <!-- Horário de início do intervalo -->
                <div class="col-md-6">
                    <input type="time" name="inicio_almoco" class="form-control" id="inicio_almoco">
                </div>

                <!-- Horário de término do intervalo -->
                <div class="col-md-6">
                    <input type="time" name="final_almoco" class="form-control" id="final_almoco">

                </div>
            </div>

        </div>
        <!-- Botão para salvar as informações -->
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
        </div>
        <!-- Campo oculto para armazenar o ID do usuário -->
        <input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">
        <!-- Campo oculto para armazenar o ID do dia a ser editado -->
        <input type="hidden" name="id_d" id="id_d">

    </div>
</form>

<small>
    <!--  Vai exibir mensagens de sucesso ou erro após salvar -->
    <div id="mensagem-dias"></div>
</small>

<big>
    <!-- Vai exibir a lista de dias configurados -->
    <div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">

    </div>
</big>

<!-- Definição da variável JavaScript 'pag' com o valor PHP da variável $pag -->
<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>

<!-- Script para carregar a lista de dias ao iniciar a página -->
<script type="text/javascript">
    $(document).ready(function() {
        var func = $("#id_dias").val(); // Obtém o ID do usuário
        listarDias(func) // Chama a função para listar os dias
    });
</script>

<!-- Script para submeter o formulário usando AJAX -->
<script type="text/javascript">
    $("#form-dias").submit(function() {

        var func = $("#id_dias").val(); // Obtém o ID do usuário
        event.preventDefault(); // Impede o envio tradicional do formulário
        var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário

        // Requisição AJAX para enviar os dados para o servidor
        $.ajax({
            url: 'paginas/' + pag + "/inserir-dias.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                $('#mensagem-dias').text(''); // Limpa a mensagem anterior
                $('#mensagem-dias').removeClass() // Remove as classes de estilo da mensagem

                // Verifica se a mensagem retornada é "Salvo com Sucesso"
                if (mensagem.trim() == "Salvo com Sucesso") {

                    //$('#btn-fechar-horarios').click();
                    $("#id_d").val(''); // Limpa o campo de ID do dia
                    listarDias(func); // Atualiza a lista de dias

                } else {

                    $('#mensagem-dias').addClass('text-danger') // Adiciona a classe de erro
                    $('#mensagem-dias').text(mensagem) // Exibe a mensagem de erro
                }

            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>

<!-- Função para listar os dias configurados -->
<script type="text/javascript">
    function listarDias(func) {

        $.ajax({
            url: 'paginas/' + pag + "/listar-dias.php",
            method: 'POST',
            data: {
                // Envia o ID do usuário para filtrar os dados
                func
            },
            dataType: "html",

            success: function(result) {
                $("#listar-dias").html(result); // Exibe a lista de dias dentro da div "listar-dias"
                $('#mensagem-dias-excluir').text(''); // Limpa qualquer mensagem de exclusão
            }
        });
    }
</script>