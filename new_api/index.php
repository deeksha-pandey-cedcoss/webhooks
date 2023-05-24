<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;
use handler\curl\Curl;

define("BASE_PATH", (__DIR__));
define("APP_PATH", BASE_PATH . '/new_api');

require_once(BASE_PATH . '/vendor/autoload.php');

require_once(BASE_PATH . '/handler/Curl.php');


$container = new FactoryDefault();

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://deekshapandey:Deeksha123@cluster0.whrrrpj.mongodb.net/?retryWrites=true&w=majority'
        );

        return $mongo->hooks;
    },
    true
);

$container->set(
    'collectionManager',
    function () {
        return new Manager();
    }
);
$app = new Micro($container);
// Define the routes here

// Retrieves all movies
$app->get(
    '/products',
    function () {
        $collection = $this->mongo->products;
        $list = $collection->find();
        $data = [];
        foreach ($list as $value) {
            $data[] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'price' => $value['price'],
                'oid' => $value['_id'],
            ];
        }
        echo json_encode($data);
    }
);

// Adds a new movie
$app->post(
    '/products',
    function () use ($app) {
        $product = $app->request->getJsonRawBody();
        $collection = $this->mongo->products;
        $arr = [
            "name" => $product[0]->name,
            "price" => $product[0]->price,
            "id" => $product[0]->id
        ];
        $status = $collection->insertOne($arr);
        if ($status->getInsertedCount() > 0) {
            $event = $this->mongo->webhook->findOne(["event" => "products.create"]);
            $c = Curl::hooks($event['url'], $arr);
        } else {
            echo "NOt inserted";
            die;
        }
    }
);

// update the movie
$app->put(
    '/products/{id:[0-9]+}',
    function ($id) use ($app) {

        $product = $app->request->getJsonRawBody();
        $response = $this->mongo->products->updateOne(
            ['id' => $id],
            ['$set' => ['name' => $product[0]->name, 'price' => $product[0]->price, 'id' => $product[0]->id]]
        );
        if ($response->getModifiedCount() > 0) {
            $event = $this->mongo->webhook->findOne(["event" => "products.update"]);
            $c = Curl::hooksupdate($event['url'], $product, $id);
        } else {
            echo "NOt updated";
            die;
        }
    }
);
$app->handle($_SERVER['REQUEST_URI']);
