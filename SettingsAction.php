<?php
namespace pheme\settings;

use yii\base\Action;
use Yii;
use pheme\settings\Module;

class SettingsAction extends Action {
	/**
     * @var string class name of the model which will be used to validate the attributes.
     * The class should have a scenario match `scenario` config for this action.
     * The model class must implement [[Model]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * The scenario this model should use to make validation
     * @var unknown
     */
    public $scenario;

    /**
     * @var string the name of the view to generate the form. Defaults to 'settings'.
     */
    public $viewName = 'settings';
	/**
	 * Render a setting form.
	 */
	public function run()
	{
		/* @var $model \yii\db\ActiveRecord */
		$model = new $this->modelClass();
		if ($this->scenario) $model->setScenario($this->scenario);
		if ($model->load(Yii::$app->request->post()) && $model->validate()){
			foreach ($model->toArray() as $key => $value) {
				Yii::$app->settings->set($key, $value, $model->formName());
			}
			Yii::$app->getSession()->addFlash('success', Module::t('app', 'Successfully save settings on {section}', ['section' => $model->formName()]));
		}
		foreach ($model->attributes() as $key){
			$model->$key = Yii::$app->settings->get($key, $model->formName());
		}
		return $this->controller->render($this->viewName, ['model' => $model]);
	}
}
