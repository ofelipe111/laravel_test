<?php
namespace App\Services\RuleEvaluator;

use Illuminate\Database\Eloquent\Model;
use App\Services\RuleEvaluator\RuleFactory;

class RuleEvaluator
{
    protected Model $user;
    protected array $rules;

    public function __construct(Model $user, array $rules)
    {
        $this->user = $user;
        $this->rules = $rules;
    }

    public function passes(): bool
    {
        foreach ($this->rules as $rule) {
            if (!$this->evaluate($rule)) {
                return false;
            }
        }

        return true;
    }

    protected function evaluate(array $rule): bool
    {
        $field = $rule['field'];
        $operator = $rule['operator'];
        $expected = $rule['value'];

        $actual = data_get($this->user, $field);

        if ($operator === '==') {
            return $actual == $expected;
        }

        $evaluator = RuleFactory::make($operator);
        return $evaluator->evaluate($actual, $expected);
    }

    protected function contains($actual, $expected): bool
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