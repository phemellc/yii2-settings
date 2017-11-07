<?php
/**
 * @link http://phe.me
 * @copyright Copyright (c) 2014 Pheme
 * @license MIT http://opensource.org/licenses/MIT
 */

use yii\helpers\Html;
use yii\grid\GridView;
use pheme\settings\Module;
use pheme\settings\models\Setting;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var pheme\settings\models\SettingSearch $searchModel
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Module::t('settings', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?=
        Html::a(
            Module::t(
                'settings',
                'Create {modelClass}',
                [
                    'modelClass' => Module::t('settings', 'Setting'),
                ]
            ),
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?=
    GridView::widget(
        [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'id',
                //'type',
                [
                    'attribute' => 'section',
                    'filter' => ArrayHelper::map(
                        Setting::find()->select('section')->distinct()->where(['<>', 'section', ''])->all(),
                        'section',
                        'section'
                    ),
                ],
                'key',
                'value:ntext',
                [
                    'class' => '\pheme\grid\ToggleColumn',
                    'attribute' => 'active',
                    'filter' => [1 => Yii::t('yii', 'Yes'), 0 => Yii::t('yii', 'No')],
                ],
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]
    ); ?>
    <?php Pjax::end(); ?>
</div>
