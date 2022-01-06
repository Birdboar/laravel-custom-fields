<?php

namespace Birdboar\LaravelCustomFields\Traits;

use Birdboar\LaravelCustomFields\Exceptions\FieldDoesNotBelongToModelException;
use Birdboar\LaravelCustomFields\Exceptions\WrongNumberOfFieldsForOrderingException;
use Birdboar\LaravelCustomFields\Models\CustomField;
use Birdboar\LaravelCustomFields\Validators\CustomFieldValidator;
use Illuminate\Http\Request;

trait HasCustomFields
{
    /**
     * Get the custom fields belonging to this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function customFields()
    {
        return $this->morphMany(CustomField::class, 'model')->orderBy('order');
    }

    /**
     * Validate the given custom fields.
     *
     * @param $fields
     * @return CustomFieldValidator
     */
    public function validateCustomFields($fields)
    {
        $validationRules = $this->customFields()->whereNull('archived_at')->get()->mapWithKeys(function ($field) {
            return ['field_' . $field->id => $field->validationRules];
        })->toArray();

        $keyAdjustedFields = collect($fields)
            ->mapWithKeys(function ($field, $key) {
                return ["field_{$key}" => $field];
            })->toArray();

        return new CustomFieldValidator($keyAdjustedFields, $validationRules);
    }

    /**
     * Validate the given custom field request.
     *
     * @param Request $request
     * @return CustomFieldValidator
     */
    public function validateCustomFieldsRequest(Request $request)
    {
        return $this->validateCustomFields($request->get(config('custom-fields.form_name', 'custom_fields')));
    }

    /**
     * Handle a request to order the fields.
     *
     * @param $fields
     * @throws FieldDoesNotBelongToModelException
     * @throws WrongNumberOfFieldsForOrderingException
     */
    public function order($fields)
    {
        $fields = collect($fields);

        if ($fields->count() !== $this->customFields()->count()) {
            throw new WrongNumberOfFieldsForOrderingException($fields->count(), $this->customFields()->count());
        }

        $fields->each(function ($id, $index) {
            $customField = $this->customFields()->find($id);

            if (! $customField) {
                throw new FieldDoesNotBelongToModelException($id, $this);
            }

            $customField->update(['order' => $index + 1]);
        });
    }
}
