<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Options $model */

$this->title = Yii::t('app', 'Yangi variant qo\'shish');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => 'Fanlar',
    'url' => ['subjects/index'],
];
$breadcrumbs['item'][] = [
    'label' => 'Savollar',
    'url' => ['questions/index' , 'id' => $model->question->subject_id],
];
$breadcrumbs['item'][] = [
    'label' => 'Variant savoli',
    'url' => ['questions/view' , 'id' => $model->question_id],
];
?>
<div class="options-create">

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
