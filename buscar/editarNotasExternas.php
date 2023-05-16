<?php  
include '../../../web/seguranca.php';

$id = $_POST['id'];
$tipo = $_POST['tipo'];

switch ($tipo) {
	case 'portugues':
		$portugues = preg_replace("/[^-0-9\.]/", ".", $_POST['portugues']);
		if($portugues > 10)
			$portugues = 10;
		if($portugues < 0)
			$portugues = -1;
		

		$query = "UPDATE notas_externas SET portugues = '$portugues' WHERE id = '$id'";
	break;

	case 'matematica':
		$matematica = preg_replace("/[^-0-9\.]/", ".", $_POST['matematica']);
		if($matematica > 10)
			$matematica = 10;
		if($matematica < 0)
			$matematica = -1;
		

		$query = "UPDATE notas_externas SET `matematica` = '$matematica' WHERE id = '$id'";		
	break;

	case 'ciencias':

		$ciencias = preg_replace("/[^-0-9\.]/", ".", $_POST['ciencias']);
		if($ciencias > 10)
			$ciencias = 10;
		if($ciencias < 0)
			$ciencias = -1;
		

		$query = "UPDATE notas_externas SET `ciencias` = '$ciencias' WHERE id = '$id'";


	break;

	case 'geografia':
		$geografia = preg_replace("/[^-0-9\.]/", ".", $_POST['geografia']);
		if($geografia > 10)
			$geografia = 10;
		if($geografia < 0)
			$geografia = -1;
		

		$query = "UPDATE notas_externas SET geografia = '$geografia' WHERE id = '$id'";
	break;

	case 'historia':
		$historia = preg_replace("/[^-0-9\.]/", ".", $_POST['historia']);
		if($historia > 10)
			$historia = 10;
		if($historia < 0)
			$historia = -1;
		

		$query = "UPDATE notas_externas SET historia = '$historia' WHERE id = '$id'";

	break;

	case 'ingles':
		$ingles = preg_replace("/[^-0-9\.]/", ".", $_POST['ingles']);
		
		if($ingles > 10)
			$ingles = 10;
		if($ingles < 0)
			$ingles = -1;
		

		$query = "UPDATE notas_externas SET ingles = '$ingles' WHERE id = '$id'";

	break;

	case 'quimica':
		$quimica = preg_replace("/[^-0-9\.]/", ".", $_POST['quimica']);
		
		if($quimica > 10)
			$quimica = 10;
		if($quimica < 0)
			$quimica = -1;
		

		$query = "UPDATE notas_externas SET quimica = '$quimica' WHERE id = '$id'";

	break;


	case 'fisica':
		$fisica = preg_replace("/[^-0-9\.]/", ".", $_POST['fisica']);
		
		if($fisica > 10)
			$fisica = 10;
		if($fisica < 0)
			$fisica = -1;
		

		$query = "UPDATE notas_externas SET fisica = '$fisica' WHERE id = '$id'";

	break;


	case 'sociologia':
		$sociologia = preg_replace("/[^-0-9\.]/", ".", $_POST['sociologia']);
		
		if($sociologia > 10)
			$sociologia = 10;
		if($sociologia < 0)
			$sociologia = -1;
		

		$query = "UPDATE notas_externas SET sociologia = '$sociologia' WHERE id = '$id'";

	break;


	case 'filosofia':
		$filosofia = preg_replace("/[^-0-9\.]/", ".", $_POST['filosofia']);
		
		if($filosofia > 10)
			$filosofia = 10;
		if($filosofia < 0)
			$filosofia = -1;
		

		$query = "UPDATE notas_externas SET filosofia = '$filosofia' WHERE id = '$id'";

	break;
	
	case 'media':
		$media = preg_replace("/[^-0-9\.]/", ".", $_POST['media']);
		
		if($media > 10)
			$media = 10;
		if($media < 0)
			$media = -1;
		

		$query = "UPDATE notas_externas SET media = '$media' WHERE id = '$id'";

	break;
	
}

/* echo '<script>alert("'.$query.'")</script>'; */

if(mysqli_query($_SG['link'], $query)){

    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $res = mysqli_query($_SG['link'], "SELECT id_usuario FROM notas_externas WHERE id = '$id'");
    $row = mysqli_fetch_assoc($res);    
    $id_usuario = $row['id_usuario'];
    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'editar-notas-externas', '$time')");

	echo '<b><span class="glyphicon glyphicon-ok"></span></b>&ensp;Nota atualizada com sucesso!';
}
else {
	echo '<b><span class="glyphicon glyphicon-alert"></span></b>&ensp;Nota não atualizada, tente novamente mais tarde.';
	echo mysqli_error($_SG['link']);
}

?>