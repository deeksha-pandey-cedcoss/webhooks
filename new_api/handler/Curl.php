<?php

namespace handler\curl;

class Curl
{

    public function hooks($url, $args)
    {

        $url = "http://172.18.0.9/products";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
        $response = curl_exec($ch);
        // print_r($response);
        return $response;
    }
    public function hooksupdate($url, $args, $id)
    {

        $url = "http://172.18.0.9/products/$id";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "put");
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        // print_r($response);
        return $response;
    }
}
