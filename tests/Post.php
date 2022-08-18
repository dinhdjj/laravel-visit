<?php

namespace Dinhdjj\Visit\Tests;

use Dinhdjj\Visit\Traits\Visitable;
use Dinhdjj\Visit\Traits\Visitor;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Visitable;
    use Visitor;
}
