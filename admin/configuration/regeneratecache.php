<?php
    include "../configurations.php";
    require_once $config->directory["library"] . "/functions/courses.php";
    updateAllCoursesCache();
    echo $tmp->getSuccess($lang["admin"]["saved"]);
?>