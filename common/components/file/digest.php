<?php
use common\models\Student;
use common\models\Direction;

/** @var Student $student */
/** @var Direction $direction */

$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$full_name = $student->last_name . ' ' . $student->first_name . ' ' . $student->middle_name;
$date = date("Y-m-d");
$modelMap = [
    1 => ['model' => \common\models\Exam::class, 'statusField' => 'status', 'statusValue' => 3],
    2 => ['model' => \common\models\StudentPerevot::class, 'statusField' => 'file_status', 'statusValue' => 2],
    3 => ['model' => \common\models\StudentDtm::class, 'statusField' => 'file_status', 'statusValue' => 2],
    4 => ['model' => \common\models\StudentMaster::class, 'statusField' => 'file_status', 'statusValue' => 2],
];

if (isset($modelMap[$student->edu_type_id])) {
    $config = $modelMap[$student->edu_type_id];

    $contract = $config['model']::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        $config['statusField'] => $config['statusValue'],
        'is_deleted' => 0
    ]);

    if ($contract && $contract->confirm_date) {
        $date = date("Y-m-d", $contract->confirm_date);
    }
}
?>

<table width="100%" style="font-family: 'Times New Roman'; font-size: 16px; border-collapse: collapse;">
    <tr>
        <td colspan="1" style="border: 1px solid #222F3E;  padding: 10px;">
            <img src="/frontend/web/images/logo_mal.png" alt="" style="width: 130px;">
            <img src="/frontend/web/images/litsenziya.png" alt="" style="width: 130px;">
            <br>
            <p>№ <?= $student->passport_serial.$student->passport_number ?><br><?= $date ?></p>
        </td>
        <td colspan="3" style="text-align: justify; border: 1px solid #222F3E; padding: 10px;">
            <p style="text-align: center; font-weight: bold; font-size: 18px; width: 100%; display: block;">MA’LUMOTNOMA</p>
            <br>
            <p>
                Abituriyent <b style="text-transform: uppercase;"><?= $full_name ?></b>
                2025-2026-o‘quv yilida <b style="text-transform: uppercase;">“ABU RAYHON BERUNIY UNIVERSITETI”</b> oliy ta’lim muassasasining <b style="text-transform: uppercase;"><?= $direction->name ?></b> yo‘nalishi <?= $eduDirection->eduForm->name_uz ?? '---' ?> ta’lim shakli bo‘yicha
                to‘lov shartnoma asosida talabalikka tavsiya etildi.
            </p>

            <br>

            <img src="/frontend/web/images/pechat.png" alt="" width="60%">
        </td>
    </tr>
</table>