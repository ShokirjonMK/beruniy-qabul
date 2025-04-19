<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Questions $model */
/** @var common\models\Subjects $subject */

$this->title = Yii::t('app', 'Create Questions');
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => $subject->name_uz,
    'url' => ['subjects/view' , 'id' => $subject->id],
];
$breadcrumbs['item'][] = [
    'label' => 'Savollar',
    'url' => ['index' , 'id' => $subject->id],
];
?>
<div class="questions-create">

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
