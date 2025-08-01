<?php

namespace Tests\Unit;

use App\Filters\NestedFilter;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NestedFilterTest extends TestCase
{
    use RefreshDatabase;

    public function testNestedFilterGeneratesCorrectSql()
    {
        $filters = [
            'patient.name' => 'John',
            'status' => 'confirmed',
            'location.city' => 'Dallas',
        ];

        $query = Appointment::query();

        $filter = new NestedFilter($query, $filters);
        $filteredQuery = $filter->apply();

        $sql = $filteredQuery->toSql();

        $this->assertStringContainsString('exists', $sql);
        $this->assertStringContainsString('patient', $sql);
        $this->assertStringContainsString('location', $sql);
        $this->assertStringContainsString('"appointments"."patient_id"', $sql);
    }
}
