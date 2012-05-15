<?php
/**
 * Created by JetBrains PhpStorm.
 * User: breathless
 * Date: 15.05.12
 * Time: 3:13
 * To change this template use File | Settings | File Templates.
 */

    $cl = new SphinxClient();
    $cl->setServer( "localhost", 9312 );

    // Собственно поиск
    $cl->setMatchMode( SPH_MATCH_ANY  ); // ищем хотя бы 1 слово из поисковой фразы
    $result = $cl->query("Сидоркина"); // поисковый запрос


    var_dump($result);