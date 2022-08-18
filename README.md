# Package to tracking visits of a given model

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dinhdjj/visit.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/visit)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/dinhdjj/visit/run-tests?label=tests)](https://github.com/dinhdjj/visit/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/dinhdjj/visit/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/dinhdjj/visit/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/dinhdjj/visit.svg?style=flat-square)](https://packagist.org/packages/dinhdjj/visit)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require dinhdjj/laravel-visit
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="visit-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="visit-config"
```

This is the contents of the published config file:

```php
// config for dinhdjj/laravel-visit package
return [

    /**
     * Table name for visit logs
     */
    'table' => 'visits',

    /**
     * The model class name that will be used to store visit logs, must be a subclass of \Dinhdjj\Visit\Models\Visit
     */
    'model' => Dinhdjj\Visit\Models\Visit::class,
];
```

## Usage

### Use `Visitable` trait

The first thing you need to do is, to use `Visitable` trait.

```php
class Post extends Model
{
    use \Dinhdjj\Visit\Traits\Visitable;
}
```

After that, you can use below methods to interact with the visit logs:

```php
    $post = Post::first();

    $post->visitLogs() // relation to visit logs

    $post->visitLogs // collection of visit logs

    $builder = $post->visitLog(User::first()) // builder visit with user as visitor

    $builder->byIp(); // prevent duplicate visit by ip
    $builder->byVisitor(); // prevent duplicate visit by visitor

    $builder->interval(60*15) // prevent duplicate visit within 15 minutes, default is 60*15

    $visit = $builder->log(); // create visit to database
```

### Use `Visitor` trait

The first thing you need to do is, to use `Visitor` trait.

```php
class User extends Model
{
    use \Dinhdjj\Visit\Traits\Visitor;
}
```

After that, you can use below methods to interact with the visit logs:

```php
    $user = Post::first();

    $user->visits() // relation to visit logs

    $post->visits // collection of visit logs

    $builder = $post->visit(Post::first()) // builder visit to post

    // ... same as above
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/dinhdjj/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [dinhdjj](https://github.com/dinhdjj)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
