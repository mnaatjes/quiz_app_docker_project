<?php

    require_once("bootstrap.php");
    /**
     * Connect to DB
     */
    use mnaatjes\DataAccess\Database;
    use mnaatjes\DataAccess\ORM;
    use mnaatjes\DataAccess\utils\TestModel;
    use mnaatjes\DataAccess\utils\TestRepository;

    $db     = Database::getInstance();
    $orm    = new ORM($db);

    $repo   = new TestRepository($orm);
    //$record = $repo->findById(23);
    //var_dump($record);

    //$records = $repo->all();
    //var_dump($records[10]->getDescription());
    //var_dump($record->getDescription());
    /*
    $model = $repo->save(
        $model = new TestModel([
            //"id" => 4,
            "name" => "A New Car",
            "description" => "Helps you poop by strapping you in",
            "price" => 99.99,
            "stock_quantity" => 69,
            "created_at" => "2025-08-14 13:52:03",
            "updated_at" => "2025-08-14 23:52:03",
    ]));
    var_dump($model);
    */

    //$results = $repo->findBy(["id > 300"]);
    /*
    $results = $repo->findWith([
        "table" => "products",
        "ON" => "orders.product_id = products.id"
    ], ["*"], ["orders.product_id" => 1]);
    var_dump($results);
    */







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
    //var_dump($repo);

    /*
    $model = new TestModel([
        "id"    => 45,
        "sku"   => "adsjkjkl3j4kljk2jk3343",
        "date"  => "2023-12-12 04:45:12",
        "text"  => "Lorem Ipsum",
        "hamSandwich" => "Apollo"
    ]);
    */
    //var_dump($model);
    //$model->setHamSandwich("Apollo is a Sploooty Boy");
    //var_dump($model->getHamSandwich());
    //var_dump($prop_value);
    //var_dump($model->toArray());
    //$arr = $model->toArray();
/*
    $model = $repo->hydrate([
        "id"    => 45,
        "sku"   => "adsjkjkl3j4kljk2jk3343",
        "date"  => "2023-12-12 04:45:12",
        "text"  => "Lorem Ipsum",
        "hamSandwich" => "Apollo"
    ]);
*/  


?>