<?php

namespace Birdboar\Tests\Support;

use Birdboar\LaravelCustomFields\Traits\HasCustomFields;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasCustomFields;
}
