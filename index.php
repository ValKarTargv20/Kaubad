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
if(isset($_REQUEST["kaubagrupp_lisamine"])&& isAdmin()) {
    addGroup($_REQUEST["kaubagrupp"]);
    header("Location: index.php");
    exit();
}
if(isset($_REQUEST["kauba_lisamine"])) {
    addItem($_REQUEST["kaubanimi"],$_REQUEST["hind"], $_REQUEST["kaubagrupp_id"]);
    header("Location: index.php");
    exit();
}
if(isset($_REQUEST["delete"])&& isAdmin()) {
    deleteItem($_REQUEST["delete"]);
}
if(isset($_REQUEST["save"])) {
    saveItem($_REQUEST["changed_id"], $_REQUEST["kaubanimi"], $_REQUEST["hind"], $_REQUEST["kaubagrupp_id"]);
}
$goods = groupData($sort, $search_term);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Kaubad ja kaubagruppid</title>
</head>
<body>
<header class="header">
    <p class="text"><?=$_SESSION["kasutaja"]?> on sisse logitud</p>
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout" class="sub">
    </form>
    <div class="container">
        <h1 class="text">Tabelid | Kaubad ja kaubagruppid</h1>
    </div>
</header>
<main class="main">
    <div class="container">
        <form action="index.php">
            <input type="text" name="search_term" placeholder="Otsi...">
        </form>
    </div>
    <?php if(isset($_REQUEST["edit"])): ?>
        <?php foreach($goods as $product): ?>
            <?php if($goods->id == intval($_REQUEST["edit"])): ?>
                <div class="container">
                    <form action="index.php">
                        <input type="hidden" name="changed_id" value="<?=$product->id ?>"/>
                        <input type="text" name="kaubanimi" value="<?=$product->kaubanimi?>">
                        <input type="text" name="hind" value="<?=$product->hind?>">
                        <?php echo createSelect("SELECT id, kaubagrupp FROM kaubagrupid", "id"); ?>
                        <a title="Katkesta muutmine" class="cancelBtn" href="index.php" name="cancel">X</a>
                        <input type="submit" name="save" value="&#10004;">
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="container">
        <table>
            <thead>
            <tr>
                <th><a href="index.php?sort=Id" class="text">Id</a></th>
                <th><a href="index.php?sort=kaubanimi" class="text">Kaubanimi</a></th>
                <th><a href="index.php?sort=hind" class="text">Hind</a></th>
                <th><a href="index.php?sort=kaubagrupp" class="text">Kaubagrupp</a></th>
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
                    <td>
                        <?php if(isAdmin()){?>
                        <a title="Kustuta inimene" class="deleteBtn" href="index.php?delete=<?=$product->id?>"
                           onclick="return confirm('Oled kindel, et soovid kustutada?');">X</a>
                        <a title="Muuda inimest" class="editBtn" href="index.php?edit=<?=$product->id?>">&#9998;</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(isAdmin()){?>
        <form action="index.php">
            <h2 class="text">Kaubagrupi lisamine:</h2>
            <dl>
                <dt class="text">Kaubagrupp nimi:</dt>
                <dd><input type="text" name="kaubagrupp" placeholder="Sisesta nimi..."></dd>
                <input type="submit" name="kaubagrupp_lisamine" value="Lisa kaubagrupp" class="sub">
            </dl>
        </form>
        <?php } ?>
        <form action="index.php">
            <h2 class="text">Kauba lisamine:</h2>
            <dl>
                <dt class="text">Kaubanimi:</dt>
                <dd><input type="text" name="kaubanimi" placeholder="Sisesta kaubanimi..."></dd>
                <dt class="text">Hind:</dt>
                <dd><input type="text" name="hind" placeholder="Sisesta hind..."></dd>
                <dt class="text">Kaubagrupp:</dt>
                <dd><?php
                    echo createSelect("SELECT id, kaubagrupp FROM kaubagrupid", "kaubagrupp_id");
                    ?></dd>
                <input type="submit" name="kauba_lisamine" value="Lisa kauba" class="sub">
            </dl>
        </form>
    </div>
</main>
</body>
</html>

<?php
/*CREATE TABLE kaubagrupid(
            id int PRIMARY KEY AUTO_INCREMENT,
            kaubagrupp varchar(100)
        );
          CREATE TABLE kaubad(
            id int PRIMARY KEY AUTO_INCREMENT,
            kaubanimi varchar(100),
            hind int,
            kaubagrupp_id int,
            FOREIGN KEY (kaubagrupp_id) REFERENCES kaubagrupid(id)
        );*/

?>