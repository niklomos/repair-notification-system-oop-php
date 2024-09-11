<?php

    include_once("class/Database.php");
    include_once("class/Account.php");

    $database = new Database;
    $db = $database->getConnection();

    $account = new Account($db);
    $account->logOut();

?>