<?php
/* @var $this yii\web\View */
/* @var $model app\models\IpAddresses */
/* @var $form ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCssFile('@web/css/mac_generate.css');
$this->registerJsFile('@web/js/mac_generate.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<h1>Генерация случайного Mac-адреса</h1>

<div class="row">
    <div class="col-md-4">
        <?php $form = ActiveForm::begin(['id' => 'generate-form']); ?>

        <?= $form->field($model, 'name'); ?>

        <div class="form-group">
            <?= Html::submitButton('Сгенерировать', ['class' => 'btn btn-primary']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="response-message">
            <div class="alert alert-success"></div>
        </div>
    </div>
</div>
