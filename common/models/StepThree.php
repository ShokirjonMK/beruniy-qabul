<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 *
 */
class StepThree extends Model
{
    public static function createEduType($student)
    {
        $errors = [];
        $modelClassMap = [
            Student::QABUL => 'common\models\Exam',
            Student::PEREVOT => 'common\models\StudentPerevot',
            Student::DTM => 'common\models\StudentDtm',
            Student::MASTER => 'common\models\StudentMaster'
        ];

        $result = self::deleteNull($student->id);
        if (!$result['is_ok']) {
            $errors[] = ['Ma\'lumot yangilashda xatolik.'];
            return ['is_ok' => false , 'errors' => $errors];
        }

        if (!isset($modelClassMap[$student->edu_type_id])) {
            $errors[] = ['Xatolik'];
        }

        $modelClass = $modelClassMap[$student->edu_type_id];
        $new = new $modelClass();
        $new->setAttributes([
            'user_id' => $student->user_id,
            'student_id' => $student->id,
            'edu_type_id' => $student->edu_type_id,
            'edu_form_id' => $student->edu_form_id,
            'language_id' => $student->lang_id,
            'edu_direction_id' => $student->edu_direction_id,
            'direction_id' => $student->direction_id,
        ]);

        if ($student->edu_type_id === Student::PEREVOT) {
            $new->setAttributes([
                'direction_course_id' => $student->direction_course_id,
                'course_id' => $student->course_id,
            ]);
        }

        if (!$new->save(false)) {
            $errors[] = ['Ma\'lumot saqlashda xatolik yuz berdi.'];
        }

        $eduDirection = EduDirection::findOne($student->edu_direction_id);

        if (isset($eduDirection) && $eduDirection->is_oferta == 1) {
            StudentOferta::updateAll(['is_deleted' => 1] ,['student_id' => $student->id]);
            $oferta = new StudentOferta();
            $oferta->setAttributes([
                'user_id' => $student->user_id,
                'student_id' => $student->id,
                'edu_type_id' => $student->edu_type_id,
                'edu_form_id' => $student->edu_form_id,
                'language_id' => $student->lang_id,
                'edu_direction_id' => $student->edu_direction_id,
                'direction_id' => $student->direction_id,
            ]);
            $oferta->save(false);
        }

//        if ($student->edu_type_id === Student::QABUL) {
//            $directionSubjects = DirectionSubject::find()
//                ->where(['edu_direction_id' => $new->edu_direction_id, 'status' => 1, 'is_deleted' => 0])
//                ->all();
//
//            if (count($directionSubjects) !== 2) {
//                $errors[] = ['Fanlar yetarli emas.'];
//            }
//
//            foreach ($directionSubjects as $directionSubject) {
//                $subject = new ExamSubject();
//                $subject->setAttributes([
//                    'exam_id' => $new->id,
//                    'user_id' => $student->user_id,
//                    'student_id' => $student->id,
//                    'edu_type_id' => $student->edu_type_id,
//                    'edu_form_id' => $student->edu_form_id,
//                    'language_id' => $student->lang_id,
//                    'edu_direction_id' => $student->edu_direction_id,
//                    'direction_id' => $student->direction_id,
//                    'direction_subject_id' => $directionSubject->id,
//                    'subject_id' => $directionSubject->subject_id,
//                ]);
//                $subject->save(false);
//            }
//        }
        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false , 'errors' => $errors];
    }

    public static function deleteNull($studentId)
    {
        Student::updateAll([
            'exam_type' => 0,
            'direction_course_id' => null,
            'direction_id' => null,
            'edu_direction_id' => null,
            'lang_id' => null,
            'edu_form_id' => null,
            'course_id' => null,
            'edu_name' => null,
            'edu_direction' => null,
            'exam_date_id' => null,
        ], ['id' => $studentId]);

        foreach (['common\models\Exam', 'common\models\ExamSubject','common\models\StudentDtm', 'common\models\StudentPerevot', 'common\models\StudentMaster', 'common\models\StudentOferta'] as $table) {
            if (class_exists($table)) {
                call_user_func([$table, 'updateAll'], ['is_deleted' => 1], ['student_id' => $studentId, 'is_deleted' => 0]);
            }
        }

        return ['is_ok' => true];
    }

}
