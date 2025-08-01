<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelTransitioning
{
    use Dispatchable, SerializesModels;

    public Model $model;
    public string $fromState;
    public string $toState;

    public function __construct(Model $model, string $fromState, string $toState)
    {
        $this->model = $model;
        $this->fromState = $fromState;
        $this->toState = $toState;
    }
}
