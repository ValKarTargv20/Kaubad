<?php
require("conf.php");
global $connection;
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login.php');
    exit();
}

if(!empty($_REQUEST["vormistamine_id"])&& isAdmin()){
    $kask=$connection->prepare(
        "UPDATE jalgrattaeksam SET luba=1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["vormistamine_id"]);
    $kask->execute();
}
$kask=$connection->prepare(
    "SELECT id, eesnimi, perekonnanimi, teooriatulemus, 
	     slaalom, ringtee, t2nav, luba FROM jalgrattaeksam;");
$kask->bind_result($id, $eesnimi, $perekonnanimi, $teooriatulemus,
    $slaalom, $ringtee, $t2nav, $luba);
$kask->execute();

function asenda($nr){
    if($nr==-1){return ".";} //tegemata
    if($nr== 1){return "korras";}
    if($nr== 2){return "ebaõnnestunud";}
    return "Tundmatu number";
}
?>
<!doctype html>
<html>
<head>
    <title>Jalgrattaeksam</title>
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
<h1>Eksamitulemused</h1>
<table>
    <tr>
        <th>Eesnimi</th>
        <th>Perekonnanimi</th>
        <th>Teooriaeksam</th>
        <th>Slaalom</th>
        <th>Ringtee</th>
        <th>Tänavasõit</th>
        <th>Lubade väljastus</th>
    </tr>
    <?php
    while($kask->fetch()){
        $asendatud_slaalom=asenda($slaalom);
        $asendatud_ringtee=asenda($ringtee);
        $asendatud_t2nav=asenda($t2nav);
        $loalahter=".";
        if ($_SESSION["onAdmin"] == 1) {
            if ($luba == 1) {
                $loalahter = "Väljastatud";
            }
            if ($luba == -1 and $t2nav == 1) {
                $loalahter = "<a href='?vormistamine_id=$id'>Vormista load</a>";
            }
            echo "
		        <tr>
		        <td>$eesnimi</td>
			    <td>$perekonnanimi</td>
			    <td>$teooriatulemus</td>
			    <td>$asendatud_slaalom</td>
			    <td>$asendatud_ringtee</td>
			    <td>$asendatud_t2nav</td>
			    <td>$loalahter</td>
			    </tr>
		    ";
        }
        else {
            if ($luba == 1) {
                $loalahter = "Väljastatud";
            }
            echo "
		     <tr>
			   <td>$eesnimi</td>
			   <td>$perekonnanimi</td>
			   <td>$teooriatulemus</td>
			   <td>$asendatud_slaalom</td>
			   <td>$asendatud_ringtee</td>
			   <td>$asendatud_t2nav</td>
			   <td>$loalahter</td>
			 </tr>
		   ";
        }
    }
    ?>
</table>
</div>
</body>
</html>