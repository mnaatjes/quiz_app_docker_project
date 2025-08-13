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
    //$last_id = $orm->create("users", DataGenerator::getUser());
    //$row_count = $orm->update("users", ["username" => "asdasdsadsdadwewewew"], ["id" => 66]);
    //$row_count = $orm->delete("users", ["id" => 64]);
    //$count = $orm->count("users", [["id", ">", 55]]);
    //$has_column = $orm->columnExists("users", "created_at");
    //var_dump($has_column);

    
?>