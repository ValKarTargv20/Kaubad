<?php
require ('conf.php');

function groupData($sort_by = "kaubanimi", $search_term = "") {
    global $connection;
    $sort_list = array("kaubanimi", "hind", "kaubagrupp");
    if(!in_array($sort_by, $sort_list)) {
        return "Seda tulpa ei saa sorteerida";
    }
    $request = $connection->prepare("SELECT kaubad.id, kaubanimi, hind, kaubagrupid.kaubagrupp
    FROM kaubad, kaubagrupid 
    WHERE kaubad.kaubagrupp_id = kaubagrupid.id 
    AND (kaubanimi LIKE '%$search_term%' OR hind LIKE '%$search_term%' OR kaubagrupp LIKE '%$search_term%')
    ORDER BY $sort_by");
    $request->bind_result($id, $kaubanimi, $hind, $kaubagrupp);
    $request->execute();
    $data = array();
    while($request->fetch()) {
        $product = new stdClass();
        $product->id = $id;
        $product->kaubanimi = htmlspecialchars($kaubanimi);
        $product->hind = htmlspecialchars($hind);
        $product->kaubagrupp = $kaubagrupp;
        array_push($data, $product);
    }
    return $data;
}

function createSelect($query, $name) {
    global $connection;
    $query = $connection->prepare($query);
    $query->bind_result($id, $data);
    $query->execute();
    $result = "<select name='$name'>";
    while($query->fetch()) {
        $result .= "<option value='$id'>$data</option>";
    }
    $result .= "</select>";
    return $result;
}

function addGroup($group_name) {
    global $connection;
    $query = $connection->prepare("INSERT INTO kaubagrupid (kaubagrupp)
    VALUES (?)")or die(mysqli_error());
    $query->bind_param("s", $group_name);
    $query->execute();
    /*if(mysql_affected_rows() == 0)
    {
        echo mysql_error();
    }*/
}

function addItem($item_name, $item_price, $group_id) {
    global $connection;
    $query = $connection->prepare("INSERT INTO kaubad (kaubanimi, hind, kaubagrupp_id)
    VALUES (?, ?, ?)");
    $query->bind_param("sid", $item_name, $item_price, $group_id);
    $query->execute();
}

function deleteItem($item_id) {
    global $connection;
    $query = $connection->prepare("DELETE FROM kaubad WHERE id=?");
    $query->bind_param("i", $item_id);
    $query->execute();
}

function saveItem($item_id, $item_name, $item_price, $group_id) {
    global $connection;
    $query = $connection->prepare("UPDATE kaubad
    SET kaubanimi=?, hind=?, kaubagrupp_id=?
    WHERE kaubad.id=?");
    $query->bind_param("siii", $item_name, $item_price, $group_id, $item_id);
    $query->execute();
}

?>
