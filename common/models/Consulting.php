<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "consulting".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $hr
 * @property string|null $bank_name_uz
 * @property string|null $bank_name_ru
 * @property string|null $bank_name_en
 * @property string|null $bank_adress_uz
 * @property string|null $bank_adress_ru
 * @property string|null $bank_adress_en
 * @property string|null $pochta_address
 * @property string|null $mail
 * @property string|null $pochta_phone
 * @property string|null $mfo
 * @property string|null $inn
 * @property string|null $tel1
 * @property string|null $tel2
 * @property string|null $domen
 * @property string|null $code
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property User[] $users
 * @property $chalaStudentsCount
 * @property $studentsCount
 * @property $contractLoad
 * @property $contract
 */
class Consulting extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    public $filial;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consulting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'], 'integer'],
            [['tel1', 'tel2' , 'pochta_phone'], 'match',
                'pattern' => '/^[+][0-9]{3} [(][0-9]{2}[)] [0-9]{3}-[0-9]{2}-[0-9]{2}$/',
                'message' => 'Telefon raqamni to\'g\'ri formatda kiriting.'
            ],
            [['pochta_phone', 'mail' , 'pochta_address', 'name', 'hr', 'bank_name_uz', 'bank_name_ru', 'bank_name_en', 'bank_adress_uz', 'bank_adress_ru', 'bank_adress_en', 'mfo', 'inn', 'tel1', 'tel2', 'domen', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'hr' => Yii::t('app', 'Hr'),
            'bank_name_uz' => Yii::t('app', 'Bank Name Uz'),
            'bank_name_ru' => Yii::t('app', 'Bank Name Ru'),
            'bank_name_en' => Yii::t('app', 'Bank Name En'),
            'bank_adress_uz' => Yii::t('app', 'Bank Adress Uz'),
            'bank_adress_ru' => Yii::t('app', 'Bank Adress Ru'),
            'bank_adress_en' => Yii::t('app', 'Bank Adress En'),
            'mfo' => Yii::t('app', 'Mfo'),
            'inn' => Yii::t('app', 'Inn'),
            'tel1' => Yii::t('app', 'Tel1'),
            'tel2' => Yii::t('app', 'Tel2'),
            'domen' => Yii::t('app', 'Domen'),
            'code' => Yii::t('app', 'Code'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['cons_id' => 'id']);
    }

    public function getStudentsCount()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin('user u', 's.user_id = u.id')
            ->where([
                'u.user_role' => 'student',
                'u.status' => [9,10],
                's.is_deleted' => 0,
                'u.step' => 5,
                'u.cons_id' => $this->id,
            ])->count();
    }

    public function getChalaStudentsCount()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin('user u', 's.user_id = u.id')
            ->where([
                'u.user_role' => 'student',
                'u.status' => [9,10],
                's.is_deleted' => 0,
                'u.cons_id' => $this->id,
            ])
            ->andWhere(['<', 'u.step' , 5])
            ->count();
    }

    public function getContract()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
                'u.cons_id' => $this->id,
            ])
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->count();
    }

    public function getContractLoad()
    {
        return Student::find()
            ->alias('s')
            ->innerJoin(User::tableName() . ' u', 's.user_id = u.id')
            ->leftJoin(Exam::tableName() . ' e', 's.id = e.student_id AND e.status = 3 AND e.is_deleted = 0')
            ->leftJoin(StudentPerevot::tableName() . ' sp', 's.id = sp.student_id AND sp.file_status = 2 AND sp.is_deleted = 0')
            ->leftJoin(StudentDtm::tableName() . ' sd', 's.id = sd.student_id AND sd.file_status = 2 AND sd.is_deleted = 0')
            ->leftJoin(StudentMaster::tableName() . ' sm', 's.id = sm.student_id AND sm.file_status = 2 AND sm.is_deleted = 0')
            ->where([
                'u.step' => 5,
                'u.status' => [9, 10],
                'u.user_role' => 'student',
                's.is_deleted' => 0,
                'u.cons_id' => $this->id,
                's.is_down' => 1,
            ])
            ->andWhere([
                'or',
                ['not', ['e.student_id' => null]],
                ['not', ['sp.student_id' => null]],
                ['not', ['sd.student_id' => null]],
                ['not', ['sm.student_id' => null]]
            ])->count();
    }

    public static function saveItem($model, $post, $isNew = true) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        // Modelni tekshirish
        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        // Modelni saqlash
        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        // Yangi yozuv bo'lmasa eski filiallar o'chiriladi
        if (!$isNew) {
            ConsultingBranch::deleteAll(['consulting_id' => $model->id]);
        }

        // Filiallarni qo'shish
        if (isset($post['filial']) && count($post['filial']) > 0) {
            foreach ($post['filial'] as $branchId => $value) {
                if ($value == 1) {
                    $new = new ConsultingBranch();
                    $new->branch_id = $branchId;
                    $new->consulting_id = $model->id;
                    if (!$new->save(false)) {
                        $errors[] = ['Filial saqlashda xatolik yuz berdi.'];
                    }
                } else {
                    $errors[] = ['Xatolik'];
                }
            }
        } else {
            $errors[] = ['Filial yuborilishi shart.'];
        }

        // Natijalarni tekshirish
        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

    public static function createItem($model, $post) {
        return self::saveItem($model, $post, true);
    }

    public static function updateItem($model, $post) {
        return self::saveItem($model, $post, false);
    }

}
