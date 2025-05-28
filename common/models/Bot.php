<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\httpclient\Client;

/**
 * Bot model
 */
class Bot extends Model
{
    const CHAT_ID = 1841508935;

    const PHONE = '+998 94 505 52 50';

    const IMG = '/frontend/web/images/bot_univer.jpg';

    const CONS = 0;

    public static function telegram($telegram)
    {
        $telegram_id = $telegram->input->message->chat->id;
        $username = $telegram->input->message->chat->username;
        $gram = Telegram::findOne([
            'telegram_id' => $telegram_id,
            'is_deleted' => 0
        ]);
        if (!$gram) {
            $gram = new Telegram();
            $gram->telegram_id = $telegram_id;
            $gram->username = $username;
            $gram->lang_id = 1;
            $gram->save(false);

            self::sendPhone($telegram, $gram);
        } else {
            $type = $gram->type;
            $lang_id = $gram->lang_id;
            $text = $telegram->input->message->text;
            $gram->username = $username;
            $gram->update(false);

            if ($type != 0) {
                if ($text == '/home' || $text == self::getT("a5", $lang_id)) {
                    self::sendHome($telegram, $lang_id, $gram);
                    return true;
                } elseif ($text == '/signup' || $text == self::getT("a3", $lang_id)) {
                    self::signUp($telegram, $lang_id, $gram);
                    return true;
                } elseif ($text == '/university' || $text == self::getT("a1", $lang_id)) {
                    self::sendUniversity($telegram, $lang_id, $gram);
                    return true;
                } elseif ($text == '/directions' || $text == self::getT("a2", $lang_id)) {
                    self::sendDirections($telegram, $lang_id, $gram);
                    return true;
                } elseif ($text == '/langupdate' || $text == self::getT("a4", $lang_id)) {
                    self::sendLang($telegram, $lang_id, $gram);
                    return true;
                }
            }

            switch ($type) {
                case 0:
                    self::main($telegram, $lang_id, $gram);
                    break;
                case 1:
                    self::sendHome($telegram, $lang_id, $gram);
                    break;
                case 10:
                    self::signUp($telegram, $lang_id, $gram);
                    break;
                case 4:
                    // Bot tilini ozgartirish
                    self::langUpdate($telegram, $lang_id, $gram);
                default:
                    break;
            }
        }
    }

    public static function main($telegram, $lang_id, $gram)
    {
        try {
            if (json_encode($telegram->input->message->contact) != "null") {
                $contact = json_encode($telegram->input->message->contact);
                $contact_new = json_decode($contact);
                $phone = preg_replace('/[^0-9]/', '', $contact_new->phone_number);
                $phoneKod = substr($phone, 0, 3);
                if ($phoneKod != 998) {
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a6", $lang_id),
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode([
                            'keyboard' => [[
                                [
                                    'text' => self::getT("a7", $lang_id),
                                    'request_contact' => true
                                ]
                            ]],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                        ])
                    ]);
                } else {
                    $gram->phone = "+" . $phone;
                    $raw = preg_replace('/\D/', '', $gram->phone);

                    $formatted = '+998 (' . substr($raw, 3, 2) . ') ' .
                        substr($raw, 5, 3) . '-' .
                        substr($raw, 8, 2) . '-' .
                        substr($raw, 10, 2);

                    $gram->phone = $formatted;
                    $gram->type = 1;
                    $gram->save(false);

                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a20", $lang_id),
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => self::getT("a1", $lang_id)],
                                    ['text' => self::getT("a2", $lang_id)],
                                ],
                                [
                                    ['text' => self::getT("a4", $lang_id)],
                                    ['text' => self::getT("a3", $lang_id)],
                                ]
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
            }
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a8", $lang_id),
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function sendHome($telegram, $lang_id, $gram)
    {
        try {
            $gram->type = 1;
            $gram->save(false);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a20", $lang_id),
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => self::getT("a1", $lang_id)],
                            ['text' => self::getT("a2", $lang_id)],
                        ],
                        [
                            ['text' => self::getT("a4", $lang_id)],
                            ['text' => self::getT("a3", $lang_id)],
                        ]
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function langUpdate($telegram, $lang_id, $gram)
    {
        try {
            $text = $telegram->input->message->text;

            if ($text == self::getT("a9", $lang_id)) {
                // O'zbek tili
                $gram->lang_id = 1;
                $gram->type = 1;
                $gram->save(false);
            } elseif ($text == self::getT("a10", $lang_id)) {
                // Ingliz tili
                $gram->type = 1;
                $gram->lang_id = 2;
                $gram->save(false);
            } elseif ($text == self::getT("a11", $lang_id)) {
                // Rus tili
                $gram->type = 1;
                $gram->lang_id = 3;
                $gram->save(false);
            } elseif ($text == self::getT("a12", $lang_id)) {
                $gram->type = 1;
                $gram->save(false);
            } else {
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a8", $lang_id),
                    'parse_mode' => 'HTML',
                ]);
            }

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a20", $lang_id),
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => self::getT("a1", $gram->lang_id)],
                            ['text' => self::getT("a2", $gram->lang_id)],
                        ],
                        [
                            ['text' => self::getT("a4", $gram->lang_id)],
                            ['text' => self::getT("a3", $gram->lang_id)],
                        ]
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function sendLang($telegram, $lang_id, $gram)
    {
        try {
            $gram->type = 4;
            $gram->save(false);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a13", $lang_id),
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => self::getT("a9", $lang_id)],
                            ['text' => self::getT("a10", $lang_id)],
                        ],
                        [
                            ['text' => self::getT("a11", $lang_id)],
                            ['text' => self::getT("a12", $lang_id)],
                        ]
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function sendDirections($telegram, $lang_id, $gram)
    {
        try {
            $gram->type = 1;
            $gram->save(false);

            $text = "Yo'nalishlar";

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => self::getT("a1", $gram->lang_id)],
                            ['text' => self::getT("a2", $gram->lang_id)],
                        ],
                        [
                            ['text' => self::getT("a4", $gram->lang_id)],
                            ['text' => self::getT("a3", $gram->lang_id)],
                        ]
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function sendUniversity($telegram, $lang_id, $gram)
    {
        try {
            $gram->type = 1;
            $gram->save(false);

            $text = "Universitet";

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => self::getT("a1", $gram->lang_id)],
                            ['text' => self::getT("a2", $gram->lang_id)],
                        ],
                        [
                            ['text' => self::getT("a4", $gram->lang_id)],
                            ['text' => self::getT("a3", $gram->lang_id)],
                        ]
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function sendPhone($telegram, $gram)
    {
        try {
            $photoUrl = "https://arbu-edu.uz/frontend/web/images/new_bino.webp";
            return $telegram->sendPhoto([
                'chat_id' => $gram->telegram_id,
                'photo' => $photoUrl,
                'caption' => "🇺🇿 *ABU RAYHON BERUNIY UNIVERSITETI* haqida rasm\n\nTelefon raqamingizni yuboring",
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => [[
                        [
                            'text' => "☎️ Telefon raqamni yuborish",
                            'request_contact' => true
                        ]
                    ]],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ])
            ]);
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( '.$e->getMessage()],
            ]);
        }
    }

    public static function signUp($telegram, $lang_id, $gram)
    {
        try {
            $text = $telegram->input->message->text;
            $gram->type = 10;
            $gram->update(false);
            $step = $gram->step;

            if ($step == 0) {
                self::step0($telegram, $lang_id, $gram, $text);
            } elseif ($step == 1) {
                self::step1($telegram, $lang_id, $gram, $text);
            } elseif ($step == 2) {
                self::step2($telegram, $lang_id, $gram, $text);
            } elseif ($step == 3) {
                self::step3($telegram, $lang_id, $gram, $text);
            } elseif ($step == 4) {
                self::step4($telegram, $lang_id, $gram, $text);
            } elseif ($step == 5) {
                self::step5($telegram, $lang_id, $gram, $text);
            } elseif ($step == 6) {
                self::step6($telegram, $lang_id, $gram, $text);
            } elseif ($step == 7) {
                self::step7($telegram, $lang_id, $gram, $text);
            } elseif ($step == 8) {
                self::step8($telegram, $lang_id, $gram, $text);
            } elseif ($step == 9) {
                self::step9($telegram, $lang_id, $gram, $text);
            } elseif ($step == 10) {
                self::step10($telegram, $lang_id, $gram, $text);
            } elseif ($step == 11) {
                self::step11($telegram, $lang_id, $gram, $text);
            } elseif ($step == 12) {
                self::step12($telegram, $lang_id, $gram, $text);
            } elseif ($step == 13) {
                self::step13($telegram, $lang_id, $gram, $text);
            } elseif ($step == 14) {
                self::step14($telegram, $lang_id, $gram, $text);
            }
        } catch (\Exception $e) {
            return $telegram->sendMessage([
                'chat_id' => self::CHAT_ID,
                'text' => ['Ik main :( ' . $e->getMessage()],
                'parse_mode' => 'HTML',
            ]);
        }
    }

    public static function step0($telegram, $lang_id, $gram, $text)
    {
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a16", $lang_id), // Pasport seriya va raqamini kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'remove_keyboard' => true
                ])
            ]);
        }

        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = 0;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a16", $lang_id), // Pasport seriya va raqamini kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'remove_keyboard' => true
                ])
            ]);
        }

        // Agar foydalanuvchi pasport seriya va raqamini to‘g‘ri kiritgan bo‘lsa
        if (self::seria($text)) {
            $gram->passport_serial = substr($text, 0, 2);
            $gram->passport_number = substr($text, 2, 9);
            $gram->step = 1;
            $gram->save(false);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a15", $lang_id), // Tug‘ilgan sanani kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Noto‘g‘ri pasport raqami kiritilgan bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a17", $lang_id), // Xatolik: noto‘g‘ri format
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'remove_keyboard' => true
            ])
        ]);
    }

    public static function step1($telegram, $lang_id, $gram, $text)
    {
        $i = 1;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a15", $lang_id), // Tug‘ilgan sanani kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a16", $lang_id), // Pasport seriya va raqamini kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'remove_keyboard' => true
                ])
            ]);
        }

        // Agar foydalanuvchi pasport seriya va raqamini to‘g‘ri kiritgan bo‘lsa
        if (self::date($text)) {
            $gram->birthday = date("Y-m-d", strtotime($text));
            $gram->step = ($i + 1);

            $passport = self::passport($gram);
            if ($passport['is_ok']) {
                $gram = $passport['gram'];
                $gram->save(false);

                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a21", $lang_id), // Qabul turini tanlang
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::eduType($lang_id)
                ]);
            } else {
                $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a19", $lang_id), // Pasport ma'lumoti yuklashda xatolik
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'remove_keyboard' => true
                    ])
                ]);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a15", $lang_id), // Tug‘ilgan sanani kiriting
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }
        }

        // Noto‘g‘ri sana kiritilgan bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a18", $lang_id), // Xatolik: noto‘g‘ri format
            'parse_mode' => 'HTML',
            'reply_markup' => json_encode([
                'keyboard' => [
                    [
                        ['text' => $backText],
                    ],
                ],
                'resize_keyboard' => true,
            ])
        ]);
    }

    public static function step2($telegram, $lang_id, $gram, $text)
    {
        $i = 2;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a21", $lang_id), // Qabul turini tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduType($lang_id)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a15", $lang_id), // Tug‘ilgan sanani kiriting
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        $backOptions = [
            1 => self::getT("a22", $lang_id),
            2 => self::getT("a23", $lang_id),
        ];

        // Agar qabul turini to‘g‘ri kiritgan bo‘lsa
        if (in_array($text, $backOptions)) {
            $gram->step = ($i + 1);
            $eduTypeId = array_search($text, $backOptions); // index (1 yoki 2) ni topadi
            $gram->edu_type_id = $eduTypeId;
            $gram->save(false);


            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a26", $lang_id), // Talim shaklini tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduForm($lang_id)
            ]);
        }

        // Talim turi noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a33", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::eduType($lang_id)
        ]);
    }

    public static function step3($telegram, $lang_id, $gram, $text)
    {
        $i = 3;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a26", $lang_id), // Talim shaklini tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduForm($lang_id)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a21", $lang_id), // Qabul turini tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduType($lang_id)
            ]);
        }

        $backOptions = [
            1 => self::getT("a28", $lang_id),
            2 => self::getT("a29", $lang_id),
        ];

        // Agar talim shakli to‘g‘ri kiritgan bo‘lsa
        if (in_array($text, $backOptions)) {
            $gram->step = ($i + 1);
            $eduFormId = array_search($text, $backOptions); // index (1 yoki 2) ni topadi
            $gram->edu_form_id = $eduFormId;
            $gram->save(false);


            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a27", $lang_id), // Talim tili tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduLang($lang_id)
            ]);
        }

        // Talim shakli noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a34", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::eduForm($lang_id)
        ]);
    }

    public static function step4($telegram, $lang_id, $gram, $text)
    {
        $i = 4;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a27", $lang_id), // Talim tili tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduLang($lang_id)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a26", $lang_id), // Talim shaklini tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduForm($lang_id)
            ]);
        }

        $backOptions = [
            1 => self::getT("a37", $lang_id),
        ];

        // Agar talim tili to‘g‘ri kiritgan bo‘lsa
        if (in_array($text, $backOptions)) {
            $gram->step = ($i + 1);
            $eduLangId = array_search($text, $backOptions); // index (1 yoki 2) ni topadi
            $gram->edu_lang_id = $eduLangId;
            $gram->save(false);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a40", $lang_id), // Filial tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::branch($lang_id)
            ]);
        }

        // Talim tili noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a35", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::eduLang($lang_id)
        ]);
    }

    public static function step5($telegram, $lang_id, $gram, $text)
    {
        $i = 5;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a40", $lang_id), // Filial tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::branch($lang_id)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a27", $lang_id), // Talim tili tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::eduLang($lang_id)
            ]);
        }

        $query = Branch::find()
            ->where(['status' => 1, 'is_deleted' => 0]);

        if ($lang_id == 1) {
            $query->andWhere(['name_uz' => $text])->andWhere(['not in', 'cons_id', [null]]);
        } elseif ($lang_id == 2) {
            $query->andWhere(['name_en' => $text])->andWhere(['not in', 'cons_id', [null]]);
        } elseif ($lang_id == 3) {
            $query->andWhere(['name_ru' => $text])->andWhere(['not in', 'cons_id', [null]]);
        }

        $branch = $query->one();

        // Agar talim tili to‘g‘ri kiritgan bo‘lsa
        if ($branch) {
            $gram->step = ($i + 1);
            $gram->branch_id = $branch->id;
            if (self::CONS == 0) {
                $gram->cons_id = $branch->cons_id;
            }
            $gram->save(false);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        // Talim tili noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a41", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::branch($lang_id)
        ]);
    }

    public static function step6($telegram, $lang_id, $gram, $text)
    {
        $i = 6;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a40", $lang_id), // Filial tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::branch($lang_id)
            ]);
        }

        list($code, $name) = explode(' - ', $text, 2);
        $code = trim($code);
        $name = trim($name);

        // 2. Lang_id bo‘yicha nom ustunini aniqlash
        $nameColumn = 'name_uz';
        if ($lang_id == 2) {
            $nameColumn = 'name_en';
        } elseif ($lang_id == 3) {
            $nameColumn = 'name_ru';
        }

        // 3. Directionni code + name orqali topamiz
        $eduDirection = EduDirection::find()
            ->where([
                'edu_direction.branch_id' => $gram->branch_id,
                'edu_direction.edu_type_id' => $gram->edu_type_id,
                'edu_direction.edu_form_id' => $gram->edu_form_id,
                'edu_direction.lang_id' => $gram->edu_lang_id,
                'edu_direction.status' => 1,
                'edu_direction.is_deleted' => 0
            ])
            ->andWhere([
                'direction.code' => $code,
                'direction.status' => 1,
                'direction.is_deleted' => 0,
                "direction.$nameColumn" => $name,
                'direction.branch_id' => $gram->branch_id
            ])
            ->joinWith('direction')
            ->one();


        // Agar talim tili to‘g‘ri kiritgan bo‘lsa
        if ($eduDirection) {
            $gram->edu_direction_id = $eduDirection->id;

            if ($gram->edu_type_id == 1) {
                $gram->step = 7;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a44", $lang_id), // Imtixon turi
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::offline($lang_id, $eduDirection)
                ]);
            } elseif ($gram->edu_type_id == 2) {
                $gram->step = 9;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // Bosqichlari
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::course($lang_id, $eduDirection)
                ]);
            } elseif ($gram->edu_type_id == 3) {
                if ($eduDirection->is_oferta == 1) {
                    $gram->step = 10;
                    $gram->save(false);

                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => $backText],
                                ],
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
                $gram->step = 12;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // DTM
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            } elseif ($gram->edu_type_id == 4) {
                if ($eduDirection->is_oferta == 1) {
                    $gram->step = 10;
                    $gram->save(false);

                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => $backText],
                                ],
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
                $gram->step = 13;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // MASTER
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }
        }

        // Talim yonalishi noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a43", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::direction($lang_id, $gram)
        ]);
    }

    public static function step7($telegram, $lang_id, $gram, $text)
    {
        $i = 7;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a44", $lang_id), // Imtixon turi
                'parse_mode' => 'HTML',
                'reply_markup' => self::offline($lang_id, $eduDirection)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        // Agar talim tili to‘g‘ri kiritgan bo‘lsa
        if ($eduDirection) {
            if ($eduDirection->exam_type != null) {
                $examTypes = json_decode($eduDirection->exam_type, true);
                foreach ($examTypes as $examType) {
                    if (Status::getExamStatus($examType) == $text) {
                        $gram->exam_type = $examType;
                        if ($examType == 0) {
                            if ($eduDirection->is_oferta == 1) {
                                $gram->step = 10;
                                $gram->save(false);
                                return $telegram->sendMessage([
                                    'chat_id' => $gram->telegram_id,
                                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                                    'parse_mode' => 'HTML',
                                    'reply_markup' => json_encode([
                                        'keyboard' => [
                                            [
                                                ['text' => $backText],
                                            ],
                                        ],
                                        'resize_keyboard' => true,
                                    ])
                                ]);
                            }
                            $gram->step = 14;
                            $gram->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $gram->telegram_id,
                                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                                'parse_mode' => 'HTML',
                                'reply_markup' => self::confirm($lang_id)
                            ]);
                        } else {
                            $gram->step = 8;
                            $gram->save(false);
                            return $telegram->sendMessage([
                                'chat_id' => $gram->telegram_id,
                                'text' => self::getT("a45", $lang_id), // Offline imtixon sanalari
                                'parse_mode' => 'HTML',
                                'reply_markup' => self::offlineDate($lang_id, $gram)
                            ]);
                        }
                    }
                }
            }
        }

        // Imtixon turi noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a53", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::offline($lang_id, $eduDirection)
        ]);
    }

    public static function step8($telegram, $lang_id, $gram, $text)
    {
        $i = 8;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a45", $lang_id), // Offline imtixon sanalari
                'parse_mode' => 'HTML',
                'reply_markup' => self::offlineDate($lang_id, $gram)
            ]);
        }

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = ($i - 1);
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a44", $lang_id), // Imtixon turi
                'parse_mode' => 'HTML',
                'reply_markup' => self::offline($lang_id, $eduDirection)
            ]);
        }


        $examDates = ExamDate::findOne([
            'is_deleted' => 0,
            'status' => 1,
            'branch_id' => $gram->branch_id,
            'date' => $text
        ]);
        if ($examDates) {
            $gram->exam_date_id = $examDates->id;
            $gram->save(false);
            if ($eduDirection->is_oferta == 1) {
                $gram->step = 10;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }
            $gram->step = 14;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                'parse_mode' => 'HTML',
                'reply_markup' => self::confirm($lang_id)
            ]);
        }

        // Imtixon sanasi noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a50", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::offlineDate($lang_id, $gram)
        ]);
    }

    public static function step9($telegram, $lang_id, $gram, $text)
    {
        $i = 9;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a54", $lang_id), // Bosqichlari
                'parse_mode' => 'HTML',
                'reply_markup' => self::course($lang_id, $eduDirection)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            $gram->step = 6;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        switch ($lang_id) {
            case 1:
                $nameColumn = 'course.name_uz';
                break;
            case 2:
                $nameColumn = 'course.name_en';
                break;
            case 3:
                $nameColumn = 'course.name_ru';
                break;
            default:
                $nameColumn = 'course.name_uz';
        }

        $course = DirectionCourse::find()
            ->joinWith('course')
            ->where([
                'direction_course.status' => 1,
                'direction_course.is_deleted' => 0,
                'direction_course.edu_direction_id' => $eduDirection->id,
            ])
            ->andWhere([$nameColumn => $text])
            ->one();

        if ($course) {
            $gram->direction_course_id = $course->id;
            $gram->step = 11;
            $gram->save(false);

            if ($eduDirection->is_oferta == 1) {
                $gram->step = 10;
                $gram->save(false);

                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a51", $lang_id), // Transkript yuklang
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }


        // Yonalish bosqichi noto'g'ri bo‘lsa
        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a52", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::course($lang_id, $eduDirection)
        ]);
    }


    public static function step10($telegram, $lang_id, $gram, $text)
    {
        $i = 9;
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            if ($gram->edu_type_id == 1) {
                if ($gram->exam_type == 0) {
                    // Imtixon turlari
                    $gram->step = 7;
                    $gram->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a44", $lang_id), // Imtixon turi
                        'parse_mode' => 'HTML',
                        'reply_markup' => self::offline($lang_id, $eduDirection)
                    ]);
                } else {
                    // offline sanalari
                    $gram->step = 8;
                    $gram->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a45", $lang_id), // Offline imtixon sanalari
                        'parse_mode' => 'HTML',
                        'reply_markup' => self::offlineDate($lang_id, $gram)
                    ]);
                }
            } elseif ($gram->edu_type_id == 2) {
                // Yonalish bosqichlariga
                $gram->step = 9;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // Bosqichlari
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::course($lang_id, $eduDirection)
                ]);
            } else {
                $gram->step = 6;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::direction($lang_id, $gram)
                ]);
            }
        }

        $result = self::fileUpload($telegram, $gram);
        if ($result['is_ok']) {
            $url = $result['data'];
            $gram->oferta = $url;
            $gram->save(false);

            if ($gram->edu_type_id == 1) {

                $gram->step = 14;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                    'parse_mode' => 'HTML',
                    'reply_markup' => self::confirm($lang_id)
                ]);

            } elseif ($gram->edu_type_id == 2) {

                $gram->step = 11;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a51", $lang_id), // Transkript yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);

            } elseif ($gram->edu_type_id == 3) {
                $gram->step = 12;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // DTM
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }elseif ($gram->edu_type_id == 4) {

                $gram->step = 13;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // MASTER
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }
        } else {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a55", $lang_id), // Fayl noto'g'ri
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }
    }


    public static function step11($telegram, $lang_id, $gram, $text)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a51", $lang_id), // Transkript yuklang
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            if ($eduDirection->is_oferta == 1) {
                $gram->step = 10;
                $gram->save(false);

                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }

            $gram->step = 9;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a54", $lang_id), // Bosqichlari
                'parse_mode' => 'HTML',
                'reply_markup' => self::course($lang_id, $eduDirection)
            ]);
        }

        $result = self::fileUpload($telegram, $gram);
        if ($result['is_ok']) {
            $url = $result['data'];
            $gram->tr = $url;
            $gram->save(false);

            $gram->step = 14;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                'parse_mode' => 'HTML',
                'reply_markup' => self::confirm($lang_id)
            ]);
        } else {
            $data = $result['data'];
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => $data, // Fayl noto'g'ri
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }
    }


    public static function step12($telegram, $lang_id, $gram, $text)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a54", $lang_id), // DTM
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            if ($eduDirection->is_oferta == 1) {
                $gram->step = 10;
                $gram->save(false);

                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }

            $gram->step = 6;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        $result = self::fileUpload($telegram, $gram);
        if ($result['is_ok']) {
            $url = $result['data'];
            $gram->dtm = $url;
            $gram->save(false);

            $gram->step = 14;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                'parse_mode' => 'HTML',
                'reply_markup' => self::confirm($lang_id)
            ]);
        } else {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a55", $lang_id), // Fayl noto'g'ri
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }
    }


    public static function step13($telegram, $lang_id, $gram, $text)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a54", $lang_id), // MASTER
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText) {
            if ($eduDirection->is_oferta == 1) {
                $gram->step = 10;
                $gram->save(false);

                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }

            $gram->step = 6;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a42", $lang_id), // Yonalish tanlang
                'parse_mode' => 'HTML',
                'reply_markup' => self::direction($lang_id, $gram)
            ]);
        }

        $result = self::fileUpload($telegram, $gram);
        if ($result['is_ok']) {
            $url = $result['data'];
            $gram->master = $url;
            $gram->save(false);

            $gram->step = 14;
            $gram->save(false);
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                'parse_mode' => 'HTML',
                'reply_markup' => self::confirm($lang_id)
            ]);
        } else {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a55", $lang_id), // Fayl noto'g'ri
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'keyboard' => [
                        [
                            ['text' => $backText],
                        ],
                    ],
                    'resize_keyboard' => true,
                ])
            ]);
        }
    }


    public static function step14($telegram, $lang_id, $gram, $text)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni

        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        if ($text === '/signup' || $text === self::getT("a3", $lang_id)) {
            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => self::getT("a46", $lang_id), // Malumotlarni tasdiqlash
                'parse_mode' => 'HTML',
                'reply_markup' => self::confirm($lang_id)
            ]);
        }

        // Agar foydalanuvchi "Orqaga" tugmasini bosgan bo‘lsa
        if ($text === $backText || $text === self::getT("a48", $lang_id)) {
            if ($gram->edu_type_id == 1) {
                if ($eduDirection->is_oferta == 1) {
                    $gram->step = 10;
                    $gram->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a49", $lang_id), // Oferta ma'lumotini yuklang
                        'parse_mode' => 'HTML',
                        'reply_markup' => json_encode([
                            'keyboard' => [
                                [
                                    ['text' => $backText],
                                ],
                            ],
                            'resize_keyboard' => true,
                        ])
                    ]);
                }
                if ($gram->exam_type == 0) {
                    // Imtixon turlari
                    $gram->step = 7;
                    $gram->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a44", $lang_id), // Imtixon turi
                        'parse_mode' => 'HTML',
                        'reply_markup' => self::offline($lang_id, $eduDirection)
                    ]);
                } else {
                    // offline sanalari
                    $gram->step = 8;
                    $gram->save(false);
                    return $telegram->sendMessage([
                        'chat_id' => $gram->telegram_id,
                        'text' => self::getT("a45", $lang_id), // Offline imtixon sanalari
                        'parse_mode' => 'HTML',
                        'reply_markup' => self::offlineDate($lang_id, $gram)
                    ]);
                }
            } elseif ($gram->edu_type_id == 2) {
                $gram->step = 11;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a51", $lang_id), // Transkript yuklang
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }  elseif ($gram->edu_type_id == 3) {
                $gram->step = 12;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // DTM
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }elseif ($gram->edu_type_id == 4) {
                $gram->step = 13;
                $gram->save(false);
                return $telegram->sendMessage([
                    'chat_id' => $gram->telegram_id,
                    'text' => self::getT("a54", $lang_id), // MASTER
                    'parse_mode' => 'HTML',
                    'reply_markup' => json_encode([
                        'keyboard' => [
                            [
                                ['text' => $backText],
                            ],
                        ],
                        'resize_keyboard' => true,
                    ])
                ]);
            }
        }


        if ($text === self::getT("a47", $lang_id)) {
            $gram->step = 50;
            $gram->save(false);

            $text = self::allData($gram, $lang_id);

            return $telegram->sendMessage([
                'chat_id' => $gram->telegram_id,
                'text' => $text,
                'parse_mode' => 'HTML',
                'reply_markup' => json_encode([
                    'remove_keyboard' => true
                ])
            ]);
        }


        return $telegram->sendMessage([
            'chat_id' => $gram->telegram_id,
            'text' => self::getT("a56", $lang_id), // Xatolik: noto‘g‘ri
            'parse_mode' => 'HTML',
            'reply_markup' => self::confirm($lang_id)
        ]);
    }

    public static function allData($gram, $lang_id)
    {
        $fullName = $gram->last_name . " " . $gram->first_name . " " . $gram->middle_name;
        $eduDirection = EduDirection::findOne($gram->edu_direction_id);
        $phone = preg_replace("/[^0-9]/", "", $gram->phone);
        $onOff = 'Online';

        // Matnlar
        $messages = [
            1 => [ // O'zbek tili
                'title' => "🏫 <b>ABU RAYHON BERUNIY UNIVERSITETI</b>\n\n",
                'success' => "Arizangiz muvaffaqiyatli yuborildi😊"."\n\n",
                'fio' => "🎓 <b>F.I.O:</b> ",
                'passport' => "📑 <b>Pasport ma'lumoti:</b> ",
                'birthday' => "🗓 <b>Tug'ilgan sana:</b> ",
                'phone' => "📞 <b>Telefon raqam:</b> ",
                'direction' => "🔘 <b>Yo'nalish:</b> ",
                'code' => "🔘 <b>Yo'nalish kodi:</b> ",
                'eduType' => "🔘 <b>Qabul turi:</b> ",
                'eduForm' => "🔘 <b>Ta'lim shakli:</b> ",
                'lang' => "🔘 <b>Ta'lim tili:</b> ",
                'examType' => "🔘 <b>Imtixon turi:</b> ",
                'note' => "<i>Arizangiz ko'rib chiqilib, tez orada siz bilan 👩‍💻 operatorlarimiz bog'lanishadi.\n\nAloqa uchun: " . self::PHONE . "</i>"
            ],
            2 => [ // English
                'title' => "🏫 <b>ABU RAYHON BERUNIY UNIVERSITETI</b>\n\n",
                'success' => "Your application has been successfully submitted😊"."\n\n",
                'fio' => "🎓 <b>Full Name:</b> ",
                'passport' => "📑 <b>Passport Info:</b> ",
                'birthday' => "🗓 <b>Date of Birth:</b> ",
                'phone' => "📞 <b>Phone number:</b> ",
                'direction' => "🔘 <b>Field:</b> ",
                'code' => "🔘 <b>Direction code:</b> ",
                'eduType' => "🔘 <b>Admission type:</b> ",
                'eduForm' => "🔘 <b>Education form:</b> ",
                'lang' => "🔘 <b>Language:</b> ",
                'examType' => "🔘 <b>Exam type:</b> ",
                'note' => "<i>Your application will be reviewed and our 👩‍💻 operators will contact you soon.\n\nContact: " . self::PHONE . "</i>"
            ],
            3 => [ // Русский
                'title' => "🏫 <b>ABU RAYHON BERUNIY UNIVERSITETI</b>\n\n",
                'success' => "Ваша заявка успешно отправлена😊"."\n\n",
                'fio' => "🎓 <b>Ф.И.О.:</b> ",
                'passport' => "📑 <b>Паспортные данные:</b> ",
                'birthday' => "🗓 <b>Дата рождения:</b> ",
                'phone' => "📞 <b>Номер телефона:</b> ",
                'direction' => "🔘 <b>Направление:</b> ",
                'code' => "🔘 <b>Код направления:</b> ",
                'eduType' => "🔘 <b>Тип приема:</b> ",
                'eduForm' => "🔘 <b>Форма обучения:</b> ",
                'lang' => "🔘 <b>Язык обучения:</b> ",
                'examType' => "🔘 <b>Тип экзамена:</b> ",
                'note' => "<i>Ваша заявка будет рассмотрена, и наши 👩‍💻 операторы скоро свяжутся с вами.\n\nКонтакт: " . self::PHONE . "</i>"
            ]
        ];

        $t = $messages[$lang_id] ?? $messages[1]; // Default: Uzbek

        $text = $t['title'];
        $text .= $t['success'];
        $text .= $t['fio'] . $fullName . "\n";
        $text .= $t['passport'] . $gram->passport_serial . " " . $gram->passport_number . "\n";
        $text .= $t['birthday'] . date("d-m-Y", strtotime($gram->birthday)) . "\n";
        $text .= $t['phone'] . $phone . "\n";
        $text .= "- - - - - -\n";

        if ($gram->exam_type == 1) {
            $examDate = ExamDate::findOne($gram->exam_date_id);
            $onOff = "Offline <i>(" . ($lang_id == 1 ? "imtixon sanasi" : ($lang_id == 2 ? "exam date" : "дата экзамена")) . ": " . $examDate->date . ")</i>";
        }

        $text .= $t['direction'] . ($eduDirection->direction['name_'.self::getSelectLanguageText($lang_id)] ?? '----') . "\n";
        $text .= $t['code'] . ($eduDirection->direction->code ?? '----') . "\n";
        $text .= $t['eduType'] . ($eduDirection->eduType['name_'.self::getSelectLanguageText($lang_id)] ?? '----') . "\n";
        $text .= $t['eduForm'] . ($eduDirection->eduForm['name_'.self::getSelectLanguageText($lang_id)] ?? '----') . "\n";
        $text .= $t['lang'] . ($eduDirection->lang['name_'.self::getSelectLanguageText($lang_id)] ?? '----') . "\n";

        if ($gram->edu_type_id == 1) {
            $text .= $t['examType'] . $onOff . "\n";
        }

        $text .= "- - - - - -\n";
        $text .= $t['note'];

        return $text;
    }

    public static function fileUpload($telegram, $gram)
    {
        $botToken = $telegram->botToken;

        $input = $telegram['input'] ?? null;
        if (!$input || empty($input['message'])) {
            return ['is_ok' => false, 'data' => 1]; // 1 → Xabar mavjud emas
        }

        $message = $input['message'];

        $document = $message['document'] ?? null;
        if (!$document) {
            return ['is_ok' => false, 'data' => 2]; // 2 → Hech qanday fayl topilmadi
        }

        $fileId = $document['file_id'] ?? null;
        $fileSize = $document['file_size'] ?? 0;
        $fileName = $document['file_name'] ?? 'file.pdf';

        if (!$fileId) {
            $fileId = $document->file_id ?? null;
            if (!$fileId) {
                return ['is_ok' => false, 'data' => 10];
            }
            return ['is_ok' => false, 'data' => 3]; // 3 → file_id mavjud emas
        }

        // getFile orqali file path olish
        $fileInfoUrl = "https://api.telegram.org/bot{$botToken}/getFile?file_id={$fileId}";
        $fileInfo = json_decode(file_get_contents($fileInfoUrl), false);

        if (empty($fileInfo->ok) || empty($fileInfo->result->file_path)) {
            return ['is_ok' => false, 'data' => 4]; // 4 → Telegramdan fayl ma’lumotlari olinmadi
        }

        $filePath = $fileInfo->result->file_path;
        $url = "https://api.telegram.org/file/bot{$botToken}/{$filePath}";

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $fileSizeLimit = 1024 * 1024 * 5; // 5 MB
        if ($fileSize > $fileSizeLimit) {
            return ['is_ok' => false, 'data' => 5]; // 5 → Fayl hajmi ruxsat etilganidan katta
        }

        if ($ext !== 'pdf') {
            return ['is_ok' => false, 'data' => 6]; // 6 → Faqat PDF fayllar ruxsat etilgan
        }

        // Faylni saqlash joyi
        $uploadPath = dirname(Yii::getAlias('@frontend')) . '/frontend/web/uploads/' . $gram->id . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $uniqueName = sha1($fileName) . "_" . time() . "." . $ext;
        $fullPath = $uploadPath . $uniqueName;

        $stream = fopen($url, 'r');
        if ($stream) {
            file_put_contents($fullPath, $stream);
            fclose($stream);
            return ['is_ok' => true, 'data' => $uniqueName]; // muvaffaqiyatli saqlandi
        } else {
            return ['is_ok' => false, 'data' => 7]; // 7 → Faylni yuklab olishda xatolik
        }
    }


    public static function course($lang_id, $eduDirection)
    {
        $backText = self::getT("a12", $lang_id);

        $courses = DirectionCourse::find()
            ->where([
                'edu_direction_id' => $eduDirection->id ?? null,
                'status' => 1,
                'is_deleted' => 0
            ])
            ->all();

        // Tilga qarab ustun nomi
        switch ($lang_id) {
            case 1:
                $nameColumn = 'name_uz';
                break;
            case 2:
                $nameColumn = 'name_en';
                break;
            case 3:
                $nameColumn = 'name_ru';
                break;
            default:
                $nameColumn = 'name_uz';
        }

        $keyboard = [];
        $row = [];

        foreach ($courses as $course) {
            $row[] = ['text' => $course->course->$nameColumn];

            if (count($row) == 2) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        if (!empty($row)) {
            $row[] = ['text' => $backText];
            $keyboard[] = $row;
        } else {
            $keyboard[] = [
                ['text' => $backText]
            ];
        }

        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);
    }

    public static function getSelectLanguageText($lang)
    {
        $array = [
            1 => "uz",
            2 => "en",
            3 => "ru",
        ];
        return isset($array[$lang]) ? $array[$lang] : null;
    }

    public static function seria($text)
    {
        $pattern = '/^[A-Z]{2}\d{7}$/';
        if (preg_match($pattern, $text)) {
            return true;
        } else {
            return false;
        }
    }

    public static function date($text)
    {
        $format = 'Y-m-d';
        $d = \DateTime::createFromFormat($format, $text);
        return $d && $d->format($format) === $text;
    }

    public static function passport($gram)
    {
        $client = new Client();
        $url = 'https://api.online-mahalla.uz/api/v1/public/tax/passport';
        $params = [
            'series' => $gram->passport_serial,
            'number' => $gram->passport_number,
            'birth_date' => $gram->birthday,
        ];
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setData($params)
            ->send();

        if ($response->isOk) {
            $responseData = $response->data;
            $passport = $responseData['data']['info']['data'];
            $gram->first_name = $passport['name'];
            $gram->last_name = $passport['sur_name'];
            $gram->middle_name = $passport['patronymic_name'];
            $gram->passport_pin = (string)$passport['pinfl'];

            $gram->gender = 1;
            return ['is_ok' => true, 'gram' => $gram];
        }
        return ['is_ok' => false];
    }

    public static function eduType($lang_id)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni
        return json_encode([
            'keyboard' => [
                [
                    ['text' => self::getT("a22", $lang_id)],
                    ['text' => self::getT("a23", $lang_id)],
                ],
                [
                    ['text' => $backText],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function eduForm($lang_id)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni
        return json_encode([
            'keyboard' => [
                [
                    ['text' => self::getT("a28", $lang_id)],
                    ['text' => self::getT("a29", $lang_id)],
                ],
                [
                    ['text' => $backText],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function eduLang($lang_id)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni
        return json_encode([
            'keyboard' => [
                [
                    ['text' => self::getT("a37", $lang_id)],
                    ['text' => $backText],
//                    ['text' => self::getT("a38", $lang_id)],
                ],
//                [
//                    ['text' => self::getT("a39", $lang_id)],
//                    ['text' => $backText],
//                ],
            ],
            'resize_keyboard' => true,
        ]);
    }

    public static function branch($lang_id)
    {
        $branches = Branch::find()
            ->where(['status' => 1, 'is_deleted' => 0])
            ->andWhere(['not in', 'cons_id', [null]])
            ->all();

        $backText = self::getT("a12", $lang_id);

        // PHP 7.4: tilga mos ustunni aniqlash
        if ($lang_id == 1) {
            $column = 'name_uz';
        } elseif ($lang_id == 2) {
            $column = 'name_en';
        } elseif ($lang_id == 3) {
            $column = 'name_ru';
        } else {
            $column = 'name_uz';
        }

        $keyboard = [];
        $row = [];

        foreach ($branches as $i => $branch) {
            $row[] = ['text' => $branch->$column];

            if (count($row) == 2) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        if (!empty($row)) {
            $row[] = ['text' => $backText]; // oxirgi branch bilan yonma-yon
            $keyboard[] = $row;
        } else {
            $keyboard[] = [
                ['text' => $backText]
            ];
        }

        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);
    }

    public static function direction($lang_id, $gram)
    {
        $backText = self::getT("a12", $lang_id);

        $directions = Direction::find()
            ->alias('d')
            ->innerJoin(['ed' => EduDirection::tableName()], 'ed.direction_id = d.id')
            ->where([
                'd.status' => 1,
                'd.is_deleted' => 0,
                'd.branch_id' => $gram->branch_id,
                'ed.branch_id' => $gram->branch_id,
                'ed.edu_type_id' => $gram->edu_type_id,
                'ed.edu_form_id' => $gram->edu_form_id,
                'ed.lang_id' => $gram->edu_lang_id,
                'ed.status' => 1,
                'ed.is_deleted' => 0
            ])
            ->groupBy('d.id') // agarda bir nechta ed bo‘lsa takrorlanmaslik uchun
            ->all();


        // Tilga qarab ustun nomini aniqlash
        if ($lang_id == 1) {
            $column = 'name_uz';
        } elseif ($lang_id == 2) {
            $column = 'name_en';
        } elseif ($lang_id == 3) {
            $column = 'name_ru';
        } else {
            $column = 'name_uz';
        }

        // Har bir yo‘nalishni alohida qatorga qo‘shish
        $keyboard = [];

        foreach ($directions as $dir) {
            $keyboard[] = [['text' => $dir->code. " - ". $dir->$column]];
        }

        // Oxiriga "Orqaga" tugmasini qo‘shamiz
        $keyboard[] = [['text' => $backText]];

        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);
    }

    public static function offline($lang_id, $eduDirection)
    {
        $backText = self::getT("a12", $lang_id);

        $exam = [];
        if ($eduDirection->exam_type != null) {
            $examTypes = json_decode($eduDirection->exam_type, true);
            foreach ($examTypes as $examType) {
                $exam[] = ['text' => Status::getExamStatus($examType)];
            }
        }

        $keyboard = [];

        $count = count($exam);

        if ($count == 2) {
            // 2 ta tugma bitta qatorda
            $keyboard[] = $exam;
            // Orqaga qaytish tugmasi alohida qatorda
            $keyboard[] = [['text' => $backText]];
        } elseif ($count == 1) {
            // Bitta tugma + orqaga qaytish yonma-yon
            $keyboard[] = [$exam[0], ['text' => $backText]];
        } else {
            // Faqat orqaga qaytish
            $keyboard[] = [['text' => $backText]];
        }

        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);

    }

    public static function offlineDate($lang_id, $gram)
    {
        $backText = self::getT("a12", $lang_id);

        $examDates = ExamDate::find()
            ->where([
                'is_deleted' => 0,
                'status' => 1,
                'branch_id' => $gram->branch_id
            ])
            ->andWhere(['>=', 'date', date('Y-m-d')])
            ->orderBy(['date' => SORT_ASC])
            ->all();

        $keyboard = [];
        $row = [];

        foreach ($examDates as $index => $examDate) {
            $row[] = ['text' => date('Y-m-d H:i', strtotime($examDate->date))];

            // Har 2 ta elementdan keyin yangi qatorga o'tamiz
            if (count($row) == 2) {
                $keyboard[] = $row;
                $row = [];
            }
        }

        // Agar oxirida bitta element qolgan bo‘lsa
        if (!empty($row)) {
            // Orqaga qaytishni yonma-yon chiqaramiz
            $row[] = ['text' => $backText];
            $keyboard[] = $row;
        } else {
            // Aks holda, orqaga qaytish alohida qatorda
            $keyboard[] = [
                ['text' => $backText]
            ];
        }

        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);
    }

    public static function confirm($lang_id)
    {
        $backText = self::getT("a12", $lang_id); // "Orqaga" tugmasi matni
        return json_encode([
            'keyboard' => [
                [
                    ['text' => self::getT("a48", $lang_id)],
                    ['text' => self::getT("a47", $lang_id)],
                ],
                [
                    ['text' => $backText],
                ],
            ],
            'resize_keyboard' => true,
        ]);
    }


    public static function getT($text, $lang_id)
    {
        $lang = self::getSelectLanguageText($lang_id);
        $array = [
            "a1" => [
                "uz" => "🏫 Universitet haqida",
                "ru" => "",
                "en" => "",
            ],
            "a2" => [
                "uz" => "🪧 Mavjud yo'nalishlar",
                "ru" => "",
                "en" => "",
            ],
            "a3" => [
                "uz" => "👨‍🎓 Ro'yhatdan o'tish",
                "ru" => "",
                "en" => "",
            ],
            "a4" => [
                "uz" => "🔄 Bot tilini o'zgartirish",
                "ru" => "",
                "en" => "",
            ],
            "a5" => [
                "uz" => "🏠 Bosh sahifa",
                "ru" => "",
                "en" => "",
            ],
            "a6" => [
                "uz" => "❌ Arizani faqat UZB telefon raqamlari orqali qoldirishingiz mumkin. \n\n<i>Aloqa uchun: ".self::PHONE."</i>",
                "ru" => "",
                "en" => "",
            ],
            "a7" => [
                "uz" => "☎️",
                "ru" => "",
                "en" => "",
            ],
            "a8" => [
                "uz" => "❌ Ma'lumotni noto'g'ri yubordingiz.\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],

            "a9" => [
                "uz" => "🇺🇿 O'zbek tili",
                "ru" => "",
                "en" => "",
            ],
            "a10" => [
                "uz" => "🇷🇺 Rus tili",
                "ru" => "",
                "en" => "",
            ],
            "a11" => [
                "uz" => "🏴󠁧󠁢󠁥󠁮󠁧󠁿 Ingliz tili",
                "ru" => "",
                "en" => "",
            ],
            "a12" => [
                "uz" => "🔙 Orqaga",
                "ru" => "",
                "en" => "",
            ],

            "a13" => [
                "uz" => "🤖 Bot tilini tanlang! \n\n Shunda bot siz tanlagan tilda javob berishni boshlaydi 😊",
                "ru" => "",
                "en" => "",
            ],
            "a14" => [
                "uz" => "❌:( Raqamingizni ro'yhatdan o'tkazib bo'lmadi.\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a15" => [
                "uz" => "📅 Tug'ilgan sanangizni (yil-oy-sana ko'rinishida) yozing.\n\n<i>Masalan: 2001-10-16</i>",
                "ru" => "",
                "en" => "",
            ],
            "a16" => [
                "uz" => "📄 Pasportingiz seriyasi va raqamini yozing.\n\n<i>Masalan: AB1234567</i>",
                "ru" => "",
                "en" => "",
            ],
            "a17" => [
                "uz" => "📄❌ Pasportingiz seriyasi va raqamini namunada ko'rsatilgan formatda yuboring .\n\n<i>Masalan: AB1234567</i>",
                "ru" => "",
                "en" => "",
            ],
            "a18" => [
                "uz" => "📅❌:( Tug'ilgan sanangiz namunada ko'rsatilgandek yuboring.\n\n<i>Masalan: 2001-10-16</i>",
                "ru" => "",
                "en" => "",
            ],
            "a19" => [
                "uz" => "❌:( Pasport ma'lumotini yuklashda xatolik sodir bo'ldi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a20" => [
                "uz" => "😊 Bosh sahifaga xush kelibsiz.",
                "ru" => "",
                "en" => "",
            ],
            "a21" => [
                "uz" => "Qabul turini tanlang",
                "ru" => "",
                "en" => "",
            ],
            "a22" => [
                "uz" => "Qabul 2025",
                "ru" => "",
                "en" => "",
            ],
            "a23" => [
                "uz" => "O'qishni ko'chirish",
                "ru" => "",
                "en" => "",
            ],
            "a24" => [
                "uz" => "UZBMB (DTM) natija",
                "ru" => "",
                "en" => "",
            ],
            "a25" => [
                "uz" => "Magistratura",
                "ru" => "",
                "en" => "",
            ],
            "a26" => [
                "uz" => "Ta'lim shaklini tanlang.",
                "ru" => "",
                "en" => "",
            ],
            "a27" => [
                "uz" => "Ta'lim tilini tanlang.",
                "ru" => "",
                "en" => "",
            ],
            "a28" => [
                "uz" => "Kunduzgi",
                "ru" => "",
                "en" => "",
            ],
            "a29" => [
                "uz" => "Sirtqi",
                "ru" => "",
                "en" => "",
            ],
            "a30" => [
                "uz" => "Kechki",
                "ru" => "",
                "en" => "",
            ],
            "a31" => [
                "uz" => "Masofaviy",
                "ru" => "",
                "en" => "",
            ],
            "a32" => [
                "uz" => "Masofaviy",
                "ru" => "",
                "en" => "",
            ],

            "a33" => [
                "uz" => "❌:( Qabul turi noto'g'ri tanlandi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a34" => [
                "uz" => "❌:( Ta'lim shakli noto'g'ri tanlandi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a35" => [
                "uz" => "❌:( Ta'lim tili noto'g'ri tanlandi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a37" => [
                "uz" => "O‘zbek tili",
                "ru" => "",
                "en" => "",
            ],
            "a38" => [
                "uz" => "Rus tili",
                "ru" => "",
                "en" => "",
            ],
            "a39" => [
                "uz" => "Ingliz tili",
                "ru" => "",
                "en" => "",
            ],
            "a40" => [
                "uz" => "Filial tanlang",
                "ru" => "",
                "en" => "",
            ],
            "a41" => [
                "uz" => "❌:( Filial noto'g'ri tanlandi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a42" => [
                "uz" => "Ta'lim yo'nalishlaridan birini tanlang.",
                "ru" => "",
                "en" => "",
            ],
            "a43" => [
                "uz" => "❌:( Ta'lim yo'nalishi noto'g'ri tanlandi .\n\n<i>Aloqa uchun: " . self::PHONE . "</i>",
                "ru" => "",
                "en" => "",
            ],
            "a44" => [
                "uz" => "Imtixon turini tanlang.",
                "ru" => "",
                "en" => "",
            ],
            "a45" => [
                "uz" => "Offline imtixon sanasini tanlang.",
                "ru" => "",
                "en" => "",
            ],
            "a46" => [
                "uz" => "Ma'lumotni tasdiqlaysizmi?",
                "ru" => "",
                "en" => "",
            ],
            "a47" => [
                "uz" => "Ha",
                "ru" => "",
                "en" => "",
            ],
            "a48" => [
                "uz" => "Yo'q",
                "ru" => "",
                "en" => "",
            ],
            "a49" => [
                "uz" => "5 yillik staj faylini yuklang",
                "ru" => "",
                "en" => "",
            ],
            "a50" => [
                "uz" => "Imtixon sanasini noto'g'ri tanladingiz",
                "ru" => "",
                "en" => "",
            ],
            "a51" => [
                "uz" => "Transkript fayl yuboring",
                "ru" => "",
                "en" => "",
            ],
            "a52" => [
                "uz" => "Bosqichni noto'g'ri tanladingiz",
                "ru" => "",
                "en" => "",
            ],
            "a53" => [
                "uz" => "Imtixon turini noto'g'ri tanladingiz",
                "ru" => "",
                "en" => "",
            ],
            "a54" => [
                "uz" => "Bosqich tanlang",
                "ru" => "",
                "en" => "",
            ],
            "a55" => [
                "uz" => "Fayl pdf formatda va 5 mbdan oshmagan holatda yuklanishi shart!",
                "ru" => "",
                "en" => "",
            ],
            "a56" => [
                "uz" => "Ma'lumot tasdiqlashda Ha yoki yo'q deb javob berishingiz kerak.",
                "ru" => "",
                "en" => "",
            ],
        ];
        if (isset($array[$text])) {
            return isset($array[$text][$lang]) ? $array[$text][$lang] : $text;
        } else {
            return $text;
        }
    }
}
