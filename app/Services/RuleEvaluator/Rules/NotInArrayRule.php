<?php

namespace App\Services\RuleEvaluator\Rules;

use App\Services\RuleEvaluator\Contracts\RuleInterface;

class NotInArrayRule implements RuleInterface
{
    public function evaluate($actual, $expected): bool
    {
        return !in_array($actual, (array) $expected);
    }
}
