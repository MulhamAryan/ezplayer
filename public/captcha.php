<?php
    include "../config.php";
    include $config->directory["library"] . "/captcha.php";

    $captcha = new Captcha();
    $_SESSION["captcha_code"] = $captcha->getCode();
    $captcha->render();