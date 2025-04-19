<?php
use common\models\Student;

/** @var $student */
/** @var $type */

if ($type == 2) {
    if ($student->language_id == 1) {
        echo $this->renderAjax('con2-uz', [
            'student' => $student
        ]);
    } elseif ($student->language_id == 3) {
        echo $this->renderPartial('con2-ru', [
            'student' => $student
        ]);
    }
} elseif ($type == 3) {
    if ($student->language_id == 1) {
        echo $this->renderPartial('con3-uz', [
            'student' => $student
        ]);
    } elseif ($student->language_id == 3) {
        echo $this->renderPartial('con3-ru', [
            'student' => $student
        ]);
    }
}
?>







