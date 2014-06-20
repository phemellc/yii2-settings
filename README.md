Yii2 Settings
=============
Yii2 Database settings

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist pheme/yii2-settings "*"
```

or add

```
"pheme/yii2-settings": "*"
```

to the require section of your `composer.json` file.

Subsequently, run

```php
./yii migrate/up --migrationPath=@vendor/pheme/yii2-settings/migrations
```

in order to create the settings table in your database.


Usage
-----

There are 2 parts to this extension. A module and a component.
The module provides a simple GUI to edit your settings.
The component provides a way to retrieve and save settings programmatically.

Add this to your main configuration's modules array

```php
	'modules' => [
        'settings' => [
            'class' => 'pheme\settings\Module',
        ],
        ...
	],
```

Add this to your main configuration's components array

```php
	'components' => [
		'settings' => [
        	'class' => 'pheme\settings\components\Settings'
        ],
        ...
	]
```

Typical component usage

```php

$settings = Yii::$app->settings;

$value = $settings->get('section.key');

$value = $settings->get('key', 'section');

$settings->set('section.key', 'value');

$settings->set('section.key', 'value', null, 'string');

$settings->set('key', 'value', 'section', 'integer');

// Automatically called on set();
$settings->clearCache();

```