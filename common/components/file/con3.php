<?php

use common\models\CrmPush;
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
$full_name = $student->last_name . ' ' . $student->first_name . ' ' . $student->middle_name;
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
    $code = 'Q3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '1&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 2) {
    $contract = StudentPerevot::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'P3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i", $contract->confirm_date);
    $link = '2&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 3) {
    $contract = StudentDtm::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'D3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '3&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
} elseif ($student->edu_type_id == 4) {
    $contract = StudentMaster::findOne([
        'edu_direction_id' => $eduDirection->id,
        'student_id' => $student->id,
        'file_status' => 2,
        'is_deleted' => 0
    ]);
    $code = 'M3/' . $cons->code . '/' . $contract->id;
    $date = date("Y-m-d H:i:s", $contract->confirm_date);
    $link = '4&id=' . $contract->id;
    $con2 = '3' . $contract->invois;
    $contract->down_time = time();
    $contract->save(false);
}

$contract->contract_price = preg_replace('/\D/', '', $contract->contract_price);

$student->is_down = 1;
$student->update(false);

$filial = Branch::findOne($student->branch_id);

$qr = (new QrCode('https://qabul.tgfu.uz/site/contract?key=' . $link . '&type=3'))->setSize(120, 120)
    ->setMargin(10);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/48a00e41-6370-49d6-baf7-ea67247beeb6'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();


?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 12px; border-collapse: collapse;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                To‘lov shartnoma (uch tomonlama) asosida mutaxassis tayyorlash to‘g‘risida<br>
                № <?= $student->passport_pin ?> - sonli SHARTNOMA (bakalavr)
            </b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="2"><?= $date ?></td>
        <td colspan="2" style="text-align: right"><span><?= $filial->name_uz ?></span></td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            «Abu Rayhon Beruniy Universiteti» nodavlat oliy ta’lim muassasasi oliy ta’lim faoliyatini amalga oshirish uchun O’zbekiston Respublikasi Oliy ta’lim, fan va innovatsiyalar
            vazirligi tomonidan 21.08.2024 yilda berilgan № 363695-sonli Litsenziya va Universitet ustavga binoan <?= $filial->rector_uz ?> rahbarligi ostida faoliyat yuritadi, va bundan keyin «Universitet» deb yuritiladi,
            bir tomondan <?= $full_name ?> bundan keyin «Talaba» deb yuritiladi, ikkinchi tomondan, va (Тashkilotning nomi) ____________________________________ (asosida faoliyat yuritadigan) bundan keyin «To‘lovchi» deb yuritiladi, uchinchi tomondan, ushbu shartnomani quyidagicha tuzdilar:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>1. SHARTNOMA MAZMUNI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            1.1. Mazkur Shartnomaga asosan Ta’lim muassasasi 2025-2026 o‘quv yili davomida belgilangan ta’lim standartlari va o‘quv dasturlariga muvofiq o‘quv jarayonlarini tashkil etadi, talaba esa shartnomaning ko‘rsatilgan tartib va miqdordagi to‘lovni amalga oshiradi hamda talaba ta’lim muassasasida belgilangan ichki-tartib qoidaga muvofiq ta’lim olish majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
            <table width="100%">
                <tr>
                    <td colspan="2">Ta’lim bosqichi:</td>
                    <td colspan="2"><b>Bakalavr</b></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: justify">
                        Ta’lim yo‘nalishi: <b><?= $direction->code . ' ' . $direction->name_uz ?></b> Mazkur Shartnoma bo‘yicha to‘lov amalga oshirilgach, bank to‘lov topshiriqnomasi yoki kvitansiya nusхasi Ta’lim tashkilotiga taqdim etilganidan so‘ng to‘lovning Ta’lim tashkilotining hisob raqamiga kеlib tushganligi tasdiqlanishi bilan Talabaning o‘qishga qabul qilinganligi to‘g‘risida Ta’lim tashkiloti tomonidan buyruq chiqariladi.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Ta’lim shakli:</td>
                    <td colspan="2"><b><?= $eduDirection->eduForm->name_uz ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">O‘qish muddati:</td>
                    <td colspan="2"><b><?= $eduDirection->duration . ' yil' ?></b></td>
                </tr>
                <tr>
                    <td colspan="2">O‘quv bosqichi:</td>
                    <?php if ($student->edu_type_id == 2) : ?>
                        <td colspan="2"><b><?= Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?></b></td>
                    <?php else: ?>
                        <td colspan="2"><b>1 kurs</b></td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <td colspan="2"><b>Stipendiyasiz.</b></td>
                    <td colspan="2"></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            Talaba joriy o‘quv semestr davri boshlangach boshqa oliy ta’lim muassasasiga o‘qishni ko‘chirishga Universitet ma’muriyati tomonidan ruxsat berilmaydi, bundan mustasno (faqatgina talabaning doimiy yashash joyi o‘zgarganda boshqa oliy ta’lim muassasasiga o‘qishni ko‘chirishga Universitet ma’muriyati tomonidan ruxsat etiladi).
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>2. TOMONLARNING HUQUQLARI VA MAJBURIYATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.1. Ta’lim muassasasining majburiyatlari:</b>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.1.	O‘qitish uchun O‘zbekiston Respublikasining amaldagi “Ta’lim to‘g‘risida”gi Qonuniga muvofiq Ta’lim muassasasi Ustavi va boshqa ichki hujjatlarida nazarda tutilgan zarur shart-sharoitlarni yaratadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.2.	Talabalarning qonun hujjatlarida belgilangan huquqlarining bajarilishini ta’minlaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.3.	Talabani tasdiqlangan o‘quv reja va dasturlarga muvofiq darajada o‘qitadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.1.4.	Talaba bakalavriat yo‘nalishini muvaffaqiyatli tamomlaganda, belgilangan tartibda O‘zbekiston Respublikasi Oliy ta’lim, fan va innovatsiyalar vazirligi tomonidan davlat namunasidagi diplom beradi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <?php if ($student->edu_type_id == 2) : ?>
                <?php $course = Course::findOne(['id' => ($student->course_id + 1)])->name_uz ?>
            <?php else: ?>
        <td colspan="2"><b><?php $course = '1 - kurs' ?></b></td>
        <?php endif; ?>
        2.1.5.	Abituriyent o‘quv yilining birinchi yarmi uchun 50 % yoki toliq to‘lovni amalga oshirganidan so‘ng uni <?= $course ?> talabalar safiga qabul qilinadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.2.Ta’lim muassasasining huquqlari:</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.1.	Talabadan shartnomaviy majburiyatlari bajarilishini, shu jumladan ta’lim muassasasining ichki hujjatlarida belgilangan qoidalarga rioya qilishni, o‘quv mashg‘ulotlarida muntazam qatnashishni, shartnoma bo‘yicha to‘lovlarni o‘z vaqtida amalga oshirishni talab qilish.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.2.	Talaba ta’lim muassasasining ichki hujjatlarida belgilangan qoidalarga rioya qilmagan, bir semestr davomida darslarni uzrli sabablarsiz, 74 soatdan ortiq qoldirgan yoki talaba o‘qitish uchun belgilangan miqdordagi to‘lovni o‘z vaqtida amalga oshirmagan bo‘lsa ta’lim muassasasi talabaga nisbatan belgilangan tartibda talabalar safidan chetlashtirish, tegishli kursda qoldirish yoki boshqa choralarni qo‘llash.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.3.	Talaba quyidagi sabablarga ko‘ra talabalar safidan chetlashtirilganda oliy ta’lim muassasasi tomonidan o‘quv yili uchun oldindan amalga oshirilgan to‘lovning qolgan qismini shartnomaning tegishli tarafi talabaning o‘qigan muddati uchun haqiqatda sarflangan xarajatlar chegirilgan xolda tegishli o‘quv yili yoki semestr uchun oldindan amalga oshirilgan to‘lovning qolgan qismi shartnomaning tegishli tarafi yozma murojaatiga ko‘ra qaytarib beriladi: <br>
            O‘z xoxishiga binoan o‘qishni boshqa ta’lim muassasasiga ko‘chirilishi munosabati bilan, salomatligi tufayli (tibbiy komissiya ma’lumotnomasi asosida), talaba sud tomonidan ozodlikdan maxrum etilganligi munosabati bilan, vafot etganligi sababli. Bunda to‘lov shartnoma asosida talabalikka qabul qilinganlar talabalar safidan chetlashtirilganda qaytariladigan to‘lov miqdori birinchi kurslar talabalikga qabul qilish (ikkinchi va undan yuqori kurslar kursdan kursga ko‘chirish) to‘g‘risidagi rektor buyrug‘i chiqqan kundan boshlab o‘quv yilining qolgan qismiga yillik to‘lov Shartnoma miqdorini teng taqsimlanish orqali aniqlanadi va shartnomaning tegishli tarafi talabaning (vafot etgan holatda tegishli tartibda vafot etganligi haqida ma’lumotnoma nusxasi taqdim etilganda) yozma murojaatiga ko‘ra  qaytarib beriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.4.	Istisno tariqasida shartnoma bo‘yicha to‘lov muddatlarini uzaytirish (Ta’lim muassasasining buyrug‘i orqali).
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.2.5.	Talaba shartnomaning ko‘rsatilgan to‘lov summa miqdorini o‘z vaqtida amalga oshirmaganida, talabani barcha o‘quv va imtihon jarayonlariga qo‘ymaslik.
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.3.	Talabaning huquqlari:</b>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.1.	Ta’lim muassasasidan shartnomaviy majburiyatlari bajarilishini talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.2.	Ta’lim muassasasida tasdiqlangan o‘quv reja va dasturlarga muvofiq darajada ta’lim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.3.	Ta’lim muassasasining ta’lim jarayonlarini yaxshilashga doir takliflar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.3.4.	O‘qish uchun to‘lov turini tanlash.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            <b>2.4.	Talabaning majburiyatlari:</b>
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.1.	Talaba mazkur bakalavriyat darajasida ta’lim olish uchun qonunchilik asosida talab etiladigan bundan oldingi ta’lim bosqichini tamomlaganligini tasdiqlovchi hujjatlarning mavjudligi va haqqoniyligi uchun to‘liq javobgarlikni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.2.	Talaba joriy o‘quv yili uchun belgilangan o‘qitish qiymatini shartnomaning 3-bobida ko‘rsatilgan tartib va miqdorda o‘z vaqtida to‘laydi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.3.	Universitet Ustavi va boshqa ichki-tartib qoida talablariga qat’iy rioya qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.4.	Talaba o‘quv mashg‘ulotlarida muntazam qatnashadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2.4.5.	Talaba ta’lim muassasasida belgilangan tartib va qoidaga asosan ta’lim oladi hamda ushbu jarayonda bilim darajasini oshirib boradi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>3. TO’LOV MIQDORLARI VA MUDDATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            2025-2026-o‘quv yilida ta’lim olish uchun talaba/buyurtmachi tomonidan to‘lanishi lozim bo‘lgan to‘lov shartnoma summasi <?= number_format((int)$contract->contract_price, 0, '', ' ') . ' (' . Contract::numUzStr($contract->contract_price) . ')' ?> so‘mni tashkil etadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.1.1. Talabalikka tavsiya etilgan abituriyentlar: <br>
            - belgilangan to‘lov-shartnoma miqdorining kamida 25% ni 25-sentabr 2025-yil (yoki Davlat qabul komissiyasi belgilangan muddatgacha);
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.1.2. Ikkinchi va undan keyingi bosqich talabalari: <br>
            - belgilangan to‘lov-shartnoma miqdorining kamida 25% ni 1-oktabr 2025-yil; <br>
            - belgilangan to‘lov-shartnoma miqdorining kamida 50% ni 1-dekabr 2025-yil; <br>
            - belgilangan to‘lov-shartnoma miqdorining kamida 75% ni 1-fevral 2026-yil; <br>
            - belgilangan to‘lov-shartnoma miqdorining kamida 100% ni 1-mart 2026-yilga qadar to‘liq to‘lashi shart.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            3.2. Talaba tomonidan shartnoma bo‘yicha o‘qish to‘lov shartnoma summasini to‘lashda to‘lov topshiriqnomasida <b>Talabaning familiyasi, ismi, sharifi hamda o‘qiyotgan kursi, yo‘nalishi, shartnoma raqami to‘liq ko‘rsatishi shart.</b>
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>4. SHARTNOMANI BEKOR QILISH</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            <b>Shartnoma quyidagi hollarda bekor qilinadi:</b>
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            4.1. Tomonlarning o‘zaro roziligi bilan.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.2. Ta’lim muasasasining tashabbusiga ko‘ra Ustavi va boshqa ichki hujjatlariga muvofiq talaba talabalar safidan chiqarilganda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.3. Shartnoma to’lov miqdori belgilangan muddat ichida to’lanmasa (bunda, ta’lim muassasasi shartnomani bir tomonlama bekor qiladi, talaba talabalar safidan chiqariladi).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.4. Talabaning tashabbusiga ko‘ra (yozma murojaatga asosan).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.5. Shartnomaning 2.2.3-bandida ko’rsatilgan hollarda (Ta’lim muassasasi tomonidan shartnomaning bir tomonlama bekor qilinishi va talabalar safidan chiqarilishi haqida talabaga yozma xabarnoma yuborish orqali).
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.6. Qonunchilikda ko’rsatilgan boshqa hollarda.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            4.7. Shartnoma bekor qilingan barcha holatlarda talaba amalga oshirgan to’lov miqdoridan ta’lim muasasasi ko’rsatgan ta’lim xizmatlari qiymati va davriga monand mablag’lar ta’lim muasasasi foydasiga ushlab qolinadi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>5. TOMONLARNING JAVOBGARLIGI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.1. Talaba tomonidan Universitet mol-mulkkiga, ta'lim vositalariga va hokazolarga moddiy zarar yetkazilganda talaba Universitet oldida to'liq miqdorida teng moddiy javobgar bo'ladi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            5.2. Mazkur shartnomada ko'zda tutilmagan boshqa javobgarlik
            choralari O'zbekiston Respublikasining amaldagi qonunchiligiga muvofiq belgilanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>6. UMUMIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            6.1. Talaba har bir kursni muvaffaqiyatli o’tganda va talaba tomonidan shartnoma shartlari buzilmasa, Universitet o'qishning muvaffaqiyatli yakunlanishidan oldin keyingi kursda o'qishni belgilangan tartibda davom ettirishni ta'minlaydi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.2. Ushbu shartnoma amal qiladigan davrda talabaning akademik qarzdorlik (fanlardan), ichki tartib-qoidalar yoki o'quv jarayoni buzilganligi sababli talabalik safidan chetlashtirilgan taqdirda, ilgari to'langan summa qaytarilmaydi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.3. Ushbu Shartnoma rus va o'zbek tillarida, <b>uchta haqiqiy nusxada (ikki nusxada, Agar to'lovchining o'zi talabaning o'zi bo'lsa),</b> teng huquqli kuchga ega bo'lgan har bir tomon uchun bir nusxada tuziladi. Shartnoma matnida (tarjimasida) qarama-qarshiliklar yoki noaniqliklar aniqlangan taqdirda, talqinning asosiy manbasi o’zbek tilidagi matn hisoblanadi;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.4. Shartnoma O'zbekiston Respublikasining amaldagi qonunchiligiga muvofiq tomonlaming yozma kelishuvi bilan o'zgartirilishi, bekor qilinishi mumkin;
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            6.5. Ushbu shartnomadan kelib chiqadigan har qanday nizolar yoki kelishmovchiliklami tomonlar muzokaralar yo'li bilan hal qilishga intiladilar. Muzokaralar yo'li bilan tartibga solinmagan shartnoma bo'yicha nizolar O'zbekiston Respublikasining amaldagi qonunchiligida belgilangan sud tartibida hal etiladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>7. FORS-MAJOR HOLATLAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            7.1. Ushbu shartnomaga asosan majburiyatlarning bajarilmasligi holatlari yengib bo'lmaydigan kuchlar (fors-major) ta’siri natijasida vujudga kelganda, tomonlar o’z majburiyatlarini bajarmaslikdan qisman yoki to’liq ozod bo’ladilar.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.2. Yengib bo’lmaydigan kuchlar (fors-major) holatlariga tomonlarning irodasi va faoliyatiga bog’liq bo’lmagan tabiat hodisalari (zilzila, ko‘chki, bo‘ron, qurg'oqchilik va boshqalar) yoki ijtimoiy-iqtisodiy holatlar (urush holati, qamal, davlat manfaatlarini ko‘zlab) sababli yuzaga kelgan sharoitlarda tomonlarga qabul qilingan majburiyatlarni bajarish imkonini bermaydigan favqulodda, oldini olib bo'lmaydigan va kutilmagan holatlar kiradi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.3. Shartnoma tomonlaridan qaysi biri uchun majburiyatlarni yengib bo’lmaydigan kuchlar (fors- major) holatlari sababli bajarmaslik ma’lum bo‘lsa, darhol ikkinchi tomonga bu xaqda 10 (o’n) kun ichida ushbu holatlar harakati sababini dalillar bilan taqdim etishi lozim. Masofaviy ta’lim bundan mustasno holat hisoblanadi. Universitet masofaviy uslubda ta’lim berish imkonini yo’lga qo‘yish huquqiga ega va talaba bu uslubda ta’lim olishga roziligini bildiradi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            7.4. Shartnomaga asosan majburiyatlarni ijro qilish muddati ushbu yengib bo’lmaydigan kuchlar (fors- major) va holatlar davom etish muddatiga qadar uzaytiriladi. Agar yengib bo’lmaydigan kuchlar (fors- major) ta’siri 90 (to’qson) kundan ortiqroq davom etsa, tomonlar kelishuviga binoan shartnoma bekor qilinishi mumkin. Masofaviy ta’lim bundan mustasno holat hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            7.5. Fors-major holatlari ta’limni masofaviy amalga oshirishga imkon bersa, tomonlar o’z majburiyatlarini masofaviy ta’limga asosan amalga oshiradilar. Bunda talaba masofaviy ta’limda qatnashish uchun barcha talab qilingan texnik va boshqa jihatdan sharoyitga ega bo'lishi kerak.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>8. YAKUNIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.1. Shartnoma bevosita tomonlar tomonidan imzolangan kundan e’tiboran kuchga kiradi.
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.2. Tomonlar o‘rtasida vujudga keladigan nizolar o‘zaro muzokaralar olib borish hamda talabnoma yuborish orqali hal etiladi,
        </td>
    </tr>


    <tr>
        <td colspan="4" style="text-align: justify">
            8.3. Shartnoma summasi faqat har semestr boshida oshirilishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.4. Shartnoma bo‘yicha o’z majburiyatlarini bajarmagan tomon qonunda belgilangan tartibda javobgar bo’ladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.5. Barcha o’zaro xabar va ogohlantirishlar, arizalar va boshqa yozishmalar tomonlar tarafidan yozma ravishda yuboriladi. Xabar va ogohlantirishlar, arizalar va boshqa yozishmalar pochta orqali, buyurtma xat, elektron xat, telegraf, faks orqali yoki tomonlarning yuridik manziliga (oluvchining qabul qilib olganligini tasdiqlovchi imzosi bilan) qo’ldan qo’lga yetkazish yo’li bilan yuborilganidagina rasman yetkazilgan xisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.6. Shartnomaga asosan majburiyatlarni ijro qilish muddati bir o’quv yilini tashkil qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.7. Talaba tomonidan taqdim qilingan barcha hujjatlar, hujjatlardagi ma’lumotlarning ishonchliligi, to’g’riligi, haqqoniyligi bo‘yicha javobgarlik talaba zimmasiga yuklanadi. Talaba tomonidan hujjatlarni qalbakilashtirish huquqbuzarligi ta’lim muassasasi tomonidan aniqlanganda darhol huquqni muhofaza qiluvchi organlarga xabar beriladi, talabalar safidan chetlashtiriladi hamda talaba to’lagan  to’lov-shartnoma summasi qaytarilmaydi va Universitet xisob-raqamida qoladi. Bunday holatlar uchun jinoyiy javobgarlik mavjudligini ma’lum qilamiz. Agar bunday holat Talaba ta’lim muassasasidan bakalavr diplomini u taqdim qilgan qalbaki hujjat(-lar) yoki hujjatlardagi qalbakilashtirilgan ma’lumotlar asosida olgan bo’lsa, unda diplom haqiqiy emas deb topiladi va ushbu holat yuzasidan huquqni muhofaza qiluvchi organlarga xabar beriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: justify">
            8.8. Ushbu shartnomaning 8.7. bandida ko’rsatilgan huquqbuzarlik sodir etilgan bo’lsa talaba tomonidan amalga oshirilgan shartnoma to’lovi qaytarilmaydi.
            Ushbu shartnoma O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq O’zbekiston Respublikasi Fuqarolik kodeksining kelishuvlar to‘g‘risidagi qoidalardan kelib chiqqan holda tuzilgan.
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
                            <b>9. TOMONLARNING YURIDIK MANZILLARI</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <b>To‘lov oluvchi:</b>
                        </td>
                        <td colspan="2">
                            <b>Talaba</b>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b><?= $filial->name_uz ?></b> <br>
                            <b>Manzili:</b> <?= $filial->address_uz ?> <br>
                            <b>H/R:</b> <?= $cons->hr ?> <br>
                            <b>Bank:</b> <?= $cons->bank_name_uz ?> <br>
                            <b>Bank kodi (MFO):</b> <?= $cons->mfo ?> <br>
                            <b>STIR (INN):</b> <?= $cons->inn ?> <br>
                            <b>Tel:</b> <?= $cons->tel1 ?> <br>
                            <b>Direktor:</b>  <?= $filial->rector_uz ?> <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>Talabaning F.I.O.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $student->passport_serial . ' ' . $student->passport_number ?> <br>
                            <b>JShShIR raqami:</b> <?= $student->passport_pin ?> <br>
                            <b>Tеlefon raqami: </b> <?= $student->user->username ?> <br>
                            <b>Talaba imzosi: </b> ______________ <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top">
                            <b>TO’LOVCHI</b> <br>
                            <b>Nomi:</b> ___________________________________________________________ <br>
                            <b>Manzili:</b> ___________________________________________________________ <br>
                            <b>Bank:</b> ___________________________________________________________ <br>
                            <b>H/R:</b> ___________________________________________________________ <br>
                            <b>Bank kodi (MFO):</b> ___________________________________________________________ <br>
                            <b>STIR (INN):</b> ___________________________________________________________ <br>
                            <b>Telefon:</b> ___________________________________________________________ <br>
                            <b>Direktor:</b>  ___________________________________________________________ <br>
                            <b>Imzo:</b>  ___________________________________________________________ <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>


                    <tr>
                        <td colspan="2" style="vertical-align: top;">
                            <img src="<?= $img ?>" width="120px">
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <img src="<?= $limg ?>" width="120px"> <br>
                            <b>Litsenziya berilgan sana va raqami</b> <br>
                            30.12.2022 <b>№ 222840</b>
                        </td>
                    </tr>

                </table>
            </div>
        </td>
    </tr>

</table>