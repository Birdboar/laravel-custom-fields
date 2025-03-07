<?php

namespace Birdboar\LaravelCustomFields\Interfaces;

interface CustomFieldable
{
    /**
     * Get the custom fields belonging to this model.
     *
     * @return mixed
     */
    public function customFields();
}
