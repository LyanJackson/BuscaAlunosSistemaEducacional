<?php

include '../../../web/seguranca.php';
include_once("../../../web/class.phpmailer.php");


$_POST['nome_cadastrar'] = htmlentities(strtoupper($_POST['nome_cadastrar']));
$_POST['end'] = htmlentities($_POST['end']);
$_POST['mae'] = htmlentities($_POST['mae']);
$_POST['pai'] = htmlentities($_POST['pai']);
//$_POST['senha'] = md5(sha1($_POST['senha']));

$rg = $_POST['rg'];
$ra = $_POST['ra'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$ano_ingresso = $_POST['ano_ingresso'];
$instagram = $_POST['instagram'];

$email_pai = $_POST['email_pai'];
$cel_pai = $_POST['cel_pai'];
//$date = str_replace("/", "-", $_POST['data_ingresso']);
//$data_ingresso = date("Y-m-d", strtotime($date));

$senha = sha1("alunopfc2019");

if (!empty($email)) {
    $sql_busca = "SELECT email FROM usuario WHERE email LIKE '$email' LIMIT 1";
    $busca = mysqli_query($_SG['link'], $sql_busca);

    if (mysqli_num_rows($busca) > 0) {
        echo "<script>alert('Esse aluno já foi cadastrado, fique atento para não duplicar um aluno.')</script>";
        echo '<script>location.href="../cadastrar/"</script>';
        exit;
    }
}

if (!empty($rg)) {

    $sql_busca = "SELECT rg FROM usuario WHERE rg LIKE '$rg' LIMIT 1";
    $busca = mysqli_query($_SG['link'], $sql_busca);

    if (mysqli_num_rows($busca) > 0) {
        echo "<script>alert('Esse aluno já foi cadastrado, fique atento para não duplicar um aluno.')</script>";
        echo '<script>location.href="../cadastrar/"</script>';
        exit;
    }
}


$sql_busca = "SELECT ra FROM alunos WHERE ra LIKE '$ra' LIMIT 1";
$busca = mysqli_query($_SG['link'], $sql_busca);

if (mysqli_num_rows($busca) > 0) {
    echo "<script>alert('Esse aluno já foi cadastrado, fique atento para não duplicar um aluno.')</script>";
    echo '<script>location.href="../cadastrar/"</script>';
    exit;
}



// $query_usuario = "INSERT INTO usuario (nome, email, tel, h, id_escola, camiseta, serie, senha, data_nasc, data_ingresso, data_cadastro, rg, ra, cel, end, sexo, etnia, mae, pai, face) VALUES ('" . $_POST['nome_cadastrar'] . "','" . $email . "','" . $_POST['tel'] . "','1','" . $_POST['escola1'] . "','" . $_POST['camiseta'] . "','" . $_POST['serie'] . "','$senha','" . $_POST['data'] . "','".$_POST['data_ingresso']."','" . date("d/m/Y") . "','" . $_POST['rg'] . "','" . $_POST['ra'] . "','" . $_POST['cel'] . "','" . $_POST['end'] . "','" . $_POST['sexo'] . "','" . $_POST['etnia'] . "','" . $_POST['mae'] . "','" . $_POST['pai'] . "','" . $_POST['face'] . "');";
$query_usuario = "INSERT INTO usuario (nome, cpf, email, h, senha, data_nasc, data_cadastro, rg, cel, end, sexo, face) VALUES ('" . $_POST['nome_cadastrar'] . "', , '" .$cpf. "','" . $email .  "','1','$senha','" . $_POST['data'] . "','" . date("d/m/Y") . "','" . $_POST['rg'] . "','" . $_POST['cel'] . "','" . $_POST['end'] . "','" . $_POST['sexo'] . "','" . $_POST['face'] . "');";


if (mysqli_query($_SG['link'], $query_usuario)) {

    $id_usuario = mysqli_insert_id($_SG['link']);

    /* $query_aluno = "INSERT INTO alunos(id_usuario, ra, id_escola, id_escola_em, serie, mae, pai, data_ingresso) VALUES ($id_usuario, '" . $_POST['ra'] . "', '" . $_POST['escola1'] . "', 0, '" . $_POST['serie'] . "', '" . $_POST['mae'] . "', '" . $_POST['pai'] . "', '" . $ano_ingresso . "')"; */
    $query_aluno = "INSERT INTO alunos(id_usuario, ra, cpf, id_escola, id_escola_em, serie, pai, data_ingresso, email_pai, cel_pai, instagram) VALUES ($id_usuario, '" . $_POST['ra'] . "', , '" . $_POST['cpf'] . "','" . $_POST['escola1'] . "', 0, '" . $_POST['serie'] . "', '" . $_POST['pai'] . "', '" . $ano_ingresso . "', '" . $email_pai . "', '" . $cel_pai . "', '" . $instagram . "')";

    if (mysqli_query($_SG['link'], $query_aluno)) {


        sendEmails($id_usuario);

                 $erro = 0;
                 /*
        for ($i = 1; $i <= 11; $i++) {
            if (isset($_POST['r'. $i])){
                if (is_array($_POST['r'. $i])){
                    $resposta = implode(',', $_POST['r'. $i]);
                }else{
                $resposta = $_POST['r'. $i];
                }
            }else{
                $resposta ='';
            }
            $query = "INSERT INTO respostase (usuario_id, pergunta_num, resposta) VALUES ('$id_usuario','$i','$resposta');";
            if (!mysqli_query($_SG['link'], $query)){
                $erro = 1;
            }
        } */

        $id_usuario_afetado = $_SESSION['usuarioID'];
        $time = date("Y-m-d H:i:s");


        $res = mysqli_query($_SG['link'], "INSERT INTO log_sistema_alunos (id_usuario, id_usuario_afetado, acao, date_time) VALUES ('$id_usuario_afetado','$id_usuario', 'cadastrar', '$time')");

        if (!$res)
            $erro = 1;

        if ($erro != 1) {
            echo "<script>alert('Aluno cadastrado com sucesso!')</script>";
            echo '<script>location.href="../cadastrar/"</script>';
        } else {
            echo "<span class='ajuda_user'>Aluno não cadastrado!</span><br>";
            echo mysqli_error($_SG['link']);
        }
    } else {
        echo "<span class='ajuda_user'>Aluno não cadastrado!</span><br>";
        echo mysqli_error($_SG['link']);
    }
} else {
    echo "<span class='ajuda_user'>Aluno não cadastrado!</span><br>";
    echo mysqli_error($_SG['link']);
}
