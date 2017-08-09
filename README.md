[![StyleCI](https://styleci.io/repos/99540308/shield?branch=master)](https://styleci.io/repos/99540308)
[![Build Status](https://travis-ci.org/monster-hunter/yii2-settings.svg?branch=master)](https://travis-ci.org/monster-hunter/yii2-settings)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/monster-hunter/yii2-settings/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/monster-hunter/yii2-settings/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/monster-hunter/yii2-settings/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/monster-hunter/yii2-settings/?branch=master)


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
        'sourceLanguage' => 'en'
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

SettingsAction
-----

To use a custom settings form, you can use the included `SettingsAction`.

1. Create a model class with your validation rules.
2. Create an associated view with an `ActiveForm` containing all the settings you need.
3. Add `pheme\settings\SettingsAction` to the controller's actions.

The settings will be stored in section taken from the form name, with the key being the field name.

__Model__:

```php
class Site extends Model {
	public $siteName, $siteDescription;
	public function rules()
	{
		return [
			[['siteName', 'siteDescription'], 'string'],
		];
	}
	
	public function fields()
	{
	        return ['siteName', 'siteDescription'];
	}
	
	public function attributes()
	{
	        return ['siteName', 'siteDescription'];
	}
}
```
__Views__:
```php
<?php $form = ActiveForm::begin(['id' => 'site-settings-form']); ?>
<?= $form->field($model, 'siteName') ?>
<?= $form->field($model, 'siteDescription') ?>
```
__Controller__:
```php
function actions(){
   return [
   		//....
            'site-settings' => [
                'class' => 'pheme\settings\SettingsAction',
                'modelClass' => 'app\models\Site',
                //'scenario' => 'site',	// Change if you want to re-use the model for multiple setting form.
                'viewName' => 'site-settings'	// The form we need to render
            ],
        //....
    ];
}
```
