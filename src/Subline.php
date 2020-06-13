<?php

namespace Faydaen;

class Subline {

    public $text;
    public $color;

    public function __construct($text, $color = null)
    {
        $this->text = $text;
        $this->color = $color;
    }
}