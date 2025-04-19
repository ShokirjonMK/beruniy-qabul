<?php

namespace frontend\models;

use common\models\Exam;
use common\models\ExamStudentQuestions;
use common\models\ExamSubject;
use common\models\Questions;
use common\models\StudentOferta;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Expression;

/**
 * @property Exam $exam
 */
class Contract extends Model
{

    function simple_errors($errors) {
        $result = [];
        foreach ($errors as $lev1) {
            foreach ($lev1 as $key => $error) {
                $result[] = $error;
            }
        }
        return array_unique($result);
    }

    public static function pdfData($student, $type)
    {
        $errors = [];
        $data = [];
        if ($student->edu_type_id == 1) {
            $exam = Exam::findOne([
                'direction_id' => $student->direction_id,
                'student_id' => $student->id,
                'status' => 3,
                'is_deleted' => 0
            ]);
            if (!$exam) {
                $errors[] = ['Shartnoma mavjud emas.'];
            } else {
                if ($exam->status != 3) {
                    $errors[] = ['Sinov test yakunlanmagan.'];
                } else {
                    $data = [
                        'sum' => $exam->contract_price,
                        'con2' => $exam->contract_second,
                        'con3' => $exam->contract_third,
                        'link' => $exam->contract_link,
                    ];
                }
            }
        }
    }



    public static function numUzStr($number)
    {
        $units = [
            '', 'bir', 'ikki', 'uch', 'to`rt', 'besh', 'olti', 'yetti', 'sakkiz', 'to`qqiz'
        ];
        $tens = [
            '', 'o`n', 'yigirma', 'o`ttiz', 'qirq', 'ellik', 'oltmish', 'yetmish', 'sakson', 'to`qson'
        ];
        $hundreds = [
            '', 'yuz', 'ikki yuz', 'uch yuz', 'to`rt yuz', 'besh yuz', 'olti yuz', 'yetti yuz', 'sakkiz yuz', 'to`qqiz yuz'
        ];
        $thousands = [
            '', 'ming', 'million', 'milliard', 'trillion'
        ];

        if ($number == 0) {
            return 'nol';
        }

        $result = '';

        // Split the number into whole and fractional parts
        $parts = explode('.', number_format($number, 2, '.', ''));
        $wholeNumber = $parts[0];
        $fractionalNumber = isset($parts[1]) ? $parts[1] : '00';

        // Process the whole number part
        $numberStr = strval($wholeNumber);
        $length = strlen($numberStr);
        $groups = ceil($length / 3);
        $numberStr = str_pad($numberStr, $groups * 3, '0', STR_PAD_LEFT);

        for ($i = 0; $i < $groups; $i++) {
            $groupValue = substr($numberStr, $i * 3, 3);
            $groupNumber = intval($groupValue);

            if ($groupNumber == 0) {
                continue;
            }

            $hundredsValue = intval(substr($groupValue, 0, 1));
            $tensValue = intval(substr($groupValue, 1, 1));
            $unitsValue = intval(substr($groupValue, 2, 1));

            if ($result != '') {
                $result .= ' ';
            }

            if ($hundredsValue > 0) {
                $result .= $hundreds[$hundredsValue] . ' ';
            }
            if ($tensValue > 0) {
                $result .= $tens[$tensValue] . ' ';
            }
            if ($unitsValue > 0) {
                $result .= $units[$unitsValue] . ' ';
            }

            $result .= $thousands[$groups - $i - 1];
        }

        // Process the fractional part (pennies)
        if ($fractionalNumber != '00') {
            $fractionalNumberStr = strval($fractionalNumber);
            $tensValue = intval(substr($fractionalNumberStr, 0, 1));
            $unitsValue = intval(substr($fractionalNumberStr, 1, 1));

            $fractionalResult = '';

            if ($tensValue > 0) {
                $fractionalResult .= $tens[$tensValue] . ' ';
            }
            if ($unitsValue > 0) {
                $fractionalResult .= $units[$unitsValue];
            }

            $result .= ' va ' . $fractionalResult . ' tiyin';
        }

        return trim($result);
    }

    public static function numRuStr($number)
    {
        $units = [
            '', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'
        ];
        $tens = [
            '', 'десять', 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'
        ];
        $teens = [
            'десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать'
        ];
        $hundreds = [
            '', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'
        ];
        $thousands = [
            '', 'тысяча', 'миллион', 'миллиард', 'триллион'
        ];

        if ($number == 0) {
            return 'ноль';
        }

        $result = '';

        // Split the number into whole and fractional parts
        $parts = explode('.', number_format($number, 2, '.', ''));
        $wholeNumber = $parts[0];
        $fractionalNumber = isset($parts[1]) ? $parts[1] : '00';

        // Process the whole number part
        $numberStr = strval($wholeNumber);
        $length = strlen($numberStr);
        $groups = ceil($length / 3);
        $numberStr = str_pad($numberStr, $groups * 3, '0', STR_PAD_LEFT);

        for ($i = 0; $i < $groups; $i++) {
            $groupValue = substr($numberStr, $i * 3, 3);
            $groupNumber = intval($groupValue);

            if ($groupNumber == 0) {
                continue;
            }

            $hundredsValue = intval(substr($groupValue, 0, 1));
            $tensValue = intval(substr($groupValue, 1, 1));
            $unitsValue = intval(substr($groupValue, 2, 1));

            if ($result != '') {
                $result .= ' ';
            }

            if ($hundredsValue > 0) {
                $result .= $hundreds[$hundredsValue] . ' ';
            }
            if ($tensValue > 1) {
                $result .= $tens[$tensValue] . ' ';
                if ($unitsValue > 0) {
                    $result .= $units[$unitsValue] . ' ';
                }
            } elseif ($tensValue == 1) {
                $result .= $teens[$unitsValue] . ' ';
            } else {
                if ($unitsValue > 0) {
                    $result .= $units[$unitsValue] . ' ';
                }
            }

            $result .= $thousands[$groups - $i - 1];
        }

        // Process the fractional part (pennies)
        if ($fractionalNumber != '00') {
            $fractionalNumberStr = strval($fractionalNumber);
            $tensValue = intval(substr($fractionalNumberStr, 0, 1));
            $unitsValue = intval(substr($fractionalNumberStr, 1, 1));

            $fractionalResult = '';

            if ($tensValue > 1) {
                $fractionalResult .= $tens[$tensValue] . ' ';
                if ($unitsValue > 0) {
                    $fractionalResult .= $units[$unitsValue];
                }
            } elseif ($tensValue == 1) {
                $fractionalResult .= $teens[$unitsValue];
            } else {
                if ($unitsValue > 0) {
                    $fractionalResult .= $units[$unitsValue];
                }
            }

            $result .= ' и ' . $fractionalResult . ' копеек';
        }

        return trim($result);
    }
}
