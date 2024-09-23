<?php require_once("cabecalho.php") ?>
<style type="text/css">
	.sub_page .hero_area {
  min-height: auto;
}
</style>

</div>





  <?php 
$query = $pdo->query("SELECT * FROM produtos where estoque > 0 and valor_venda >  0 ORDER BY id desc");
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$total_registro = @count($resultado);
if($total_registro > 0){ 
   ?>

  <section class="product_section layout_padding">
    <div class="container-fluid">
      <div class="heading_container heading_center ">
        <h2 class="">
          Nossos Produtos
        </h2>
        <p class="col-lg-8 px-0">
          Confira alguns de nossos produtos, damos desconto caso compre em grande quantidade.
        </p>
      </div>
      <div class="row">

<?php 
for($i=0; $i < $total_registro; $i++){
  foreach ($resultado[$i] as $key => $value){}
 
  $id = $resultado[$i]['id'];
  $nome = $resultado[$i]['nome'];   
  $valor = $resultado[$i]['valor_venda'];
  $foto = $resultado[$i]['foto'];
  $descricao = $resultado[$i]['descricao'];
   $valorFormatado = number_format($valor, 2, ',', '.');
 $nomeFormatado = mb_strimwidth($nome, 0, 23, "...");

 ?>

        <div class="col-sm-6 col-md-3">
          <div class="box">
            <div class="img-box">
              <img src="sistema/painel/img/produtos/<?php echo $foto ?>" title="<?php echo $descricao ?>">
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
              <a target="_blank" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>&text=Ola, gostaria de saber mais informações sobre o produto <?php echo $nome ?>">
               Comprar Agora
              </a>
            </div>
          </div>
        </div>
      
   <?php } ?>    


      </div>
      
    </div>
  </section>

<?php } ?>

  <!-- product section ends -->




 
   <?php require_once("rodape.php") ?>