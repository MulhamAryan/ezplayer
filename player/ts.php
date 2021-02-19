<?php
    require "../config.php";
    require_once $config->directory["library"] . "/player.php";

    $player = new Player();
    echo $player->ts();
