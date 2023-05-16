<?php 
include '../../../web/seguranca.php';

$id_usuario = $_POST['id_usuario'];
$ano = $_POST['ano'];

// $res = mysqli_query("SELECT serie FROM usuario WHERE id_usuario = '$id_usuario'");
$res = mysqli_query($_SG['link'], "SELECT serie FROM alunos WHERE id_usuario = '$id_usuario'");
$row = mysqli_fetch_array($res);
$serie = $row['serie'];

$query = "INSERT INTO notas (id_usuario, `maratona-do-conhecimento-1`, `maratona-do-conhecimento-2`, `concurso-literario`, `clube-de-ciencia`, serie, ano, estrelas, ouro, prata, bronze) VALUES ('$id_usuario', '0', '0', '0', '0', '$serie', '$ano','0', '$ouro', '$prata', '$bronze')";

if(mysqli_query($_SG['link'], $query)){
    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'cadastrar-notas-internas', '$time')");

	echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Nota cadastrada com sucesso!';
}
else {
	echo '<b><span class="glyphicon glyphicon-alert"></span></b>&ensp;Nota não cadastrada, tente novamente mais tarde.';
	echo mysqli_error();
}
?>