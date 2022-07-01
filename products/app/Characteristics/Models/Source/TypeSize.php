<?php

namespace App\Admin\Model\Source;

class TypeSize
{
    const NUMBER = "sizeNumber";
    const STRING = "sizeString";
    const CUSTOM = "sizeCustom";

    public static function toOptionsArray()
    {
        return [
            self::NUMBER => 0,
            self::STRING => 1,
            self::CUSTOM => 2,
        ];
    }

    public function _getOptions()
    {
        return self::toOptionsArray();
    }

    public static function status($type)
    {
        switch ($type) {
            case self::NUMBER:
                return 0;
            case self::STRING:
                return 1;
            case self::CUSTOM:
                return 2;
        }
        return 0;
    }
}
