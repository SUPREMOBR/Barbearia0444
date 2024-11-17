<?php require_once("cabecalho.php") // Inclui o cabeçalho do sistema
?>
<style type="text/css">
  .sub_page .hero_area {
    min-height: auto;
  }
</style>

</div>

<!-- Seção de Serviços -->
<section class="product_section layout_padding">
  <div class="container-fluid">
    <div class="heading_container heading_center ">
      <h2 class="">
        Nossos Serviços
      </h2>
      <p class="col-lg-8 px-0">
        <?php
        // Busca e exibe as categorias de serviços disponíveis
        $query = $pdo->query("SELECT * FROM categoria_servicos ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
        if ($total_registro > 0) {
          for ($i = 0; $i < $total_registro; $i++) {
            foreach ($resultado[$i] as $key => $value) {
            }
            $id = $resultado[$i]['id'];
            $nome = $resultado[$i]['nome'];

            // Exibe o nome da categoria de serviço
            echo $nome;

            if ($i < ($total_registro - 1)) {
              echo ' / '; // Adiciona uma barra entre as categorias
            }
          }
        }
        // Busca os serviços ativos no banco de dados
        $query = $pdo->query("SELECT * FROM servicos where ativo = 'Sim' ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
        if ($total_registro > 0) {
        ?>
      </p>
    </div>
    <!-- Exibe os serviços disponíveis em um layout de grade -->
    <div class="row" style="background: #f0f0f2">

      <?php
          for ($i = 0; $i < $total_registro; $i++) {
            foreach ($resultado[$i] as $key => $value) {
            }

            $id = $resultado[$i]['id'];
            $nome = $resultado[$i]['nome'];
            $valor = $resultado[$i]['valor'];
            $foto = $resultado[$i]['foto'];
            // Formata o valor do serviço
            $valorF = number_format($valor, 2, ',', '.');
            // Limita o nome a 20 caracteres
            $nomeF = mb_strimwidth($nome, 0, 20, "...");

      ?>

        <div class="col-sm-6 col-md-3">
          <div class="box">
            <div class="img-box">
              <img src="sistema/painel/img/servicos/<?php echo $foto ?>" title="<?php echo $descricao ?>">
            </div>
            <div class="detail-box">
              <h5>
                <?php echo $nomeF // Limita o nome a 20 caracteres
                ?>
              </h5>
              <h6 class="price">
                <span class="new_price">
                  R$ <?php echo $valorF // Preço do serviço
                      ?>
                </span>

              </h6>
              <!-- Link para a página de agendamentos -->
              <a href="agendamentos">
                Agendar
              </a>
            </div>
          </div>
        </div>

      <?php } // Fecha o loop de exibição dos serviços
      ?>


    </div>

  <?php } // Fecha a verificação de serviços ativos
  ?>

  </div>
</section>



<!-- product section ends -->





<?php require_once("rodape.php") ?>