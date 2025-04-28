<?php

namespace backend\models;

use common\models\AuthAssignment;
use common\models\Branch;
use common\models\Consulting;
use common\models\CrmPush;
use common\models\Direction;
use common\models\EduDirection;
use common\models\Exam;
use common\models\Message;
use common\models\StepThree;
use common\models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\httpclient\Client;

/**
 * Signup form
 */
class UserUpdate extends Model
{
    public $password;
    public $status;

    public function rules()
    {
        return [
            [['password', 'status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => [9, 10 , 0], 'message' => 'Status faqat 9 yoki 10 bo\'lishi kerak'],
            [['password'], 'string', 'min' => 6, 'max' => 20, 'tooShort' => 'Parol minimum 6 xonali bo\'lishi kerak', 'tooLong' => 'Parol maksimal 20 xonali bo\'lishi kerak'],
        ];
    }

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public function ikStep($student)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];

        if (!$this->validate()) {
            $errors[] = $this->simple_errors($this->errors);
            $transaction->rollBack();
            return ['is_ok' => false , 'errors' => $errors];
        }

        $user = $student->user;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();
        $user->new_key = null;
        $user->sms_number = 0;
        $user->sms_time = 0;
        $user->new_password = null;
        $user->status = $this->status;
        $user->save(false);

        $student->username = $user->username;
        $student->password = $this->password;
        $student->save(false);

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }





    public static function dub()
    {
        $inputFileName = __DIR__ . '/ik11111 (2).xlsx';

        $transaction = Yii::$app->db->beginTransaction();
        $errors = [];
        $spreadsheet = IOFactory::load($inputFileName);
        $data = $spreadsheet->getActiveSheet()->toArray();

        foreach ($data as $key => $row) {

            if ($key != 0) {
                $domen = $row[0];
                $step = $row[1];
                $username = $row[2];
                $first_name = $row[3];
                $last_name = $row[4];
                $middle_name = $row[5];
                $password = $row[6];
                $gender = $row[7];
                $birthday = $row[8];
                $passport_number = $row[9];
                $passport_serial = $row[10];
                $passport_pin = $row[11];
                $edu_form_id = $row[12];
                $edu_type_id = $row[13];
                $language_id = $row[14];
                $code = $row[15];
                $exam_type = $row[16];
                $edu_name = $row[17];
                $edu_direction = $row[18];
                $filial_id = $row[19];

                if ($edu_form_id != 3) {
                    if ($edu_type_id == 1 || $edu_type_id == null) {
                        $user = User::findOne([
                            'username' => $username
                        ]);
                        if (!$user) {
                            if ($step == 1) {
                                $result = self::stepOne($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }
                            }
                            if ($step == 2) {
                                $result = self::stepOne($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepSecond($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }
                            }
                            if ($step == 3) {
                                $result = self::stepOne($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepSecond($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepThree($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }
                            }
                            if ($step == 4 || $step == 5) {
                                $result = self::stepOne($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepSecond($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepThree($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }

                                $result = self::stepFour($domen,
                                    $step,
                                    $username,
                                    $first_name,
                                    $last_name,
                                    $middle_name,
                                    $password,
                                    $gender,
                                    $birthday,
                                    $passport_number,
                                    $passport_serial,
                                    $passport_pin,
                                    $edu_form_id,
                                    $edu_type_id,
                                    $language_id,
                                    $code,
                                    $exam_type,
                                    $edu_name,
                                    $edu_direction, $filial_id);
                                if (!$result['is_ok']) {
                                    $transaction->rollBack();
                                    dd($result['errors']);
                                }
                            }
                        } else {
                            echo $username."\n";
                        }
                    }
                } else {
                    echo $username."\n";
                }
            }

        }

        if (count($errors) == 0) {
            $transaction->commit();
            return ['is_ok' => true];
        }
        $transaction->rollBack();
        return ['is_ok' => false, 'errors' => $errors];
    }



    public static function stepOne($domen,
                                   $step,
                                   $username,
                                   $first_name,
                                   $last_name,
                                   $middle_name,
                                   $password,
                                   $gender,
                                   $birthday,
                                   $passport_number,
                                   $passport_serial,
                                   $passport_pin,
                                   $edu_form_id,
                                   $edu_type_id,
                                   $language_id,
                                   $code,
                                   $exam_type,
                                   $edu_name,
                                   $edu_direction, $filial_id)
    {

        $errors = [];
        if ($filial_id == 2) {
            $filial_id = 3;
        } else {
            $filial_id = 2;
        }

        if ($domen == 'qabul.tgfu.uz') {
            if ($filial_id == 3) {
                $cons = 2;
            } else {
                $cons = 1;
            }
        } elseif ($domen == 'qabul1.tgfu.uz') {
            $cons = 4;
        } elseif ($domen == 'qabul2.tgfu.uz') {
            $cons = 6;
        } elseif ($domen == 'qabul3.tgfu.uz') {
            $cons = 7;
        } elseif ($domen == 'qabul4.tgfu.uz') {
            $cons = 8;
        } elseif ($domen == 'qabul5.tgfu.uz') {
            $cons = 10;
        } elseif ($domen == 'qabul6.tgfu.uz') {
            $cons = 11;
        } elseif ($domen == 'qabul7.tgfu.uz') {
            $cons = 12;
        } elseif ($domen == 'qabul8.tgfu.uz') {
            $cons = 13;
        } elseif ($domen == 'qabul9.tgfu.uz') {
            $cons = 15;
        } elseif ($domen == 'qabul10.tgfu.uz') {
            $cons = 16;
        }

        $user = new User();
        $user->username = $username;
        $user->user_role = 'student';

        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->generatePasswordResetToken();

        $user->status = 10;
        $user->new_key = null;
        $user->sms_number = 0;
        $user->sms_time = 0;
        $user->step = 1;
        $user->cons_id = $cons;

        if ($user->save(false)) {
            $newAuth = new AuthAssignment();
            $newAuth->item_name = 'student';
            $newAuth->user_id = $user->id;
            $newAuth->created_at = time();
            $newAuth->save(false);

            $newStudent = new Student();
            $newStudent->user_id = $user->id;
            $newStudent->username = $user->username;
            $newStudent->password = $password;
            $newStudent->branch_id = $filial_id;
            $newStudent->created_by = 0;
            $newStudent->updated_by = 0;
            $newStudent->save(false);

            $amo = CrmPush::processType(1, $newStudent, $user);
            if (!$amo['is_ok']) {
                $errors[] = 'USer Create';
            }
        }
        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false, 'errors' => $errors];
    }


    public static function stepSecond($domen,
                                   $step,
                                   $username,
                                   $first_name,
                                   $last_name,
                                   $middle_name,
                                   $password,
                                   $gender,
                                   $birthday,
                                   $passport_number,
                                   $passport_serial,
                                   $passport_pin,
                                   $edu_form_id,
                                   $edu_type_id,
                                   $language_id,
                                   $code,
                                   $exam_type,
                                   $edu_name,
                                   $edu_direction, $filial_id)
    {
            $errors = [];
            $user = User::findOne([
                'username' => $username
            ]);
            $student = $user->student;

            $student->first_name = $first_name;
            $student->last_name = $last_name;
            $student->middle_name = $middle_name;
            $student->passport_number = $passport_number;
            $student->passport_serial = $passport_serial;
            $student->passport_pin = (string)$passport_pin;
            $student->birthday = $birthday;
            $student->gender = $gender;

            $amo = CrmPush::processType(3, $student, $user);
            if (!$amo['is_ok']) {
                $errors[] = 'CRM 2';
            }

            $student->update(false);
            $user->step = 2;
            $user->update(false);
        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false, 'errors' => $errors];
    }

    public static function stepThree($domen,
                                      $step,
                                      $username,
                                      $first_name,
                                      $last_name,
                                      $middle_name,
                                      $password,
                                      $gender,
                                      $birthday,
                                      $passport_number,
                                      $passport_serial,
                                      $passport_pin,
                                      $edu_form_id,
                                      $edu_type_id,
                                      $language_id,
                                      $code,
                                      $exam_type,
                                      $edu_name,
                                      $edu_direction, $filial_id)
    {
        $errors = [];

        $user = User::findOne([
            'username' => $username
        ]);
        $student = $user->student;

        $student->edu_type_id = 1;

        $amo = CrmPush::processType(4, $student, $user);
        if (!$amo['is_ok']) {
            $errors[] = 'CRM 3';
        }
        $user->step = 3;
        $user->save(false);
        $student->save(false);

        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false, 'errors' => $errors];
    }


    public static function stepFour($domen,
                                     $step,
                                     $username,
                                     $first_name,
                                     $last_name,
                                     $middle_name,
                                     $password,
                                     $gender,
                                     $birthday,
                                     $passport_number,
                                     $passport_serial,
                                     $passport_pin,
                                     $edu_form_id,
                                     $edu_type_id,
                                     $language_id,
                                     $code,
                                     $exam_type,
                                     $edu_name,
                                     $edu_direction, $filial_id)
    {
        $errors = [];

        $user = User::findOne([
            'username' => $username
        ]);
        $student = $user->student;
        $direction = Direction::findOne([
            'branch_id' => $student->branch_id,
            'code' => $code
        ]);
        if (!$direction) {
            $errors[] = 'Yonalish mavjud emas'. $code;
        } else {
            $eduDirection = EduDirection::findOne([
                'direction_id' => $direction->id,
                'edu_type_id' => $student->edu_type_id,
                'edu_form_id' => $edu_form_id,
                'lang_id' => $language_id,
                'is_deleted' => 0
            ]);
            if (!$eduDirection) {
                $errors[] = 'edu Yonalish mavjud emas'. $code;
            } else {
                $student->setAttributes([
                    'lang_id' => $eduDirection->lang_id,
                    'edu_form_id' => $eduDirection->edu_form_id,
                    'edu_direction_id' => $eduDirection->id,
                    'edu_type_id' => $eduDirection->edu_type_id,
                    'direction_id' => $eduDirection->direction_id,
                ]);
                StepThree::createEduType($student);
                $student->save(false);
                $user->step = 4;
                $user->save(false);

                $amo = CrmPush::processType(5, $student, $user);
                if (!$amo['is_ok']) {
                    $errors[] = 'CRM FOUR';
                }

                $exam = Exam::findOne([
                    'edu_direction_id' => $eduDirection->id,
                    'student_id' => $student->id,
                    'status' => 1,
                    'is_deleted' => 0
                ]);
                if (!$exam) {
                    $errors[] = 'Imtixon mavjud emas!';
                } else {
                    $exam->contract_price = $eduDirection->price;
                    $exam->confirm_date = time();
                    $exam->ball = 60;
                    $exam->status = 3;
                    $exam->save(false);
                }

                $user->step = 5;
                $user->is_confirm = time();
                $user->save(false);

                $amo = CrmPush::processType(6, $student, $user);
                if (!$amo['is_ok']) {
                    $errors[] = 'CRM 5';
                }
            }
        }

        if (count($errors) == 0) {
            return ['is_ok' => true];
        }
        return ['is_ok' => false, 'errors' => $errors];
    }
}
