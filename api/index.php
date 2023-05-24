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

        return var_dump($status);
    }
);

// update the movie
$app->put(
    '/products',
    function ($id) use ($app) {
        $product = $app->request->getJsonRawBody();
        $product = (array)$product;
        // $product= $product[0];
        // print_r($product[0]);die;
        // print_r($product);
        // die;
        $response = $this->mongo->products->updateOne(['id' => $id], ['$set' => ['name' => $product->name, 'price' => $product->price, 'id' => $product->id]]);
        // var_dump($product);
        // return $response;
    }
);


$app->handle($_SERVER['REQUEST_URI']);
