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
use common\models\Constalting;

/** @var \common\models\StudentGroup $student */
/** @var Direction $direction */
/** @var User $user */

$direction = $student->drift;
$form = $student->driftForm->eduForm->name_uz;
$course = $student->course->name_uz;

$std = $student->std;
$full_name = $std->last_name.' '.$std->first_name.' '.$std->middle_name;
$joy = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

$seria = $std->passport_serial;
$number = $std->passport_number;
$pin = $std->passport_pin;
$phone = $std->student_phone;

$hr = '2020 8000 0055 1905 4002';
$price = $student->price;
$code = 'TA2UZ';
$date = date("Y-m-d H:i:s");
$con2 = $student->contract_second;
if ($student->down_time == null) {
    $student->down_time = time();
    $student->save(false);
}

$lqr = (new QrCode('https://license.gov.uz/registry/da127cfb-12a8-4dd6-b3f8-7516c1e9dd82'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();
?>


<table width="100%" style="font-family: 'Times New Roman'; font-size: 12px; border-collapse: collapse;">

    <tr>
        <td colspan="4" style="text-align: center">
            <b>
                2024-2025 o‘quv yilida to‘lov asosida ta’lim xizmatlarini ko‘rsatish bo‘yicha <br>
                <?= $code ?> &nbsp; | &nbsp; <?= $student->id ?> – sonli SHARTNOMA
            </b>
        </td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="2"><?= $date ?></td>
        <td colspan="2" style="text-align: right">Toshkent shahri</td>
    </tr>

    <tr><td>&nbsp;</td></tr>

    <tr>
        <td colspan="4">
            <b>“GLOBAL SOFTLINE”</b> oliy ta’lim tashkiloti (keyingi o‘rinlarda “Universitet”) nomidan Ustav asosida ish yurituvchi direktor <b>SHARIPOV MUZAFFAR TOLIBJONOVICH</b> birinchi tomondan,
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b><?= $full_name ?></b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            (keyingi o‘rinlarda “Talaba”) ikkinchi tomondan, keyingi o‘rinlarda birgalikda “Tomonlar” deb ataluvchilar o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>I. SHARTNOMA PREDMETI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 1.1. Mazkur shartnomaga muvofiq Universitet Talabani quyida ko‘rsatilgan ta’lim yo‘nalishi va ta’lim shakli bo‘yicha oliy ta’limning davlat ta’lim standartlari asosida tasdiqlangan o‘quv reja va o‘quv dasturlari asosida o‘qitish majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 5px;">
            <table width="100%">
                <tr>
                    <td>Ta’lim yo‘nalishi:</td>
                    <td><b><?= $direction->code.' '.$direction->name_uz ?></b></td>
                </tr>
                <tr>
                    <td>Ta’lim shakli:</td>
                    <td><b><?= $form ?></b></td>
                </tr>
                <tr>
                    <td>O‘quv kursi:</td>
                    <td><b><?= $course ?></b></td>
                </tr>
                <tr>
                    <td>Shartnomaning umumiy narxi (bir o‘quv yili uchun):</td>
                    <td><b><?= number_format((int)$price, 0, '', ' ') .' so‘m'?></b></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> Talaba esa Universitet tomonidan belgilangan tartib qoidalarga rioya qilgan holda ta’lim olish va ta’lim olganlik uchun haq to‘lash majburiyatini oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 1.2. Universitetda shartnoma asosida o‘qitishning to‘lovi (keyingi o‘rinlarda – shartnoma to‘lovi) miqdori ta’lim yo‘nalishi, ta’lim shakli kunduzgi, kechki va sirtqi, o‘qishni ko‘chirish bilan bog‘liq fanlarning farqini o‘qitish hamda to‘plagan ballidan kelib chiqib, har bir o‘quv yili uchun alohida belgilanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>II. TOMONLARNING HUQUQ VA MAJBURIYATLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1. Universitetning huquqlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.1. Talaba tomonidan o‘z majburiyatlarini bajarishini doimiy nazorat qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.2. Talabadan shartnoma majburiyatlarining bajarilishini, ichki tartib qoidalariga rioya etilishini, shartnoma bo‘yicha to‘lovlarni o‘z vaqtida to‘lashni talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.3. Shartnoma to‘lovini amalga oshirish tartibini, ichki tartib va ta’lim dasturi qoidalarini buzganligi, semestr davomida uzrli sababsiz Universitetda belgilangan akademik soat miqdoridan ortiq dars qoldirgani uchun talabani talabalar safidan ogohlantirmasdan chiqarish (chetlashtirish) yoki tegishli kursda qoldirish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.4. Talabadan o‘rnatilgan tartibda tegishli hujjatlarni talab qilish va ular taqdim etilmagan holda shartnoma to‘lovi amalga oshirilganidan qat’i nazar, Talabani o‘qishga qabul qilish yoki keyingi kursga o‘tkazish to‘g‘risidagi Universitet rektorining buyrug‘iga kiritmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.5. Universitetning ichki hujjatlarida belgilangan hollarda Talabani imtihonga kiritmaslik.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.6. Talaba quyidagi qilmishlardan birini sodir etgan taqdirda Universitet Buyurtmachini xabardor qilgan holda shartnomani bir tomonlama bekor qilish huquqiga ega:
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> a) ijtimoiy tarmoqlarda Universitet faoliyati to‘g‘risida ataylab yolg‘on ma’lumotlar tarqatganda, shuningdek, professor-o‘qituvchilar va ma’muriy xodimlarga hurmatsizlik bilan munosabatda bo‘lganda;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> b) hujjatlarni qalbakilashtirish, shu jumladan, imtihon savollariga javoblarni hamda shu kabi boshqa materiallarni imtihonga olib kirish, imtihon paytida ulardan foydalanish yoki boshqa talabalarga tarqatish;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> c) Universitet tovar belgisi/logotipidan Universitetning yozma ruxsatisiz (turli xil buyumlar, kiyim-kechaklar tayyorlash uchun va hokazo) foydalanish;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> d) Universitet talabalari, o‘qituvchilari va xodimlariga nisbatan jinsiy, irqiy, diniy, millatlararo xarakterdagi kamsituvchi harakatlar sodir etish, odob-ahloq qoidalarini buzish, jismoniy va/yoki ruhiy bosim o‘tkazish;
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <?= $joy ?> e) Universitet ichki tartib qoidalariga, ichki hujjatlari talablariga rioya qilish talablarini muntazam (2 va undan ortiq marta) qasddan buzish, o‘ziga oshkor bo‘lgan Universitetga yoki boshqa talabalarga tegishli maxfiy ma’lumotni tarqatish;
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <?= $joy ?> f) o‘qish jarayonida Universitetning yozma roziligisiz xorijga ketish;
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <?= $joy ?> g) bir semestr davomida 36 akademik soat dars qoldirganligi yoki ma’lumoti haqidagi hujjatning asl nusxasini Universitetga o‘z vaqtida taqdim etmaganligi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> h) boshqa oliy ta’lim tashkilotidan, jumladan xorijiy oliy ta’lim tashkilotidan o‘qishini ko‘chirish uchun murojaat qilib Universitetga o‘qishga qabul qilinishida soxta hujjatlardan foydalanganligi yoki o‘qishni ko‘chirish bilan bog‘liq hujjatlarida yolg‘on va haqqoniy bo‘lmagan ma’lumotlar mavjudligi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> i) Universitetning ichki hujjatlarida nazarda tutilgan boshqa hollarda;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> j) O‘zbekiston Respublikasining amaldagi qonun hujjatlarida nazarda tutilgan boshqa normalarni buzganlikda aybdor deb topilganida.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.7. Ko‘rsatilayotgan ta’lim xizmatlarining miqdori va sifatini pasaytirmagan holda tasdiqlangan dars jadvaliga o‘zgartirishlar kiritish, O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va fors-major holatlariga qarab, ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini kamaytirmasdan o‘qitish rejimini masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <?= $joy ?> 2.1.8. Mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdorini o‘zgartirish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.1.9. Shartnoma to‘lovi muddatlarini uzaytirish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.1.10. O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va fors-major holatlariga qarab, ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini kamaytirmasdan o‘qitish rejimini masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.2. Universitetning majburiyatlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.1. Talabani davlat ta’lim standartlari, ta’lim sohasidagi qonunchilik talablari, o‘quv dasturlari hamda ushbu shartnoma shartlariga muvofiq o‘qitish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.2. Talabaning qonunchilikda belgilangan huquqlarini ta’minlash.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.3. O‘quv jarayonini yuqori malakali professor-o‘qituvchilarni jalb qilgan holda tashkil etish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.4. O‘quv yili davomida elektron hisob fakturalar yuborish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.2.5. Universitet quyidagilarni o‘z zimmasiga olmaydi:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.5.1. Talabaning stipendiya va moddiy jihatdan ta’minoti;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.5.2. Talabaning hayoti, sog‘ligi va uning shaxsiy mulki uchun javobgarlik;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.2.5.3. Talaba va Buyurtmachining o‘zaro majburiyatlari uchun javobgarlik.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3. Buyurtmachining huquqlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3.1. Universitet va Talabadan shartnoma majburiyatlari bajarilishini talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3.2. Talabaning Universitet o‘quv reja va dasturlariga muvofiq ta’lim olishini nazorat qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3.3. Talabaning fanlarni o‘zlashtirish darajasiga oid ma’lumotlarni Universitetdan belgilangan tartibda so‘rash va olish.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3.4. Universitetdan Talabaga sifatli ta’lim berilishini talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.3.5. Universitetning ta’lim jarayonlarini yaxshilashga doir takliflar berish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.4. Buyurtmachining majburiyatlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.4.1. Shartnoma to‘lovini mazkur shartnomada belgilangan muddatlarda to‘lash.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.4.2. Universitet Ustavi va ichki tartib-qoidalariga qat’iy rioya qilishni hamda o‘quv reja va dasturlarga muvofiq ta’lim olishni Talabadan talab qilish;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.4.3. Mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, mos ravishda shartnoma to‘lovi miqdoriga mutanosib ravishda to‘lovni o‘z vaqtida amalga oshirish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5. Talabaning huquqlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.1. Universitetdan shartnoma bo‘yicha o‘z majburiyatlarini bajarishni talab qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.2. Universitet tomonidan tasdiqlangan o‘quv reja va dasturlarga muvofiq davlat standarti talablari darajasida ta’lim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.3. Universitetning moddiy-texnik bazasidan, jumladan laboratoriya jihozlari, asbob-uskunalar, axborot-resurs markazi va Wi-Fi hududidan foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.5.4. Universitetning o‘quv jarayonlarini takomillashtirish bo‘yicha takliflar kiritish.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.5. Shartnoma to‘lovini shartnoma shartlariga muvofiq to‘lash.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.6. Bilim va ko‘nikmalarini rivojlantirish, takomillashtirish, Universitet taqdim etayotgan barcha imkoniyatlaridan foydalanish, shuningdek, dars va o‘qishdan bo‘sh vaqtlarida jamiyat hayotida faol ishtirok etish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.5.7. Quyidagi hollarda Universitet ruxsati bilan 1 (bir) yilgacha akademik ta’til olish:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> a) salomatlik holati davlat sog‘liqni saqlash tizimiga kiruvchi tibbiyot muassasalarining davolovchi shifokorlari tomonidan hujjatlar bilan tasdiqlangan sezilarli darajada yomonlashganda;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> b) homiladorlik va tug‘ish, shuningdek bola ikki yoshga to‘lgunga qadar parvarishlash bo‘yicha ta’tilga bog‘liq hollarda;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  v) yaqin qarindoshining vafoti munosabati bilan bu holda akademik ta’til berish Universitet rahbariyati tomonidan har bir holat alohida ko‘rib chiqiladi va qaror qabul qilinadi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  g) harbiy xizmatni o‘tash uchun safarbar etilishi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> d) boshqa hollarda Universitet rahbariyatining qaroriga ko‘ra.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.5.8. O‘qishning barcha bosqichlarini muvaffaqiyatli tamomlagandan so‘ng O‘zbekiston Respublikasida oliy ma’lumot to‘g‘risidagi hujjat bo‘lgan Universitetning oliy ma’lumot to‘g‘risidagi diplomini olish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.6. Talabaning majburiyatlari:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.1. Ushbu shartnoma Universitet va Talaba o‘rtasida (Buyurtmachisiz) tuzilgan taqdirda shartnoma to‘lovi bo‘yicha barcha majburiyatlarni o‘z zimmasiga olish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.2. O‘zbekiston Respublikasi qonunchiligida, shuningdek Universitetning o‘quv jarayoni va faoliyatini tartibga soluvchi normativ-huquqiy hujjatlarida belgilangan oliy ta’lim muassasalari talabalariga qo‘yiladigan talablarga muvofiq ta’lim olish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.6.3. Univesitet ichki hujjatlariga muvofiq talab etiladigan barcha hujjatni taqdim etish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.4. Universitet ichki tartib qoidalari, Universitetga kirish-chiqish, shaxsiy va yong‘in xavfsizligi qoidalari talablariga qat’iy rioya qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.6.5. O‘zbekiston Respublikasi qonunchiligiga zid harakatlar va qilmishlarni sodir etmaslik.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.6. Universitetning texnik va boshqa o‘quv qurollari, shuningdek asbob-uskuna/jihozlari va boshqa mol-mulkidan oqilona foydalanish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.7. Pasport ma’lumotlari, yashash manzili va telefon raqami o‘zgarganligi to‘g‘risida ular o‘zgartirilgan kundan e’tiboran besh kun ichida Universitetni xabardor qilish.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  2.6.8. O‘zbekiston Respublikasi hududini Universitetning yozma ruxsati asosida tark etish.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.9. O‘zlashtirish darajasi, fanlar/darslar bo‘yicha davomat foizi, Universitet oldidagi moliyaviy majburiyatlarning bajarilishi ustidan doimiy nazorat olib borish.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 2.6.10. Talaba ushbu shartnomada ko‘zda tutilmagan qo‘shimcha xizmatlarni olganida xizmat haqini to‘lash. Universitetning ichki hujjatlari talablarini buzganda jarima nazarda tutilgan hollarda mazkur jarima(lar)ni o‘z vaqtida to‘lash.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center">
            <b>III. TA’LIM TO‘LOVINING MIQDORI, TARTIBI VA TO‘LOV SHARTLARI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.1. 2024-2025 o‘quv yili uchun shartnoma to‘lovi <?= number_format((int)$price, 0, '', ' ') .' so‘m'?> so‘mni tashkil etadi
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.2. Universitet xizmatlarning narxini o‘zgartirish huquqini o‘zida saqlab qoladi. Bunday holatda qo‘shimcha
            kelishuv tuziladi va Tomonlar yangi qiymatni hisobga olgan holda o‘zaro hisob-kitoblarni amalga oshirish majburiyatini
            oladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3. O‘qish uchun to‘lov quyidagi tartibda amalga oshiriladi:
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.1. 2024-yil 15-sentabrga qadar – 25 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.2. 2024-yil 15-dekabrga qadar – 50 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.3.3. 2025-yil 15-fevralga qadar – 75 foizidan kam bo‘lmagan miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  3.3.4. 2025-yil 15-aprelga qadar – 3.1-bandda nazarda tutilgan ta’lim to‘lovining amalga oshirilmagan qismi
            miqdorda.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 3.4. Buyurtmachi tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi, familiyasi, ismi
            va sharifi hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. To‘lov kuni Universitetning bank hisob
            raqamiga mablag‘ kelib tushgan kun hisoblanadi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.5. Talaba tegishli fanlar bo‘yicha akademik qarzdorlikni qayta topshirish sharti bilan keyingi kurs (semestr)ga o‘tkazilgan taqdirda, keyingi semestr uchun shartnoma to‘lovi Talaba tomonidan akademik qarzdorlik belgilangan muddatda topshirilishiga qadar amalga oshiriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.6. Talaba ushbu Shartnomaning amal qilish muddati davomida o‘quv darslarini o‘zlashtira olmagani, ichki tartib qoidalarini, odob-axloq qoidalarini yoki o‘quv jarayonini buzgani va unga nisbatan o‘qishini to‘xtatish yoki o‘qishdan chetlatish chorasi ko‘rilganligi, uni o‘qish uchun haq to‘lash bo‘yicha moliyaviy majburiyatlardan ozod etmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 3.7. Shartnoma Universitet tashabbusi bilan Talaba uning hatti-harakatlari (harakatsizligi) sababli talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>IV. SHARTNOMAGA O‘ZGARTIRISH KIRITISH VA UNI BEKOR QILISH TARTIBI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.1. Ushbu Shartnoma shartlari Tomonlar kelishuvi bilan yoki O‘zbekiston Respublikasi qonunchiligiga muvofiq o‘zgartirilishi mumkin. Shartnomaga kiritilgan barcha o‘zgartirish va qo‘shimchalar, agar ular yozma ravishda tuzilgan va Tomonlar yoki ularning vakolatli vakillari tomonidan imzolangan bo‘lsa, haqiqiy hisoblanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2. Ushbu Shartnoma quyidagi hollarda bekor qilinishi mumkin:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.1. Tomonlarning kelishuviga binoan;
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.2. Universitetning tashabbusiga ko‘ra bir tomonlama (2.1.6-bandda nazarda tutilgan hollarda);
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.3. sudning qonuniy kuchga kirgan qarori asosida;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2.4. shartnoma muddati tugashi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 4.2.5. Talaba o‘qishni muvaffaqiyatli tamomlaganligi munosabati bilan;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  4.2.6. Universitet faoliyati tugatilgan taqdirda.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 4.3. Shartnomani Universitetning tashabbusiga ko‘ra bir tomonlama tartibda bekor qilinganida Buyurtmachining yuridik yoki elektron pochta manziliga tegishli xabar yuboriladi va shu bilan Buyurtmachi xabardor qilingan hisoblanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>V. FORS-MAJOR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 5.1. Tomonlardan biri tarafidan shartnomani to‘liq yoki qisman bajarishni imkonsiz qiladigan holatlar, xususan, yong‘in, tabiiy ofat, urush, har qanday harbiy harakatlar, mavjud huquqiy hujjatlarni almashtirish va boshqa mumkin bo‘lgan tomonlarga bog‘liq bo‘lmagan fors-major holatlari shartnoma bo‘yicha majburiyatlarni bajarish muddatlari ushbu holatlarning amal qilish muddatiga mos ravishda uzaytiriladi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 5.2. Ushbu shartnoma bo‘yicha o‘z majburiyatlarini bajarishga qodir bo‘lmagan tomon ikkinchi tomonni ushbu holatlarni bajarishiga to‘sqinlik qiladigan holatlar yuzaga kelganligi yoki bekor qilinganligi to‘g‘risida darhol xabardor qilishi shart.
            <br>
            <?= $joy ?> Xabarnoma shartnomada ko‘rsatilgan yuridik manzilga yuboriladi va jo‘natuvchi pochta bo‘limi tomonidan tasdiqlanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 5.3. Agar shartnoma tomonlariga bog‘liq bo‘lmagan tarzda sodir bo‘lgan har qanday hodisa, tabiiy ofatlar, urush yoki mamlakatdagi favqulodda holat, davlat hokimiyati organi tomonidan qabul qilingan qaror, uning ijrosi, uning yuzasidan amalga oshirilgan harakatlar (shular bilan cheklanmagan hodisalar) tufayli yuzaga kelgan bo‘lsa, bir tomon ikkinchi tomon oldida ushbu shartnomani bajarmaslik yoki bajarishni kechiktirish oqibatlari uchun javobgar bo‘lmaydi. Ijrosi shu tarzda to‘xtatilgan tomon bunday majburiyatlarni bajarish muddatini tegishli ravishda uzaytirish huquqiga ega bo‘ladi.
        </td>
    </tr>


    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VI. TOMONLARNING JAVOBGARLIGI</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.1. Talaba mol-mulk, jihozlar, o‘quv qurollari va hokazoga moddiy zarar yetkazgan taqdirda Universitet oldida to‘liq moddiy javobgarlikni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2. Ushbu shartnomaning 3.3-bandga muvofiq o‘qish uchun to‘lov kechiktirilgan taqdirda:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.1. Talabaning Universitetga kirishi cheklanadi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.2. Quyidagilar to‘xtatiladi:
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - Universitet tomonidan akademik xizmatlar ko‘rsatilishi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - Talabani imtihonlarga kiritilishi;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> - har qanday akademik ma’lumotnomalar/sertifikatlar berish;
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.2.3. Talaba 2.1.6-bandga muvofiq talabalar safidan chiqarilishi mumkin.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.3. Universitet shartnoma to‘lovi manbalari uchun javobgarlikni o‘z zimmasiga olmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.4. Universitet shartnoma to‘lovini amalga oshirishda yo‘l qo‘yilgan xatolar uchun javobgar bo‘lmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  6.5. Talabaning o‘qishdan chetlashtirilishi yoki talabalar safidan chiqarilishi Buyurtmachi va Talabani ushbu shartnoma bo‘yicha Talabaga ko‘rsatilgan ta’lim xizmatlari uchun haq to‘lash hamda boshqa majburiyatlardan ozod etmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 6.6. Tomonlarning ushbu Shartnomada nazarda tutilmagan javobgarlik choralari O‘zbekiston Respublikasining amaldagi qonunchiligi bilan belgilanadi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VII. QO‘SHIMCHA SHARTLAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.1. Universitetning Talabani o‘qishga qabul qilish buyrug‘i Talaba tomonidan barcha kerakli hujjatlarni taqdim etish va shartnomaning 3.3.1-bandiga muvofiq to‘lovni amalga oshirish sharti bilan chiqariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.2. Talabaga Universitet tomonidan stipendiya to‘lanmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  7.3. Mazkur Shartnomaning 1-bandida nazarda tutilgan majburiyatlar O‘zbekiston Respublikasining amaldagi qonunchiligi talablariga muvofiq, bevosita yoki onlayn tarzda taqdim etilishi mumkin. Akademik ta’lim xizmatlari onlayn tarzda taqdim etilgan taqdirda, Talaba texnik va telekommunikatsiya aloqalari holatining sifati uchun shaxsan javobgardir.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.4. Ushbu Shartnoma Tomonlar bir o‘quv yili uchun uning predmetida ko‘rsatilgan maqsadlar uchun o‘z majburiyatlarini to‘liq bajarguniga qadar, lekin 2025-yil 1-iyuldan kechikmagan muddatga qadar tuziladi. Shartnomaning amal qilish muddati tugaganligi qarzdor Tomonlarni o‘z zimmasidagi majburiyatlarini bajarishdan ozod qilmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.5. O‘qish davrida Talaba yoki boshqa shaxsga rasmiy hujjatlarning asl nusxalari, shu jumladan o‘rta yoki o‘rta maxsus ta’lim muassasasining bitiruv hujjatlari (attestat/diplom/sertifikat) berilmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.6. Universitet Talabani ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
        </td>
    </tr>


    <tr>
        <td colspan="4">
            <?= $joy ?> 7.7. Shartnoma to‘lovlari va ularni qaytarish bilan bog‘liq barcha bank xizmatlari Buyurtmachi yoki Talaba tomonidan to‘lanadi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.8. Universitet tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.9. Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular tomonlar tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.10. Tomonlar shartnomada Universitet faksimilesini tegishli imzo sifatida tan olishga kelishib oldilar.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 7.11. Ushbu shartnomadan kelib chiqadigan har qanday nizo yoki kelishmovchiliklarni tomonlar muzokaralar yo‘li bilan hal qilishga intiladi. Kelishuvga erishilmagan taqdirda, nizolar O‘zbekiston Respublikasi qonun hujjatlarida belgilangan tartibda Universitet joylashgan yerdagi sud tomonidan ko‘rib chiqiladi.
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4" style="text-align: center;">
            <b>VIII. YAKUNIY QOIDALAR</b>
        </td>
    </tr>

    <tr>
        <td>&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.1. Ushbu shartnoma Tomonlar tomonidan imzolangan paytdan boshlab kuchga kiradi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.2. Buyurtmachi va Talaba shartnoma shartlaridan norozi bo‘lgan taqdirda 2024-yil 30-noyabrdan kechiktirmay murojaat qilishi lozim, bunda mazkur sanaga qadar Universitet bilan shartnoma tuzmagan Talaba o‘qishga qabul qilinmaydi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  8.3. O‘zbekiston Respublikasi Prezidentining tegishli farmoniga muvofiq mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdori navbatdagi semestr boshidan oshiriladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?> 8.3. Mazkur shartnomani imzolanishi, o‘zgartirilishi, ijro etilishi, bekor qilinishi yuzasidan Tomonlar o‘rtasida yozishmalar shartnomada ko‘rsatilgan Tomonlarning rasmiy elektron pochta manzillari orqali amalga oshirilishi mumkin va Tomonlar bu tartibda yuborilgan xabarlarning yuridik kuchga ega ekanligini tan oladilar. Elektron pochta manzili o‘zgarganligi to‘g‘risida boshqa tomonni yozma ravishda xabardor qilmagan tomon bu bilan bog‘liq barcha xavflarni o‘z zimmasiga oladi.
        </td>
    </tr>

    <tr>
        <td colspan="4">
            <?= $joy ?>  8.4. Ushbu Shartnoma o‘zbek tilida, uch asl nusxada, teng yuridik kuchga ega, har bir tomon uchun bir nusxadan tuzildi.
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
                            <b>IX. TOMONLARNING YURIDIK MANZILLARI VA BANK REKVIZITLARI</b>
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
                            <b>Universitet</b>
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
                            <b>“GLOBAL SOFTLINE” oliy ta’lim tashkiloti</b> <br>
                            <b>Manzil:</b> Toshkent shahri, Yunusobod tumani, Posira MFY, Bog'ishamol ko'chasi, 220-uy <br>
                            <b>H/R:</b> <?= $hr ?> <br>
                            <b>Bank:</b> “KAPITALBANK” ATB Sirg’ali filiali <br>
                            <b>Bank kodi (MFO):</b> 01042  <br>
                            <b>IFUT (OKED):</b> 85420  <br>
                            <b>STIR (INN):</b> 309477784 <br>
                            <b>Tel:</b> +998 77 129-29-29 <br>
                            <b>Tel:</b> +998 55 500-02-50 <br>
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <b>F.I.Sh.:</b> <?= $full_name ?> <br>
                            <b>Pasport ma’lumotlari:</b> <?= $seria.' '.$number ?> <br>
                            <b>JShShIR:</b> <?= $pin ?> <br>
                            <b>Ro‘yxatdan o‘tgan tеlefon raqami: </b> <?= $phone ?> <br>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding-right: 20px">
                            M.O‘.
                        </td>
                        <td style="text-align: right">
                            <?= $full_name ?>
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            Direktor _________________________ SHARIPOV M.T.
                        </td>
                        <td colspan="2">
                            Imzo: _________________________________________
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                    </tr>

                    <tr>
                        <td colspan="2" style="vertical-align: top; padding-left: 40px">
                            <!--<img src="--><?php //= $img ?><!--" width="120px">-->
                        </td>
                        <td colspan="2" style="vertical-align: top">
                            <img src="<?= $limg ?>" width="120px"> <br>
                            <b>Litsenziya berilgan sana va raqami</b> <br>
                            19.10.2022 <b>№ 043951</b>
                        </td>
                    </tr>



                </table>
            </div>
        </td>
    </tr>

    <tr>
        <td colspan="4" style="border: 1px solid #000000; padding: 10px;">
            <b>SANA: </b> &nbsp; <?= $date ?> <br>
            <b>INVOYS RAQAMI: &nbsp; </b> <?= $con2 ?> <br>
            <b>KONTRAKT TO‘LOV MIQDORI: &nbsp; </b> <?= number_format((int)$price, 0, '', ' ') . ' (' . Contract::numUzStr($price) . ')' ?> <br>
            <table width="100%">
                <tr>
                    <td colspan="4">To‘lovni amalga oshirish usullari:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <?= $joy ?> Yuridik shaxslar va bank kassalari orqali. Bunda To‘lov maqsadida - Invoys raqam. JSHSHIR. Talabaning
                        FISH tartibida yozilgan bo‘lishi talab etiladi
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4" style="padding: 5px; border: 2px solid red;">
                        <table width="100%">
                            <tr>
                                <td><?= $con2 ?> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;</td>
                                <td><?= $pin ?> &nbsp;&nbsp;&nbsp; |</td>
                                <td class="2" style="text-align: center;"><?= $full_name ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td>
                        To‘lov maqsadi belgilangan tartibda to‘ldirilmagan taqdirda to‘lovni qabul qilishga doir muammolar kelib chiqishi
                        mumkin. Shu sababli to‘lov qilish jarayonida to‘lov maqsadini belgilangan tartibda ko‘rsatilishi shart!
                    </td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style="border-top: 2px double #000;">&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4"><b>To‘lovlarni amalgi oshirish uchun Universitetning bank hisob ma’lumotlari:</b></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="4">

                        <table width="100%" style="border-collapse: collapse; border: 1px solid;">
                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Qabul qiluvchi tashkilot nomi:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>“GLOBAL SOFTLINE” MCHJ</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Hisob raqami:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b><?= $hr ?></b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Bank kodi (MFO):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>01042</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>Bank nomi:</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>“KAPITALBANK” ATB Sirg’ali filiali</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>STIR (INN):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>309477784</b></td>
                            </tr>

                            <tr>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>IFUT (OKED):</b></td>
                                <td colspan="2" style="padding: 5px; border: 1px solid;"><b>85420</b></td>
                            </tr>
                        </table>

                    </td>
                </tr>

            </table>
        </td>
    </tr>

</table>