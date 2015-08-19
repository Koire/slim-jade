# slim-jade
Jade templating for Slim3

## This is pretty much a copy of the Twig Views implementation for Slim 3
===============

Use at your own risk, I haven't used it much yet.
Requires Slim 3 and at least PHP 5.4.0
## Install 
```bash
$ composer require pike/slim-jade
```

## Injection
```php
$view = new Slim\Views\JadeView(
    $pathToYourTemplates,
    $ArrayOfYourSettings
        );
```