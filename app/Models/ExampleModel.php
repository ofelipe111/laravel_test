<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\StateMachine;

class ExampleModel extends Model
{
    use StateMachine;

    protected $fillable = ['state', 'role', 'email_verified_at'];

    public static $states = [
        'draft' => ['submitted'],
        'submitted' => ['approved', 'rejected'],
        'approved' => [],
        'rejected' => [],
    ];

    public array $transitionRules = [
        'submitted' => [
            ['field' => 'role', 'operator' => '==', 'value' => 'staff'],
            ['field' => 'email_verified_at', 'operator' => '!=', 'value' => null],
        ],
    ];
}
