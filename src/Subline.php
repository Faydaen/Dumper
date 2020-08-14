<?php

namespace Faydaen;

class Subline {

    public string $text;
    public ?string $color;

    public function __construct($text, $color = null)
    {
        $this->text = $text;
        $this->color = $color;
    }
}