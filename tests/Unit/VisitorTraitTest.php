<?php

use Dinhdjj\Visit\Tests\Post;
use Dinhdjj\Visit\Visit;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;
use function Spatie\PestPluginTestTime\testTime;

beforeEach(function () {
    $this->post = Post::create();

    $this->post->visit(Post::create())->log();

    Post::create()->visit(Post::create())->log();
});

it('has visits', function () {
    assertSame(1, $this->post->visits()->count());
});

it('has auto delete visits on delete', function () {
    $this->post->delete();
    assertSame(0, $this->post->visits()->count());
});

it('can create visit', function () {
    assertInstanceOf(Visit::class, $this->post->visit(Post::create()));
});

it('can determine weather a visitor is online', function () {
    testTime()->freeze();

    assertSame(true, $this->post->isOnline());
    assertSame(false, Post::create()->isOnline());

    testTime()->addMinutes(4);
    assertSame(true, $this->post->isOnline());

    testTime()->addMinutes(1);
    assertSame(false, $this->post->isOnline());
});

it('can determine weather a visitor is online with eager loading', function () {
    testTime()->freeze();

    $this->post->load('visits');

    assertSame(true, $this->post->isOnline());
    assertSame(false, Post::create()->load('visits')->isOnline());

    testTime()->addMinutes(4);
    assertSame(true, $this->post->isOnline());

    testTime()->addMinutes(1);
    assertSame(false, $this->post->isOnline());
});
