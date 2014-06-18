<?php

namespace pheme\settings\models;

use pheme\settings\Module;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property string $module
 * @property string $section
 * @property string $key
 * @property string $value
 */
class Setting extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['module', 'section', 'key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('settings', 'ID'),
            'module' => Module::t('settings', 'Module'),
            'section' => Module::t('settings', 'Section'),
            'key' => Module::t('settings', 'Key'),
            'value' => Module::t('settings', 'Value'),
        ];
    }
}
