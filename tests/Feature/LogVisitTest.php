<?php

use Dinhdjj\Visit\Tests\Post;
use Dinhdjj\Visit\Visit;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    $this->post = Post::create();
});

it('can log', function () {
    $visit = new Visit(request(), $this->post);
    assertDatabaseCount('visits', 0);

    $visit->log();
    assertDatabaseCount('visits', 1);

    $visit->log();
    assertDatabaseCount('visits', 2);
});

it('can log duplication by ip', function () {
    testTime()->freeze();

    $visit = new Visit(request(), $this->post);
    $visit->byIp();
    assertDatabaseCount('visits', 0);

    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(14);
    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(1);
    $visit->log();
    assertDatabaseCount('visits', 2);
});

it('can log duplication by visitor', function () {
    testTime()->freeze();

    $visitor = Post::create();
    $visit = new Visit(request(), $this->post, $visitor);
    $visit->byVisitor();
    assertDatabaseCount('visits', 0);

    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(14);
    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(1);
    $visit->log();
    assertDatabaseCount('visits', 2);
});

it('can custom interval duplicate', function () {
    testTime()->freeze();

    $visitor = Post::create();
    $visit = new Visit(request(), $this->post, $visitor);
    $visit->byVisitor()->hourly();
    assertDatabaseCount('visits', 0);

    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(14);
    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(1);
    $visit->log();
    assertDatabaseCount('visits', 1);

    testTime()->addMinutes(45);
    $visit->log();
    assertDatabaseCount('visits', 2);
});

it('update updated_at if duplicate', function () {
    testTime()->freeze();
    $visit = new Visit(request(), $this->post);
    $visit->byIp();

    $visit->log();
    assertDatabaseCount('visits', 1);
    assertDatabaseHas('visits', [
        'updated_at' => now(),
    ]);

    testTime()->addMinutes(14);
    $visit->log();
    assertDatabaseCount('visits', 1);
    assertDatabaseHas('visits', [
        'updated_at' => now(),
    ]);
});
