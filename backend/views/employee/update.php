<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Employee $model */
/** @var common\models\Constalting $cons */
/** @var common\models\AuthItem $roles */
/** @var common\models\AuthItem $role */

$this->title = Yii::t('app', 'Xodim ma\'lumotini o\'zgartirish');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Xodimlar'),
    'url' => ['index'],
];
?>
<div class="employee-update">

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php
            foreach ($breadcrumbs['item'] as $item) {
                echo "<li class='breadcrumb-item'><a href='". Url::to($item['url']) ."'>". $item['label'] ."</a></li>";
            }
            ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
