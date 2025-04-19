<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DirectionSubject $model */
/** @var common\models\EduDirection $eduDirection */

$this->title = 'Qo\'shish';
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Ta\'lim yo\'nalishlari'),
    'url' => ['edu-direction/index'],
];
$breadcrumbs['item'][] = [
    'label' => $eduDirection->direction->name.' yo\'nalish fanlari',
    'url' => ['index' , 'id' => $eduDirection->id],
];
?>
<div class="page">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <?php foreach ($breadcrumbs['item'] as $item) : ?>
            <li class='breadcrumb-item'>
                <?= Html::a($item['label'], $item['url'], ['class' => '']) ?>
            </li>
            <?php endforeach; ?>
            <li class="breadcrumb-item active" aria-current="page"><?= Html::encode($this->title) ?></li>
        </ol>
    </nav>

    <div class="form-section">
        <div class="form-section_item">
            <?= $this->render('_form', [
                'model' => $model,
                'eduDirection' => $eduDirection,
            ]) ?>
        </div>
    </div>

</div>
