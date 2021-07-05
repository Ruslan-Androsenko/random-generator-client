<?php
/* @var $this yii\web\View */

$this->registerCssFile('@web/css/mac_export.css');
$this->registerJsFile('@web/js/mac_export.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<h1>Выгрузка в CSV-файл</h1>

<div class="row">
    <div class="col-md-6">
        <div class="btn-group">
            <a class="btn btn-info" id="exportAll" href="/mac/exportAll/">Выгрузить все Mac-адреса</a>
        </div>

        <div class="btn-group">
            <a class="btn btn-primary" id="exportToSubnet" href="#">Выгрузить Mac-адреса в указанной подсети</a>
        </div>
    </div>
</div>

<div class="row subnet-form-wrapper">
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">Выберите подсеть</div>
            <div class="panel-body">
                <form class="form-horizontal" type="post" action="/mac/exportSubnet/">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="subnet" placeholder="192.168.1-255" required />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success">Выгрузить</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>