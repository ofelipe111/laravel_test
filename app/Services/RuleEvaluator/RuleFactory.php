<?php

namespace App\Services\RuleEvaluator;

use App\Services\RuleEvaluator\Rules\EqualsRule;
use App\Services\RuleEvaluator\Rules\NotEqualsRule;
use App\Services\RuleEvaluator\Rules\GreaterThanRule;
use App\Services\RuleEvaluator\Rules\LessThanRule;
use App\Services\RuleEvaluator\Rules\InArrayRule;
use App\Services\RuleEvaluator\Rules\NotInArrayRule;
use App\Services\RuleEvaluator\Rules\ContainsRule;

class RuleFactory
{
    public static function make(string $operator)
    {
        return match ($operator) {
            '==' => new EqualsRule(),
            '!=' => new NotEqualsRule(),
            '>' => new GreaterThanRule(),
            '<' => new LessThanRule(),
            'in' => new InArrayRule(),
            'not_in' => new NotInArrayRule(),
            'contains' => new ContainsRule(),
            default => throw new \InvalidArgumentException("Unsupported operator: $operator"),
        };
    }
}
