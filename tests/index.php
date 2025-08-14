<?php

    /**
     * Connect to DB
     */
    require_once("bootstrap.php");

use mnaatjes\DataAccess\BaseRepository;
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

    //$records = $orm->query("SELECT u.username, p.name AS product_name, o.total_amount FROM users u INNER JOIN orders o ON u.id = o.user_id INNER JOIN products p ON o.product_id = p.id LIMIT 10");
    //var_dump($records);
    /*
    $records = $orm->join("orders", [
        ["users ON orders.user_id = users.id"],
        [
            "table" => "products",
            "ON" => "orders.product_id = products.id",
        ]
    ], [
        "orders.id",
        "orders.order_date",
        "orders.total_amount",
        "users.username"
    ], ["orders.order_date" => "DESC"], 2);

    var_dump($records);
    */
    //$all =  $orm->all("products");
    //var_dump(count($all));

    //$first = $orm->first("products");
    //var_dump($first);
    /*
    $testA = $orm->query("SELECT * FROM users WHERE id = ?", [12]);
    $testB = $orm->findOne("users", ["id" => 12]);
    $testC = $orm->find("users", ["id = 12"]);
    var_dump($testA[0]["username"]);
    var_dump($testB["username"]);
    var_dump($testC[0]["username"]);
    */
    //$fail = $orm->findOrFail("users", [["id", "=", 12]]);
    //var_dump($fail)

    //$create = $orm->create("users", DataGenerator::getRandomUser());
    //var_dump($create);

    //$one = $orm->find("users", ["id > 18", "id <= 20"]);
    //var_dump($one);

    $repo = new BaseRepository($orm);
    var_dump($repo);
?>