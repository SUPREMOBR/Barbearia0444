<?php require_once("cabecalho.php") ?>
<style type="text/css">
  .sub_page .hero_area {
    min-height: auto;
  }
</style>

</div>





<section class="product_section layout_padding">
  <div class="container-fluid">
    <div class="heading_container heading_center ">
      <h2 class="">
        Nossos Serviços
      </h2>
      <p class="col-lg-8 px-0">
        <?php
        $query = $pdo->query("SELECT * FROM categoria_servicos ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
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

        $query = $pdo->query("SELECT * FROM servicos where ativo = 'Sim' ORDER BY id asc");
        $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_registro = @count($resultado);
        if ($total_registro > 0) {
        ?>
      </p>
    </div>
    <div class="row" style="background: #f0f0f2">

      <?php
          for ($i = 0; $i < $total_registro; $i++) {
            foreach ($resultado[$i] as $key => $value) {
            }

            $id = $resultado[$i]['id'];
            $nome = $resultado[$i]['nome'];
            $valor = $resultado[$i]['valor'];
            $foto = $resultado[$i]['foto'];
            $valorFormatado = number_format($valor, 2, ',', '.');
            $nomeFormatado = mb_strimwidth($nome, 0, 20, "...");

      ?>

        <div class="col-sm-6 col-md-3">
          <div class="box">
            <div class="img-box">
              <img src="sistema/painel/img/servicos/<?php echo $foto ?>" title="<?php echo $descricao ?>">
            </div>
            <div class="detail-box">
              <h5>
                <?php echo $nomeFormatado ?>
              </h5>
              <h6 class="price">
                <span class="new_price">
                  R$ <?php echo $valorFormatado ?>
                </span>

              </h6>
              <a href="agendamentos">
                Agendar
              </a>
            </div>
          </div>
        </div>

      <?php } ?>


    </div>

  <?php } ?>

  </div>
</section>



<!-- product section ends -->





<?php require_once("rodape.php") ?>