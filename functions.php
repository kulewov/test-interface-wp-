<?php

require_once __DIR__ . '/inc/classes/Tests.php';

/** Создаем экземпляр Tests */
if (isset($_GET[\SoV\Tests::MAIN_GET_PARAM])) {
    new \SoV\Tests();
}