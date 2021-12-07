<?php
require_once("conf.php");
global $connection;
session_start();
if(!empty($_REQUEST["korras_id"])){
    $kask=$connection->prepare(
        "UPDATE jalgrattaeksam SET t2nav=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["korras_id"]);
    $kask->execute();
}
if(!empty($_REQUEST["vigane_id"])){
    $kask=$connection->prepare(
        "UPDATE jalgrattaeksam SET t2nav=2 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["vigane_id"]);
    $kask->execute();
}
$kask=$connection->prepare("SELECT id, eesnimi, perekonnanimi 
     FROM jalgrattaeksam WHERE slaalom=1 AND ringtee=1 AND t2nav=-1");
$kask->bind_result($id, $eesnimi, $perekonnanimi);
$kask->execute();
?>
<!doctype html>
<html>
<head>
    <title>Tänavasõit</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php
if($_SESSION["onAdmin"] == 1) {
    include('navigation.php');
}
if ($_SESSION["onAdmin"] == 0) {
    include('nav.php');
}
?>
<div class="container">
    <header class="header">
        <p><?=$_SESSION["kasutaja"]?> on sisse logitud</p>
        <form action="logout.php" method="post">
            <input type="submit" value="Logi välja" name="logout" class="sub">
        </form>
    </header>
<h1>Tänavasõit</h1>
<table>
    <?php
    while($kask->fetch()){
        echo "
		    <tr>
			  <td>$eesnimi</td>
			  <td>$perekonnanimi</td>
			  <td>
			    <a href='?korras_id=$id'>Korras</a>
			    <a href='?vigane_id=$id'>Ebaõnnestunud</a>
			  </td>
			</tr>
		  ";
    }
    ?>
</table>
</div>
</body>
</html>
