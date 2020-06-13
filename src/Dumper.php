<?php

namespace Faydaen;

use Faydaen\Renderers\WebRenderer;

class Dumper
{
    const CLASS_NAME_DEEP = 3;

    private $tabs = 0;

    private $value;

    private $key = null;

    private $separator = null;

    private $bracket = null;

    private $signOnEnd = null;

    private $map = [];
    const ITERATION_TYPE_SCALAR = 'scalar';
    const ITERATION_TYPE_ARRAY = 'array';
    const ITERATION_TYPE_OBJECT = 'object';

    /**
     * @var Line[]
     */
    private $lines;

    public function dump($entity, $comment = '')
    {
        $this->putValue($entity);
        $renderer = new WebRenderer($this->lines, $comment);
        echo $renderer->render();
    }

    private function putValue($entity)
    {
        if ($this->isScalar($entity)) {
            $this->value = $this->getScalarType($entity);
            $this->putLine(self::ITERATION_TYPE_SCALAR);
        } else {
            switch (TypeRecogniser::recognizeType($entity)) {
                case TypeRecogniser::TYPE_FLAT_ARRAY:
                    $this->createFlatArrayType($entity);
                    break;
                case TypeRecogniser::TYPE_ASSOCIATES_ARRAY:
                    $this->createAssociatesArrayType($entity);
                    break;
                case TypeRecogniser::TYPE_OBJECT:
                    if ($this->tabs < self::CLASS_NAME_DEEP) {
                        $this->createObjectType($entity);
                    }
                    else {
                        $this->value = $this->createClassName($entity);
                        $this->putLine(self::ITERATION_TYPE_SCALAR);
                    }
                    break;
                case TypeRecogniser::TYPE_YII_MODEL:
                    $this->createObjectType($entity,true);
                    break;
            }
        }
    }

    private function putLine($iterationType = null) {
        if (!is_null($iterationType)) {
            $this->map[$this->tabs] = $iterationType;
        }

        $this->lines[] = new Line(
            $this->tabs,
            $this->key,
            $this->separator,
            $this->value,
            $this->bracket,
            $this->signOnEnd
        );
    }

    private function getScalarType($entity)
    {
        switch (TypeRecogniser::recognizeType($entity)) {
            case TypeRecogniser::TYPE_NULL:
                return $this->createNullType();

            case TypeRecogniser::TYPE_INTEGER:
                return $this->createIntegerType($entity);

            case TypeRecogniser::TYPE_DOUBLE:
                return $this->createDoubleType($entity);

            case TypeRecogniser::TYPE_STRING:
                return $this->createStringType($entity);

            case TypeRecogniser::TYPE_BOOL:
                return $this->createBoolType($entity);

            case TypeRecogniser::TYPE_EMPTY_ARRAY:
                return $this->createEmptyArrayType();

            case  TypeRecogniser::TYPE_QUERY_COMMAND:
                return $this->createQueryCommandType($entity);

            case  TypeRecogniser::TYPE_QUERY:
                return $this->createQueryType($entity);

            default:
                return $this->createTypeName($entity);
        }
    }

    private function createAssociatesArrayType($array) {
        $this->createArray($array, true);
    }

    private function createFlatArrayType($array){
        $this->createArray($array, false);
    }

    private function createObjectType($object,$isModel = false) {
        $this->value = $this->createClassName($object);
        $this->bracket = new Subline(' {');
        $this->signOnEnd = null;
        $this->putLine(self::ITERATION_TYPE_OBJECT);
        $this->bracket = null;

        if ($isModel) {
            $this->modelIteration($object);
        } else {
            $this->objectIteration($object);
        }

        $this->key = null;
        $this->separator = null;
        $this->value = null;

        $this->signOnEnd = $this->getFinaleSeparator();

        $this->bracket = new Subline('}');
        $this->putLine();
        $this->bracket = null;
    }

    private function objectIteration($object)
    {
        $this->tabs++;
        $this->map[$this->tabs] = self::ITERATION_TYPE_OBJECT;

        $counter = 0;
        foreach ($object as $key => $value) {

            $counter++;
            $this->signOnEnd = new Subline(';');
            $this->separator = new Subline(' = ');
            $this->key = new Subline('$' . $key );

            $this->putValue($value);

        }
        $this->tabs--;
    }

    private function modelIteration($model)
    {
        $this->tabs++;
        $this->map[$this->tabs] = self::ITERATION_TYPE_OBJECT;

        $counter = 0;
        foreach ($model->attributes() as $field) {

            $counter++;
            $this->signOnEnd = new Subline(';');
            $this->separator = new Subline(' = ');
            $this->key = new Subline('$' . $field );

            $this->putValue($model->{$field});

        }
        $this->tabs--;
    }

    private function createArray($array, $isAssociative) {
        $this->value = null;
        $this->bracket = new Subline('[');
        $this->signOnEnd = null;
        $this->putLine(self::ITERATION_TYPE_ARRAY);
        $this->bracket = null;

        $this->arrayIteration($array, $isAssociative);

        $this->key = null;
        $this->signOnEnd = $this->getFinaleSeparator();

        $this->separator = null;
        $this->value = null;
        $this->bracket = new Subline(']');
        $this->putLine();
        $this->bracket = null;
    }

    private function getFinaleSeparator() {
        if ($this->tabs == 0){
            return new Subline(';');
        }
        if($this->map[$this->tabs-1] == self::ITERATION_TYPE_ARRAY){
            return new Subline(',');
        }
        if($this->map[$this->tabs-1] == self::ITERATION_TYPE_OBJECT){
            return new Subline(';');
        }
        return null;
    }

    private function arrayIteration($array, $isAssociative){
        $this->tabs++;
        $this->map[$this->tabs] = self::ITERATION_TYPE_ARRAY;

        if (!$isAssociative){
            $this->separator = null;
            $this->key = null;
        }

        $total = count($array);
        $counter = 0;
        foreach ($array as $key => $value) {
            $counter++;

            if ($isAssociative){
                $this->separator = new Subline(' => ');
                $this->key = $this->getScalarType($key);
            }
            $this->signOnEnd = ($total!=$counter) ? new Subline(',') : null;
            $this->putValue($value);
        }

        $this->tabs--;
    }

    private function createNullType() {
        return new Subline('null', Line::COLOR_NULL);
    }

    private function createEmptyArrayType() {
        return new Subline('[ ]', Line::COLOR_DEFAULT);
    }

    private function createIntegerType($entity) {
        return new Subline($entity, Line::COLOR_NUMERIC);
    }

    private function createDoubleType($entity) {
        return new Subline($entity . 'f', Line::COLOR_NUMERIC);
    }

    private function createStringType($entity) {
        $this->escapeQuotes($entity);
        return new Subline("'" . $entity . "'", Line::COLOR_STRING);
    }

    private function createBoolType($entity) {
        $bool = $entity ? 'true' : 'false';
        return new Subline($bool, Line::COLOR_BOOL);
    }

    private function createQueryCommandType($entity) {
        $query = $entity->getRawSql();
        return new Subline($query, Line::COLOR_SQL);
    }

    private function createQueryType($entity) {
        $query = $entity->createCommand();
        return $this->createQueryCommandType($query);
    }

    private function createClassName($entity) {
        return new Subline(get_class($entity).'::class', Line::COLOR_CLASS_NAME);
    }

    private function createTypeName($entity) {
        return new Subline(gettype($entity), Line::COLOR_UNKNOWN_TYPE);
    }

    private function isScalar($entity) {
        return in_array(TypeRecogniser::recognizeType($entity), [
            TypeRecogniser::TYPE_NULL,
            TypeRecogniser::TYPE_INTEGER,
            TypeRecogniser::TYPE_DOUBLE,
            TypeRecogniser::TYPE_STRING,
            TypeRecogniser::TYPE_BOOL,
            TypeRecogniser::TYPE_EMPTY_ARRAY,
            TypeRecogniser::TYPE_QUERY_COMMAND,
            TypeRecogniser::TYPE_QUERY,
            TypeRecogniser::TYPE_UNKNOWN,
        ]);
    }

    private function escapeQuotes($string){
        $string = str_replace(' ', '&nbsp;', $string);
        $string = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $string);
        return $string;
    }
}
