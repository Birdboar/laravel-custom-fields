<?php

namespace Birdboar\Tests\Feature;

use Birdboar\LaravelCustomFields\Models\CustomField;
use Birdboar\Tests\Support\Survey;
use Birdboar\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HasCustomFieldsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function custom_fields_can_be_created_and_accessed_on_models_with_trait()
    {
        $model = Survey::create();

        $customField = CustomField::factory()->make([
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'description' => 'Lil Wayne',
        ]);

        $model->customFields()->save($customField);

        $this->assertCount(1, $model->fresh()->customFields);
        $this->assertEquals('Lil Wayne', $model->fresh()->customFields->first()->description);
    }
}
