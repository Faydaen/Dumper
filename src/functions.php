<?php

function dd($entity, $comment = '') {
    $dumper = new \Faydaen\Dumper();
    $dumper->dump($entity, $comment);
    die();
}

function ll($entity, $comment = '') {
    $dumper = new \Faydaen\Dumper();
    $dumper->dump($entity, $comment);
    echo '<hr>';
}
