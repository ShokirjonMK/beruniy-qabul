<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Permission;

$this->title = Yii::t('app', 'Ruxsatlar [ ').$role." ]";
$breadcrumbs = [];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Bosh sahifa'),
    'url' => ['/'],
];
$breadcrumbs['item'][] = [
    'label' => Yii::t('app', 'Rollar'),
    'url' => ['auth-item/index'],
];
?>
<div class="page">
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

    <?php $form = ActiveForm::begin(); ?>
    <div class="accordion">
        <?php if (isset($model)) : ?>
            <?php foreach ($model as $item) : ?>

                <p class="title mb-1"><?= $item->description ?></p>
                <div class="form-section mb-3">
                    <div class="form-section_item">
                        <div class="row">
                            <?php if (isset($item->actions)) : ?>
                                <?php foreach ($item->actions as $action) :  ?>

                                    <?php
                                        $check = false;
                                        $isPermission = Permission::findOne([
                                            'role_name' => $role,
                                            'action_id' => $action->id,
                                            'status' => 1
                                        ]);
                                        if (isset($isPermission)) {
                                            $check = true;
                                        }
                                    ?>
                                    <div class="mb-2 mt-2 col-lg-4 col-md-6 col-sm-12 col-12">
                                        <div class="d-flex align-items-center">
                                            <input type="checkbox" class="bu-check" name="action[<?= $action->id ?>]" id="check<?= $action->id ?>" <?php if ($check) { echo "checked";}  ?>>
                                            <label for="check<?= $action->id ?>" class="permission_label"><?= $action->description ?></label>
                                        </div>
                                    </div>

                                <?php endforeach;  ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="form-group d-flex justify-content-end mt-4 mb-5">
        <?= Html::submitButton(Yii::t('app', 'Saqlash'), ['class' => 'b-btn b-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

