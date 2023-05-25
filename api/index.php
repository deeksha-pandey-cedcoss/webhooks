<?php

use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Collection\Manager;

define("BASE_PATH", __DIR__);

require_once(BASE_PATH . '/vendor/autoload.php');

// Use Loader() to autoload our model
$container = new FactoryDefault();

$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client(
            'mongodb+srv://deekshapandey:Deeksha123@cluster0.whrrrpj.mongodb.net/?retryWrites=true&w=majority'
        );

        return $mongo->hook2;
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
            "name" => $product->name,
            "price" => $product->price,
            "id" => $product->id
        ];
        $status = $collection->insertOne($arr);

        return $status;
    }
);

// update the movie
$app->put(
    '/products/{id:[0-9]+}',
    function ($id) use ($app) {
        $product = json_decode(file_get_contents('php://input'));
        $response = $this->mongo->products->updateOne(
            ['id' => $id],
            ['$set' => ['name' => $product[0]->name, 'price' => $product[0]->price]]
        );
        return $response;
    }
);


$app->handle($_SERVER['REQUEST_URI']);
