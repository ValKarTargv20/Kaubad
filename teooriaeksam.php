<?php
require_once("conf.php");
global $connection;
session_start();
if(!empty($_REQUEST["teooriatulemus"])){
    $kask=$connection->prepare(
        "UPDATE jalgrattaeksam SET teooriatulemus=? WHERE id=?");
    $kask->bind_param("ii", $_REQUEST["teooriatulemus"], $_REQUEST["id"]);
    $kask->execute();
}
$kask=$connection->prepare("SELECT id, eesnimi, perekonnanimi 
     FROM jalgrattaeksam WHERE teooriatulemus=-1");
$kask->bind_result($id, $eesnimi, $perekonnanimi);
$kask->execute();
?>
<!doctype html>
<html>
<head>
    <title>Teooriaeksam</title>
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
<h1>Registreerimine</h1>
<table>
    <?php
    while($kask->fetch()){
        echo "
		    <tr>
			  <td>$eesnimi</td>
			  <td>$perekonnanimi</td>
			  <td><form action=''>
			         <input type='hidden' name='id' value='$id' />
					 <input type='text' name='teooriatulemus' />
					 <input type='submit' value='Sisesta tulemus' />
			      </form>
			  </td>
			</tr>
		  ";
    }
    ?>
</table>
</div>
</body>
</html>