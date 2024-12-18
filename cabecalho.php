<?php require_once("sistema/conexao.php") ?> <!-- Inclui o arquivo de conexão com o banco de dados para uso neste script -->
<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="barbearia freitas, salão de beleza" />
  <meta name="description" content="Fazemos todo tipo de serviço ..." />
  <meta name="author" content="Sr.Barriga" />
  <link rel="shortcut icon" href="images/<?php echo $icone_site ?>" type="image/x-icon"> <!-- Ícone do site -->

  <title><?php echo $nome_sistema ?></title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body class="sub_page"> <!-- Define uma classe CSS para estilizar páginas secundárias -->
  <div class="hero_area">
    <div class="hero_bg_box">
      <img src="images/<?php echo $img_banner_index ?>" alt=""> <!-- Exibe o banner de fundo na área hero -->
    </div>
    <!-- Início da seção de cabeçalho -->
    <!-- header section strats -->
    <header class="header_section">
      <div class="container">
        <!-- Barra de navegação principal -->
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <img src="sistema/img/logo.png" width="80px" style="filter: invert(100%); margin-right: 3px">
          <a class="navbar-brand " href="index"> <?php echo $nome_sistema ?> </a>
          <!-- Botão para exibir menu em dispositivos móveis -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>
          <!-- Itens do menu de navegação -->
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item active">
                <!-- Link para a página inicial -->
                <a class="nav-link" href="index">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <!-- Link para a página de agendamentos -->
                <a class="nav-link" href="agendamentos"> Agendamentos</a>
              </li>
              <li class="nav-item">
                <!-- Link para a página de produtos -->
                <a class="nav-link" href="produtos">Produtos</a>
              </li>
              <li class="nav-item">
                <!-- Link para a página de serviços -->
                <a class="nav-link" href="servicos">Serviços</a>
              </li>


              <li class="nav-item">
                <!-- Link para o sistema em nova aba -->
                <a title="Ir para o Sistema" class="nav-link" href="sistema" target="_blank"> <i class="fa fa-user" aria-hidden="true"></i> </a>
              </li>

              <li class="nav-item">
                <!-- Link para o WhatsApp -->
                <a title="Ir para o Whatsapp" class="nav-link" href="http://api.whatsapp.com/send?1=pt_BR&phone=<?php echo $telefone_whatsapp ?>" target="_blank"> <i class="fa fa-whatsapp" aria-hidden="true"></i> </a>
              </li>

              <li class="nav-item">
                <!-- Link para o Instagram -->
                <a title="Ver Instagram" class="nav-link" href="<?php echo $instagram_sistema ?>" target="_blank"> <i class="fa fa-instagram" aria-hidden="true"></i> </a>
              </li>

            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->