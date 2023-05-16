<?php 
include '../../../web/seguranca.php';
include_once("../../../web/class.phpmailer.php");

$id_usuario = $_POST['id_usuario'];

// Busca o usuario para comparar se H foi alterado
// $user = mysqli_fetch_assoc(mysqli_query("SELECT usuario.nome, usuario.h, usuario.serie, usuario.id_usuario, escola.nome_escola, escola.cidade FROM usuario JOIN escola ON usuario.id_escola = escola.id_escola WHERE usuario.id_usuario = '$id_usuario'"));
$user = mysqli_fetch_assoc(mysqli_query($_SG['link'], "SELECT usuario.nome, usuario.h, alunos.serie, alunos.id_usuario, escola.nome_escola, escola.cidade FROM alunos JOIN escola ON alunos.id_escola = escola.id_escola JOIN usuario ON usuario.id_usuario = alunos.id_usuario WHERE alunos.id_usuario = '$id_usuario'"));

$_POST['nome_cadastrar'] = htmlentities(strtoupper($_POST['nome_cadastrar']));
$_POST['end'] = htmlentities($_POST['end']);
$_POST['mae'] = htmlentities($_POST['mae']);
$_POST['pai'] = htmlentities($_POST['pai']);

if($_SESSION['h'] != 1 AND $_POST['email'] === $_SESSION['usuarioEmail'])
    $email = '';
else
    $email = $_POST['email'];

// NÃO FOR ALUNO
if($_SESSION['h'] != 1){
    // $senha = sha1($_POST['senha']);
    $query_usuario = "UPDATE usuario SET nome = '".$_POST['nome_cadastrar']."', email = '$email', tel = '".$_POST['tel']."', data_nasc = '".$_POST['data']."',  rg = '".$_POST['rg']."', end = '".$_POST['end']."', cel = '".$_POST['cel']."', sexo = '".$_POST['sexo']."', etnia = '".$_POST['etnia']."', face = '".$_POST['face']."', h = '".$_POST['h']."'";
    $query_aluno = "UPDATE alunos SET serie = '".$_POST['serie']."', ra = '".$_POST['ra']."', cpf = '".$_POST['cpf']."', data_ingresso = '".$_POST['ano_ingresso']."', mae = '".$_POST['mae']."', pai = '".$_POST['pai']."',  id_escola = '".$_POST['escola1']."' ";
}
// ALUNO
else {
    $query_usuario = "UPDATE usuario SET nome = '".$_POST['nome_cadastrar']."', email = '$email', tel = '".$_POST['tel']."', data_nasc = '".$_POST['data']."',  rg = '".$_POST['rg']."', end = '".$_POST['end']."', cel = '".$_POST['cel']."', sexo = '".$_POST['sexo']."', etnia = '".$_POST['etnia']."', face = '".$_POST['face']."'";
    $query_aluno = "UPDATE alunos SET serie = '".$_POST['serie']."', ra = '".$_POST['ra']."', cpf = '".$_POST['cpf']."', data_ingresso = '".$_POST['ano_ingresso']."', mae = '".$_POST['mae']."', pai = '".$_POST['pai']."',  id_escola = '".$_POST['escola1']."' ";   
}

$query_aluno .= " WHERE alunos.id_usuario = '".$_POST['id_usuario']."'";
$query_usuario .= " WHERE usuario.id_usuario = '".$_POST['id_usuario']."'";

if (mysqli_query($_SG['link'], $query_usuario) AND mysqli_query($_SG['link'], $query_aluno)){

    if($_SESSION['h'] == 1){
        $_SESSION['usuarioNome'] = $_POST['nome_cadastrar'];
        $_SESSION['usuarioSexo'] = $_POST['sexo'];
        $_SESSION['usuarioEmail'] = $email;
    }
    
    $erro = 0;

    for ($i = 1; $i <= 11; $i++) {
        $query2 = "SELECT * FROM respostase WHERE usuario_id = '$id_usuario'";
        $result2 = mysqli_query($_SG['link'], $query2);
        if (isset($_POST['r'. $i])){
            if (is_array($_POST['r'. $i])){
                $resposta = implode(',', $_POST['r'. $i]);
            }else{
            $resposta = $_POST['r'. $i];
            }
        }else{
            $resposta ='';
        }
        if (mysqli_num_rows($result2)!= 0){
            $query = "UPDATE respostase SET resposta = '$resposta' WHERE usuario_id = '$id_usuario' AND pergunta_num = '$i'";
        }else{
          
             $query = "INSERT INTO respostase (usuario_id, pergunta_num, resposta) VALUES ('$id_usuario','$i','$resposta');";
        }        
        if (!mysqli_query($_SG['link'], $query)){
            $erro = 1;
        }
    }


    // Verificar se alterou para embaixador
    if($user['h'] != $_POST['h']){


    	switch ($user['h']) {
    		case 1:
    			// Caso seja verdadeiro significa que o aluno se tornou embaixador
    			$embaixador = true;
			break;

    		case 4:
    			// Caso seja falso significa que o aluno deixou de ser embaixador
    			$embaixador = false;
			break;
    		
    		default:
    			$embaixador = NULL;
			break;
    	}

    	sendEmails($user, $embaixador);
    }

    // Ficou ao contrário id_usuario e id_usuario_afetado
    // É a vida... Segue o jogo
    $id_usuario_afetado = $_SESSION['usuarioID'];
    $time = date("Y-m-d H:i:s");

    $res = mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'editar', '$time')");

    if(!$res)
        $erro = 1;


    if ($erro != 1) {
        echo '<span class="glyphicon glyphicon-ok"></span></b>&ensp;Dados atualizados com sucesso!';
    } else {
        echo "<span class='ajuda_user'>Dados não atualizados!</span><br>";
        echo mysqli_error($_SG['link']);
    }
}else{
    echo "<span class='ajuda_user'>Dados não atualizados!</span><br>";
    echo mysqli_error($_SG['link']);
}




?>