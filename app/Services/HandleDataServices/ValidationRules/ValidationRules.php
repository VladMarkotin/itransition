<?php

declare(strict_types=1);

namespace App\Services\HandleDataServices\ValidationRules;

class ValidationRules
{
    private static array $rules = [
        'Cost in GBP' => [
            'min' => 5,
            'max' => 1000,
        ],
        'Stock' => [
            'min' => 10,
        ]
    ];

    public static function getRule($index, $rule): mixed
    {
        if (isset(self::$rules[$index][$rule])) {
            return self::$rules[$index][$rule];
        }

        dd(
            "Validation error. Non-existent rule for '{$index}' with parameter '[{$rule}]'"
        );
    }
}
