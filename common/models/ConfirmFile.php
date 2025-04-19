<?php

namespace common\models;

use common\models\Direction;
use common\models\EduDirection;
use common\models\EduForm;
use common\models\EduType;
use common\models\Lang;
use common\models\Student;
use common\models\User;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "student_oferta".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $student_id
 * @property int|null $edu_direction_id
 * @property int|null $direction_id
 * @property int|null $language_id
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property string|null $file
 * @property int|null $file_status
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Direction $direction
 * @property EduDirection $eduDirection
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property Lang $language
 * @property Student $student
 * @property User $user
 */
class ConfirmFile extends \yii\db\ActiveRecord
{
    public $file_status;

    public function rules()
    {
        return [
            [['file_status'], 'integer'],
        ];
    }

    public static function confirm($model, $old) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $student = $model->student;
        $user = $student->user;


        $model->user_id = $old->user_id;
        $model->student_id = $old->student_id;
        $model->edu_direction_id = $old->edu_direction_id;
        $model->direction_id = $old->direction_id;
        $model->language_id = $old->language_id;
        $model->edu_type_id = $old->edu_type_id;
        $model->edu_form_id = $old->edu_form_id;
        $model->file = $old->file;

        $model->contract_price = null;
        $model->confirm_date = null;

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $direction = $model->eduDirection;
        if ($model->file_status == 2) {
            $model->contract_price = $direction->price;
            $model->confirm_date = time();
        }
        if ($direction->is_oferta == 1) {
            $oferta = StudentOferta::findOne([
                'edu_direction_id' => $direction->id,
                'student_id' => $model->student_id,
                'status' => 1,
                'is_deleted' => 0
            ]);
            if ($oferta->file_status != 2) {
                $errors[] = ['Oferta tasdiqlanmagan'];
            }
        }

        $model->save(false);

        if (count($errors) == 0) {
            $student = $model->student;
            $student->is_down = 0;
            $student->update(false);

            $user = $student->user;
            if ($user->step == 5) {
                $amo = CrmPush::processType(6, $student, $user);
                if (!$amo['is_ok']) {
                    $transaction->rollBack();
                    return ['is_ok' => false , 'errors' => $amo['errors']];
                }
            }

            $transaction->commit();
            return ['is_ok' => true];
        }else {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
    }
}
