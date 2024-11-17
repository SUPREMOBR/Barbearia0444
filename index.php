<?php require_once("cabecalho.php") ?> <!-- Inclui o arquivo de cabeçalho, que pode conter a estrutura inicial ou variáveis globais -->


<?php
// Consulta a tabela 'textos_index' para obter textos a serem exibidos no slider
$query = $pdo->query("SELECT * FROM textos_index ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
// Verifica se há registros para exibir
if ($total_registro > 0) {
?>
  <!-- slider section -->
  <!-- Início da seção do slider -->
  <section class="slider_section ">
    <div id="customCarousel1" class="carousel slide" data-ride="carousel">
      <div class="carousel-inner">

        <?php
        // Loop para iterar sobre cada registro da tabela 'textos_index
        for ($i = 0; $i < $total_registro; $i++) {
          foreach ($resultado[$i] as $key => $value) {
          }
          // Recupera cada campo do registro atual
          $id = $resultado[$i]['id'];
          $titulo = $resultado[$i]['titulo'];
          $descricao = $resultado[$i]['descricao'];

          // Limita a descrição a 50 caracteres para exibir uma prévia
          $descricaoF = mb_strimwidth($descricao, 0, 50, "...");

          // Define a primeira imagem como 'ativa' para ser exibida primeiro
          if ($i == 0) {
            $ativo = 'active';
          } else {
            $ativo = '';
          }
        ?>
          <!-- Estrutura do item de slider para cada registro -->
          <div class="carousel-item <?php echo $ativo ?>">
            <div class="container ">
              <div class="row">
                <div class="col-md-6 ">
                  <div class="detail-box">
                    <h1>
                      <!-- Exibe o título do slide -->
                      <?php echo $titulo ?>
                    </h1>
                    <p>
                      <!-- Exibe a descrição completa -->
                      <?php echo $descricao ?>
                    </p>
                    <div class="btn-box">
                      <!-- Link para contato via WhatsApp -->
                      <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>" target="_blank" class="btn1">
                        Contate-nos
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>


      </div>
      <div class="container">
        <div class="carousel_btn-box">
          <!-- Botões de controle para o slider -->
          <a class="carousel-control-prev" href="#customCarousel1" role="button" data-slide="prev">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#customCarousel1" role="button" data-slide="next">
            <i class="fa fa-arrow-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
  <!-- Fim da seção do slider -->

<?php } ?>

</div>


<!-- Início da seção de produtos/serviços -->
<section class="product_section layout_padding">
  <div class="container">
    <div class="heading_container heading_center ">
      <h2 class="">
        Nossos Serviços
      </h2>
      <p class="col-lg-8 px-0">
        <?php
        // Consulta para obter as categorias de serviços
        $query = $pdo->query("SELECT * FROM categoria_servicos ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
        // Exibe cada categoria separada por '/'
        if ($total_registro > 0) {
          for ($i = 0; $i < $total_registro; $i++) {
            foreach ($resultado[$i] as $key => $value) {
            }
            $id = $resultado[$i]['id'];
            $nome = $resultado[$i]['nome'];

            echo $nome;

            if ($i < ($total_registro - 1)) {
              echo ' / ';
            }
          }
        }
        // Consulta para obter os serviços ativos
        $query = $pdo->query("SELECT * FROM servicos where ativo = 'Sim' ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
        // Verifica se há serviços ativos para exibir
        if ($total_registro > 0) {
        ?>
      </p>
    </div>
    <div class="product_container">
      <div class="product_owl-carousel owl-carousel owl-theme ">

        <?php
          // Loop para exibir cada serviço ativo
          for ($i = 0; $i < $total_registro; $i++) {
            foreach ($resultado[$i] as $key => $value) {
            }

            $id = $resultado[$i]['id'];
            $nome = $resultado[$i]['nome'];
            $valor = $resultado[$i]['valor'];
            $foto = $resultado[$i]['foto'];

            // Formatação de valores e nome do serviço
            $valorF = number_format($valor, 2, ',', '.');
            $nomeF = mb_strimwidth($nome, 0, 20, "...");
        ?>
          <!-- Estrutura de exibição de cada serviço -->
          <div class="item">
            <div class="box">
              <div class="img-box">
                <!-- Exibe a imagem do serviço -->
                <img src="sistema/painel/img/servicos/<?php echo $foto ?>" alt="">
              </div>
              <div class="detail-box">
                <h4>
                  <!-- Exibe o nome abreviado do serviço -->
                  <?php echo $nomeF ?>
                </h4>

                <h6 class="price">
                  <span class="new_price">
                    <!-- Exibe o preço formatado -->
                    R$ <?php echo $valorF ?>
                  </span>

                </h6>
                <!-- Link para página de agendamento -->
                <a href="agendamentos">
                  Agendar
                </a>
              </div>
            </div>
          </div>

        <?php
          }
        ?>


      </div>
    </div>

  <?php } ?>
  </div>
</section>

<!-- product section ends -->

<!-- Início da seção "Sobre Nós" -->

<section class="about_section ">
  <div class="container-fluid">
    <div class="row">
      <!-- Coluna para imagem sobre a empresa -->
      <div class="col-md-6 px-0">
        <div class="img-box ">
          <!-- Exibe a imagem do campo "sobre" -->
          <img src="images/<?php echo $imagem_sobre ?>" class="box_img" alt="about img">
          <?php  ?>
        </div>
      </div>

      <!-- Coluna para o texto "Sobre Nós" -->
      <div class="col-md-5">
        <div class="detail-box ">
          <div class="heading_container">
            <!-- Título da seção -->
            <h2 class="">
              Sobre Nós
            </h2>
          </div>
          <p class="detail_p_mt">
            <!-- Exibe o texto da seção "Sobre Nós" -->
            <?php echo $texto_sobre ?>
          </p>
          <!-- Botão que leva ao WhatsApp para mais informações -->
          <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>" class="">
            Mais Informações
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Fim da seção "Sobre Nós" -->

<!-- Início da seção de produtos -->

<?php
// Consulta a tabela 'produtos' para exibir os produtos em estoque e com valor de venda maior que zero, limitando a 8 registros
$query = $pdo->query("SELECT * FROM produtos where estoque > 0 and valor_venda >  0 ORDER BY id desc limit 8");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
?>

  <section class="product_section layout_padding">
    <div class="container-fluid">
      <div class="heading_container heading_center ">
        <!-- Título da seção de produtos -->
        <h2 class="">
          Nossos Produtos
        </h2>

      </div>
      <div class="row">

        <?php
        // Loop para exibir cada produto da consulta
        for ($i = 0; $i < $total_registro; $i++) {
          foreach ($resultado[$i] as $key => $value) {
          }

          $id = $resultado[$i]['id'];
          $nome = $resultado[$i]['nome'];
          $valor = $resultado[$i]['valor_venda'];
          $foto = $resultado[$i]['foto'];
          $descricao = $resultado[$i]['descricao'];

          // Formata o valor e o nome do produto para exibição
          $valorF = number_format($valor, 2, ',', '.');
          $nomeF = mb_strimwidth($nome, 0, 23, "...");

        ?>
          <!-- Estrutura de cada produto exibido -->
          <div class="col-sm-6 col-md-3">
            <div class="box">
              <div class="img-box">
                <!-- Exibe a imagem do produto -->
                <img src="sistema/painel/img/produtos/<?php echo $foto ?>" title="<?php echo $descricao ?>">
              </div>
              <div class="detail-box">
                <h5>
                  <!-- Exibe o nome abreviado do produto -->
                  <?php echo $nomeF ?>
                </h5>
                <h6 class="price">
                  <span class="new_price">
                    <!-- Exibe o valor do produto formatado -->
                    R$ <?php echo $valorF ?>
                  </span>

                </h6>
                <!-- Link para o WhatsApp para comprar o produto -->
                <a target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>&text=Ola, gostaria de saber mais informações sobre o produto <?php echo $nome ?>">
                  Comprar Agora
                </a>
              </div>
            </div>
          </div>

        <?php } ?>

      </div>
      <div class="btn-box">
        <!-- Botão para ver mais produtos, redireciona para a página completa de produtos -->
        <a href="produtos">
          Ver mais Produtos
        </a>
      </div>
    </div>
  </section>

  <!-- Fim do loop de produtos se houver registros para exibir -->
<?php } ?>

<!-- Fim da seção de produtos -->


<!-- Seção de Contato -->
<section class="contact_section layout_padding-bottom">
  <div class="container">
    <div class="heading_container">
      <!-- Título da seção de contato -->
      <h2>
        Contate-nos
      </h2>
    </div>
    <div class="row">
      <!-- Formulário de contato -->
      <div class="col-md-6">
        <div class="form_container">
          <form id="form-email">
            <div>
              <!-- Campo para nome do usuário -->
              <input type="text" name="nome" placeholder="Seu Nome" required />
            </div>
            <div>
              <!-- Campo para telefone do usuário -->
              <input type="text" name="telefone" id="telefone" placeholder="Seu Telefone" required />
            </div>
            <div>
              <!-- Campo para email do usuário -->
              <input type="email" name="email" placeholder="Seu Email" required />
            </div>
            <div>
              <!-- Campo para a mensagem -->
              <input type="text" name="mensagem" class="message-box" placeholder="Mensagem" required />
            </div>
            <div class="btn_box">
              <!-- Botão para enviar a mensagem -->
              <button>
                Enviar
              </button>
            </div>
          </form>

          <br>
          <!-- Div para mostrar mensagens de feedback do formulário -->
          <div id="mensagem"></div>
        </div>
      </div>
      <!-- Seção do Mapa -->
      <div class="col-md-6">
        <div class="map_container ">
          <!-- Exibe o mapa inserido no banco de dados -->
          <?php echo $mapa ?>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Fim da seção de contato -->

<!-- Seção de Depoimentos dos Clientes -->

<?php
// Consulta a tabela 'comentarios' para obter depoimentos ativos de clientes
$query = $pdo->query("SELECT * FROM comentarios where ativo = 'Sim' ORDER BY id asc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if ($total_registro > 0) {
?>
  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container">
        <!-- Título da seção de depoimentos -->
        <h2>
          Depoimento dos nossos Clientes
        </h2>
      </div>
      <div class="client_container">
        <div class="carousel-wrap">
          <div class="owl-carousel client_owl-carousel">

            <?php
            // Loop para exibir cada comentário
            for ($i = 0; $i < $total_registro; $i++) {
              foreach ($resultado[$i] as $key => $value) {
              }

              $id = $resultado[$i]['id'];
              $nome = $resultado[$i]['nome'];
              $texto = $resultado[$i]['texto'];
              $foto = $resultado[$i]['foto'];
            ?>
              <!-- Cada item de depoimento do cliente -->
              <div class="item">
                <div class="box">
                  <div class="img-box">
                    <!-- Foto do cliente -->
                    <img src="sistema/painel/img/comentarios/<?php echo $foto ?>" alt="" class="img-1" style="aspect-ratio: 1 / 1; object-fit: fill;">
                  </div>
                  <div class="detail-box">
                    <h5>
                      <!-- Nome do cliente -->
                      <?php echo $nome ?>
                    </h5>

                    <p>
                      <!-- Texto do depoimento -->
                      <?php echo $texto ?>
                    </p>
                  </div>
                </div>
              </div>

            <?php } ?>

          </div>
        </div>
      </div>
    </div>
    <!-- Botão para inserir um novo depoimento -->
    <div class="btn-box2">
      <a href="" data-toggle="modal" data-target="#modalComentario">
        Inserir Depoimento
      </a>
    </div>

  </section>

<?php } ?>

<!-- Fim da seção de depoimentos -->

<?php require_once("rodape.php") ?> <!-- Importa o rodapé da página -->


<!-- Modal Depoimentos -->
<div class="modal fade" id="modalComentario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <!-- Título do modal -->
        <h5 class="modal-title" id="exampleModalLabel">Inserir Depoimento
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -20px">
          <!-- Botão para fechar o modal -->
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="form">
        <div class="modal-body">

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="exampleInputEmail1">Nome</label>
                <input type="text" class="form-control" id="nome_cliente" name="nome" placeholder="Nome" required>
              </div>
            </div>
            <div class="col-md-12">

              <div class="form-group">
                <label for="exampleInputEmail1">Texto <small>(Até 500 Caracteres)</small></label>
                <textarea maxlength="500" class="form-control" id="texto_cliente" name="texto" placeholder="Texto Comentário" required> </textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Foto</label>
                <input class="form-control" type="file" name="foto" onChange="carregarImg();" id="foto">
              </div>
            </div>
            <div class="col-md-4">
              <div id="divImg">
                <img src="sistema/painel/img/comentarios/sem-foto.jpg" width="80px" id="target">
              </div>
            </div>

          </div>

          <!-- Campo oculto para armazenar o ID do depoimento, se necessário -->
          <input type="hidden" name="id" id="id">

          <!-- Campo oculto para indicar o cliente (valor fixo neste caso) -->
          <input type="hidden" name="cliente" value="1">

          <br>
          <small>
            <!-- Div para exibir mensagens de feedback após a inserção -->
            <div id="mensagem-comentario" align="center"></div>
          </small>
        </div>

        <div class="modal-footer">
          <!-- Botão para enviar o formulário -->
          <button type="submit" class="btn btn-primary">Inserir</button>
        </div>
      </form>

    </div>
  </div>
</div>


<script type="text/javascript">
  // Envio de Email
  $("#form-email").submit(function() {

    // Impede o envio normal do formulário
    event.preventDefault();
    // Cria um objeto FormData a partir do formulário
    var formData = new FormData(this);

    $.ajax({
      url: 'ajax/enviar-email.php', // URL para onde a requisição será enviada
      type: 'POST', // Método HTTP utilizado
      data: formData, // Dados do formulário

      success: function(mensagem) {
        // Limpa mensagens anteriores
        $('#mensagem').text('');
        // Remove todas as classes de estilo
        $('#mensagem').removeClass()
        if (mensagem.trim() == "Enviado com Sucesso") {
          // Adiciona classe de sucesso
          $('#mensagem').addClass('text-success')
          // Exibe a mensagem de sucesso
          $('#mensagem').text(mensagem)

        } else {
          // Adiciona classe de erro
          $('#mensagem').addClass('text-danger')
          // Exibe a mensagem de erro
          $('#mensagem').text(mensagem)
        }

      },

      cache: false, // Desativa o cache
      contentType: false, // Permite que o jQuery não defina o tipo de conteúdo
      processData: false, // Impede que o jQuery processe os dados

    });

  });
</script>


<script type="text/javascript">
  // exibir uma prévia da imagem selecionada pelo usuário antes do envio.
  function carregarImg() {
    // Seleciona o elemento onde a imagem será exibida
    var target = document.getElementById('target');
    // Pega o primeiro arquivo selecionado
    var file = document.querySelector("#foto").files[0];
    // Cria um objeto FileReader
    var reader = new FileReader();

    reader.onloadend = function() {
      // Define a imagem do elemento target como o resultado da leitura
      target.src = reader.result;
    };

    if (file) {
      // Lê o arquivo como uma URL de dados
      reader.readAsDataURL(file);

    } else {
      // Reseta a imagem caso nenhum arquivo seja selecionado
      target.src = "";
    }
  }
</script>


<script type="text/javascript">
  // Inserir COMENTÁRIO
  $("#form").submit(function() {
    // Impede o envio normal do formulário
    event.preventDefault();
    // Cria um objeto FormData a partir do formulário
    var formData = new FormData(this);

    $.ajax({
      url: 'sistema/painel/paginas/comentarios/salvar.php', // URL para onde a requisição será enviada
      type: 'POST', // Método HTTP utilizado
      data: formData, // Dados do formulário

      success: function(mensagem) {
        // Limpa mensagens anteriores
        $('#mensagem-comentario').text('');
        // Remove todas as classes de estilo
        $('#mensagem-comentario').removeClass()
        if (mensagem.trim() == "Salvo com Sucesso") {
          // Adiciona classe de sucesso
          $('#mensagem-comentario').addClass('text-success')
          // Exibe mensagem de sucesso
          $('#mensagem-comentario').text('Comentário Enviado para Aprovação!')
          // Limpa o campo de nome
          $('#nome_cliente').val('');
          // Limpa o campo de texto
          $('#texto_cliente').val('');

        } else {
          // Adiciona classe de erro
          $('#mensagem-comentario').addClass('text-danger')
          // Exibe a mensagem de erro
          $('#mensagem-comentario').text(mensagem)
        }

      },

      cache: false,
      contentType: false,
      processData: false,

    });

  });
</script>