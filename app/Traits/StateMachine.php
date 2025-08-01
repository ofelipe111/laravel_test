<?php

namespace App\Traits;

use App\Events\ModelTransitioning;
use App\Events\ModelTransitioned;
use Illuminate\Database\Eloquent\Model;

trait StateMachine
{    protected string $stateColumn = 'state';

    /**
     * Perform a state transition.
     *
     * @param string $newState
     * @return bool
     * @throws \Exception
     */
    public function transitionTo(string $newState): bool
    {
        $currentState = $this->{$this->stateColumn} ?? null;

        if (!$currentState) {
            throw new \Exception("Current state is not set.");
        }

        $allowedTransitions = static::$states[$currentState] ?? null;

        if (is_null($allowedTransitions)) {
            throw new \Exception("Current state '{$currentState}' is not defined in states.");
        }

        if (!in_array($newState, $allowedTransitions)) {
            throw new \Exception("Transition from '{$currentState}' to '{$newState}' is not allowed.");
        }

        if (property_exists($this, 'transitionRules')) {
            $rules = $this->transitionRules[$newState] ?? [];
            if (!$this->checkRules($rules)) {
                throw new \Exception("Transition rules not satisfied for state '{$newState}'.");
            }
        }

        event(new ModelTransitioning($this, $currentState, $newState));

        $this->{$this->stateColumn} = $newState;
        $saved = $this->save();

        event(new ModelTransitioned($this, $currentState, $newState));

        return $saved;
    }

    /**
     * Check transition rules (simple rule engine).
     * Rules example:
     * [
     *   ['field' => 'role', 'operator' => '==', 'value' => 'staff'],
     *   ['field' => 'email_verified_at', 'operator' => '!=', 'value' => null],
     * ]
     *
     * @param array $rules
     * @return bool
     */
    protected function checkRules(array $rules): bool
    {
        foreach ($rules as $rule) {
            $field = $rule['field'];
            $operator = $rule['operator'];
            $value = $rule['value'];

            $fieldValue = data_get($this, $field);

            switch ($operator) {
                case '==':
                    if ($fieldValue != $value) {
                        return false;
                    }
                    break;
                case '!=':
                case '! =':
                    if ($fieldValue == $value) {
                        return false;
                    }
                    break;
                case '>':
                    if (!($fieldValue > $value)) {
                        return false;
                    }
                    break;
                case '<':
                    if (!($fieldValue < $value)) {
                        return false;
                    }
                    break;
                case '>=':
                    if (!($fieldValue >= $value)) {
                        return false;
                    }
                    break;
                case '<=':
                    if (!($fieldValue <= $value)) {
                        return false;
                    }
                    break;
                default:
                    throw new \Exception("Unsupported operator '{$operator}' in transition rules.");
            }
        }

        return true;
    }
}
