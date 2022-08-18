<?php

namespace Dinhdjj\Visit\Traits;

use Dinhdjj\Visit\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Visitable
{
    protected static function bootVisitable(): void
    {
        static::deleting(function (self $Visitable): void {
            if (method_exists($Visitable, 'isForceDeleting') ? $Visitable->isForceDeleting() : true) {
                $Visitable->visitLogs->each->delete();
            }
        });
    }

    public function visitLogs(): MorphMany
    {
        return $this->morphMany(config('visit.model'), 'visitable');
    }

    public function visitLog(?Model $visitor = null): Visit
    {
        return new Visit(request(), $this, $visitor);
    }
}
