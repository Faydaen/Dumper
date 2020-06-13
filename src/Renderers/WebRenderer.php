<?php


namespace Faydaen\Renderers;

use Faydaen\Line;

class WebRenderer
{
    /**
     * @var Line[] $variables
     */
    private $variables;

    private $comment;

    /**
     * @var string[] $variables
     */
    private $colors;

    public function __construct($variables, $comment = '')
    {
        $this->variables = $variables;
        $this->comment = $comment;

        $this->colors = [
            Line::COLOR_DEFAULT => '',
            Line::COLOR_NULL => 'color:#C04E19',
            Line::COLOR_NUMERIC => 'color:#0000FF',
            Line::COLOR_STRING => 'color:#D67F1D',
            Line::COLOR_BOOL => 'color:#C04E19',
            Line::COLOR_CLASS_ATTRIBUTE => '',
            Line::COLOR_SQL => 'font-weight:bold;',
            Line::COLOR_CLASS_NAME => '',
            Line::COLOR_UNKNOWN_TYPE => 'color:#997229',
        ];
    }

    public function render()
    {
        $result = '';
        $result .= $this->renderComment();
        $result .= $this->renderList();
        return $result;
    }

    public function renderComment() {
        $comment = '';
        if ($this->comment != ''){
            $comment = '<b>'.$this->comment.'</b><br>';
        }
        return $comment;
    }

    public function renderList(){
        $result = '';
        foreach ($this->variables as $variable){

            $result .= $this->tabs($variable->tabs);

            $result .= $this->colorPrint($variable->key);
            $result .= $this->colorPrint($variable->separator);
            $result .= $this->colorPrint($variable->value);
            $result .= $this->colorPrint($variable->bracket);
            $result .= $this->colorPrint($variable->signOnEnd);

            $result .= '<br>';
            $result .= PHP_EOL;
        }

        return $result;
    }

    private function colorPrint($subline){
        $result = '';
        if (!is_null($subline)){
            $result .= $this->color_start($subline->color);
            $result .= $subline->text;
            $result .= $this->color_end($subline->color);
        }
        return $result;
    }



    private function color_start($color) {
        if (is_null($color)){
            return '';
        }
        $style = '"'.$this->colors[$color].'"';
        return "<span style=$style>";
    }

    private function color_end($color) {
        if (is_null($color)){
            return '';
        }
        return '</span>';
    }

    private function tabs($tabsNum)
    {
        $tabs = '';
        for ($i = 0; $i < $tabsNum; $i++) {
            $tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $tabs;
    }
}
