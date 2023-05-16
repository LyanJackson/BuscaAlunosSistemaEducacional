<?php 

include "../../../web/seguranca.php";

$id_usuario = $_POST['id_usuario'];
$titulo = htmlentities($_POST['titulo']);
$categoria = $_POST['categoria_premio'];
$ano = $_POST['ano'];
/*
$_POST['melhor_aluno_literario'] = ( isset($_POST['melhor_aluno_literario']) ) ? true : null;
$_POST['melhores_clubes']  = ( isset($_POST['melhores_clubes']) )  ? true : null;
$_POST['melhor_nota_maratona']  = ( isset($_POST['melhor_nota_maratona']) )  ? true : null;
$_POST['aluno_destaque']  = ( isset($_POST['aluno_destaque']) )  ? true : null;
*/
if ($categoria == "melhor_aluno_literario") {
	$pontuacao = "5";
}elseif ($categoria == "melhores_clubes") {
	$pontuacao = "5";
}elseif ($categoria == "melhor_nota_maratona") {
	$pontuacao = "5";
}elseif ($categoria == "aluno_destaque") {
	$pontuacao = "5";
}

$sql = "INSERT INTO premiacoes (id_usuario, titulo, categoria_premio, ano, pontuacao) VALUES ('$id_usuario', '$titulo', '$categoria', '$ano', '$pontuacao')";

// echo '<script>alert("'.$sql.'")</script>';

if(mysqli_query($_SG['link'], $sql)){
	echo '<script>$(".alerta").addClass("alert-success")</script>';
	echo '<b><i class="fa fa-check"></i></b> Premiação cadastrada com sucesso!';	
}
else {
	echo '<script>$(".alerta").addClass("alert-danger");</script>';
	echo '<b><i class="fa fa-close"></i></b> Houve um erro no sistema, aguarde uns instantes e tente novamente.';
}


echo "<script>setTimeout(function(){ 
        $('.alerta').fadeOut(500).removeClass('alert-success alert-danger', 500);
    	}, 2000);
    </script>";

 ?>