<?php
require("conf.php");
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login.php');
    exit();
}

require("functions.php");
$sort = "kaubanimi";
$search_term = "";
if(isset($_REQUEST["sort"])) {
    $sort = $_REQUEST["sort"];
}
if(isset($_REQUEST["search_term"])) {
    $search_term = $_REQUEST["search_term"];
}
$goods = groupData($sort, $search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Inimesed ja maakonnad</title>
</head>
<body>
<header class="header">
    <p>Vaatamine on sisse logitud</p>
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
    <div class="container">
        <h1>Kaubad ja kaubagruppid</h1>
    </div>
</header>
<main class="main">
    <div class="container">
        <form action="vaata.php">
            <input type="text" name="search_term" placeholder="Otsi...">
        </form>
    </div>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th><a href="index.php?sort=Id">Id</a></th>
                <th><a href="index.php?sort=kaubanimi">Kaubanimi</a></th>
                <th><a href="index.php?sort=hind">Hind</a></th>
                <th><a href="index.php?sort=kaubagrupp">Kaubagrupp</a></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($goods as $product): ?>
            <tr>
                <td><strong><?=$product->id ?></strong></td>
                <td><?=$product->kaubanimi ?></td>
                <td><?=$product->hind ?></td>
                <td><?=$product->kaubagrupp ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
            </tr>
            </thead>
        </table>

    </div>
</main>
</body>
</html>

<?php
/*CREATE TABLE maakond(
id int not null PRIMARY KEY AUTO_INCREMENT,
maakonna_nimi varchar(100),
maakonna_keskus varchar(100)
);
 * CREATE TABLE inimene(
    id int not null PRIMARY KEY AUTO_INCREMENT,
    eesnimi varchar(100),
    perekonnanimi varchar(100),
    maakonna_id int,
    FOREIGN KEY (maakonna_id) REFERENCES maakond(id)
);*/

?>