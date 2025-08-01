<?php

namespace App\Services\RuleEvaluator\Rules;

use App\Services\RuleEvaluator\Contracts\RuleInterface;

class ContainsRule implements RuleInterface
{
    public function evaluate($actual, $expected): bool
    {
        if (is_array($actual)) {
            return in_array($expected, $actual);
        }

        if (is_string($actual)) {
            return str_contains($actual, (string) $expected);
        }

        return false;
    }
}
