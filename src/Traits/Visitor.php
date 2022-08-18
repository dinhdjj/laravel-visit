<?php

namespace Dinhdjj\Visit\Traits;

use Dinhdjj\Visit\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Visitor
{
    protected static function bootVisitor(): void
    {
        static::deleting(function (self $visitor): void {
            if (method_exists($visitor, 'isForceDeleting') ? $visitor->isForceDeleting() : true) {
                $visitor->visits->each->delete();
            }
        });
    }

    public function visits(): MorphMany
    {
        return $this->morphMany(config('visit.model'), 'visitor');
    }

    public function visit(Model $visitable): Visit
    {
        return new Visit(request(), $visitable, $this);
    }

    public function isOnline(int $seconds = 300): bool
    {
        if ($this->relationLoaded('visits')) {
            return $this->visits->where('updated_at', '>', now()->subSeconds($seconds))->count() >= 1;
        }

        return $this->visits()->where('updated_at', '>', now()->subSeconds($seconds))->exists();
    }
}
