<?php

namespace Dinhdjj\Visit;

use Dinhdjj\Visit\Models\Visit as ModelVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

/**
 * @method $this by(string $field) Prevent duplicate visits by x field.
 * @method $this byIp() Prevent duplicate visits by ip.
 * @method $this byVisitor() Prevent duplicate visits by visitor.
 * @method $this interval(int $seconds) seconds to prevent duplicate.
 * @method $this hourly() Prevent duplicate visits by hour.
 * @method $this daily() Prevent duplicate visits by day.
 * @method $this weekly() Prevent duplicate visits by week.
 */
class Visit
{
    protected Agent $parser;

    protected array $logData;

    public function __construct(
        protected Request $request,
        protected Model $visitable,
        protected ?Model $visitor = null,
        protected ?DuplicationBuilder $duplicationBuilder = new DuplicationBuilder(),
    ) {
        $parser = new Agent();
        $parser->setUserAgent($request->userAgent());
        $parser->setHttpHeaders($request->headers);
        $this->parser = $parser;
    }

    private function getDevice(): string
    {
        return $this->parser->device();
    }

    private function getPlatform(): string
    {
        return $this->parser->platform();
    }

    private function getBrowser(): string
    {
        return $this->parser->browser();
    }

    /**
     * @return string[]
     */
    private function getLanguages(): array
    {
        return $this->parser->languages();
    }

    private function getIp(): string
    {
        return $this->request->ip();
    }

    private function getVisitModel(): ModelVisit
    {
        $class = config('visit.model');

        return new $class();
    }

    protected function getLogData(): array
    {
        if (isset($this->logData)) {
            return $this->logData;
        }

        $this->logData = [
            'languages' => $this->getLanguages(),
            'device' => $this->getDevice(),
            'platform' => $this->getPlatform(),
            'browser' => $this->getBrowser(),
            'ip' => $this->getIp(),
            'visitable_type' => $this->visitable->getMorphClass(),
            'visitable_id' => $this->visitable->getKey(),
            'visitor_type' => $this->visitor ? $this->visitor->getMorphClass() : null,
            'visitor_id' => $this->visitor ? $this->visitor->getKey() : null,
        ];

        return $this->logData;
    }

    protected function getDuplicationVisit(): ?ModelVisit
    {
        $duplicationBuilder = $this->duplicationBuilder;
        $data = $this->getLogData();
        $by = $duplicationBuilder->getBy();
        $seconds = $duplicationBuilder->getInterval();

        if (! $by) {
            return null;
        }

        $duplicateData = collect($by)->mapWithKeys(function ($field) use ($data) {
            return [$field => $data[$field]];
        })->toArray();

        /** @var ModelVisit */
        $visit = $this->getVisitModel()
            ->query()
            ->where($duplicateData)
            ->where('visitable_type', $data['visitable_type'])
            ->where('visitable_id', $data['visitable_id'])
            ->where('created_at', '>', now()->subSeconds($seconds))
            ->first();

        return $visit;
    }

    public function log(): ModelVisit
    {
        $visit = $this->getDuplicationVisit();

        if ($visit) {
            $visit->touch();

            return $visit;
        }

        $visit = $this->getVisitModel()->fill($this->getLogData());

        $visit->save();

        return $visit;
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->duplicationBuilder, $name)) {
            $this->duplicationBuilder->{$name}(...$arguments);
        }

        return $this;
    }
}
