<?php

if (!defined("PHB")) die();

$settings = array(

    "php_debug" => true,

    "mysql" => array(
        "host" => "localhost",
        "user" => "root",
        "pass" => "",
        "db" => "financas"
    ),

    "allowed_currencys" => array("BRL" => "R$", "USD" => "$", "EUR" => "€"),

    "mail" => array(
        "host" => "",
        "port" => 465,
        "user" => "",
        "password" => ""
    )

);
