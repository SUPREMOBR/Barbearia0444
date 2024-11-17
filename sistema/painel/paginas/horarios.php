<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como funcionarios
$pag = 'funcionarios';
?>

<!-- Formulário de agendamento de horários -->
<form id="form-horarios">
    <div class="row">
        <!-- Campo para selecionar o horário -->
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Horário</label>
                <input type="time" class="form-control" id="horario" name="horario" required>
            </div>
        </div>
        <!-- Campo para selecionar a data (opcional, para horários encaixados) -->
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Data <small>(Encaixado)</small></label>
                <input type="date" class="form-control" id="data" name="data">
            </div>
        </div>
        <!-- Botão de submit para salvar o horário -->
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
        </div>
        <!-- Campo oculto para armazenar o ID do usuário -->
        <input type="hidden" name="id" id="id_horarios" value="<?php echo $id_usuario ?>">

    </div>
</form>

<small>
    <div id="mensagem-horarios"></div>
</small>

<big>
    <!-- Div onde serão listados os horários agendados -->
    <div class="bs-example widget-shadow" style="padding:15px" id="listar-horarios">

    </div>
</big>

<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var func = $("#id_horarios").val(); // Obtém o ID do usuário
        listarHorarios(func) // Chama a função para listar os horários ao carregar a página
    });
</script>

<script type="text/javascript">
    $("#form-horarios").submit(function() {

        var func = $("#id_horarios").val(); // Obtém o valor do ID do usuário
        event.preventDefault(); // Impede o comportamento padrão do formulário (evita o recarregamento da página)
        var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário

        $.ajax({
            url: 'paginas/' + pag + "/inserir-horario.php",
            type: 'POST',
            data: formData, // Dados a serem enviados na requisição

            success: function(mensagem) {
                $('#mensagem-horarios').text('');
                $('#mensagem-horarios').removeClass()
                if (mensagem.trim() == "Salvo com Sucesso") {

                    //$('#btn-fechar-horarios').click();
                    listarHorarios(func); // Se a mensagem for de sucesso, recarrega a lista de horários

                } else {

                    $('#mensagem-horarios').addClass('text-danger')
                    $('#mensagem-horarios').text(mensagem)
                }

            },

            cache: false,
            contentType: false,
            processData: false,

        });

    });
</script>

<script type="text/javascript">
    // Função JavaScript para carregar a lista de horários do usuário especificado
    function listarHorarios(func) {

        $.ajax({
            url: 'paginas/' + pag + "/listar-horarios.php",
            method: 'POST',
            data: {
                // Envia o ID do usuário para listar os horários dele
                func
            },
            dataType: "html",

            success: function(result) {
                $("#listar-horarios").html(result); // Atualiza o conteúdo da div com a lista de horários
                $('#mensagem-horario-excluir').text(''); // Limpa mensagens anteriores
            }
        });
    }
</script>