<?php
/* @var $this yii\web\View */
/* @var $macAddresses \app\models\MacAddresses[] */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->registerJsFile('@web/js/mac_list.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$dataProvider = new ArrayDataProvider([
    'allModels' => $macAddresses,
    'sort' => [
        'attributes' => ['id', 'name', 'status', 'ip'],
    ],
    'pagination' => [
        'pageSize' => 20,
    ]
]);
?>
<h1>Список добавленных Mac-адресов</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'rowOptions' => function ($model) {
        $rowClass = $model->status ? 'alert-success' : 'alert-danger';

        return ['class' => $rowClass];
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'name',
        'ip',
        [
            'attribute' => 'status',
            'value' => function ($data) {
                return $data->status ? 'Активен': 'Не активен';
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{switch}',
            'buttons' => [
                'switch' => function ($url, $model, $key) {
                    $labelBtn = $model->status ? 'Деактивировать' : 'Активировать';
                    $btnClass = $model->status ? 'btn-danger' : 'btn-success';

                    return Html::a($labelBtn , Url::toRoute('mac/switch/' . $model->id), ['class' => 'btn btn-xs change-status ' . $btnClass, 'data-id' => $model->id]);
                }
            ]
        ],
    ],
]); ?>
