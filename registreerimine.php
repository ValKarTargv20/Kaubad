<?php
require_once("conf.php");
global $connection;
session_start();
if(isSet($_REQUEST["sisestusnupp"])){
    $kask=$connection->prepare(
        "INSERT INTO jalgrattaeksam(eesnimi, perekonnanimi) VALUES (?, ?)");
    $kask->bind_param("ss", $_REQUEST["eesnimi"], $_REQUEST["perekonnanimi"]);
    $kask->execute();
    $connection->close();
    header("Location: $_SERVER[PHP_SELF]?lisatudeesnimi=$_REQUEST[eesnimi]");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <title>Kasutaja registreerimine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css" class="active"></style>
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
<?php
if(isSet($_REQUEST["lisatudeesnimi"])){
    echo "Lisati $_REQUEST[lisatudeesnimi]";
}
?>
<form action="?">
    <dl>
        <dt>Eesnimi:</dt>
        <dd><input type="text" name="eesnimi" /></dd>
        <dt>Perekonnanimi:</dt>
        <dd><input type="text" name="perekonnanimi" /></dd>
        <dt><input type="submit" name="sisestusnupp" value="sisesta" /></dt>
    </dl>
</form>
</div>
</body>
</html>
