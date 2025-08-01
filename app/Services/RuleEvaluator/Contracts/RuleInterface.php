<?php

namespace App\Services\RuleEvaluator\Contracts;

interface RuleInterface
{
    public function evaluate($actual, $expected): bool;
}
