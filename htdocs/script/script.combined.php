<?php

header('Content-Type: text/javascript; charset=utf-8');

if(isset($_GET['minify']) || !file_exists('generated/'.basename(__FILE__))) {
    set_include_path(__DIR__);

    //include('../includes/minify.transit.inc.php');
    //ob_start(/*"minify"*/);

    include 'utils.transit.js';
    include 'polyShims.js';

    /*echo $out = minify(ob_get_clean());
    file_put_contents('generated/'.basename(__FILE__), $out);*/
} else
    include 'generated/'.basename(__FILE__);
