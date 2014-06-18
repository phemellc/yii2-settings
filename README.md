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

Once the extension is installed, simply use it in your code by  :