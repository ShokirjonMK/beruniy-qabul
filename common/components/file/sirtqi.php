<?php

use common\models\Student;
use common\models\Direction;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Course;
use Da\QrCode\QrCode;
use frontend\models\Contract;
use common\models\User;
use common\models\Consulting;
use common\models\Branch;
use common\models\StudentMaster;

/** @var Student $student */
/** @var Direction $direction */
/** @var User $user */
/** @var Branch $filial */

$user = $student->user;
$cons = Consulting::findOne($user->cons_id);
$eduDirection = $student->eduDirection;
$direction = $eduDirection->direction;
$full_name = $student->last_name.' '.$student->first_name.' '.$student->middle_name;
$code = '';
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$date = '';
$link = '';
$con2 = '';
if ($student->edu_type_id == 1) {
    $contract = Exam::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $code = 'Q2/'.$cons->code.'/'.$contract->id;
    $date = date("Y-m-d H:i" , $contract->confirm_date);
    $link = '1&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'P2/'.$cons->code.'/'.$contract->id;
    $date = date("Y-m-d H:i" , $contract->confirm_date);
    $link = '2&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'D2/'.$cons->code.'/'.$contract->id;
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = '3&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 4) {
    $contract = StudentMaster::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'M2/'.$cons->code.'/'.$contract->id;
    $date = date("Y-m-d H:i:s" , $contract->confirm_date);
    $link = '4&?id='.$contract->id;
    $con2 = '2'.$contract->invois;
    $contract->down_time = time();
    $contract->save(false);
}

$student->is_down = 1;
$student->update(false);

$filial = Branch::findOne($student->branch_id);

$qr = (new QrCode('https://qabul.tgfu.uz/site/contract?key='.$link.'&type=2'))->setSize(120, 120)
    ->setMargin(10);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/48a00e41-6370-49d6-baf7-ea67247beeb6'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();
?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 14px; border-collapse: collapse; line-height: 20px;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                To‘lov-kontrakt (Ikki tomonlama) asosida mutaxassis tayyorlashga <br>
                KONTRAKT № <?= $code ?>
            </b>
        </td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="2" style="text-align: left;">Toshkent shahri</td>
        <td colspan="2" style="text-align: right;"><?= $date ?></td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            <?= $joy ?> O‘zbekiston Respublikasi Prezidentining 2022-yil 15-iyundagi “Davlat oliy ta’lim muassasalariga o‘qishga qabul qilish jarayonlarini tashkil etish to‘g‘risida”gi PQ-279-son qarori, Vazirlar Mahkamasining “Oliy ta’lim muassasalariga o‘qishga qabul qilish, talabalar o‘qishini ko‘chirish, qayta tiklash va o‘qishdan chetlashtirish tartibi to‘g‘risidagi nizomlarni tasdiqlash haqida” 2017-yil 20-iyundagi 393-son qarori, O‘zbekiston Respublikasi oliy va o‘rta maxsus ta’lim vazirining
            2012-yil  28-dekabrdagi  508-son  buyrug‘i  (ro‘yxat  raqami  2431,  2013-yil  26-fevral)  bilan  tasdiqlangan  Oliy  va  o‘rta maxsus, kasb-hunar ta’limi muassasalarida o‘qitishning to‘lov-kontrakt shakli va undan tushgan mablag‘larni taqsimlash tartibi to‘g‘risidagi Nizomga muvofiq, <?= $filial->name_uz ?> (keyingi o‘rinlarda “Ta’lim muassasasi”) nomidan rektor (direktor)
            <?= $filial->rector_uz ?> bir tomondan, <?= $student->passport_pin.' - '.$full_name ?> (keyingi o‘rinlarda “Ta’lim oluvchi”) ikkinchi tomondan, birgalikda “Tomonlar”
            deb ataladigan shaxslar mazkur kontraktni quyidagicha tuzdilar:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>I. KONTRAKT PREDMETI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            1.1. Ta’lim muassasasi ta’lim xizmatini ko‘rsatishni, Ta’lim oluvchi o‘qish uchun belgilangan to‘lovni o‘z vaqtida amalga oshirishni va tasdiqlangan o‘quv reja bo‘yicha darslarga to‘liq qatnashish va ta’lim olishni o‘z zimmalariga oladi. Ta’lim oluvchining ta’lim ma’lumotlari quyidagicha:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
            <table width="100%">
                <tr>
                    <td>O‘quv kursi:</td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td>Ta’lim shakli:</td>
                    <td><b><?= $eduDirection->eduForm->name_uz ?></b></td>
                </tr>
                <tr>
                    <td>O‘qish muddati:</td>
                    <td><b><?= $eduDirection->duration .' yil' ?></b></td>
                </tr>
                <tr>
                    <td>Ta’lim yo‘nalishi:</td>
                    <td><b><?= $direction->code.' '.$direction->name_uz ?></b></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            1.2.   “Ta’lim   muassasasi”ga   o‘qishga   qabul   qilingan   “Ta’lim   oluvchi”lar   O‘zbekiston   Respublikasining   “Ta’lim to‘g‘risida”gi Qonuni va davlat ta’lim standartlarga muvofiq ishlab chiqilgan o‘quv rejalar va fan dasturlari asosida ta’lim oladilar.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>II. TA’LIM XIZMATINI KO‘RSATISH NARXI, TO‘LASH MUDDATI VA TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.1. 2024-2025 o‘quv yili uchun o‘qitish qiymati <?= number_format((int)$contract->contract_price, 0, '', ' ') ?> (<?= Contract::numUzStr($contract->contract_price) ?>)ni so‘mni tashkil etadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.2. Yillik kontrakt summasini Talaba 2024-o’quv yilining 15-noyabrgacha amalga oshirsa, kontrakt summasining belgilangan qiymatining 10% miqdorida imtiyoz beriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            2.3. Universitet xizmatlar narxini mehnatga haq to’lashning eng kam miqdoriga kiritilgan o’zgartirilishlar  orqali vakolatini o‘zida saqlab qoladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.4. Kontrakt to'lovining 25 foizi 15-dekabrgacha to'lov amalga oshiriladi, (to'lov amalga oshirilmasa 15-dekabrdan talaba darslarga  qo'yilmaydi)  25% (50%) to'lov mazkur  shartnoma  tuzilgan  sanadan  boshlab  2025-yil  1-yanvar kuniga qadar, bahorgi semestr uchun 25% (75%) 2025-yil 1-mart kuniga qadar va qolgan 25% (100%) qismi o‘quv yilining bahorgi semestri uchun yakuniy nazoratlar boshlanguniga qadar to‘lanadi. Kontrakt to`lovlari o`z vaqtida amalga oshirilmagan taqdirda to`lov muddati kechiktirilgan summaga 5 % miqdorida jarima qo`llanilishiga sabab bo`lishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.5. Har bir oila(ota,ona, farzandlari va ularning turmush o’rtoqlari ) dan kamida 2 nafar abituriyentlar universitetda o’qish istagini bildirib, kirish imtihonlarini muvaffaqiyatli topshirib talabalikka tavsiya qilinganda mazkur oilaning har bir a’zosiga “KATTA OILA” granti taqdim qilinadi, ya’ni har o’quv yili uchun to’lov-kontrakt summasining 10 foizi miqdorida chegirmalar  beriladi. “ZARMED”  MCHJ xodimlarining  farzandlariga  tabaqalashtirilgan  tartibda imtiozlar beriladi, bunda 5 yilgacha ish stajiga ega bo’lgan xodimlarning farzandlariga to’lov-kontrakt summasining
            5 foizi, 5 yildan 10 yilgacha ish stajiga ega bo’lgan xodimlarning farzandlariga to’lov-kontrakt  summasining 10 foizi,  10 yildan  ortiq  ish stajiga  ega bo’lgan  xodimlarning  farzandlariga  to’lov-kontrakt  summasining  15 foizi miqdorida  chegirma  beriladi.  Shu  bilan  xodimlarning  nevaralari  ZARMED  universitetida  tahsil  olsa  20  foizi chegirma beriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.6. Talaba tomonidan kontrakt summasi Universitet hisob raqamiga kelib tushgan sanada kontrakt summmasi to‘langan deb hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            2.7. Quyidagi holatlarda kontrakt to‘lovi (qisman) qaytarilmaydi:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            2.8. Universitet tashabbusi bilan talabalar safidan chetlashtirilganda (tegishli semestr uchun to‘langan kontrakt  talabaning o’qigan davri uchun to‘lov kontrakti qaytarilmaydi);
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            2.9. Talaba  talabalar safidan chetlashtirish to‘g‘risida  murojaat qilganda  o’qigan davri uchun to’lov amalga oshirilishi shart shundagina, talabaning hujjatlar to’plami qaytariladi (aks holda to’lov amalga oshirilmasa hujjatlar to’plami qaytarilmaydi);
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            2.10. Talaba vafot etganda, ozodlikdan mahrum qilinganda yoki boshqa davlatlarga ko’chib ketganda o‘qigan davriga mutanosib ravishda kontrakt to‘lovlari  amalga oshiriladi, qolgan qismi qaytariladi..
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center; font-weight: bold;">
            <b>III. TOMONLARNING MAJBURIYATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>



    <tr>
        <td colspan="4" style="text-align: justify; font-weight: bold;">
            3.1. Ta’lim muassasasi majburiyatlari:
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.1 O‘qitish uchun belgilangan kontrakt summasi o‘z vaqtida to’lov  amalga oshirgandan so‘ng, “Ta’lim    oluvchi”ni buyruq asosida talabalikka qabul qilish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.2  Ta’lim  oluvchiga,  o‘qishi  uchun  O‘zbekiston  Respublikasining  “Ta’lim  to‘g‘risida”gi  Qonuni  va
            “Ta’lim muassasasi”ning  Ustavida nazarda tutilgan  shart-sharoitlarni yaratib berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.3 Ta’lim oluvchining huquq va erkinliklari, qonuniy manfaatlari hamda ta’lim muassasasi Ustaviga muvofiq professor- o‘qituvchilar tomonidan o‘zlarining funksional vazifalarini to‘laqonli bajarishini ta’minlash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.4 Ta’lim oluvchini tahsil olayotgan ta’lim yo‘nalishi (mutaxassisligi) bo‘yicha tasdiqlangan o‘quv rejasi va dasturlariga muvofiq davlat ta’lim standarti talablari darajasida tayyorlash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.5 O‘quv yili boshlanishida ta’lim oluvchini yangi o‘quv yili uchun belgilangan to‘lov miqdori to‘g‘risida o‘quv jarayoni boshlanishidan oldin xabardor qilish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.1.6 Kontrakt to’lov  miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda ta’lim oluvchiga ta’limning qolgan muddati uchun to‘lov miqdori haqida xabar berish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify; font-weight: bold;">
            3.2. Ta’lim oluvchining majburiyatlari:
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.2.1 Kontraktning 2- bandida belgilangan to‘lov summasini shu bandda ko‘rsatilgan muddatlarda to‘lab borish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            3.2.2 Kontrakt to’lov  miqdori yoki tariflar o‘zgarishi natijasida o‘qitish uchun belgilangan to‘lov miqdori o‘zgargan taqdirda, o‘qishning qolgan muddati uchun ta’lim muassasasiga haq to‘lash bo‘yicha bir oy muddat ichida kontraktga qo‘shimcha bitim rasmiylashtirish va to‘lov farqini to‘lash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            3.2.3  Ta’lim  oluvchi  o‘qitish  uchun  belgilangan  to‘lov  miqdorini  to‘laganlik  to‘g‘risidagi  bank  tasdiqnomasi  va kontraktning bir nusxasini o‘z vaqtida hujjatlarni rasmiylashtirish uchun ta’lim muassasasiga topshirish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            3.2.4  Tahsil  olayotgan  ta’lim  yo‘nalishining  (mutaxassisligining)  tegishli  malaka  tavsifnomasiga  muvofiq  kelajakda mustaqil faoliyat yuritishga zarur bo‘lgan barcha bilimlarni egallash, dars va mashg‘ulotlarga to‘liq qatnashish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            3.2.5Ta’lim muassasasi va talabalar turar joyining ichki nizomlariga qat’iy rioya qilish, professor o’qituvchilar va xodimlarga hurmat bilan qarash, “Ta’lim muassasasi” obro‘siga putur yetkazadigan harakatlar qilmaslik, moddiy bazasini asrash, ziyon keltirmaslik, ziyon keltirganda o‘rnini qoplash.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center; font-weight: bold;">
            <b>IV. TOMONLARNING HUQUQLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify; font-weight: bold;">
            4.1. Ta’lim muassasasi huquqlari:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            4.1.1 O‘quv jarayonini mustaqil ravishda amalga oshirish, “Ta’lim oluvchi”ning oraliq va yakuniy nazoratlarni topshirish, qayta topshirish tartibi hamda vaqtlarini belgilash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            4.1.2 O‘zbekiston Respublikasi qonunlari, “Ta’lim muassasasi” nizomi hamda mahalliy normativ-huquqiy hujjatlarga muvofiq “Ta’lim oluvchi”ga rag‘batlantiruvchi yoki intizomiy choralarni qo‘llash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            4.1.3 Agar “Ta’lim oluvchi” o‘quv yili semestrlarida yakuniy nazoratlarni topshirish, qayta topshirish natijalariga ko‘ra akademik qarzdor bo‘lib qolsa uni kursdan-kursga qoldirish huquqiga ega.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            4.1.4 “Ta’lim muassasasi” “Ta’lim oluvchi”ning qobiliyati, darslarga sababsiz qatnashmaslik, intizomni buzish, “Ta’lim muassasasi”ning ichki tartib qoidalariga amal qilmaganda,normativ-huquqiy hujjatlarida nazarda tutilgan boshqa sabablarga ko‘ra hamda o‘qitish uchun belgilangan to‘lov o‘z vaqtida amalga oshirilmaganda “Ta’lim oluvchi”ni talabalar safidan chetlashtirish huquqiga ega.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify; font-weight: bold;">
            4.2.Ta’lim oluvchining huquqlari:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            4.2.1 O‘quv yili uchun kontrakt summasini semestrlarga yoki choraklarga bo‘lmasdan bir yo‘la to‘liqligicha to‘lash mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            4.2.2 Ta’lim oluvchi mazkur kontrakt bo‘yicha naqd pul, bank plastik kartasi, bankdagi omonat hisob raqami orqali, ish joyidan arizasiga asosan oylik maoshini o‘tkazishi yoki banklardan ta’lim krediti olish orqali to‘lovni amalga oshirishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            4.2.3 Professor-o‘qituvchilarning o‘z funksional vazifalarini bajarishidan yoki ta’lim muassasasidagi shart- sharoitlardan norozi bo‘lgan taqdirda ta’lim muassasasi rahbariyatiga yozma shaklda murojaat qilish.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. KONTRAKTNING AMAL QILISH MUDDATI, UNGA O‘ZGARTIRISH VA QO‘SHIMCHALAR KIRITISH HAMDA BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            5.1. Ushbu kontrakt ikki tomonlama imzolangandan so‘ng kuchga kiradi hamda ta’lim xizmatlarini taqdim etish o‘quv yili tugagunga qadar amalda bo‘ladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.2. Ushbu kontrakt shartlariga ikkala tomon kelishuviga asosan tuzatish, o‘zgartirish va qo‘shimchalar kiritilishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.3.  Kontraktga  tuzatish,  o‘zgartirish  va  qo‘shimchalar  faqat  yozma  ravishda  “Kontraktga  qo‘shimcha  bitim”  tarzida kiritiladi va imzolanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.4. Kontrakt quyidagi hollarda bekor qilinishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.5 Tomonlarning o‘zaro kelishuviga binoan.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.6 “Ta’lim oluvchi” talabalar safidan chetlashtirganda “Ta’lim muassasasi” tashabbusi bilan bir tomonlama bekor qilinishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.7 Tomonlardan biri o‘z majburiyatlarini bajarmaganda yoki lozim darajada bajarmaganda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.8 Uzrli sabablar bilan, “Ta’lim oluvchi”ning tashabbusiga ko‘ra.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            5.9 Ta’lim muassasasi tugatilganda, ta’lim oluvchi bilan o‘zaro qayta hisob-kitob qilinadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. KONTRAKTNING AMAL QILISH MUDDATI, UNGA O‘ZGARTIRISH VA QO‘SHIMCHALAR KIRITISH HAMDA BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            6.1. Ushbu kontraktni bajarish jarayonida kelib chiqishi mumkin bo‘lgan nizo va ziddiyatlar tomonlar o‘rtasida muzokaralar olib borish yo‘li bilan hal etiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            6.2. Muzokaralar olib borish yo‘li bilan nizoni hal etish imkoniyati bo‘lmagan taqdirda, tomonlar nizolarni hal etish uchun amaldagi qonunchilikka muvofiq iqtisodiy sudga murojaat etishlari mumkin.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify;">
            6.3. “Ta’lim muassasasi” axborotlar va xabarnomalarni internetdagi veb-saytida, axborot tizimida yoki e’lonlar taxtasida e’lon joylashtirish orqali xabar berishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            6.4. Kontrakt 2 (ikki) nusxada, tomonlarning har biri uchun bir nusxadan tuzildi va ikkala nusxa ham bir xil huquqiy kuchga ega.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify;">
            6.5.  Ushbu  kontraktga  qo‘shimcha  bitim  kiritilgan  taqdirda  ushbu  barcha  kiritilgan  qo‘shimcha  bitimlar  kontraktning ajralmas qismi hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <div>
                <table width="100%">

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="4" style="text-align: center;">
                            <b>VII. TOMONLARNING REKVIZITLARI VA IMZOLARI</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <b>7.1. Ta’lim muassasasi:</b>
                        </td>
                        <td colspan="2">
                            <b>7.2. Ta’lim oluvchi:</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top; padding-right: 5px;">
                            <b><?= $filial->name_uz ?></b> <br>
                            <b>Pochta manzili:</b> <br>
                            <b>Manzil:</b> <?= $cons->pochta_address ?> <br>
                            <b>E-mail:</b> <?= $cons->mail ?> <br>
                            <b>Tel:</b> <?= $cons->pochta_phone ?> <br>
                            <b>Bank rekvizitlari: </b> <br>
                            <b>Bank:</b> <?= $cons->bank_name_uz ?>  <br>
                            <b>H/R:</b> <?= $cons->hr ?> <br>
                            <b>STIR (INN):</b> <?= $cons->inn ?> <br>
                            <b>Bank kodi (MFO):</b> <?= $cons->mfo ?>  <br>
                            <b>Ta’lim muassasasi rahbari:</b> <?= $filial->rector_uz ?> <br>
                        </td>
                        <td colspan="2" style="vertical-align: top; padding-left: 5px;">
                            <b>F.I.Sh.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial.' '.$student->passport_number ?> <br>
                            <b>JShShIR:</b> <?= $student->passport_pin ?> <br>
                            <b>Tеlefon raqami: </b> <?= $student->user->username ?> <br>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">

                        </td>
                        <td colspan="2">

                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>
</table>