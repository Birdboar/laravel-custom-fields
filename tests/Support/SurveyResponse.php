<?php

namespace Birdboar\Tests\Support;

use Birdboar\LaravelCustomFields\Traits\HasCustomFieldResponses;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    use HasCustomFieldResponses;
}
