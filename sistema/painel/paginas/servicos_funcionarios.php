<?php
@session_start(); // Inicia a sessão para manter informações do usuário durante a navegação.
require_once("verificar.php"); // Inclui o arquivo "verificar.php", para autenticar o usuário ou verificar permissões.
require_once("../conexao.php"); // Conecta ao banco de dados.

// Define o nome da página atual como funcionarios
$pag = 'funcionarios';
?>

<!-- Formulário para selecionar um serviço e salvar as informações -->
<form id="form-dias">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="exampleInputEmail1">Dia</label>
                <!-- Campo de seleção para os serviços disponíveis -->
                <select class="form-control sel3" id="servico" name="servico" style="width:100%;" required>

                    <?php
                    // Consulta ao banco de dados para buscar todos os serviços ordenados por nome
                    $query = $pdo->query("SELECT * FROM servicos ORDER BY nome asc");
                    $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
                    $total_registro = @count($resultado); // Conta o número de registros retornados
                    // Verifica se há serviços para exibir
                    if ($total_registro > 0) {
                        // Loop para exibir cada serviço como uma opção na lista suspensa
                        for ($i = 0; $i < $total_registro; $i++) {
                            foreach ($resultado[$i] as $key => $value) {
                            }
                            // Exibe a opção com o nome do serviço e o valor id
                            echo '<option value="' . $resultado[$i]['id'] . '">' . $resultado[$i]['nome'] . '</option>';
                        }
                    }
                    ?>

                </select>
            </div>
        </div>

        <div class="col-md-4">
            <button type="submit" class="btn btn-primary" style="margin-top:20px">Salvar</button>
        </div>
        <!-- Campo oculto que armazena o ID do usuário para o formulário -->
        <input type="hidden" name="id" id="id_dias" value="<?php echo $id_usuario ?>">

    </div>
</form>

<small>
    <div id="mensagem-dias"></div>
</small>

<big>
    <div class="bs-example widget-shadow" style="padding:15px" id="listar-dias">

    </div>
</big>

<script type="text/javascript">
    var pag = "<?= $pag ?>"
</script>

<script type="text/javascript">
    // Quando o documento estiver completamente carregado, executa a função
    $(document).ready(function() {
        var func = $("#id_dias").val(); // Obtém o valor do id do usuário que foi passado no input oculto "id_dias"
        listarServicos(func) // Chama a função listarServicos passando o ID do funcionário (func)
    });
</script>

<script type="text/javascript">
    $("#form-dias").submit(function() {

        var func = $("#id_dias").val(); // Obtém o valor do id do usuário 
        event.preventDefault();
        var formData = new FormData(this); // Cria um objeto FormData contendo os dados do formulário

        // Realiza a requisição AJAX para o arquivo "inserir-servico.php" para processar os dados do formulário
        $.ajax({
            url: 'paginas/' + pag + "/inserir-servico.php",
            type: 'POST',
            data: formData,

            success: function(mensagem) {
                // Limpa qualquer mensagem de erro anterior
                $('#mensagem-dias').text('');
                $('#mensagem-dias').removeClass()
                if (mensagem.trim() == "Salvo com Sucesso") {

                    //$('#btn-fechar-horarios').click();
                    // Se a mensagem for "Salvo com Sucesso", chama a função listarServicos passando o ID do funcionário
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

<script type="text/javascript">
    function listarServicos(func) {

        $.ajax({
            url: 'paginas/' + pag + "/listar-servicos.php",
            method: 'POST',
            data: {
                func
            },
            dataType: "html",

            success: function(result) {
                $("#listar-dias").html(result);
                $('#mensagem-dias-excluir').text('');
            }
        });
    }
</script>