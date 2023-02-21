<?php

mb_internal_encoding("UTF-8");
session_start();

function autoloadFunkce(string $trida) : void
{
    if (preg_match('/Kontroler$/', $trida)) {
        require("kontrolery/" . $trida . ".php");
    }
    else {
        require("modely/" . $trida . ".php");
    }
}
spl_autoload_register("autoloadFunkce");

Db::pripoj("127.0.0.1", "root", "", "evidence_pojisteni");

$smerovac = new SmerovacKontroler();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));

$smerovac->vypisPohled();
