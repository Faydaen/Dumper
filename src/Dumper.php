<?php

namespace Faydaen;

class Dumper
{
    private $tabs = 0;
    const NUMERIC_COLOR = '#0000FF';
    const STRING_COLOR = '#D67F1D';
    const BOOL_COLOR = '#C04E19';
    const NULL_COLOR = '#C04E19';

    private $arrayItem;
    private $deep = 0;

    const CLASS_NAME_DEEP = 1;

    private static function color_print($sting, $color)
    {
        return '<span style="color: ' . $color . '">' . $sting . '</span>';
    }

    public function is_flat_array($array)
    {
        return array_keys($array) === range(0, count($array) - 1);
    }

    private function print_empty_array()
    {
        echo '[ ]';
    }

    private function print_normal_array($entity)
    {
        $this->arrayItem = true;
        $count = count($entity);
        $i = 0;
        $this->deep++;

        $isFlat = $this->is_flat_array($entity);

        echo '[';
        echo '<br>' . PHP_EOL;
        $this->tabs++;

        foreach ($entity as $key => $value) {
            echo $this->tabs();
            if (!$isFlat) {
                if (is_numeric($key)) {
                    echo self::color_print($key, self::NUMERIC_COLOR);
                } else {
                    echo self::color_print("'" . $key . "'", self::STRING_COLOR);
                }
                echo ' => ';
            }

            $this->p($value);
            $i++;
            $this->print_comma($i, $count);
            echo '<br>' . PHP_EOL;
        }
        $this->tabs--;
        $this->deep--;
        echo $this->tabs();
        echo ']';
    }

    public function print_comma($i, $count)
    {
        if ($i < $count) {
            if ($this->arrayItem) {
                echo ',';
            }
        }
    }

    public function print_array($entity)
    {
        if (empty($entity)) {
            $this->print_empty_array();
        } else {
            $this->print_normal_array($entity);
            echo PHP_EOL;
        }
    }

    public function print_class_name($entity)
    {
        echo get_class($entity);
    }

    public function print_class($entity)
    {
        if ($this->deep <= self::CLASS_NAME_DEEP) {
            $this->print_deep_class($entity);
        } else {
            $this->print_class_name($entity);
        }
    }

    public function print_deep_class($entity)
    {
        $this->deep++;
        $this->print_class_name($entity);
        echo ' ';

        $this->arrayItem = true;
        $count = count(get_object_vars($entity));
        $i = 0;

        echo '{';
        echo '<br>' . PHP_EOL;
        $this->tabs++;

        foreach (get_object_vars($entity) as $key => $value) {
            echo $this->tabs();
            echo '$' . $key;
            echo ' = ';

            $this->p($value);
            $i++;
            $this->print_comma($i, $count);
            echo '<br>' . PHP_EOL;
        }
        $this->tabs--;
        $this->deep--;
        echo $this->tabs();
        echo '}';
    }

    public function print_yii_model(\yii\base\Model $entity)
    {
        echo get_class($entity);
        echo ': {';
        echo '<br>' . PHP_EOL;
        $this->tabs++;

        foreach (get_object_vars($entity) as $key => $value) {
            echo $this->tabs();
            echo '$' . $key;
            echo ' = ';
            $this->p($value);
            echo ',';
            echo '<br>' . PHP_EOL;
        }

        foreach ($entity->attributes() as $field) {
            echo $this->tabs();
            echo $field . ' => ';
            $this->p($entity->{$field});
            echo '<br>' . PHP_EOL;
        }

        $this->tabs--;
        echo $this->tabs();
        echo '}' . PHP_EOL;
    }

    public function print_numeric($entity)
    {


        if (gettype($entity) === 'double'){
            $entity = $entity . 'f';
        }

        echo self::color_print($entity, self::NUMERIC_COLOR);
    }

    public function print_string($entity)
    {



        $entity = str_replace(' ', '&nbsp;', $entity);
        $entity = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $entity);

        $string = "'" . $entity . "'";
        echo self::color_print($string, self::STRING_COLOR);
    }

    public function print_bool($entity)
    {
        $string = $entity ? 'true' : 'false';
        echo self::color_print($string, self::BOOL_COLOR);
    }

    public function print_null()
    {
        echo self::color_print('null', self::NULL_COLOR);
    }

    public function print_query(yii\db\ActiveQuery $entity)
    {
        $this->print_query_command($entity->createCommand());
    }

    public function print_query_command(yii\db\Command $entity)
    {
        echo '<b>' . $entity->getRawSql() . '</b>';
    }

    public static function get_type($entity)
    {
        if (is_null($entity)) {
            return 'null';
        }

        if (is_numeric($entity) && !is_string($entity)) {
            return 'numeric';
        }

        if (is_string($entity)) {
            return 'string';
        }

        if (is_bool($entity)) {
            return 'bool';
        }

        if (is_array($entity)) {
            return 'array';
        }

        if (is_object($entity)) {
            if (is_a($entity, \yii\base\Model::class)) {
                return 'yii_model';
            }

            if (is_a($entity, app\components\db\pgpdo\Command::class)) {
                return 'query_command';
            }

            if (is_a($entity, \yii\db\QueryInterface::class)) {
                return 'query';
            }

            return 'object';
        }

        return 'unknown';
    }

    private function tabs()
    {
        $tabs = '';
        for ($i = 0; $i < $this->tabs; $i++) {
            $tabs .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }

        return $tabs;
    }

    public function p($entity, $comment = '')
    {
        if (!empty($comment)) {
            echo '<b>' . $comment . '</b><br>';
        }

        switch (self::get_type($entity)) {
            case 'null':
                $this->print_null();
                break;
            case 'numeric':
                $this->print_numeric($entity);
                break;
            case 'string':
                $this->print_string($entity);
                break;
            case 'bool':
                $this->print_bool($entity);
                break;
            case 'array':
                $this->print_array($entity);
                break;
            case 'yii_model':
                $this->print_yii_model($entity);
                break;
            case 'query_command':
                $this->print_query_command($entity);
                break;
            case 'query':
                $this->print_query($entity);
                break;
            case 'object':
                $this->print_class($entity);
                break;
            case 'unknown':
                echo gettype($entity);
                break;
        }
    }
}

