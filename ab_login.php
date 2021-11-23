<?php
require("conf.php");
global $connection;
session_start();
/*if (!isset($_SESSION['tuvastamine'])) {
    header('Location: index.php');
    exit();
}*/
if (!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=htmlspecialchars(trim($_POST['login']));
    $pass=htmlspecialchars(trim($_POST['pass']));

    $sool='tavalinetext';
    $krypt=crypt($pass,$sool);
    //проверка, что пароль и логин есть в базе данных
    $paring="SELECT nimi, onAdmin, koduleht FROM kasutajad WHERE nimi=? AND parool=?";
    $kask=$connection->prepare($paring);
    $kask->bind_param("ss", $login, $krypt);
    $kask->bind_result($nimi, $onAdmin, $koduleht);
    $kask->execute();
    //$yhendus=mysqli_query($connection, $paring);
    //if(mysqli_num_rows($yhendus)==1){
    if ($kask->fetch()) {
        $_SESSION['tuvastamine'] = 'misiganes';
        $_SESSION['kasutaja'] = $nimi;
        $_SESSION['onAdmin'] = $onAdmin;
        if (isset($koduleht)) {
            header("Location: $koduleht");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
    else {
        echo "Kasutaja $login või parool on valed";
    }

    /*if ($login='admin' && $pass='admin'){
        $_SESSION['tuvastamine']='Tere!';
        header('Location: index.php');
    }*/
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="logstyle.css">
        <title>Kaubad ja kaubagruppid login vorm</title>
    </head>
    <body>
<h1 class="text">Login vorm</h1>
<table>
    <form action="" method="post">
    <tr>
        <td class="text">Kasutaja nimi:</td>
        <td>
            <input type="text" name="login" placeholder="Kasutajanimi">
        </td>
    </tr>
        <tr>
        <td class="text">Salasõna:</td>
        <td>
            <input type="password" name="pass">
        </td>
    </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Logi sisse" class="sub">
            </td>
        </tr>
    </form>
</table>
<?php
/*CREATE TABLE kasutajad(
    id int PRIMARY KEY AUTO_INCREMENT,
    nimi varchar (10),
    parool text)*/
    ?>