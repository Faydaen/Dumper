<?php

namespace Faydaen;

class Line {
    public const COLOR_DEFAULT = 'default';
    public const COLOR_NULL = 'null';
    public const COLOR_NUMERIC = 'numeric';
    public const COLOR_STRING = 'string';
    public const COLOR_BOOL = 'bool';
    public const COLOR_CLASS_ATTRIBUTE = 'class_attribute';
    public const COLOR_SQL = 'sql';
    public const COLOR_CLASS_NAME = 'class_name';
    public const COLOR_UNKNOWN_TYPE = 'unknown_type';

    public ?Subline $value;
    public ?Subline $key;
    public ?Subline $separator;
    public ?Subline $bracket;
    public int $tabs;
    public ?Subline $signOnEnd;

    public function __construct
    (
        $tabs = 0,
        ?Subline $key = null,
        ?Subline $separator = null,
        ?Subline $value = null,
        ?Subline $bracket = null,
        ?Subline $signOnEnd = null
    ) {
        $this->value = $value;
        $this->key = $key;
        $this->separator = $separator;
        $this->tabs = $tabs;
        $this->signOnEnd = $signOnEnd;
        $this->bracket = $bracket;
    }
}
