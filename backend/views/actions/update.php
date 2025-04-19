<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Actions $model */

$this->title = Yii::t('app', 'O\'zgartirish');
?>
<div class="page">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
