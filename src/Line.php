<?php

namespace Faydaen;

class Line {
    const COLOR_DEFAULT = 'default';
    const COLOR_NULL = 'null';
    const COLOR_NUMERIC = 'numeric';
    const COLOR_STRING = 'string';
    const COLOR_BOOL = 'bool';
    const COLOR_CLASS_ATTRIBUTE = 'class_attribute';
    const COLOR_SQL = 'sql';
    const COLOR_CLASS_NAME = 'class_name';
    const COLOR_UNKNOWN_TYPE = 'unknown_type';

    public $value;
    public $key;
    public $separator;
    public $bracket;
    public $tabs;
    public $signOnEnd;

    public function __construct(
        $tabs = 0,
        $key = null,
        $separator = null,
        $value = null,
        $bracket = null,
        $signOnEnd = null
    ) {
        $this->value = $value;
        $this->key = $key;
        $this->separator = $separator;
        $this->tabs = $tabs;
        $this->signOnEnd = $signOnEnd;
        $this->bracket = $bracket;
    }
}
