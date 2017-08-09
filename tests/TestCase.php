<?php

namespace pheme\settings\tests;

use pheme\settings\models\Setting;
use PHPUnit_Framework_TestCase;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/7
 * Time: 下午1:49
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var \pheme\settings\models\Setting
     */
    public $model;

    protected function setUp()
    {
        parent::setUp();
        $this->mockApplication();
        $this->createTestDbData();
        $this->model = new Setting();
    }

    protected function tearDown()
    {
        $this->destroyTestDbData();
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     *
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        return new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost:3306;dbname=test',
                    'username' => 'root',
                    'password' => '',
                    'tablePrefix' => 'tb_'
                ],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                        ]
                    ]
                ],
                'settings' => [
                    'class' => 'pheme\settings\components\Settings'
                ],
                'cache'  =>[
                    'class' =>'yii\caching\ArrayCache'
                ],
            ],
            'modules' => [
                'settings' => [
                    'class' => 'pheme\settings\Module',
                    'sourceLanguage' => 'en'
                ]
            ]
        ], $config));
    }

    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        return new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'components' => [
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=localhost:3306;dbname=test',
                    'username' => 'root',
                    'password' => '',
                    'tablePrefix' => 'tb_'
                ],
                'i18n' => [
                    'translations' => [
                        '*' => [
                            'class' => 'yii\i18n\PhpMessageSource',
                        ]
                    ]
                ],
                'settings' => [
                    'class' => 'pheme\settings\components\Settings'
                ],
                'cache'  =>[
                    'class' =>'yii\caching\ArrayCache'
                ],
            ],
            'modules' => [
                'settings' => [
                    'class' => '\pheme\settings\Module',
                    'sourceLanguage' => 'en'
                ]
            ]

        ], $config));
    }

    /**
     * @return string vendor path
     */
    protected function getVendorPath()
    {
        return dirname(__DIR__) . '/vendor';
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        if (Yii::$app && Yii::$app->has('session', true)) {
            Yii::$app->session->close();
        }
        Yii::$app = null;
    }

    protected function destroyTestDbData()
    {
        $db = Yii::$app->getDb();
        $res = $db->createCommand()->dropTable('tb_settings')->execute();
    }

    protected function createTestDbData()
    {
        //$this->mockApplication()->runAction('/migrate');
        $db = Yii::$app->getDb();
        try {
            $db->createCommand()->createTable('tb_settings', [
                'id' => 'pk',
                'type' => "string(255) not null",
                'section' => "string(255) not null",
                'key' => "string(255) not null",
                'value' => "text",
                'active' => "tinyint(1)",
                'created' => "datetime",
                'modified' => "datetime",
            ])->execute();
        } catch (Exception $e) {
            return;
        }
    }
}
