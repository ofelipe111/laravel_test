<?php
namespace App\Services\RuleEvaluator\Rules;

use App\Services\RuleEvaluator\Contracts\RuleInterface;

class LessThanRule implements RuleInterface
{
    public function evaluate($actual, $expected): bool
    {
        return $actual < $expected;
    }
}
