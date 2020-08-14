<?php

if (!function_exists('dd')){
    function dd($entity, $comment='') {
        $dumper = new \Faydaen\Dumper();
        $dumper->dump($entity, $comment);
        die();
    }
}

if (!function_exists('dl')){
    function dl($entity, $comment='') {
        $dumper = new \Faydaen\Dumper();
        $dumper->dump($entity, $comment);
        echo '<hr>';
    }
}
