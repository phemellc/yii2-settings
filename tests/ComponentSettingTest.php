<?php
/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/7
 * Time: 下午6:00
 */

namespace pheme\settings\tests;

use Yii;

class ComponentSettingTest extends TestCase
{
    /**
     * @var \pheme\settings\components\Settings
     */
    public $setting;

    public function setUp()
    {
        parent::setUp();
        $this->setting = Yii::$app->settings;
        $this->model->value = "i am testSet value";
        $this->model->section = "testSetKey";
        $this->model->type = 'string';
        $this->model->modified = time();
        $this->model->active = "0";
        $this->model->key = 'testSetKey';
        $this->model->save();
    }

    public function testSet()
    {
        $res = $this->setting->set('testSetKey', "i am testSet value", 'testSetKey');
        $this->assertTrue($res, '通过组件来修改testSetKey的section');
    }

    public function testGet()
    {
        $this->setting->activate("testSetKey", "testSetKey");
        $res = $this->setting->get("testSetKey", "testSetKey");
        $this->assertTrue($res == "i am testSet value");
    }

    public function testHas()
    {
        $this->setting->activate("testSetKey", "testSetKey");
        $res = $this->setting->has("testSetKey", "testSetKey", true);
    }

    public function testDelete()
    {
        $res = $this->setting->delete("testSetKey", "testSetKey");
        $this->assertTrue($res == 1);
    }

    public function testDeleteAll()
    {
        $res = $this->setting->deleteAll();
        $this->assertTrue($res > 0);
    }

    public function testActivate()
    {
        $res = $this->setting->activate("testSetKey", "testSetKey");
        $this->assertTrue($res);
    }
}
