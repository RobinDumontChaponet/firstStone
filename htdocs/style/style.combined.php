<?php

header('Content-Type: text/css; charset=utf-8');

if (isset($_GET['minify']) || !file_exists('generated/'.basename(__FILE__))) {
    //include('../includes/minify.transit.inc.php');
    //ob_start(/*"minify"*/);

    include 'base.part.css';
    include 'animations.css';
//     include 'header.part.css';
//     include 'wrapper.part.css';
    include 'forms.part.css';
//     include 'queries.part.css';

    /*echo $out = minify(ob_get_clean());
    file_put_contents('generated/'.basename(__FILE__), $out);*/
} else
    include 'generated/'.basename(__FILE__);
