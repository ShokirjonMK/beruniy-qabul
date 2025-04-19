<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam_date".
 *
 * @property int $id
 * @property string|null $date
 * @property int|null $status
 * @property int|null $limit
 * @property int|null $branch_id
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 *
 * @property Student[] $students
 * @property Student[] $branch
 */
class ExamDate extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exam_date';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'branch_id', 'status', 'limit'], 'required'],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted' , 'branch_id', 'limit'], 'integer'],
            [['date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'branch_id' => Yii::t('app', 'Filial'),
            'date' => Yii::t('app', 'Date'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
        ];
    }

    /**
     * Gets query for [[Students]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStudents()
    {
        return $this->hasMany(Student::class, ['exam_date_id' => 'id']);
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
    }

    public function getName()
    {
        $status = '';
        if ($this->status == 0 && $this->is_deleted == 1) {
            $status = 'O\'chirilgan';
        } elseif ($this->status == 0 && $this->is_deleted == 0) {
            $status = 'No faol';
        } elseif ($this->status == 1 && $this->is_deleted == 0) {
            $status = 'Faol';
        }
        return $this->date.' '.$status;
    }

    public static function createItem($model, $post) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$model->validate()) {
            $errors[] = $model->simple_errors($model->errors);
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        if ($model->limit == 0) {
            $model->limit = 60;
        }

        // Modelni saqlash
        if (!$model->save(false)) {
            $errors[] = ['Model saqlashda xatolik yuz berdi.'];
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }

        $query = ExamDate::findOne([
            'status' => 1,
            'is_deleted' => 0,
            'branch_id' => $model->branch_id,
        ]);
        if (!$query) {
            $errors[] = ['Aktiv imtixon sanasi mavjud emas.'];
        }

        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

    public static function deleteItem($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $model->is_deleted = 1;
        $model->status = 0;
        $model->save(false);

        $examDate = ExamDate::findOne([
            'status' => 1,
            'branch_id' => $model->branch_id,
            'is_deleted' => 0,
        ]);
        if (!$examDate) {
            $errors[] = ['Aktiv imtixon sanasi mavjud emas.'];
        } else {
            $students = Student::find()
                ->joinWith('user')
                ->where([
                    'student.exam_date_id' => $model->id,
                ])
                ->andWhere(['user.status' => [9, 10]])
                ->all();

            foreach ($students as $student) {
                $user = $student->user;
                $new = new CrmPush();
                $new->student_id = $student->id;
                $new->type = 101;
                $new->lead_id = $user->lead_id;
                $new->data = json_encode([
                    CrmPush::EXAM_DATE => $examDate->date,
                ], JSON_UNESCAPED_UNICODE);
                $new->save(false);
            }
        }

        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

    public static function dateCheck() {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $fromDate = date('Y-m-d');

        $branchs = Branch::find()
            ->all();
        foreach ($branchs as $branch) {
            $activeDate = 0;
            $examDates = ExamDate::find()
                ->where([
                    'branch_id' => $branch->id,
                    'is_deleted' => 0
                ])->all();
            foreach ($examDates as $examDate) {
                if ($examDate->status == 1) {
                    if ($examDate->date <= $fromDate) {
                        $examDate->status = 0;
                        $examDate->save(false);
                    } else {
                        $activeDate++;
                    }
                }
            }

            if ($activeDate < 4) {
                $examDateDesc = ExamDate::find()
                    ->where([
                        'branch_id' => $branch->id,
                        'is_deleted' => 0,
                        'status' => 1,
                    ])
                    ->orderBy('date desc')
                    ->one();

                if (!$examDateDesc) {
                    $date = date("Y-m-d");
                } else {
                    $date = $examDateDesc->date;
                }

                $date = new \DateTime($date);

                if ($date->format('N') != 6) {
                    $daysToAdd = 6 - $date->format('N');
                    if ($daysToAdd <= 0) {
                        $daysToAdd += 7;
                    }
                    $date->modify("+$daysToAdd days");
                }

                for ($i = 1; $i <= (4 - $activeDate); $i++) {
                    $t = true;
                    while ($t) {
                        $query = ExamDate::findOne([
                            'branch_id' => $branch->id,
                            'date' => $date->format('Y-m-d H:i'),
                            'is_deleted' => 0
                        ]);
                        if ($query) {
                            $date->modify('+7 days');
                        } else {
                            $t = false;
                        }
                    }
                    $new = new ExamDate();
                    $new->branch_id = $branch->id;
                    $new->date = $date->format('Y-m-d H:i');
                    $new->status = 1;
                    $new->save(false);
                    $date->modify('+7 days');
                }
            }
        }

        if (empty($errors)) {
            $transaction->commit();
            return ['is_ok' => true];
        } else {
            $transaction->rollBack();
            return ['is_ok' => false, 'errors' => $errors];
        }
    }

}
