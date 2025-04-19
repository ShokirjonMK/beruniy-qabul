<?php
use yii\helpers\Url;
use common\models\Student;
use common\models\StudentPerevot;
use yii\helpers\Html;
use common\models\StudentOferta;
use common\models\Direction;
use common\models\Exam;
use common\models\Course;
use common\models\Status;
use common\models\ExamSubject;
use common\models\Options;
use yii\widgets\LinkPager;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Student $student */
/** @var Direction $direction */
/** @var Exam $exam */

$lang = Yii::$app->language;
$this->title = Yii::t("app" , "a141");
$questionData = $dataProvider->getModels();
$finishTime = (date("m/d/Y H:i:s", $exam->finish_time));
$examSubjects = $exam->examSubjects;
$direction = $student->direction;
?>


<div class="ika_test">
    <div class="test">
        <div class="test-left">

            <div class="mobile-test">
                <div class="mobile-test-time">
                    <div class="page-card">
                        <div class="page-card-item">
                            <div class="test-time-item">
                                <div class="test-time-item-left divBorderRight">
                                    <img src="/frontend/web/images/clock.gif" alt="">
                                </div>
                                <div class="test-time-item-center">
                                    <h6><span id="day">1156</span> : <span id="hour">14</span> : <span id="minute">35</span> : <span id="secund">01</span></h6>
                                </div>
                                <button class="test-time-item-left divBorderLeft" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight1" aria-controls="offcanvasRight">
                                    <img src="/frontend/web/images/list.gif" alt="">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offSidebarSecond offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight1" aria-labelledby="offcanvasRightLabel">
                <div class="offSidebarItemSecond">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel">
                            <img src="/frontend/web/images/clock.gif" alt="">
                        </h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">

                        <div class="test-time">
                            <p>Vaqtingiz yakunlanishiga shuncha vaqt qoldi: <span>kun</span> | <span>soat</span> | <span>minut</span> | <span>sekund</span></p>
                            <h6><span id="day2">00</span> : <span id="hour2">00</span> : <span id="minute2">00</span> : <span id="secund2">00</span></h6>
                        </div>
                        <div class="question-info">
                            <div class="ika_subjects">

                                <?php if (count($examSubjects) > 0) : ?>
                                    <?php foreach ($examSubjects as $examSubject) : ?>
                                        <?php $directionSubject = $examSubject->directionSubject ?>
                                        <?php $subjectQuestions = $examSubject->studentQuestions; ?>
                                        <?php $startCount = '-'; ?>
                                        <?php $endCount = '-'; ?>
                                        <?php if ($directionSubject->count > 0) : ?>
                                            <?php $i = 0; ?>
                                            <?php foreach ($subjectQuestions as $subjectQuestion) : ?>
                                                <?php
                                                if ($i == 0) {
                                                    $startCount = $subjectQuestion->order;
                                                } else {
                                                    $endCount = $subjectQuestion->order;
                                                }
                                                $i++;
                                                ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <p class="subject-title"><?= $examSubject->subject['name_'.$lang] ?></p>
                                        <div class="subject-info">
                                            <p><span>Savollar soni: <?= $directionSubject->count ?> ta &nbsp; | &nbsp; <?= $startCount ?>-<?= $endCount ?> gacha</span></p>
                                            <p><span>Har bir savolga beriladigan bal: <?= $directionSubject->ball ?></span></p>
                                        </div>
                                        <?php if ($directionSubject->count > 0) : ?>
                                            <div class="subject-question-number">
                                                <ul>
                                                    <?php foreach ($subjectQuestions as $subjectQuestion) : ?>
                                                        <?php
                                                        $page = (int)($subjectQuestion->order / 10);
                                                        $urlPage = $page;
                                                        ?>
                                                        <li id="order_<?= $subjectQuestion->order; ?>" class="<?php if ($subjectQuestion->option_id != null) { echo "active";} ?>">
                                                        <span>
                                                            <a href="<?= Url::to(['cabinet/test' , 'page' => $urlPage , 'per-page' => 10]) ?>"><?= $subjectQuestion->order; ?></a>
                                                        </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        <br>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <button class="testTheEnd" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <span>Yakunlash</span>
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <?php if (count($questionData) > 0) : ?>
                <?php $number = 1; ?>
                <?php foreach ($questionData as $grantStudentQuestions) : ?>
                    <?php  $question = $grantStudentQuestions->question;  ?>
                    <?php  $options = Options::options($question->id , $grantStudentQuestions->option); ?>
                    <div class="question">
                        <div class="page-card">
                            <div class="page-card-item">
                                <!-- Questions -->
                                <div class="question-item">
                                    <p class="question-number"><?= $grantStudentQuestions->order ?> - savol.</p>

                                    <?php if ($question->text != null) : ?>
                                        <div class="question-text">
                                            <p><?= $question->text ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($question->image != null) : ?>
                                        <div class="question-img">
                                            <img src="/backend/web/uploads/questions/<?= $question->image ?>">
                                        </div>
                                    <?php endif; ?>


                                    <div class="options">
                                        <?php if (count($options) > 0) : ?>
                                            <?php $varinat = 1; ?>
                                            <?php foreach ($options as $option) : ?>

                                                <div class="inputDiv">
                                                    <input type="radio"
                                                           name="question_name_<?= $grantStudentQuestions->order ?>"
                                                           id="questionId_<?= $number ?>"
                                                           data-question="<?= $grantStudentQuestions->id ?>"
                                                           data-option="<?= $option->id ?>"
                                                           data-order="<?= $grantStudentQuestions->order ?>"
                                                        <?php if ($grantStudentQuestions->option_id == $option->id) { echo "checked";} ?>
                                                           class="visually-hidden">
                                                    <label for="questionId_<?= $number ?>" class="option-label">
                                                        <div class="option-label-left">
                                                        <span>
                                                            <?php
                                                            $variants = ["A", "B", "C", "D", "E"];

                                                            if ($varinat >= 1 && $varinat <= count($variants)) {
                                                                echo $variants[$varinat - 1];
                                                            } else {
                                                                echo "X";
                                                            }
                                                            $varinat++;
                                                            ?>
                                                        </span>
                                                        </div>
                                                        <div class="option-label-right">
                                                            <?php if ($option->text != null) : ?>
                                                                <p>
                                                                    <?= $option->text ?>
                                                                </p>
                                                            <?php endif; ?>
                                                            <?php if ($option->image != null) : ?>
                                                                <div class="question-img">
                                                                    <img src="/backend/web/uploads/options/<?= $option->image ?>" alt="RASN MAVJUD EMAS">
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </label>
                                                </div>

                                                <?php $number++ ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'linkOptions' => ['class' => 'paginationLink'],
                    'linkContainerOptions' => ['class' => 'paginationConOpt'],
                    'firstPageLabel' => '<i class="bi bi-arrow-left-short"></i>',
                    'lastPageLabel' => '<i class="bi bi-arrow-right-short"></i>',
                    'nextPageLabel' => false,
                    'prevPageLabel' => false,
                ]);
                ?>

            <?php endif; ?>

        </div>

        <div class="test-right">
            <div class="test-right-sticky">
                <div class="page-card">
                    <div class="page-card-item">
                        <div class="test-time">
                            <p>Vaqtingiz yakunlanishiga shuncha vaqt qoldi: <span>kun</span> | <span>soat</span> | <span>minut</span> | <span>sekund</span></p>
                            <h6><span id="day1">00</span> : <span id="hour1">00</span> : <span id="minute1">00</span> : <span id="secund1">00</span></h6>
                        </div>
                        <div class="question-info">
                            <div class="ika_subjects">

                                <?php if (count($examSubjects) > 0) : ?>
                                    <?php foreach ($examSubjects as $examSubject) : ?>
                                        <?php $subjectQuestions = $examSubject->studentQuestions; ?>
                                        <?php $qCount = count($subjectQuestions); ?>
                                        <?php $startCount = '-'; ?>
                                        <?php $endCount = '-'; ?>
                                        <?php if ($qCount > 0) : ?>
                                            <?php $i = 0; ?>
                                            <?php foreach ($subjectQuestions as $subjectQuestion) : ?>
                                                <?php
                                                if ($i == 0) {
                                                    $startCount = $subjectQuestion->order;
                                                } else {
                                                    $endCount = $subjectQuestion->order;
                                                }
                                                $i++;
                                                ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <p class="subject-title"><?= $examSubject->subject['name_'.$lang] ?></p>
                                        <div class="subject-info">
                                            <p><span>Savollar soni: <?= $qCount ?> ta &nbsp; | &nbsp; <?= $startCount ?>-<?= $endCount ?> gacha</span></p>
                                            <p><span>Har bir savolga beriladigan bal: <?= $examSubject->ball ?></span></p>
                                        </div>
                                        <?php if ($qCount > 0) : ?>
                                            <div class="subject-question-number">
                                                <ul>
                                                    <?php foreach ($subjectQuestions as $subjectQuestion) : ?>
                                                        <?php
                                                        $page = (int)($subjectQuestion->order / 10);
                                                        $urlPage = $page;
                                                        ?>
                                                        <li data-order="<?= $subjectQuestion->order; ?>" id="order1_<?= $subjectQuestion->order; ?>" class="<?php if ($subjectQuestion->option_id != null) { echo "active";} ?>">
                                                        <span>
                                                            <a href="<?= Url::to(['cabinet/test' , 'page' => $urlPage , 'per-page' => 10]) ?>"><?= $subjectQuestion->order; ?></a>
                                                        </span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        <br>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <button class="testTheEnd" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <span>Yakunlash</span>
                                </button>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="ikmodel">
                <div class="ikmodel_item">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert_question">
                            <div class="alert_danger_circle">
                                <div class="alert_danger_box">
                                    <i class="fa-solid fa-question"></i>
                                </div>
                            </div>
                            <p>
                                <?= Yii::t("app" , "a143") ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-around align-items-center top35">
                            <?= Html::button(Yii::t("app" , "a73"), ['class' => 'step_left_btn step_btn', 'data-bs-dismiss' => 'modal']) ?>
                            <?= Html::a(Yii::t("app" , "a144"), ['cabinet/finish', 'id' => $exam->id], ['class' => 'step_right_btn step_btn', 'name' => 'login-button']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$js = <<<JS
    (function () {
        const   second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;
    
        const countDown = new Date(' $finishTime ').getTime();
        const x = setInterval(function () {
            const now = new Date().getTime();
            const distance = countDown - now;
            
            const d = Math.floor(distance / day);
            const h = Math.floor((distance % day) / hour);
            const m = Math.floor((distance % hour) / minute);
            const s = Math.floor((distance % minute) / second);
     
            document.getElementById("day").innerText = formatTime(d);
            document.getElementById("day1").innerText = formatTime(d);
            document.getElementById("day2").innerText = formatTime(d);
            document.getElementById("hour").innerText = formatTime(h);
            document.getElementById("hour1").innerText = formatTime(h);
            document.getElementById("hour2").innerText = formatTime(h);
            document.getElementById("minute").innerText = formatTime(m);
            document.getElementById("minute1").innerText = formatTime(m);
            document.getElementById("minute2").innerText = formatTime(m);
            document.getElementById("secund").innerText = formatTime(s);
            document.getElementById("secund1").innerText = formatTime(s);
            document.getElementById("secund2").innerText = formatTime(s);
            
            if (distance < 0) {
                clearInterval(x);
                window.location.reload();
                return;
            }
        }, 1000);
        
        function formatTime(time) {
            return time >= 0 ? (time < 10 ? "0" + time : time) : "--";
        }
    }());

    $(document).ready(function() {
        $(".inputDiv input").on('change', function () {
            var question = $(this).data('question');
            var option = $(this).data('option');
            var order = $(this).data('order');
            $.ajax({
                url: '../file/option-change/',
                data: {
                    question: question,
                    option: option
                },
                type: 'POST',
                success: function (data) {
                    if (data == 1) {
                        $("#order_"+order).addClass('active');
                        $("#order1_"+order).addClass('active');
                    } else {
                        window.location.reload();   
                    }
                },
                error: function () {
                     window.location.reload();
                }
            });
        });
    });

    JS;
$this->registerJs($js);
?>