<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

namespace pheme\settings\models;

use Yii;
use yii\helpers\Json;
use yii\base\DynamicModel;
use pheme\settings\Module;
use yii\base\InvalidParamException;

/**
 * This is the model class for table "settings".
 *
 * @author Aris Karageorgos <aris@phe.me>
 */
class Setting extends BaseSetting
{
    /**
     * @param bool $forDropDown if false - return array or validators, true - key=>value for dropDown
     * @return array
     */
    public function getTypes($forDropDown = true)
    {
        $values = [
            'string' => ['value', 'string'],
            'integer' => ['value', 'integer'],
            'boolean' => ['value', 'boolean', 'trueValue' => "1", 'falseValue' => "0", 'strict' => true],
            'float' => ['value', 'number'],
            'email' => ['value', 'email'],
            'ip' => ['value', 'ip'],
            'url' => ['value', 'url'],
            'object' => [
                'value',
                function ($attribute, $params) {
                    $object = null;
                    try {
                        Json::decode($this->$attribute);
                    } catch (InvalidParamException $e) {
                        $this->addError($attribute, Module::t('settings', '"{attribute}" must be a valid JSON object', [
                            'attribute' => $attribute,
                        ]));
                    }
                }
            ],
        ];

        if (!$forDropDown) {
            return $values;
        }

        $return = [];
        foreach ($values as $key => $value) {
            $return[$key] = Module::t('settings', $key);
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'string'],
            [['section', 'key'], 'string', 'max' => 255],
            [
                ['key'],
                'unique',
                'targetAttribute' => ['section', 'key'],
                'message' =>
                    Module::t('settings', '{attribute} "{value}" already exists for this section.')
            ],
            ['type', 'in', 'range' => array_keys($this->getTypes(false))],
            [['type', 'created', 'modified'], 'safe'],
            [['active'], 'boolean'],
        ];
    }

    public function beforeSave($insert)
    {
        $validators = $this->getTypes(false);
        if (!array_key_exists($this->type, $validators)) {
            $this->addError('type', Module::t('settings', 'Please select correct type'));
            return false;
        }

        $model = DynamicModel::validateData([
            'value' => $this->value
        ], [
            $validators[$this->type],
        ]);

        if ($model->hasErrors()) {
            $this->addError('value', $model->getFirstError('value'));
            return false;
        }

        if ($this->hasErrors()) {
            return false;
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('settings', 'ID'),
            'type' => Module::t('settings', 'Type'),
            'section' => Module::t('settings', 'Section'),
            'key' => Module::t('settings', 'Key'),
            'value' => Module::t('settings', 'Value'),
            'active' => Module::t('settings', 'Active'),
            'created' => Module::t('settings', 'Created'),
            'modified' => Module::t('settings', 'Modified'),
        ];
    }
}
