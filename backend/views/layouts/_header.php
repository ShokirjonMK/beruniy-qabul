<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Student;
use common\models\Employee;

$user = Yii::$app->user->identity;
$logo = "/frontend/web/images/logo_blue.svg";
$employee = Employee::findOne(['user_id' => $user->id]);
$full_name = $employee->last_name . " " . $employee->first_name;
$postion = $user->authItem->description;
$cons = $user->cons;
?>
<div class="main_nav">
    <div class="navbar_item left-260">
        <div class="header">
            <div class="header_item">
                <div class="header_left">
                    <div class="close_nav display_close">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="logo">
                        <a href="#" title="O'quv markaz logo rasmi">
                            <img src="<?= $logo ?>" alt="">
                        </a>
                    </div>
                </div>
                <div class="header_right">

                    <!-- flag -->
                    <div class="nav_icon mr_20">
                        <a href="#">
                            <i class="fa-solid fa-envelope"></i>
                            <span>6</span>
                        </a>
                    </div>

                    <div class="user-profil">
                        <div class="dropdown">
                            <button class="dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user_title">
                                    <h6><?= $full_name ?></h6>
                                    <p><?= $postion ?></p>
                                </div>
                                <div class="user_img">
                                    <img src="<?= $logo ?>" alt="">
                                </div>
                            </button>
                            <ul class="drop_m dropdown-menu dropdown-menu-end">
                                <ul class="drop_m_ul">
                                    <li>
                                        <a href="<?= Url::to(['employee/view', 'id' => $employee->id]) ?>">
                                            <span>Profil</span>
                                            <i class="fa-solid fa-user"></i>
                                        </a>
                                    </li>

                                    <li>
                                        <form action="#">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                <span>Chiqish</span>
                                                <i class="fa-solid fa-right-from-bracket"></i>
                                            </button>
                                        </form>
                                    </li>

                                </ul>
                            </ul>
                        </div>
                    </div>

                    <div class="close_nav display_show">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="education-name">
            <h2>
                <a href="<?= Url::to(['site/index']) ?>">TOSHKENT GUMANITAR FANLAR UNIVERSITETI < <?= $cons->name ?> ></a>
            </h2>
        </div>
    </div>
</div>


<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="form-section">
                <div class="form-section_item">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Kabinetdan chiqish</h1>
                        <p class="btn-close" data-bs-dismiss="modal" aria-label="Close"></p>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center mt-3">
                            <?php
                            echo Html::beginForm(['/site/logout'], 'post', ['class' => ''])
                                . Html::submitButton(
                                    '<span>Chiqish</span> &nbsp;&nbsp; <i class="fa-solid fa-right-from-bracket"></i>',
                                    ['class' => 'b-btn b-primary']
                                )
                                . Html::endForm();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>