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
    $paring="SELECT * FROM kasutajad WHERE nimi='$login' AND parool='$krypt'";
    $yhendus=mysqli_query($connection,$paring);
    if(mysqli_num_rows($yhendus)==1){
        $_SESSION['tuvastamine']='Tere!';
        if($login=='valeria'){
            header('Location: index.php');
        }
        if($login=='kasutaja1'){
            header('Location: tava.php');
        }
        if($login=='kasutaja2'){
            header('Location: vaata.php');
        }
    }
    else{
        echo "Kasutaja või parool on valed";
    }

    /*if ($login='admin' && $pass='admin'){
        $_SESSION['tuvastamine']='Tere!';
        header('Location: index.php');
    }*/
}
?>
<h1>Login vorm</h1>
<table>
    <form action="" method="post">
    <tr>
        <td>Kasutaja nimi:</td>
        <td>
            <input type="text" name="login" placeholder="Kasutajanimi">
        </td>
    </tr>
        <tr>
        <td>Salasõna:</td>
        <td>
            <input type="password" name="pass">
        </td>
    </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Logi sisse">
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