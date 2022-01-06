<?php

namespace Birdboar\Tests\Support;

use Birdboar\LaravelCustomFields\Traits\HasCustomFieldResponses;
use Illuminate\Database\Eloquent\Model;

class HasCustomFieldResponsesModel extends Model
{
    use HasCustomFieldResponses;
}
