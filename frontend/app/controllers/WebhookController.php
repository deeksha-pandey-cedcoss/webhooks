<?php

use Phalcon\Mvc\Controller;

class WebhookController extends Controller
{
    public function indexAction()
    {
        // default action
    }
    public function webhookAction()
    {

        $collection = $this->mongo->webhook;

        $url = $_GET['url'];
        $event = $_GET['event'];
        $key =  $_GET['key'];


        $arr = [
            "url" => $url,
            "event" => $event,
            "key" => $key
        ];
        $status = $collection->insertOne($arr);
       
    }
}
