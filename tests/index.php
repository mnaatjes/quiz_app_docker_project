<?php

    /**
     * Connect to DB
     */
    require_once("bootstrap.php");
    use mnaatjes\DataAccess\Database;
    use mnaatjes\DataAccess\ORM;
    use mnaatjes\DataAccess\utils\DataGenerator;

    $db = Database::getInstance();

    $orm = new ORM($db);

    //var_dump(json_encode($orm->find("users"), JSON_PRETTY_PRINT));
    //var_dump($orm->showTables());
    $orm->create("users", DataGenerator::getUser());
?>