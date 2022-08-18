<?php

namespace Dinhdjj\Visit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Visit extends Model
{
    protected $fillable = [
        'languages',
        'device',
        'platform',
        'browser',
        'ip',
        'visitable',
        'visitor',
    ];

    protected $hidden = [
        'ip',
    ];

    protected $casts = [
        'languages' => 'array',
    ];

    protected $appends = [
    ];

    public function getTable(): string
    {
        return config('visit.table');
    }

    public function visitable(): MorphTo
    {
        return $this->morphTo();
    }

    public function visitor(): MorphTo
    {
        return $this->morphTo();
    }
}
