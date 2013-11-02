<?php

require_once("includes.php");

if (isset($_GET["name"]) && isset($_GET["mail"]) && isset($_GET["pw"])) {
    $lusername = strtolower($_GET["name"]);
    $db = openDB();
    $stmt = $db->prepare("Select * from " . $tbl_prefix . "users WHERE username = ?");
    $stmt->execute(array($lusername));
    if ($stmt->rowCount() == 0) {
        
        $pwhash = getPWHash($_GET["pw"]);
        $email = $_GET["mail"];
        $db->prepare("INSERT INTO ".$tbl_prefix."users (username, password, email) VALUES(?, ?, ?)")
       ->execute(array($lusername, $pwhash, $email));
    $db = null;
    echo "Registered";
    }else{
        echo "Failed";
    }
}
?>
