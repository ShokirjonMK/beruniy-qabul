<?php
use yii\helpers\Url;
use common\models\EduType;
use common\models\Menu;
use common\models\Permission;

$user = Yii::$app->user->identity;
$role = $user->authItem;
$logo = "/frontend/web/images/logo_blue.svg";

function getActive($cont, $act)
{
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if ($controller == $cont && $action == $act) {
        return [
            'style' => 'display: block;',
            'class' => 'menu_active',
        ];
    } else {
        return [
            'style' => '',
            'class' => '',
        ];
    }
}

function getActiveSubMenu($cont, $act)
{
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if ($controller == $cont && $action == $act) {
        return "sub_menu_active";
    } else {
        return false;
    }
}

function getActiveTwo($cont, $act)
{
    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    if ($controller == $cont && $action == $act) {
        return "active_menu";
    } else {
        return false;
    }
}

$menu = Menu::find()
    ->where([
        'status' => 0
    ])
    ->orderBy('order asc')
    ->all();
?>

<div id="sidebar" class="root_left">
    <div class="sidebar-item">
        <div class="close_button">
            <span></span>
            <span></span>
        </div>
        <div class="sidebar-logo">
            <a href="<?= Url::to(['/site/index']) ?>">
                <img src="<?= $logo ?>" alt="">
            </a>
        </div>

        <div class="sidebar_menu">
            <ul class="sidebar_ul">

                <?php foreach ($menu as $item) : ?>
                    <?php
                    $activeClass = "";
                    $activeStyle = "";
                    $drop = false;
                    if ($item->action_id == null) {
                        $drop = true;
                        $subMenus = Menu::find()
                            ->where([
                                'parent_id' => $item->id,
                                'status' => 1
                            ])
                            ->orderBy('order asc')
                            ->all();
                        if (isset($subMenus)) {
                            foreach ($subMenus as $style) {
                                $activeClass = $activeClass . getActive($style->action->controller, $style->action->action)['class']. " ";
                                $activeStyle = $activeStyle . getActive($style->action->controller, $style->action->action)['style']. " ";
                            }
                        }
                    }
                    if (!$drop) {
                        $isPermission = Permission::isPermission($item->action_id, $user);
                    }
                    ?>

                    <?php if ($isPermission) : ?>
                        <li class="sidebar_li <?php if ($drop) { echo "sidebar_drop";} echo $activeClass;?> ">
                            <a href="<?php if ($drop) { echo "javascript: void(0);";} else { echo Url::to([$item->action->controller."/".$item->action->action]); }  ?>" class="sidebar_li_link <?php if (!$drop) { echo getActiveTwo( $item->action->controller, $item->action->action); }  ?>">
                                <i class="i-n <?= $item->icon ?>"></i>
                                <span><?= $item->name ?></span>
                                <?php if ($drop) { echo "<i class='icon-n fa-solid fa-chevron-right'></i>";}  ?>
                            </a>
                            <?php if ($drop) : ?>
                                <div class="menu_drop" style="<?= $activeStyle ?>">
                                    <ul class="sub_menu_ul">
                                        <?php if (isset($subMenus)) : ?>
                                            <?php foreach ($subMenus as $subMenu) : ?>
                                                <?php $subMenuPermission = Permission::isPermission($subMenu->action_id, $user); ?>
                                                <?php if ($subMenuPermission) : ?>
                                                    <li class="sub_menu_li <?= getActiveSubMenu($subMenu->action->controller, $subMenu->action->action) ?>">
                                                        <a href="<?= Url::to([$subMenu->action->controller."/".$subMenu->action->action]) ?>">
                                                            <?= $subMenu->name ?>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                            <?php endif; ?>
                        </li>
                    <?php endif; ?>

                    <?php
                    $activeClass = "";
                    $activeStyle = "";
                endforeach; ?>
            </ul>
        </div>
    </div>
</div>