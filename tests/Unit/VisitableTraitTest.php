<?php

use Dinhdjj\Visit\Tests\Post;
use Dinhdjj\Visit\Visit;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertSame;

beforeEach(function () {
    $this->post = Post::create();

    $this->post->visitLog()->log();

    Post::create()->visitLog()->log();
});

it('has visit logs', function () {
    assertSame(1, $this->post->visitLogs()->count());
});

it('has auto delete visit logs on delete', function () {
    $this->post->delete();
    assertSame(0, $this->post->visitLogs()->count());
});

it('can create visit', function () {
    assertInstanceOf(Visit::class, $this->post->visitLog(Post::create()));
    assertInstanceOf(Visit::class, $this->post->visitLog());
});
