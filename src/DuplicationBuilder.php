<?php

namespace Dinhdjj\Visit;

class DuplicationBuilder
{
    /**
     * Fields to prevent duplicate
     *
     * @var string[]
     **/
    protected array $by = [];

    /** seconds to prevent duplicate */
    protected int $seconds = 900;

    public function getBy(): array
    {
        return $this->by;
    }

    public function getInterval(): int
    {
        return $this->seconds;
    }

    /**
     * Prevent duplicate visits by x field.
     *
     * @return $this
     **/
    public function by(string $field): static
    {
        $this->by[] = $field;

        return $this;
    }

    /**
     * Prevent duplicate visits by ip.
     *
     * @return $this
     **/
    public function byIp(): static
    {
        return $this->by('ip');
    }

    /**
     * Prevent duplicate visits by visitor.
     *
     * @return $this
     **/
    public function byVisitor(): static
    {
        return $this->by('visitor_type')->by('visitor_id');
    }

    /**
     * Prevent duplicate visits by device.
     *
     * @return $this
     **/
    public function byDevice(): static
    {
        return $this->by('device');
    }

    /**
     * Prevent duplicate visits by platform.
     *
     * @return $this
     **/
    public function byPlatform(): static
    {
        return $this->by('platform');
    }

    /**
     * Prevent duplicate visits by browser.
     *
     * @return $this
     **/
    public function byBrowser(): static
    {
        return $this->by('browser');
    }

    /**
     * Prevent duplicate visits x seconds interval.
     *
     * @return $this
     **/
    public function interval(int $seconds): static
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * Prevent duplicate visits hourly interval.
     *
     * @return $this
     **/
    public function hourly(): static
    {
        return $this->interval(60 * 60);
    }

    /**
     * Prevent duplicate visits daily interval.
     *
     * @return $this
     **/
    public function daily(): static
    {
        return $this->interval(60 * 60 * 24);
    }

    /**
     * Prevent duplicate visits weekly interval.
     *
     * @return $this
     **/
    public function weekly(): static
    {
        return $this->interval(60 * 60 * 24 * 7);
    }
}
