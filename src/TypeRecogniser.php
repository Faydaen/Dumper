<?php

namespace Faydaen;

class TypeRecogniser
{
    const TYPE_NULL = 'null';
    const TYPE_INTEGER = 'integer';
    const TYPE_DOUBLE = 'double';
    const TYPE_STRING = 'string';
    const TYPE_BOOL = 'bool';
    const TYPE_ASSOCIATES_ARRAY = 'associates_array';
    const TYPE_FLAT_ARRAY = 'flat_array';
    const TYPE_EMPTY_ARRAY = 'empty_array';
    const TYPE_YII_MODEL = 'yii_model';
    const TYPE_QUERY_COMMAND = 'query_command';
    const TYPE_QUERY = 'query';
    const TYPE_OBJECT = 'object';
    const TYPE_UNKNOWN = 'unknown';

    /**
     *
     * @param $entity
     * @return string
     */
    public static function recognizeType($entity)
    {
        if (is_null($entity)) {
            return self::TYPE_NULL;
        }

        if (is_string($entity)) {
            return self::TYPE_STRING;
        }

        if ( gettype($entity) === 'integer'){
            return self::TYPE_INTEGER;
        }

        if ( gettype($entity) === 'double'){
            return self::TYPE_DOUBLE;
        }

        if (is_bool($entity)) {
            return self::TYPE_BOOL;
        }

        if (is_array($entity)) {
            if (empty($entity)){
                return self::TYPE_EMPTY_ARRAY;
            }

            if(self::isFlatArray($entity)){
                return self::TYPE_FLAT_ARRAY;
            }

            return self::TYPE_ASSOCIATES_ARRAY;
        }

        if (is_object($entity)) {
            if (is_a($entity, 'yii\base\Model')) {
                return self::TYPE_YII_MODEL;
            }

            if (is_a($entity, 'app\components\db\pgpdo\Command')) {
                return self::TYPE_QUERY_COMMAND;
            }

            if (is_a($entity, 'yii\db\QueryInterface')) {
                return self::TYPE_QUERY;
            }

            return self::TYPE_OBJECT;
        }

        return self::TYPE_UNKNOWN;
    }

    private static function isFlatArray($array)
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
