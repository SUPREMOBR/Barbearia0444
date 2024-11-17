<?php require_once("sistema/conexao.php") // Conexão com o banco de dados 
?>
<!-- footer section -->
<footer class="footer_section">
  <div class="container">
    <div class="footer_content ">
      <div class="row ">
        <!-- Coluna com detalhes do sistema -->
        <div class="col-md-5 col-lg-5 footer-col">
          <div class="footer_detail">
            <a href="index.html">
              <h4>
                <?php echo $nome_sistema // Nome do sistema 
                ?>
              </h4>
            </a>
            <p>
              <?php echo $texto_rodape // Texto personalizado para o rodapé 
              ?>
            </p>
          </div>
        </div>
        <!-- Coluna de contatos -->
        <div class="col-md-7 col-lg-4 ">
          <h4>
            Contatos
          </h4>
          <div class="contact_nav footer-col">
            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                <?php echo $endereco_sistema // Endereço do sistema 
                ?>
              </span>
            </a>
            <a href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Whatsapp : <?php echo $whatsapp_sistema // Número de WhatsApp 
                            ?>
              </span>
            </a>
            <a href="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                Email : <?php echo $email_sistema // E-mail do sistema 
                        ?>
              </span>
            </a>
          </div>
        </div>
        <!-- Coluna para cadastro -->
        <div class="col-lg-3">
          <div class="footer_form footer-col">
            <h4>
              CADASTRE-SE
            </h4>
            <!-- Formulário para cadastro de telefone e nome -->
            <form id="form_cadastro">
              <input type="text" name="telefone" id="telefone_rodape" placeholder="Seu Telefone DDD + número" />
              <input type="text" name="nome" placeholder="Seu Nome" />
              <button type="submit">
                Cadastrar
              </button>
            </form>
            <br><small>
              <!-- Exibe a mensagem de retorno do cadastro -->
              <div id="mensagem-rodape"></div>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

</footer>
<!-- Fim da seção do rodapé -->

<!-- Scripts adicionais para funcionalidades -->
<!-- jQery -->
<script src="js/jquery-3.4.1.min.js"></script>
<!-- popper.js para funcionalidades de popups -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<!-- bootstrap.js para responsividade -->
<script src="js/bootstrap.js"></script>
<!-- owl.carousel para exibir sliders -->>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Script customizado -->
<script src="js/custom.js"></script>
<!-- Google Map para exibir mapa de localização -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap"></script>
<!-- End Google Map -->
<!-- Script de máscaras para formatação de campos -->
<script type="text/javascript" src="sistema/painel/js/mascaras.js"></script>
<!-- Biblioteca para aplicar máscaras em formulários -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>

</body>

</html>

<!-- Script para submissão do formulário de cadastro -->
<script type="text/javascript">
  $("#form_cadastro").submit(function() {
    // Evita o envio padrão do formulário
    event.preventDefault();
    // Coleta os dados do formulário
    var formData = new FormData(this);

    $.ajax({
      url: 'ajax/cadastrar.php', // URL do arquivo de cadastro
      type: 'POST',
      data: formData, // Envia os dados via AJAX

      // Retorno do servidor
      success: function(mensagem) {
        $('#mensagem-rodape').text('');
        $('#mensagem-rodape').removeClass()
        // Se o cadastro for bem-sucedido
        if (mensagem.trim() == "Salvo com Sucesso") {
          //$('#mensagem-rodape').addClass('text-success')
          $('#mensagem-rodape').text(mensagem)

        } else {
          // Se houver erro no cadastro 
          //$('#mensagem-rodape').addClass('text-danger')
          $('#mensagem-rodape').text(mensagem)
        }


      },

      cache: false,
      contentType: false,
      processData: false,

    });

  });
</script>