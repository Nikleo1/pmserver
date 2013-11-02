<?php

require_once("includes.php");

if (isset($_GET["name"])) {
    $lusername = strtolower($_GET["name"]);
    $db = openDB();
    $stmt = $db->prepare("Select * from " . $tbl_prefix . "users WHERE username = ?");
    $stmt->execute(array($lusername));
    if ($stmt->rowCount() > 0) {
        echo "true";
    }else{
        echo "false";
    }
    
    $db = null;
}
?>
