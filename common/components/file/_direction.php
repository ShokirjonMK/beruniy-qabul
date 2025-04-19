<?php

use common\models\Student;
use common\models\Direction;
use common\models\Course;

/** @var Student $student */
/** @var Direction $direction */
?>



<tr>
    <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
        <table width="100%">
            <tr>
                <td>O‘quv kursi:</td>
                <?php if ($student->edu_type_id == 2) : ?>
                    <td><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                <?php else: ?>
                    <td><b>1 kurs</b></td>
                <?php endif; ?>
            </tr>
            <tr>
                <td>Ta’lim shakli:</td>
                <td><b><?= $direction->eduForm->name_uz ?></b></td>
            </tr>
            <tr>
                <td>O‘qish muddati:</td>
                <td><b><?= $direction->duration .' yil' ?></b></td>
            </tr>
            <tr>
                <td>Ta’lim yo‘nalishi:</td>
                <td><b><?= $direction->direction->code.' '.$direction->direction->name_uz ?></b></td>
            </tr>
        </table>
    </td>
</tr>