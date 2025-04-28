<?php

namespace common\models;


class Status
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const LEVEL_1 = 1;
    const LEVEL_2 = 2;
    const LEVEL_3 = 3;

    const CORRECT = 1;
    const IN_CORRECT = 0;

    const USER_STATUS_DELETE = 0;
    const USER_STATUS_NO_FAOL = 9;
    const USER_STATUS_BLOCK = 5;
    const USER_STATUS_ACTIVE = 10;


    public static function accessStatus($id = null)
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Faol',
            self::STATUS_INACTIVE => 'No faol',
        ];

        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }


    public static function rolType($id = null)
    {
        $statuses = [
            1 => 'Filial hammasi && Consulting hammasi',
            2 => 'Filial bitta && Consulting hammasi',
            3 => 'Filial hammasi && Consulting bitta',
            4 => 'Filial bitta && Consulting bitta',
        ];

        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }


    public static function ofertaStatus($id = null)
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Qabul qilinsin',
            self::STATUS_INACTIVE => 'Qabul qilinmasin',
        ];

        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }

    public static function contractStatus($id = null)
    {
        $statuses = [
            1 => 'Shartnoma olganlar',
            0 => 'Shartnoma olmaganlar',
        ];

        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }


    public static function grantStatus()
    {
        return [
            2 => 'Boshlash',
            3 => 'Yakunlash',
        ];
    }

    public static function gender($id = null)
    {
        $data = [
            1 => 'Erkak',
            2 => 'Ayol',
        ];
        if ($id == null) {
            return $data;
        }
        return $data[$id];
    }

    public static function genderId($id)
    {
        $data = [
            1 => 'Erkak',
            2 => 'Ayol',
        ];
        return $data[$id];
    }

    public static function userStatus($id = null)
    {
        $data = [
            self::USER_STATUS_ACTIVE => 'Faol',
            self::USER_STATUS_NO_FAOL => 'Blocklangan',
            self::USER_STATUS_DELETE => 'Arxivlangan',
        ];

        return $id === null ? $data : ($data[$id] ?? null);
    }

    public static function getExamTestStatus($id = null)
    {
        $data = [
            1 => 'Boshlamagan',
            2 => 'Boshlagan',
            3 => 'Yakunlagan',
            4 => 'O\'tolmadi',
        ];
        return $id === null ? $data : ($data[$id] ?? null);
    }

    public static function getExamStatus($id = null)
    {
        $data = [
            0 => 'Online',
            1 => 'Offline',
        ];
        return $id === null ? $data : ($data[$id] ?? null);
    }


    public static function userStatusUpdate($id = null)
    {
        $data = [
            self::USER_STATUS_ACTIVE => 'Faol',
            self::USER_STATUS_BLOCK => 'Blocklangan',
            self::USER_STATUS_NO_FAOL => 'SMS parol tasdiqlamagan',
            self::USER_STATUS_DELETE => 'Arxivlangan',
        ];
        if ($id == null) {
            return $data;
        }
        return $data[$id];
    }

    public static function stdStatus($id = null)
    {
        $data = [
            self::USER_STATUS_ACTIVE => 'Faol',
            self::USER_STATUS_DELETE => 'Arxivlangan',
        ];
        if ($id == null) {
            return $data;
        }
        return $data[$id];
    }

    public static function targetStatus()
    {
        return [
            0 => 'Bosh sahifa',
            1 => 'Royhatdan o\'tish',
        ];
    }

    public static function eStatus($id = null)
    {
        $data = [
            1 => 'Test ishlamagan',
            2 => 'Testda',
            3 => 'Testdan o\'tdi',
            4 => 'Testdan o\'tolmadi',
            5 => 'Shartnoma olgan',
            6 => 'Shartnoma olmagan',
        ];
        if ($id == null) {
            return $data;
        }
        return $data[$id];
    }

    public static function testPractical($id = null)
    {
        $statuses = [
            0 => 'Test',
            1 => 'Amaliy',
        ];
        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }

    public static function botStatus($id = null)
    {
        return [
            0 => 'Jarayonda',
            1 => 'Kelib tushgan',
            2 => 'Tasdiqlangan',
            5 => 'Blocklangan',
        ];
    }

    public static function perevotFileStatus($id = null)
    {
        $data = [
            0 => 'File yo\'q',
            1 => 'Kelib tushgan',
            2 => 'Tasdiqlandi',
            3 => 'Bekor qilindi',
        ];
        if ($id == null) {
            return $data;
        }

        return $data[$id];
    }

    public static function fileStatus($id = null)
    {
        $statuses = [
            0 => 'Yuborilmagan',
            1 => 'Kelib tushgan',
            2 => 'Tasdiqlandi',
            3 => 'Bekor qilindi',
        ];

        return $id === null ? $statuses : ($statuses[$id] ?? null);
    }

    public static function perStatus()
    {
        return [
            0 => 'Yuborilmagan',
            1 => 'Kelib tushgan',
            2 => 'Tasdiqlandi',
            3 => 'Bekor qilindi',
        ];
    }

    public static function examStatus()
    {
        return [
            0 => 'Online',
            1 => 'Offline',
        ];
    }

    public static function questionLevel($id = null)
    {
        $data = [
            self::LEVEL_1 => 'Oson',
            self::LEVEL_2 => 'O\'rta',
            self::LEVEL_3 => 'Qiyin',
        ];
        if ($id == null) {
            return $data;
        } else {
            return $data[$id];
        }
    }


    public static function step($id = null)
    {
        $data = [
            1 => 'Pasport ma\'lumotini kiritmagan',
            2 => 'Qabul turini tanlamagan',
            3 => 'Yo\'nalish tanlamagan',
            4 => 'Tasdiqdalamagan',
            5 => 'To\'liq ro\'yhatdan o\'tmagan',
            6 => 'Parol tiklash sms kod kiritmagan',
        ];
        if ($id == null) {
            return $data;
        } else {
            return $data[$id];
        }
    }

    public static function questionLevelId($id)
    {
        return [
            self::LEVEL_1 => 'Oson',
            self::LEVEL_2 => 'O\'rta',
            self::LEVEL_3 => 'Qiyin',
        ];
    }

    public static function optionIsCorrect()
    {
        return [
            self::IN_CORRECT => 'Noto\'g\'ri javob',
            self::CORRECT => 'To\'g\'ri javob',
        ];
    }

    public static function month($id)
    {
        $data = [
            1 => 'Yanvar',
            2 => 'Fevral',
            3 => 'Mart',
            4 => 'Aprel',
            5 => 'May',
            6 => 'Iyun',
            7 => 'Iyul',
            8 => 'Avgust',
            9 => 'Sentabr',
            10 => 'Oktabr',
            11 => 'Noyabr',
            12 => 'Dekabr',
        ];
        if ($id == null) {
            return $data;
        } else {
            return $data[$id * 1];
        }
    }
}