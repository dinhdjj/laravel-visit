<?php

namespace Dinhdjj\Visit\Traits;

use Carbon\Carbon;
use Dinhdjj\Visit\Visit;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function scopeOrderByVisitLogsCount(Builder $query, string $direction = 'desc', ?Carbon $from = null, ?Carbon $to = null): Builder
    {
        return $query
        ->withCount([
            'visitLogs' => function (Builder $query) use ($from, $to): void {
                $query
                ->when($from, function (Builder $query) use ($from): void {
                    $query->where('created_at', '>=', $from);
                })
                ->when($to, function (Builder $query) use ($to): void {
                    $query->where('created_at', '<=', $to);
                });
            },
        ])
        ->orderBy('visit_logs_count', $direction);
    }
}
