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
    "bandeiras_cartoes" => array("Alelo", "American Express", "Aura", "Banescard", "Banricompras", "Cabal", "Diners Club", "Discover Network", "Elo", "Good Card", "GreenCard", "Hiper", "Hipercard", "JCB", "MasterCard", "Redeshop", "Sodexo", "Sorocred", "Ticket Serviços", "Visa", "VR Benefícios", "Outro"),
    "mail" => array(
        "host" => "",
        "port" => 465,
        "user" => "",
        "password" => ""
    )
);
