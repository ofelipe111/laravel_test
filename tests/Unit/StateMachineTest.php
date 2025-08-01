<?php

namespace Tests\Unit;

use App\Models\ExampleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StateMachineTest extends TestCase
{
    use RefreshDatabase;

    public function testValidTransition()
    {
        $model = ExampleModel::create([
            'state' => 'draft',
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        $result = $model->transitionTo('submitted');

        $this->assertTrue($result);
        $this->assertEquals('submitted', $model->state);
    }

    public function testInvalidTransitionThrowsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Transition from 'draft' to 'approved' is not allowed.");

        $model = ExampleModel::create([
            'state' => 'draft',
            'role' => 'staff',
            'email_verified_at' => now(),
        ]);

        $model->transitionTo('approved'); 
    }

    public function testTransitionFailsRules()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Transition rules not satisfied for state 'submitted'.");

        $model = ExampleModel::create([
            'state' => 'draft',
            'role' => 'user', // Not 'staff'
            'email_verified_at' => null,
        ]);

        $model->transitionTo('submitted');
    }
}
