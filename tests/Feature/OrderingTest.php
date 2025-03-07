<?php

namespace Birdboar\Tests\Feature;

use Birdboar\LaravelCustomFields\Exceptions\FieldDoesNotBelongToModelException;
use Birdboar\LaravelCustomFields\Exceptions\WrongNumberOfFieldsForOrderingException;
use Birdboar\LaravelCustomFields\Models\CustomField;
use Birdboar\Tests\Support\Survey;
use Birdboar\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_fields_are_ordered_by_default()
    {
        $survey = Survey::create();
        $survey->customfields()->saveMany([
            CustomField::factory()->make([
                'title' => 'email',
                'type' => 'text',
            ]),
            CustomField::factory()->make([
                'title' => 'phone',
                'type' => 'text',
            ]),
        ]);

        $this->assertEquals(1, $survey->customfields->firstWhere('title', 'email')->order);
        $this->assertEquals(2, $survey->customfields->firstWhere('title', 'phone')->order);
    }

    /** @test */
    public function order_function_replaces_field_orders()
    {
        $survey = Survey::create();
        $survey->customfields()->saveMany([
            CustomField::factory()->make([
                'title' => 'email',
                'type' => 'text',
            ]),
            CustomField::factory()->make([
                'title' => 'phone',
                'type' => 'text',
            ]),
        ]);

        $survey->order([2, 1]);

        $this->assertEquals(2, $survey->customfields->firstWhere('title', 'email')->order);
        $this->assertEquals(1, $survey->customfields->firstWhere('title', 'phone')->order);
    }

    /** @test */
    public function order_function_throws_exception_for_wrong_number_of_ids()
    {
        $survey = Survey::create();
        $survey->customfields()->saveMany([
            CustomField::factory()->make([
                'title' => 'email',
                'type' => 'text',
            ]),
            CustomField::factory()->make([
                'title' => 'phone',
                'type' => 'text',
            ]),
        ]);

        $this->expectException(WrongNumberOfFieldsForOrderingException::class);
        $this->expectExceptionMessage('Wrong number of fields passed for ordering. 3 given, 2 expected.');

        $survey->order([3, 2, 1]);
    }

    /** @test */
    public function order_function_throws_exception_if_passed_fields_not_belonging_to_model()
    {
        $survey1 = Survey::create();
        $survey1->customfields()->saveMany([
            CustomField::factory()->make([
                'title' => 'email',
                'type' => 'text',
            ]),
            CustomField::factory()->make([
                'title' => 'phone',
                'type' => 'text',
            ]),
        ]);

        $survey2 = Survey::create();
        $survey2->customfields()->saveMany([
            CustomField::factory()->make([
                'title' => 'fax',
                'type' => 'text',
            ]),
            CustomField::factory()->make([
                'title' => 'telegraph',
                'type' => 'text',
            ]),
        ]);

        $this->expectException(FieldDoesNotBelongToModelException::class);
        $this->expectExceptionMessage('Field 1 does not belong to Birdboar\Tests\Support\Survey with id 2');

        $survey2->order([1, 2]);
    }
}
