<?php
// Inicia uma sessão, caso ainda não tenha sido iniciada
@session_start();

// Destrói a sessão atual, encerrando a sessão do usuário
@session_destroy();

// Redireciona o usuário para a página de login (index.php)
echo "<script>window.location='../index.php'</script>";
