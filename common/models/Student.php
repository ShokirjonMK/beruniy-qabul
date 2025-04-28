<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "student".
 *
 * @property int $id
 * @property int $user_id
 * @property int $is_down
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $student_phone
 * @property string|null $username
 * @property string|null $password
 * @property int|null $gender
 * @property string|null $birthday
 * @property string|null $passport_number
 * @property string|null $passport_serial
 * @property string|null $passport_pin
 * @property string|null $passport_issued_date
 * @property string|null $passport_given_date
 * @property string|null $passport_given_by
 * @property string|null $adress
 * @property int|null $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $is_deleted
 * @property int|null $edu_type_id
 * @property int|null $edu_form_id
 * @property int|null $direction_id
 * @property int|null $edu_direction_id
 * @property int|null $exam_date_id
 * @property int|null $lang_id
 * @property int|null $direction_course_id
 * @property int|null $course_id
 * @property int|null $branch_id
 * @property int|null $exam_type
 * @property string|null $edu_name
 * @property string|null $edu_direction
 *
 * @property Course $course
 * @property Direction $direction
 * @property DirectionCourse $directionCourse
 * @property EduDirection $eduDirection
 * @property EduForm $eduForm
 * @property EduType $eduType
 * @property Lang $lang
 * @property User $user
 * @property User $branch
 * @property ExamDate $examDate
 * @property $ipCheck
 */
class Student extends \yii\db\ActiveRecord
{
    use ResourceTrait;

    const QABUL = 1;
    const PEREVOT = 2;
    const DTM = 3;
    const MASTER = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'student';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'is_down', 'gender', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'edu_type_id', 'edu_form_id', 'direction_id', 'edu_direction_id', 'lang_id', 'direction_course_id', 'course_id', 'exam_type', 'branch_id', 'exam_date_id'], 'integer'],
            [['birthday'], 'safe'],
            [['adress'], 'string'],
            [['first_name', 'last_name', 'middle_name', 'username', 'password', 'passport_number', 'passport_serial', 'passport_pin', 'passport_issued_date', 'passport_given_date', 'passport_given_by', 'edu_name', 'edu_direction'], 'string', 'max' => 255],
            [['student_phone'], 'string', 'max' => 100],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['direction_course_id'], 'exist', 'skipOnError' => true, 'targetClass' => DirectionCourse::class, 'targetAttribute' => ['direction_course_id' => 'id']],
            [['direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => Direction::class, 'targetAttribute' => ['direction_id' => 'id']],
            [['edu_direction_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduDirection::class, 'targetAttribute' => ['edu_direction_id' => 'id']],
            [['edu_form_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduForm::class, 'targetAttribute' => ['edu_form_id' => 'id']],
            [['edu_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EduType::class, 'targetAttribute' => ['edu_type_id' => 'id']],
            [['lang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branch_id' => 'id']],
            [['exam_date_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['exam_date_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'middle_name' => Yii::t('app', 'Middle Name'),
            'student_phone' => Yii::t('app', 'Student Phone'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'gender' => Yii::t('app', 'Gender'),
            'birthday' => Yii::t('app', 'Birthday'),
            'passport_number' => Yii::t('app', 'Passport Number'),
            'passport_serial' => Yii::t('app', 'Passport Serial'),
            'passport_pin' => Yii::t('app', 'Passport Pin'),
            'passport_issued_date' => Yii::t('app', 'Passport Issued Date'),
            'passport_given_date' => Yii::t('app', 'Passport Given Date'),
            'passport_given_by' => Yii::t('app', 'Passport Given By'),
            'adress' => Yii::t('app', 'Adress'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'is_deleted' => Yii::t('app', 'Is Deleted'),
            'edu_type_id' => Yii::t('app', 'Edu Type ID'),
            'edu_form_id' => Yii::t('app', 'Edu Form ID'),
            'direction_id' => Yii::t('app', 'Direction ID'),
            'edu_direction_id' => Yii::t('app', 'Edu Direction ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'direction_course_id' => Yii::t('app', 'Direction Course ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'exam_type' => Yii::t('app', 'Exam Type'),
            'edu_name' => Yii::t('app', 'Edu Name'),
            'edu_direction' => Yii::t('app', 'Edu Direction'),
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Direction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirection()
    {
        return $this->hasOne(Direction::class, ['id' => 'direction_id']);
    }

    /**
     * Gets query for [[DirectionCourse]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirectionCourse()
    {
        return $this->hasOne(DirectionCourse::class, ['id' => 'direction_course_id']);
    }

    /**
     * Gets query for [[EduDirection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduDirection()
    {
        return $this->hasOne(EduDirection::class, ['id' => 'edu_direction_id']);
    }

    /**
     * Gets query for [[EduForm]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduForm()
    {
        return $this->hasOne(EduForm::class, ['id' => 'edu_form_id']);
    }

    /**
     * Gets query for [[EduType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEduType()
    {
        return $this->hasOne(EduType::class, ['id' => 'edu_type_id']);
    }

    /**
     * Gets query for [[Lang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::class, ['id' => 'lang_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
    }

    public function getExamDate()
    {
        return $this->hasOne(ExamDate::class, ['id' => 'exam_date_id']);
    }

    public function getFullName()
    {
        return $this->last_name." ".$this->first_name." ".$this->middle_name;
    }

    public function getIpCheck()
    {
        if ($this->exam_type == 1) {
            if (checkAllowedIP()) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function getUserStatus()
    {
        $text = '-----';
        $user = $this->user;
        if ($user->status == 10) {
            $text = 'Faol';
        } elseif ($user->status == 9) {
            $text = 'No faol';
        } elseif ($user->status == 0) {
            $text = 'Arxivlangan';
        }
        return $text;
    }

    public function getContractStatus() {
        $text = 'Shartnoma olmadi';

        switch ($this->edu_type_id) {
            case 1:
                $model = Exam::class;
                break;
            case 2:
                $model = StudentPerevot::class;
                break;
            case 3:
                $model = StudentDtm::class;
                break;
            case 4:
                $model = StudentMaster::class;
                break;
            default:
                return "<div class='badge-table-div active'><span>$text</span></div>";
        }

        $record = $model::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if ($record) {
            if ($model == Exam::class) {
                if ($record->status == 3) {
                    $text = !empty($record->down_time) ? 'Shartnoma oldi' : 'Shartnoma olmadi';
                }
            } else {
                if (isset($record->file_status) && $record->file_status == 2) {
                    $text = !empty($record->down_time) ? 'Shartnoma oldi' : 'Shartnoma olmadi';
                }
            }
        }

        return "<div class='badge-table-div active'><span>$text</span></div>";
    }


    public function getContractPrice() {
        $text = 'Shartnoma yo\'q';

        switch ($this->edu_type_id) {
            case 1:
                $model = Exam::class;
                break;
            case 2:
                $model = StudentPerevot::class;
                break;
            case 3:
                $model = StudentDtm::class;
                break;
            case 4:
                $model = StudentMaster::class;
                break;
            default:
                return "<div class='badge-table-div active'><span>$text</span></div>";
        }

        $record = $model::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if ($record) {
            if ($model == Exam::class) {
                if ($record->status == 3) {
                    $text = preg_replace('/\D/', '', $record->contract_price);
                    $text = number_format((int)$text, 0, '', ' ');
                }
            } else {
                if (isset($record->file_status) && $record->file_status == 2) {
                    $text = preg_replace('/\D/', '', $record->contract_price);
                    $text = number_format((int)$text, 0, '', ' ');
                }
            }
        }

        return "<div class='badge-table-div active'><span>$text</span></div>";
    }

    public function getContractConfirmDate() {
        $text = '----';

        switch ($this->edu_type_id) {
            case 1:
                $model = Exam::class;
                break;
            case 2:
                $model = StudentPerevot::class;
                break;
            case 3:
                $model = StudentDtm::class;
                break;
            case 4:
                $model = StudentMaster::class;
                break;
            default:
                return "<div class='badge-table-div active'><span>$text</span></div>";
        }

        $record = $model::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if ($record) {
            if ($model == Exam::class) {
                if ($record->status == 3) {
                    $text = date("d-m-Y", $record->confirm_date);
                }
            } else {
                if (isset($record->file_status) && $record->file_status == 2) {
                    $text = date("d-m-Y", $record->confirm_date);
                }
            }
        }

        return "<div class='badge-table-div active'><span>$text</span></div>";
    }

    public function getContractDownDate() {
        $text = '----';

        switch ($this->edu_type_id) {
            case 1:
                $model = Exam::class;
                break;
            case 2:
                $model = StudentPerevot::class;
                break;
            case 3:
                $model = StudentDtm::class;
                break;
            case 4:
                $model = StudentMaster::class;
                break;
            default:
                return "<div class='badge-table-div active'><span>$text</span></div>";
        }

        $record = $model::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if ($record) {
            if ($model == Exam::class) {
                if ($record->status == 3 && $record->down_time > 0) {
                    $text = date("d-m-Y H:i:s", $record->down_time);
                }
            } else {
                if (isset($record->file_status) && $record->file_status == 2 && $record->down_time > 0) {
                    $text = date("d-m-Y H:i:s", $record->down_time);
                }
            }
        }

        return "<div class='badge-table-div active'><span>$text</span></div>";
    }


    public function getChalaStatus() {
        $user = $this->user;
        if ($user->status == 9 && $user->step > 0) {
            $text = 'Parol tiklashda SMS parol tasdiqlamaan';
        } elseif ($user->status == 9 && $user->step == 0) {
            $text = 'SMS parol tasdiqlamaan';
        } elseif ($user->step == 1) {
            $text = 'Pasport ma\'lumotini kiritmagan';
        } elseif ($user->step == 2) {
            $text = 'Qabul turini tanlamagan';
        } elseif ($user->step == 3) {
            $text = 'Yo\'nalish tanlamagan';
        } elseif ($user->step == 4) {
            $text = 'Tasdiqlamagan';
        }
        return "<div class='badge-table-div active'><span>".$text."</span></div>";
    }

    public function getEduStatus()
    {
        if ($this->edu_type_id == 1) {
            return $this->getExamStatus();
        } elseif (in_array($this->edu_type_id, [2, 3, 4])) {
            return $this->getPerevotStatus();
        }
        return "-----";
    }

    private function getExamStatus()
    {
        $eduExam = Exam::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if (!$eduExam) return "-----";

        $statuses = [
            0 => "<div class='badge-table-div danger'><span>Bekor qilindi</span></div>",
            1 => "<div class='badge-table-div active'><span>Test ishlamagan</span></div>",
            2 => "<div class='badge-table-div active'><span>Testda</span></div>",
            3 => $eduExam->down_time !== null
                ? "<div class='badge-table-div active'><span>Yakunlab shartnoma oldi</span></div>"
                : "<div class='badge-table-div active'><span>Yakunlab shartnoma olmadi</span></div>",
            4 => "<div class='badge-table-div active'><span>Testdan o'tolmadi</span></div>",
        ];

        return $statuses[$eduExam->status] ?? "-----";
    }

    private function getPerevotStatus()
    {
        $models = [
            2 => StudentPerevot::class,
            3 => StudentDtm::class,
            4 => StudentMaster::class
        ];

        $modelClass = $models[$this->edu_type_id] ?? null;
        if (!$modelClass) return "-----";

        $perevot = $modelClass::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'status' => 1,
            'is_deleted' => 0
        ]);

        if (!$perevot) return "-----";

        $statuses = [
            0 => "<div class='badge-table-div danger'><span>Yuborilmagan</span></div>",
            1 => "<div class='badge-table-div active'><span>Kelib tushdi</span></div>",
            2 => "<div class='badge-table-div active'><span>Tasdiqlandi</span></div>",
            3 => "<div class='badge-table-div active'><span>Bekor qilindi</span></div>",
        ];

        return $statuses[$perevot->file_status] ?? "-----";
    }

    public function getIsConfirm()
    {
        $user = Yii::$app->user->identity;

        if ($user->step != 5) {
            return false;
        }

        $student = Student::findOne(['user_id' => $user->id]);
        if (!$student) {
            return false;
        }

        $eduTypeChecks = [
            1 => [['class' => Exam::class, 'status' => 3]],
            2 => [
                ['class' => StudentPerevot::class, 'status' => 2],
                ['class' => StudentDtm::class, 'status' => 2],
                ['class' => StudentMaster::class, 'status' => 2],
            ]
        ];

        if (!isset($eduTypeChecks[$student->edu_type_id])) {
            return false;
        }

        foreach ($eduTypeChecks[$student->edu_type_id] as $check) {
            if ($check['class']::findOne([
                'student_id' => $student->id,
                'status' => $check['status'],
                'is_deleted' => 0
            ])) {
                return true;
            }
        }

        return false;
    }

    public function getEducationStatus()
    {
        $eduModels = [
            1 => Exam::class,
            2 => StudentPerevot::class,
            3 => StudentDtm::class,
            4 => StudentMaster::class,
        ];

        if (!isset($eduModels[$this->edu_type_id])) {
            return '----';
        }

        $eduRecord = $eduModels[$this->edu_type_id]::findOne([
            'student_id' => $this->id,
            'edu_direction_id' => $this->edu_direction_id,
            'is_deleted' => 0
        ]);

        if (!$eduRecord) {
            return '----';
        }

        if ($this->edu_type_id == 1) {
            switch ($eduRecord->status) {
                case 0:
                    return "Bekor qilindi";
                case 1:
                    return "Test ishlamagan";
                case 2:
                    return "Testda";
                case 3:
                    return $eduRecord->down_time !== null ? "Yakunlab shartnoma oldi" : "Yakunlab shartnoma olmadi";
                case 4:
                    return "Testdan o'tolmadi";
            }
        }

        switch ($eduRecord->file_status) {
            case 0:
                return "Yuborilmagan";
            case 1:
                return "Kelib tushdi";
            case 2:
                return $eduRecord->down_time !== null ? "Tasdiqlandi. Yakunlab shartnoma oldi" : "Tasdiqlandi. Yakunlab shartnoma olmadi";
            case 3:
                return "Bekor qilindi";
        }

        return '----';
    }



    public static function userUpdate($model, $old)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $time = time();

        $user = $model->user;

        if ($user->status == 0) {
            $errors[] = ['Arxivlangan ma\'lumotni tahrirlab bo\'lmaydi.'];
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
        $user->status = $model->status;

        $user->username = $model->username;
        $user->setPassword($model->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();

        $query = User::find()
            ->where(['username' => $user->username])
            ->andWhere(['<>', 'id', $user->id])
            ->one();
        if ($query) {
            $errors[] = ['Bu telefon raqam avval ro\'yhatdan o\'tgan.'];
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }
        $user->save(false);

        $new = new CrmPush();
        $new->student_id = $model->id;
        $new->type = 101;
        $new->lead_id = $user->lead_id;
        $new->data = json_encode([
            CrmPush::TEL => $user->username,
        ], JSON_UNESCAPED_UNICODE);
        $new->save(false);

        if ($user->status == User::STATUS_DELETED) {
            $user->username = $user->username."__".$time;
            $user->update(false);
            $model->username = $user->username;
            $model->save(false);

            $amo = CrmPush::processType(12, $model, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }

        if ($user->step == 0 && $user->status == User::STATUS_ACTIVE) {
            $user->new_key = null;
            $user->sms_number = 0;
            $user->sms_time = 0;
            $user->step = 1;
            $user->update(false);

            $amo = CrmPush::processType(2, $model, $user);
            if (!$amo['is_ok']) {
                $transaction->rollBack();
                return ['is_ok' => false , 'errors' => $amo['errors']];
            }
        }


        $model->status = 1;
        $model->save(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }

    public static function contractUpdate($query, $model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        $query->contract_price = $model->price;
        $query->save(false);

        $student = $query->student;
        $student->is_down = 0;
        $student->update(false);
        $user = $student->user;

        $amo = CrmPush::processType(6, $student, $user);
        if (!$amo['is_ok']) {
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $amo['errors']];
        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false , 'errors' => $errors];
    }

}
