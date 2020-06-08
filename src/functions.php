<?php

if (!function_exists('dd')){
    function dd($entity = null, $comment = '')
    {
        (new Faydaen\Dumper())->p($entity, $comment);
        die();
    }
}