# Laravel Zendesk

This package provides integration with the Zendesk API. It supports creating tickets, retrieving and updating tickets, deleting tickets, etc.

The package simply provides a `Zendesk` class which acts as a wrapper to the [zendesk/zendesk_api_client_php](https://github.com/zendesk/zendesk_api_client_php) package.

**NB:** Currently only supports token-based authentication.

## Installation

You can install this package via Composer by:

- Register the repository in your composer.json

```json
"repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/AidansCode/zendesk-laravel"
    }
  ],
  "require-all": true
```

- Add the package to your required list
```json
"require": {
    "...": "...",
    "AidansCode/zendesk-laravel": "master"
  },
```

- Run:
```bash
composer install
```

You must also install the service provider.

> Laravel 5.5+ users: this step may be skipped, as the package supports auto discovery.

```php
// config/app.php
'providers' => [
    ...
    Huddle\Zendesk\Providers\ZendeskServiceProvider::class,
    ...
];
```

## Configuration


To publish the config file to `app/config/zendesk-laravel.php` run:

```bash
php artisan vendor:publish --provider="Huddle\Zendesk\Providers\ZendeskServiceProvider"
```


- `ZENDESK_DRIVER` _(Optional)_

Set this to `null` or `log` to prevent calling the Zendesk API directly from your environment.

## Usage

This fork removed the Facade from the original repo in favor of providing the Zendesk credentials on construction of a new instance. This allows for supporting multiple Zendesk accounts, as such may be necessary in a multi-tenant environment.

```php
$zendesk = new Zendesk($subdomain, $username, $password);

// Get all tickets
$zendesk->tickets()->findAll();

// Create a new ticket
$zendesk->tickets()->create([
  'subject' => 'Subject',
  'comment' => [
      'body' => 'Ticket content.'
  ],
  'priority' => 'normal'
]);

// Update multiple tickets
$zendesk->ticket([123, 456])->update([
  'status' => 'urgent'
]);

// Delete a ticket
$zendesk->ticket(123)->delete();
```

This package is available under the [MIT license](http://opensource.org/licenses/MIT).
