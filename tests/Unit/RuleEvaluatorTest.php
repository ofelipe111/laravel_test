<?php

use Tests\TestCase;
use App\Models\User;
use App\Services\RuleEvaluator\RuleEvaluator;

class RuleEvaluatorTest extends TestCase
{
    public function test_user_passes_rules()
    {
        $user = new User([
            'role' => 'admin',
            'age' => 30,
            'tags' => ['verified', 'vip']
        ]);

        $rules = [
            ['field' => 'role', 'operator' => '==', 'value' => 'admin'],
            ['field' => 'age', 'operator' => '>', 'value' => 18],
            ['field' => 'tags', 'operator' => 'contains', 'value' => 'verified'],
        ];

        $evaluator = new RuleEvaluator($user, $rules);

        $this->assertTrue($evaluator->passes());
    }
}
