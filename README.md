# WordPress Translation Manager

This package provides a composer plugin for manage
(install/update/delete) WordPress translation for composer based
WordPress projects

## Install

1. Install this package via composer:

``` bash
composer require rockschtar/wordpress-translation-manager
```

2. Define languages as
   [WordPress Locale Code](https://wpastra.com/docs/complete-list-wordpress-locale-codes/)
   and wp-content directory in the extras object of your `composer.json`
   file.

Example:
``` json
  "extra": {
    "wordpress-translation-manager": {
      "languages": [
        "de_DE",
        "es_ES"
      ],
      "wordpress-content-directory": "web/app"
    },

  },
```

## Usage

Every time you install or update a package via `composer install`,
`composer require` or `composer update` the plugin automatically updates
the translations for you specified plugins/version or wordpress core.

If you start with this on an existing composer based wordpress project
you can run `composer update-wordpress-translations` to force the update
of the translations.

## Credits

- [Stefan Helmer](https://github.com/rockschtar)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.