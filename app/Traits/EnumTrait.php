<?php

namespace App\Traits;

trait EnumTrait
{
    public static function array($namesFirst = false): array
    {
        if ($namesFirst)
            return array_combine(self::names(), self::values());

        return array_combine(self::values(), self::names());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function pairFromValue($enumValue): array
    {
        $enum = self::from($enumValue);
        return ["name" => $enum->name, 'value' => $enum->value];
    }

    public static function pairFromName($enumName): array
    {
        $enum = constant("self::$enumName");
        return ["name" => $enum->name, 'value' => $enum->value];
    }

    public static function separatedElementsArray(): array
    {
        $enumArray = self::array(namesFirst: true);
        $opportunitiesArray = [];
        array_walk($enumArray, function ($value, $name) use (&$opportunitiesArray) {
            $opportunitiesArray[] = ['name' => $name, 'value' => $value];
        });
        return $opportunitiesArray;
    }

    public static function caseFromValue($enumValue): string
    {
        return self::from($enumValue)->name;
    }
}
