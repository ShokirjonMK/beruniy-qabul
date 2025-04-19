<?php
use Da\QrCode\QrCode;
use common\models\Student;
use common\models\Exam;
use common\models\StudentPerevot;
use common\models\StudentDtm;
use common\models\Direction;
use frontend\models\Contract;
use common\models\Course;

/** @var Student $student */
/** @var Direction $direction */
/** @var $type */

$direction = $student->direction;

if ($student->edu_type_id == 1) {
    $query = Exam::findOne([
        'direction_id' => $student->direction_id,
        'student_id' => $student->id,
        'status' => 3,
        'is_deleted' => 0
    ]);
    $summa = $query->contract_price;
    $con2 = $query->contract_second;
    $con3 = $query->contract_third;
    $link = $query->contract_link;
    $date = date("Y-m-d H:i:s" , $query->created_at);
    $id = $query->id;
} elseif ($student->edu_type_id == 2) {
    $query = StudentPerevot::findOne([
        'direction_id' => $student->direction_id,
        'student_id' => $student->id,
        'file_status' => 2,
        'status' => 1,
        'is_deleted' => 0
    ]);
    $summa = $query->contract_price;
    $con2 = $query->contract_second;
    $con3 = $query->contract_third;
    $link = $query->contract_link;
    $date = date("Y-m-d H:i:s" , $query->created_at);
    $id = $query->id;
}

$qr = (new QrCode('https://cons.perfectuniversity.uz/site/shartnoma?key=' . $link))->setSize(100, 100)
    ->setMargin(10);
$img = $qr->writeDataUri();

$lqr = (new QrCode('https://license.gov.uz/registry/da127cfb-12a8-4dd6-b3f8-7516c1e9dd82'))->setSize(100, 100)
    ->setMargin(10);
$limg = $lqr->writeDataUri();
?>

<?php if ($student->language_id == 1): ?>
    <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman'">
        <tr>
            <td colspan="2" style="text-align: center;padding-bottom: 10px; font-size: 16px">
                <div><b>2024-2025 o‘quv yilida to‘lov asosida ta’lim xizmatlarini ko‘rsatish bo‘yicha <br><?= $direction->code ?> |  <?= 20000 + $id ?>-Sonli ikki tomonlama shartnoma</b></div>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0px"><p><?= $date ?></p></td>
            <td style="text-align: right"><p>Toshkent shahri</p></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 10px">
                <div><b>“GLOBAL SOFTLINE” </b>oliy ta’lim tashkiloti, keyingi o‘rinlarda “Universitet” deb ataluvchi
                    nomidan buyruq asosida ish yurituvchi Universitetning rektori ASQARALIYEV ODILBEK ULUG‘BEK O‘G‘LI
                    bir tomondan, <br>
                    ____________________________________________________________________________________________________
                    (keyingi o‘rinlarda “Buyurtmachi”) nomidan ___________________________ asosida ish yurituvchi ___________________ __________________
                    yoki fuqaro ______________________________________________________________ ikkinchi tomondan
                </div>

            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div>
                    <div style="text-align: center">
                        <b><?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?></b> <br></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>(keyingi o‘rinlarda “Talaba”) deb ataluvchi ikkinchi tomondan, keyingi o‘rinlarda birgalikda
                    “Tomonlar”
                    deb ataluvchilar o‘rtasida mazkur shartnoma quyidagilar haqida tuzildi:
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div>
                    <div style="text-align: center"><b>I. Shartnoma predmeti</b> <br></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div> &nbsp;&nbsp;&nbsp;&nbsp; 1.1. Mazkur shartnomaga muvofiq Universitet Talabani quyida ko'rsatilgan
                    ta’lim yo‘nalishi va ta'lim shakli bo‘yicha oliy ta’limning davlat ta’lim standartlari asosida
                    tasdiqlangan o‘quv reja va o‘quv dasturlari asosida o‘qitish majburiyatini oladi.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid #000;width: 100%">
                <div>
                    <div class="row" style="width: 100%;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Ta'lim yo'nalishi:</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= $direction->code . ' ' . $direction->name_uz ?></b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Ta'lim shakli:</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= $direction->eduForm->name_uz ?></b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">O'qish muddati:</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= $direction->edu_duration ?> YIL</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">O'quv kursi:</div>
                                </td>
                                <td style="width: 70%">
                                    <?php if ($student->edu_type_id == 2) : ?>
                                        <?php $courseId = $student->course_id + 1 ?>
                                        <?php $course = Course::findOne($courseId) ?>
                                        <div class="col-md-6"><b><?= $course->name_uz ?></b></div>
                                    <?php else: ?>
                                        <div class="col-md-6"><b>1 kurs</b></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Shartnomaning umumiy narxi (bir
                                        o‘quv yili uchun):</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= number_format((int)$summa, 0, '', ' ') . ' (' . Contract::numUzStr($summa) . ')' ?>
                                            so'm
                                        </b></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    <div>
                        &nbsp;&nbsp;&nbsp;&nbsp; Talaba esa Universitet tomonidan belgilangan tartib-qoidalarga rioya
                        qilgan
                        holda ta’lim olish va ta’lim olganlik uchun haq to‘lash majburiyatini oladi.
                    </div>
                    <div>
                        &nbsp;&nbsp;&nbsp;&nbsp; 1.2. Universitetda shartnoma asosida o‘qitishning to‘lovi (keyingi
                        o‘rinlarda – shartnoma to‘lovi) miqdori ta’lim yo‘nalishi, ta’lim shakli kunduzgi, kechki va
                        sirtqi,
                        o‘qishni ko‘chirish bilan bog‘liq fanlarning farqini o‘qitish hamda to‘plagan ballidan kelib
                        chiqib,
                        har bir o‘quv yili uchun alohida belgilanadi.
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2"  style="text-align: center">
                <div>
                    <b>II. TOMONLARNING HUQUQ VA MAJBURIYATLARI</b>
                </div>

            </td>
        </tr>
        <tr>
            <td colspan="2">

                &nbsp;&nbsp;&nbsp;&nbsp;2.1. Universitetning huquqlari: <br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.1. Talaba tomonidan o‘z majburiyatlarini bajarishini doimiy nazorat qilish.
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.2. Talabadan shartnoma majburiyatlarining bajarilishini, ichki tartib qoidalariga rioya etilishini,
                shartnoma bo‘yicha to‘lovlarni o‘z vaqtida to‘lashni talab qilish.<br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.3. Shartnoma to‘lovini amalga oshirish tartibini, ichki tartib va ta’lim dasturi qoidalarini buzganligi,
                semestr davomida uzrli sababsiz Universitetda belgilangan akademik soat miqdoridan ortiq dars qoldirgani
                uchun talabani talabalar safidan ogohlantirmasdan chiqarish (chetlashtirish) yoki tegishli kursda qoldirish.
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.4. Talabadan o‘rnatilgan tartibda tegishli hujjatlarni talab qilish va ular taqdim etilmagan holda
                shartnoma to‘lovi amalga oshirilganidan qat’i nazar, Talabani o‘qishga qabul qilish yoki keyingi kursga
                o‘tkazish to‘g‘risidagi Universitet rektorining buyrug‘iga kiritmaslik.<br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.5. Universitetning ichki hujjatlarida belgilangan hollarda Talabani imtihonga kiritmaslik.
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;2.1.6. Talaba quyidagi qilmishlardan birini sodir etgan taqdirda Universitet Buyurtmachini xabardor
                qilgan holda shartnomani bir tomonlama bekor qilish huquqiga ega:
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;a) ijtimoiy tarmoqlarda Universitet faoliyati to‘g‘risida ataylab yolg‘on ma’lumotlar tarqatganda,
                shuningdek, professor-o‘qituvchilar va ma’muriy xodimlarga hurmatsizlik bilan munosabatda bo‘lganda;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;b) hujjatlarni qalbakilashtirish, shu jumladan, imtihon savollariga javoblarni hamda shu kabi boshqa
                materiallarni imtihonga olib kirish, imtihon paytida ulardan foydalanish yoki boshqa talabalarga tarqatish;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;c) Universitet tovar belgisi/logotipidan Universitetning yozma ruxsatisiz (turli xil buyumlar, kiyimkechaklar tayyorlash uchun va hokazo) foydalanish;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;d) Universitet talabalari, o‘qituvchilari va xodimlariga nisbatan jinsiy, irqiy, diniy, millatlararo
                xarakterdagi kamsituvchi harakatlar sodir etish, odob-ahloq qoidalarini buzish, jismoniy va/yoki ruhiy
                bosim o‘tkazish;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;e) Universitet ichki tartib qoidalariga, ichki hujjatlari talablariga rioya qilish talablarini muntazam (2 va
                undan ortiq marta) qasddan buzish, o‘ziga oshkor bo‘lgan Universitetga yoki boshqa talabalarga tegishli
                maxfiy ma’lumotni tarqatish;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;f) o‘qish jarayonida Universitetning yozma roziligisiz xorijga ketish;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;g) bir semestr davomida 36 akademik soat dars qoldirganligi yoki ma’lumoti haqidagi hujjatning asl
                nusxasini Universitetga o‘z vaqtida taqdim etmaganligi
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;h) boshqa oliy ta’lim tashkilotidan, jumladan xorijiy oliy ta’lim tashkilotidan o‘qishini ko‘chirish uchun
                murojaat qilib Universitetga o‘qishga qabul qilinishida soxta hujjatlardan foydalanganligi yoki o‘qishni
                ko‘chirish bilan bog‘liq hujjatlarida yolg‘on va haqqoniy bo‘lmagan ma’lumotlar mavjudligi;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;i) Universitetning ichki hujjatlarida nazarda tutilgan boshqa hollarda;
                <br>
                &nbsp;&nbsp;&nbsp;&nbsp;j) O‘zbekiston Respublikasining amaldagi qonun hujjatlarida nazarda tutilgan boshqa normalarni
                buzganlikda aybdor deb topilganida.
                <br>

                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.7. Ko‘rsatilayotgan ta’lim xizmatlarining miqdori va sifatini pasaytirmagan holda tasdiqlangan dars
                    jadvaliga o‘zgartirishlar kiritish, O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va forsmajor holatlariga qarab, ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini
                    kamaytirmasdan o‘qitish rejimini masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.8. Mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda,
                    shartnoma to‘lovi miqdorini o‘zgartirish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.9. Shartnoma to‘lovi muddatlarini uzaytirish.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">

                <div>


                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.10. O‘zbekiston Respublikasining amaldagi qonunchiligiga muvofiq va fors-major holatlariga qarab,
                    ushbu shartnoma shartlarida belgilangan ta’lim xizmatlari xarajatlarini kamaytirmasdan o‘qitish rejimini
                    masofaviy shaklga o‘tkazish to‘g‘risida qaror qabul qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2. Universitetning majburiyatlari: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.1. Talabani davlat ta’lim standartlari, ta’lim sohasidagi qonunchilik talablari, o‘quv dasturlari hamda
                    ushbu shartnoma shartlariga muvofiq o‘qitish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.2. Talabaning qonunchilikda belgilangan huquqlarini ta’minlash <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.3. O‘quv jarayonini yuqori malakali professor-o‘qituvchilarni jalb qilgan holda tashkil etish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.4. O‘quv yili davomida elektron hisob fakturalar yuborish.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5. Universitet quyidagilarni o‘z zimmasiga olmaydi:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.1. Talabaning stipendiya va moddiy jihatdan ta’minoti;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.2. Talabaning hayoti, sog‘ligi va uning shaxsiy mulki uchun javobgarlik;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.3. Talaba va Buyurtmachining o‘zaro majburiyatlari uchun javobgarlik.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3. Buyurtmachining huquqlari: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.1. Universitet va Talabadan shartnoma majburiyatlari bajarilishini talab qilish
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.2. Talabaning Universitet o‘quv reja va dasturlariga muvofiq ta’lim olishini nazorat qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.3. Talabaning fanlarni o‘zlashtirish darajasiga oid ma’lumotlarni Universitetdan belgilangan tartibda
                    so‘rash va olish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.4. Universitetdan Talabaga sifatli ta’lim berilishini talab qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.5. Universitetning ta’lim jarayonlarini yaxshilashga doir takliflar berish.
                    <br>
                </div>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4. Buyurtmachining majburiyatlari: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.1. Shartnoma to‘lovini mazkur shartnomada belgilangan muddatlarda to‘lash.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.2. Universitet Ustavi va ichki tartib-qoidalariga qat’iy rioya qilishni hamda o‘quv reja va dasturlarga
                    muvofiq ta’lim olishni Talabadan talab qilish;                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.3. Mehnatga haq to‘lashning eng kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, mos
                    ravishda shartnoma to‘lovi miqdoriga mutanosib ravishda to‘lovni o‘z vaqtida amalga oshirish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5. Talabaning huquqlari:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.1. Universitetdan shartnoma bo‘yicha o‘z majburiyatlarini bajarishni talab qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.1. Universitetdan shartnoma bo‘yicha o‘z majburiyatlarini bajarishni talab qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.2. Universitet tomonidan tasdiqlangan o‘quv reja va dasturlarga muvofiq davlat standarti talablari
                    darajasida ta’lim olish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.3. Universitetning moddiy-texnik bazasidan, jumladan laboratoriya jihozlari, asbob-uskunalar,
                    axborot-resurs markazi va Wi-Fi hududidan foydalanish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.4. Universitetning o‘quv jarayonlarini takomillashtirish bo‘yicha takliflar kiritish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.5. Shartnoma to‘lovini shartnoma shartlariga muvofiq to‘lash.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.6. Bilim va ko‘nikmalarini rivojlantirish, takomillashtirish, Universitet taqdim etayotgan barcha
                    imkoniyatlaridan foydalanish, shuningdek, dars va o‘qishdan bo‘sh vaqtlarida jamiyat hayotida faol ishtirok
                    etish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.7. Quyidagi hollarda Universitet ruxsati bilan 1 (bir) yilgacha akademik ta’til olish:
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;a) salomatlik holati davlat sog‘liqni saqlash tizimiga kiruvchi tibbiyot muassasalarining davolovchi
                    shifokorlari tomonidan hujjatlar bilan tasdiqlangan sezilarli darajada yomonlashganda;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;b) homiladorlik va tug‘ish, shuningdek bola ikki yoshga to‘lgunga qadar parvarishlash bo‘yicha ta’tilga
                    bog‘liq hollarda;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;v) yaqin qarindoshining vafoti munosabati bilan bu holda akademik ta’til berish Universitet rahbariyati
                    tomonidan har bir holat alohida ko‘rib chiqiladi va qaror qabul qilinadi;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;g) harbiy xizmatni o‘tash uchun safarbar etilishi munosabati bilan;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;v) yaqin qarindoshining vafoti munosabati bilan bu holda akademik ta’til berish Universitet rahbariyati
                    tomonidan har bir holat alohida ko‘rib chiqiladi va qaror qabul qilinadi;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;d) boshqa hollarda Universitet rahbariyatining qaroriga ko‘ra.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.8. O‘qishning barcha bosqichlarini muvaffaqiyatli tamomlagandan so‘ng O‘zbekiston
                    Respublikasida oliy ma’lumot to‘g‘risidagi hujjat bo‘lgan Universitetning oliy ma’lumot to‘g‘risidagi
                    diplomini olish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6. Talabaning majburiyatlari:
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.1. Ushbu shartnoma Universitet va Talaba o‘rtasida (Buyurtmachisiz) tuzilgan taqdirda shartnoma
                    to‘lovi bo‘yicha barcha majburiyatlarni o‘z zimmasiga olish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.2. O‘zbekiston Respublikasi qonunchiligida, shuningdek Universitetning o‘quv jarayoni va
                    faoliyatini tartibga soluvchi normativ-huquqiy hujjatlarida belgilangan oliy ta’lim muassasalari talabalariga
                    qo‘yiladigan talablarga muvofiq ta’lim olish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.3. Univesitet ichki hujjatlariga muvofiq talab etiladigan barcha hujjatni taqdim etish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.4. Universitet ichki tartib qoidalari, Universitetga kirish-chiqish, shaxsiy va yong‘in xavfsizligi
                    qoidalari talablariga qat’iy rioya qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.5. O‘zbekiston Respublikasi qonunchiligiga zid harakatlar va qilmishlarni sodir etmaslik
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.6. Universitetning texnik va boshqa o‘quv qurollari, shuningdek asbob-uskuna/jihozlari va boshqa
                    mol-mulkidan oqilona foydalanish
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.7. Pasport ma’lumotlari, yashash manzili va telefon raqami o‘zgarganligi to‘g‘risida ular
                    o‘zgartirilgan kundan e’tiboran besh kun ichida Universitetni xabardor qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.8. O‘zbekiston Respublikasi hududini Universitetning yozma ruxsati asosida tark etish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.7. Pasport ma’lumotlari, yashash manzili va telefon raqami o‘zgarganligi to‘g‘risida ular
                    o‘zgartirilgan kundan e’tiboran besh kun ichida Universitetni xabardor qilish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.9. O‘zlashtirish darajasi, fanlar/darslar bo‘yicha davomat foizi, Universitet oldidagi moliyaviy
                    majburiyatlarning bajarilishi ustidan doimiy nazorat olib borish.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.10. Talaba ushbu shartnomada ko‘zda tutilmagan qo‘shimcha xizmatlarni olganida xizmat haqini
                    to‘lash. Universitetning ichki hujjatlari talablarini buzganda jarima nazarda tutilgan hollarda mazkur
                    jarima(lar)ni o‘z vaqtida to‘lash.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 15px">

                <div>
                    <b>III. TA’LIM TO‘LOVINING MIQDORI, TARTIBI VA TO‘LOV SHARTLARI</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.1. 2024-2025 o‘quv yili uchun shartnoma to‘lovi<b> <?= number_format((int)$summa, 0, '', ' ') . ' (' . Contract::numUzStr($summa). ')' ?>
                        so'm</b>ni tashkil etadi.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.2. Universitet xizmatlarning narxini o‘zgartirish huquqini o‘zida saqlab qoladi. Bunday holatda
                    qo‘shimcha kelishuv tuziladi va Tomonlar yangi qiymatni hisobga olgan holda o‘zaro hisob-kitoblarni
                    amalga oshirish majburiyatini oladi.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.3. O‘qish uchun to‘lov quyidagi tartibda amalga oshiriladi:
                    <br>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;3.3.1. <b>2024-yil 15-sentabrga qadar – 25 foizidan kam bo‘lmagan miqdorda.</b>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;3.3.2. <b>2024-yil 15-dekabrga qadar – 50 foizidan kam bo‘lmagan miqdorda</b>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;3.3.3. <b>2025-yil 15-fevralga qadar – 75 foizidan kam bo‘lmagan miqdorda.</b>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;3.3.4. <b>2025-yil 15-aprelga qadar – 3.1-bandda nazarda tutilgan ta’lim to‘lovining amalga oshirilmagan
                        qismi miqdorda.</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.4. Buyurtmachi tomonidan shartnoma to‘lovini to‘lashda shartnomaning tartib raqami va sanasi,
                    familiyasi, ismi va sharifi hamda o‘quv kursi to‘lov topshiriqnomasida to‘liq ko‘rsatiladi. To‘lov kuni
                    Universitetning bank hisob raqamiga mablag‘ kelib tushgan kun hisoblanadi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.5. Talaba tegishli fanlar bo‘yicha akademik qarzdorlikni qayta topshirish sharti bilan keyingi kurs
                    (semestr)ga o‘tkazilgan taqdirda, keyingi semestr uchun shartnoma to‘lovi Talaba tomonidan akademik
                    qarzdorlik belgilangan muddatda topshirilishiga qadar amalga oshiriladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.6. Talaba ushbu Shartnomaning amal qilish muddati davomida o‘quv darslarini o‘zlashtira olmagani,
                    ichki tartib qoidalarini, odob-axloq qoidalarini yoki o‘quv jarayonini buzgani va unga nisbatan o‘qishini
                    to‘xtatish yoki o‘qishdan chetlatish chorasi ko‘rilganligi, uni o‘qish uchun haq to‘lash bo‘yicha moliyaviy
                    majburiyatlardan ozod etmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.7. Shartnoma Universitet tashabbusi bilan Talaba uning hatti-harakatlari (harakatsizligi) sababli
                    talabalar safidan chetlashtirilsa, shartnoma bo‘yicha to‘langan mablag‘lar qaytarilmaydi.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 10px 0px">
                <div style="text-align: center">
                    <b>
                        IV. SHARTNOMAGA O‘ZGARTIRISH KIRITISH VA UNI BEKOR QILISH TARTIBI
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;4.1. Ushbu Shartnoma shartlari Tomonlar kelishuvi bilan yoki O‘zbekiston Respublikasi qonunchiligiga
                    muvofiq o‘zgartirilishi mumkin. Shartnomaga kiritilgan barcha o‘zgartirish va qo‘shimchalar, agar ular
                    yozma ravishda tuzilgan va Tomonlar yoki ularning vakolatli vakillari tomonidan imzolangan bo‘lsa,
                    haqiqiy hisoblanadi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2. Ushbu Shartnoma quyidagi hollarda bekor qilinishi mumkin: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.1. Tomonlarning kelishuviga binoan;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.2. Universitetning tashabbusiga ko‘ra bir tomonlama (2.1.6-bandda nazarda tutilgan hollarda);
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.3. sudning qonuniy kuchga kirgan qarori asosida;                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.4. shartnoma muddati tugashi munosabati bilan; <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.5. Talaba o‘qishni muvaffaqiyatli tamomlaganligi munosabati bilan;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.6. Universitet faoliyati tugatilgan taqdirda.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.3. Shartnomani Universitetning tashabbusiga ko‘ra bir tomonlama tartibda bekor qilinganida
                    Buyurtmachining yuridik yoki elektron pochta manziliga tegishli xabar yuboriladi va shu bilan Buyurtmachi
                    xabardor qilingan hisoblanadi.
                    <br>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <div style="text-align: center">
                    <b>
                        V. FORS-MAJOR
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;5.1. Tomonlardan biri tarafidan shartnomani to‘liq yoki qisman bajarishni imkonsiz qiladigan holatlar,
                    xususan, yong‘in, tabiiy ofat, urush, har qanday harbiy harakatlar, mavjud huquqiy hujjatlarni almashtirish
                    va boshqa mumkin bo‘lgan tomonlarga bog‘liq bo‘lmagan fors-major holatlari shartnoma bo‘yicha
                    majburiyatlarni bajarish muddatlari ushbu holatlarning amal qilish muddatiga mos ravishda uzaytiriladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;5.2. Ushbu shartnoma bo‘yicha o‘z majburiyatlarini bajarishga qodir bo‘lmagan tomon ikkinchi
                    tomonni ushbu holatlarni bajarishiga to‘sqinlik qiladigan holatlar yuzaga kelganligi yoki bekor qilinganligi
                    to‘g‘risida darhol xabardor qilishi shart.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;Xabarnoma shartnomada ko‘rsatilgan yuridik manzilga yuboriladi va jo‘natuvchi pochta bo‘limi
                    tomonidan tasdiqlanadi. <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;5.3. Agar shartnoma tomonlariga bog‘liq bo‘lmagan tarzda sodir bo‘lgan har qanday hodisa, tabiiy
                    ofatlar, urush yoki mamlakatdagi favqulodda holat, davlat hokimiyati organi tomonidan qabul qilingan
                    qaror, uning ijrosi, uning yuzasidan amalga oshirilgan harakatlar (shular bilan cheklanmagan hodisalar)
                    tufayli yuzaga kelgan bo‘lsa, bir tomon ikkinchi tomon oldida ushbu shartnomani bajarmaslik yoki
                    bajarishni kechiktirish oqibatlari uchun javobgar bo‘lmaydi. Ijrosi shu tarzda to‘xtatilgan tomon bunday
                    majburiyatlarni bajarish muddatini tegishli ravishda uzaytirish huquqiga ega bo‘ladi.

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VI. TOMONLARNING JAVOBGARLIGI
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;6.1. Talaba mol-mulk, jihozlar, o‘quv qurollari va hokazoga moddiy zarar yetkazgan taqdirda
                    Universitet oldida to‘liq moddiy javobgarlikni o‘z zimmasiga oladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2. Ushbu shartnomaning 3.3-bandga muvofiq o‘qish uchun to‘lov kechiktirilgan taqdirda:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2.1. Talabaning Universitetga kirishi cheklanadi;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2.2. Quyidagilar to‘xtatiladi:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- Universitet tomonidan akademik xizmatlar ko‘rsatilishi;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- Talabani imtihonlarga kiritilishi;                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- har qanday akademik ma’lumotnomalar/sertifikatlar berish;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2.3. Talaba 2.1.6-bandga muvofiq talabalar safidan chiqarilishi mumkin.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.3. Universitet shartnoma to‘lovi manbalari uchun javobgarlikni o‘z zimmasiga olmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.4. Universitet shartnoma to‘lovini amalga oshirishda yo‘l qo‘yilgan xatolar uchun javobgar bo‘lmaydi.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.5. Talabaning o‘qishdan chetlashtirilishi yoki talabalar safidan chiqarilishi Buyurtmachi va Talabani
                    ushbu shartnoma bo‘yicha Talabaga ko‘rsatilgan ta’lim xizmatlari uchun haq to‘lash hamda boshqa
                    majburiyatlardan ozod etmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.6. Tomonlarning ushbu Shartnomada nazarda tutilmagan javobgarlik choralari O‘zbekiston
                    Respublikasining amaldagi qonunchiligi bilan belgilanadi.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VII. QO‘SHIMCHA SHARTLAR
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;7.1. Universitetning Talabani o‘qishga qabul qilish buyrug‘i Talaba tomonidan barcha kerakli hujjatlarni
                    taqdim etish va shartnomaning 3.3.1-bandiga muvofiq to‘lovni amalga oshirish sharti bilan chiqariladi.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.2. Talabaga Universitet tomonidan stipendiya to‘lanmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.3. Mazkur Shartnomaning 1-bandida nazarda tutilgan majburiyatlar O‘zbekiston Respublikasining
                    amaldagi qonunchiligi talablariga muvofiq, bevosita yoki onlayn tarzda taqdim etilishi mumkin. Akademik
                    ta’lim xizmatlari onlayn tarzda taqdim etilgan taqdirda, Talaba texnik va telekommunikatsiya aloqalari
                    holatining sifati uchun shaxsan javobgardir.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.4. Ushbu Shartnoma Tomonlar bir o‘quv yili uchun uning predmetida ko‘rsatilgan maqsadlar uchun
                    o‘z majburiyatlarini to‘liq bajarguniga qadar, lekin 2025-yil 1-iyuldan kechikmagan muddatga qadar
                    tuziladi. Shartnomaning amal qilish muddati tugaganligi qarzdor Tomonlarni o‘z zimmasidagi
                    majburiyatlarini bajarishdan ozod qilmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.5. O‘qish davrida Talaba yoki boshqa shaxsga rasmiy hujjatlarning asl nusxalari, shu jumladan o‘rta
                    yoki o‘rta maxsus ta’lim muassasasining bitiruv hujjatlari (attestat/diplom/sertifikat) berilmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.6. Universitet Talabani ishga joylashtirish majburiyatini o‘z zimmasiga olmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.7. Shartnoma to‘lovlari va ularni qaytarish bilan bog‘liq barcha bank xizmatlari Buyurtmachi yoki
                    Talaba tomonidan to‘lanadi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.8. Universitet tomonidan ushbu shartnoma bo‘yicha mablag‘lar qaytarilishi lozim bo‘lgan hollarda
                    mazkur mablag‘lar tegishli hujjat o‘z kuchiga kirgan paytdan boshlab 30 (o‘ttiz) kun ichida qaytariladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.9. Ushbu Shartnomaga kiritilgan har qanday o‘zgartirish va/yoki qo‘shimchalar, agar ular tomonlar
                    tomonidan yozma shaklda rasmiylashtirilgan, imzolangan/muhrlangan bo‘lsagina amal qiladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.10. Tomonlar shartnomada Universitet faksimilesini tegishli imzo sifatida tan olishga kelishib oldilar
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.11. Ushbu shartnomadan kelib chiqadigan har qanday nizo yoki kelishmovchiliklarni tomonlar
                    muzokaralar yo‘li bilan hal qilishga intiladi. Kelishuvga erishilmagan taqdirda, nizolar O‘zbekiston
                    Respublikasi qonun hujjatlarida belgilangan tartibda Universitet joylashgan yerdagi sud tomonidan ko‘rib
                    chiqiladi.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VIII. YAKUNIY QOIDALAR
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;8.1. Ushbu shartnoma Tomonlar tomonidan imzolangan paytdan boshlab kuchga kiradi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.2. Buyurtmachi va Talaba shartnoma shartlaridan norozi bo‘lgan taqdirda 2024-yil 30-noyabrdan
                    kechiktirmay murojaat qilishi lozim, bunda mazkur sanaga qadar Universitet bilan shartnoma tuzmagan
                    Talaba o‘qishga qabul qilinmaydi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.3. O‘zbekiston Respublikasi Prezidentining tegishli farmoniga muvofiq mehnatga haq to‘lashning eng
                    kam miqdori yoki bazaviy hisoblash miqdori o‘zgarganda, shartnoma to‘lovi miqdori navbatdagi semestr
                    boshidan oshiriladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.4. Mazkur shartnomani imzolanishi, o‘zgartirilishi, ijro etilishi, bekor qilinishi yuzasidan Tomonlar
                    o‘rtasida yozishmalar shartnomada ko‘rsatilgan Tomonlarning rasmiy elektron pochta manzillari orqali
                    amalga oshirilishi mumkin va Tomonlar bu tartibda yuborilgan xabarlarning yuridik kuchga ega ekanligini
                    tan oladilar. Elektron pochta manzili o‘zgarganligi to‘g‘risida boshqa tomonni yozma ravishda xabardor
                    qilmagan tomon bu bilan bog‘liq barcha xavflarni o‘z zimmasiga oladi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.5. Ushbu Shartnoma o‘zbek tilida, uch asl nusxada, teng yuridik kuchga ega, har bir tomon uchun bir
                    nusxadan tuzildi.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 10px">
                <div style="text-align: center">
                    <b>
                        IX. TOMONLARNING YURIDIK MANZILLARI VA BANK REKVIZITLARI
                    </b>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse">
        <tr>
            <td style="width: 50%">
                <div><b>“GLOBAL SOFTLINE” oliy ta’lim tashkiloti</b></div>
                <br>
                <p>
                <div><b>Manzil: Toshkent shahri, Yunusobod tumani, <br> Posira MFY, Bog'ishamol ko'chasi, 220-uy</b>
                </div>
                </p>
                <p>
                <div><b>H/R: 2020 8000 0055 1905 4002</b></div>
                </p>
                <p>
                <div><b>Bank: “KAPITALBANK” ATB Sirg’ali filiali</b></div>
                </p>
                <p>
                <div><b>Bank kodi (MFO): 01042 </b></div>
                </p>
                <p>
                <div><b>IFUT (OKED): 85420</b></div>
                </p>
                <p>
                <div><b>STIR (INN): 309477784</b></div>
                </p>
                <p>
                <div><b>Tel: +998 77 129-29-29</b></div>
                </p>
            </td>
            <td style="width: 50%">
                <div><b>Talaba: <?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?></b></div>
                <p>
                <div><b>Pasport ma’lumotlari: <?= $student->passport_serial .' '.$student->passport_number ?></b></div>
                </p>
                <p>
                <div><b>JShShIR: <?= $student->passport_pin ?></b></div>
                </p>
                <p>
                <div><b>
                        <div><b>Telefon raqam: <?= Yii::$app->user->identity->username ?></b></div>
                    </b></div>
                </p>
                <p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px">
                M.O‘.
            </td>
            <td style="padding: 10px">
                <?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px">
                Rektor_______________ Asqaraliyev O.U.
            </td>
            <td style="padding: 10px">
                Talaba:_________________
            </td>
        </tr>
    </table>
    <table style="text-align:center; width: 90%;border-collapse: collapse;" border="">
        <tr>
            <td style="width: 50%; text-align: center"><img src="<?= $img ?>" width="100px"></td>
            <td style="width: 50%; text-align: center"><img src="<?= $limg ?>" width="100px">
                <br><b>Litsenziya berilgan sana va raqami</b><br>19.10.2022 &nbsp;&nbsp;№ 043951
            </td>
        </tr>
    </table>
    <div style="page-break-before:always">&nbsp;</div>
    <div>
        <div style="border: 2px solid black; padding: 10px">
            <div><b>SANA: <?= $date ?></b></div>
            <div style="width: 100%;">
                <b>INVOYS RAQAMI: <?= $con2 ?></b></div>
            <div>
                <b>KONTRAKT TO’LOV
                    MIQDORI: <?= number_format((int)$summa, 0, '', ' ') . ' (' . Contract::numUzStr($summa) . ')' ?>
                    so’m</b>
                <br>
            </div>
            <div>
                To’lovni amalga oshirish usullari: <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yuridik shaxslar va bank kassalari orqali. Bunda To’lov
                maqsadida - Invoys raqam. JSHSHIR. Talabaning IFSH tartibida yozilgan bo’lishi talab etiladi
                <br>
            </div>
            <br>
            <div style="border:2px solid red; width: 100%; display: block; padding: 10px;">
                <span style="padding: 20px;"><?= $con2 ?></span>  &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <span style="padding: 20px; display: inline-block;"><?= $student->passport_pin ?? '' ?> </span> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <span style="padding: 20px; display: inline-block;"><?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?></span>
            </div>
            <br>
            <div style="padding: 10px;color:red;">
                <small>To’lov maqsadi belgilangan tartibda to’ldirilmagan taqdirda to’lovni qabul qilishga
                    doir muammolar kelib chiqishi mumkin. Shu sababli to’lov qilish jarayonida to’lov
                    maqsadini belgilangan tartibda ko’rsatilishi shart!</small>
            </div>
            <br>
            <div>
                <small>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
                    = = = = </small>
            </div>
            <br>
            <div style="color:#17406C">
                <b>To’lovlarni amalgi oshirish uchun Universitetning bank hisob ma’lumotlari:</b>
            </div>
            <br>
            <div>
                <table style="width:100%; border-collapse: collapse;" border="1">
                    <tr>
                        <td><b>Qabul qiluvchi tashkilot nomi: </b></td>
                        <td><b>“GLOBAL SOFTLINE” MCHJ</b></td>
                    </tr>
                    <tr>
                        <td><b>Hisob raqami: </b></td>
                        <td><b>2020 8000 0055 1905 4002</b></td>
                    </tr>
                    <tr>
                        <td><b>Bank kodi (MFO):</b></td>
                        <td><b>01042 </b></td>
                    </tr>
                    <tr>
                        <td><b>Bank nomi: </b></td>
                        <td><b>“KAPITALBANK” ATB Sirg’ali filiali </b></td>
                    </tr>
                    <tr>
                        <td><b>STIR (INN):</b></td>
                        <td><b>309477784 </b></td>
                    </tr>
                    <tr>
                        <td><b>IFUT (OKED):</b></td>
                        <td><b>85420 </b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


<?php elseif ($student->language_id == 3): ?>
    <table style="width: 100%; border-collapse: collapse; font-family: 'Times New Roman'">
        <tr>
            <td colspan="2" style="text-align: center;padding-bottom: 10px">
                <div><b>HU <?= $direction->code ?> | Договор № T-<?=20000 + $id ?><br> на 2024 -2025 учебный год по оказанию образовательных услуг на платной основе</b></div>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0px"><p><?= $date ;?></p></td>
            <td style="text-align: right"><p>г.Ташкент</p></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-bottom: 10px">
                <div>От имени организации высшего образования <b>«GLOBAL SOFTLINE» </b>(дальнейшем – «Университет»)
                    ректор АСКАРАЛИЕВ ОДИЛБЕК УЛУГБЕК УГЛИ, действуя на основании Устава, с одной стороны,
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div>
                    <div style="text-align: center"><b><?= $student->first_name. ' '. $student->last_name. ' '. $student->middle_name ?></b> <br></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>именуемый в дальнейшем «Студент», с другой стороны, совместно «Стороны», заключили настоящий договор о нижеследующем:
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div>
                    <div style="text-align: center"><b>I. ПРЕДМЕТ ДОГОВОРА</b> <br></div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div> &nbsp;&nbsp;&nbsp;&nbsp;1.1. В соответствии с настоящим договором Университет обязуется осуществлять обучение
                    Обучающегося на основе учебных планов и образовательных программ, утвержденных на основе
                    государственных образовательных стандартов высшего образования в области образования и форме
                    обучения, указанных ниже.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border:1px solid #000;width: 100%">
                <div>
                    <div class="row" style="width: 100%;">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Направление :</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= $direction->code.' '.$direction->name_ru ?></b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Форма обучения :</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?= $direction->eduForm->name_ru ?></b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Период обучения :</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?=$direction->edu_duration ?> ЛЕТ</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Курс:</div>
                                </td>
                                <td style="width: 70%">
                                    <?php if ($student->edu_type_id == 2) : ?>
                                        <?php $courseId = $student->course_id + 1 ?>
                                        <?php $course = Course::findOne($courseId) ?>
                                        <div class="col-md-6"><b><?= $course->name_ru ?></b></div>
                                    <?php else: ?>
                                        <div class="col-md-6"><b>1 курс</b></div>
                                    <?php endif; ?>
                                    <div class="col-md-6"><b>1 курс</b></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 30%">
                                    <div class="col-md-6">Общая стоимость договора (за один
                                        учебный год) :</div>
                                </td>
                                <td style="width: 70%">
                                    <div class="col-md-6"><b><?=number_format($summa,0,'',' ').' ('. Contract::numRuStr($summa) .')'?> сум</b></div>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    <div>
                        &nbsp;&nbsp;&nbsp;&nbsp; Обучающийся обязуется получать образование и оплачивать обучение в соответствии с
                        правилами процедуры, установленными Университетом.
                    </div>
                    <div>
                        &nbsp;&nbsp;&nbsp;&nbsp; 1.2. Размер платы за обучение в вузе на основании договора (далее - плата за договор) зависит от
                        направления обучения, формы обучения - очная, вечерняя и заочная, а также разницы в предметах,
                        связанных с обучением. перевод обучения определяется отдельно для каждого учебного года, исходя
                        из преподавания и полученных баллов.
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <b>II. ПРАВА И ОБЯЗАННОСТИ СТОРОН</b>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1. Университет вправе: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.1. Постоянный контроль за выполнением студентом своих обязательств.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.2. Требовать от студента выполнения договорных обязательств, соблюдения правил
                    внутреннего распорядка, своевременной оплаты обучения по договору.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.3. Отчисление (исключение) обучающегося из числа состава студентов без предупреждения
                    или оставление его на соответствующем курсе за нарушение порядка осуществления контрактной
                    оплаты, внутреннего порядка и правил образовательной программы, за пропуск занятий большего
                    числа академических часов, определённых Университетом в течение семестра без уважительной
                    причины<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.4. Требовать от обучающегося соответствующие документы в установленном порядке и не
                    включать их в приказ ректора Университета о зачислении или переводе обучающегося на следующий
                    курс, независимо от того, что оплата контракта произведена без них.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.5. Не допуск обучающегося к экзамену в случаях, предусмотренных внутренними
                    документами университета.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.6. В случае совершения обучающимся одного из следующих действий Университет имеет
                    право расторгнуть договор в одностороннем порядке, уведомив об этом Заказчика:
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;а) при умышленном распространении ложной информации о деятельности Университета в
                    социальных сетях, а также неуважительном отношении к профессорско-преподавательскому составу,
                    преподавателям и административному персоналу;
                    &nbsp;&nbsp;&nbsp;&nbsp;б) фальсификация документов, в том числе принесение на экзамен ответов на экзаменационные
                    вопросы и другие подобные материалы, их использование во время экзамена или распространение
                    среди других обучающихся;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;в) Использование товарного знака/логотипа Университета без письменного разрешения
                    Университета (для изготовления различных предметов, одежды и т.п.);
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;г) совершение сексуальной, расовой, религиозной, этнической дискриминации в отношении
                    обучающихся, преподавателей и сотрудников Университета, нарушение правил этикета, физическое и
                    (или) моральное давление;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;д) систематическое (2 и более раза) умышленное нарушение требований соблюдения правил
                    внутреннего распорядка Университета, требований внутренних документов, распространение
                    конфиденциальной информации, относящейся к Университету или другим обучающимся, известной
                    ему;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;е ) выезд за границу без письменного согласия Университета во время обучения;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;ж) пропуск в течение одного семестра 36 учебных часов или не предоставление в Университет в
                    установленный срок оригинала документа об образовании;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;з) заявление о переводе на обучение из другого высшего учебного заведения, в том числе
                    иностранного высшего учебного заведения, использование поддельных документов для поступления
                    в Университет или ложь в документах, связанных с переводом на обучение, и наличие
                    недостоверных сведений;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;и) в иных случаях, предусмотренных внутренними документами Университета ;
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;к) если он признан виновным в нарушении иных норм, предусмотренных действующим
                    законодательством Республики Узбекистан.
                    <br>

                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.7. Внесение изменений в утверждённый график занятий без снижения количества и качества
                    предоставляемых образовательных услуг, в соответствии с действующим законодательством
                    Республики Узбекистан и в зависимости от форс- мажорных обстоятельств, без уменьшения
                    стоимости образовательных услуг, указанных в условиях настоящего договора принятие решения о
                    переводе режима обучения на дистанционную форму.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.8. Изменение размера оплаты договора при изменении минимального размера
                    вознаграждения за работу или суммы базового расчёта.
                    <br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.9. Продление сроков оплаты контракта.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.1.10. В соответствии с действующим законодательством Республики Узбекистан и в
                    зависимости от форс- мажорных обстоятельств принятие решения о переводе режима обучения на
                    дистанционную форму без уменьшения стоимости образовательных услуг, указанных в условиях
                    настоящего договора.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2. Обязанности Университета: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.1. Осуществлять обучение обучающегося в соответствии с государственными
                    образовательными стандартами, требованиями законодательства в области образования,
                    образовательными программами и условиями настоящего договора.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.2. Обеспечение прав студента, установленных законом.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.3. Организация учебного процесса с привлечением высококвалифицированных профессоров
                    и преподавателей.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.4. Отправка электронных счетов в течение учебного года.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5. Университет не обязуется:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.1. Стипендия и финансовая поддержка студента;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.2. Ответственность за жизнь , здоровье и личное имущество обучающегося ;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.2.5.3. Ответственность по взаимным обязательствам обучающегося и заказчика.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3. Права заказчика: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.1. Требовать от Университета и Студента выполнения своих договорных обязательств.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.2. Контроль обучения студента в соответствии с учебным планом и программами
                    Университета.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.3. Запрашивать и получать информацию об освоении студентами предметов из Университета
                    в установленном порядке. <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.4. Требовать от Университета предоставления Студенту качественного образования.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.3.5. Внесение предложений по совершенствованию образовательного процесса вуза.<br>
                </div>
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4. Обязанности заказчика:                <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.1. Оплата контрактного вознаграждения в сроки, указанные в настоящем договоре.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.2. Требовать от Студента строгого соблюдения Устава и внутренних процедур Университета и
                    получения образования в соответствии с учебным планом и программами;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.4.3. Своевременная оплата пропорционально сумме договорной оплаты соответственно при
                    изменении минимального размера оплаты труда или суммы базового расчета.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5. Права студента :<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.1. Требовать от Университета выполнения своих договорных обязательств.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.2. Образование на уровне требований Государственного стандарта в соответствии с учебными
                    планами и программами, утвержденными вузом.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.3. Использование материально-технической базы университета, включая лабораторное
                    оборудование, инструменты , информационно-ресурсный центр и зону Wi-Fi.
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.4. Внесение предложений по совершенствованию образовательного процесса университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.5. Оплата контрактного вознаграждения в соответствии с условиями контракта.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.6. Развитие и совершенствование знаний и навыков, использование всех возможностей,
                    предлагаемых Университетом, а также активное участие в жизни общества в свободное от занятий и
                    учёбы время.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.7. Взятие академического отпуска сроком до 1 (одного) года с разрешения Университета в
                    следующих случаях: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;а) при значительном ухудшении состояния здоровья, подтверждённом документами лечащих
                    врачей медицинских учреждений, входящих в государственную систему здравоохранения;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;б) в случаях, связанных с беременностью и родами, а также отпуском по уходу за ребёнком до
                    достижения ребёнком двухлетнего возраста;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;в) предоставление академического отпуска в связи со смертью близкого родственника, в этом
                    случае руководство Университета рассмотрит каждый случай отдельно и примет решение;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;ж) в связи с мобилизацией на военную службу;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;г) в иных случаях по решению руководства Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.5.8. После успешного прохождения всех этапов обучения, получение диплома о высшем
                    образовании Университета, который является документом о высшем образовании в Республике
                    Узбекистан.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6. Обязанности студентов<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.1. В случае заключения этого договора между Университетом и Студентом (без Заказчика)
                    принятие на себя всех обязательств по оплате.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.2. Получать образование в соответствии с требованиями, предъявляемыми к студентам
                    высших учебных заведений, установленными законодательством Республики Узбекистан, а также
                    нормативными правовыми документами, регулирующими образовательный процесс и деятельность
                    Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.3. Подача всех необходимых документов в соответствии с внутренними документами
                    Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.4. Строгое соблюдение требований внутреннего распорядка Университета, входа и выхода в
                    Университет, правил личной и пожарной безопасности.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.5. Не совершать действий и поступков, противоречащих законодательству Республики
                    Узбекистан.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.6. Разумное использование технических и других средств обучения, а также оборудования/
                    оборудования и иного имущества Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.7. Уведомление Университета об изменении паспортных данных, адреса проживания и номера
                    телефона в течение пяти дней со дня их изменения.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.8. Выезд за территорию Республики Узбекистан на основании письменного разрешения
                    Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.9. Постоянный контроль за уровнем освоения знаний, процентом посещаемости предметов/
                    уроков, выполнением финансовых обязательств перед Университетом.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;2.6.10. Оплата сервисных сборов при получении студентом дополнительных услуг, не
                    предусмотренных настоящим договором. Своевременная уплата такого штрафа(ов) в случаях, когда
                    штраф предусмотрен за нарушение требований внутренних документов Университета.<br>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 8px">

                <div>
                    <b>III. СТОИМОСТЬ ОБРАЗОВАТЕЛЬНЫХ УСЛУГ, СРОКИ И ПОРЯДОК ИХ ОПЛАТЫ</b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.1. 2024-2025 за учебный год Стоимость договора составляет <b>
                        <?=number_format($summa,0,'',' ').' ('. Contract::numRuStr($summa) .')'?>
                    </b> сум.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.2. Университет оставляет за собой право изменять стоимость Услуг. В этом случае заключается
                    дополнительное соглашение и Стороны обязуются производить взаиморасчеты с учетом новой
                    стоимости.<br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 10px">
                <div>
                    &nbsp;&nbsp;3.3. Оплата за обучение производится в следующем порядке:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.3.1. <b>До 15 сентября 2024 года — не менее 25 процентов.</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.3.2. <b>К 15 декабря 2024 года — не менее 50 процентов.</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.3.3. <b>До 15 февраля 2025 года — не менее 75 процентов.</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.3.4. <b>До 15 апреля 2025 года – сумма неоплаченной части оплаты обучения, предусмотренной
                        пунктом 3.1.</b><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.4. При оплате клиентом платы за договор в платежном поручении полностью указываются
                    номер и дата заключения договора, фамилия, имя ,отчество. а также курс обучения. Днем оплаты
                    считается день поступления денежных средств на банковский счет Университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.5. В случае перевода студента на следующий курс (семестр) при условии повторной сдачи им
                    академической задолженности по соответствующим предметам, договорная оплата за следующий семестр будет производиться студентом до момента сдачи академической задолженности в течение
                    установленного срока.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.6. Тот факт, что обучающийся не выучил уроки в течение срока действия настоящего
                    Соглашения, нарушил правила внутреннего распорядка, правила этикета или учебного процесса, и в
                    отношении него была принята мера по прекращению учебы или отчислению из состава студентов, не
                    освобождает от финансовых обязательств по оплате стоимости обучения.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;3.7. В случае отчисления обучающегося из состава студентов вследствие его действий
                    (бездействия) по инициативе Университета, уплаченные по договору средства не возвращаются.<br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 10px 0px">
                <div style="text-align: center">
                    <b>
                        IV. ПОРЯДОК ИЗМЕНЕНИЯ И РАСТОРЖЕНИЯ ДОГОВОРА
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;4.1. Условия настоящего Соглашения могут быть изменены по соглашению Сторон или в
                    соответствии с законодательством Республики Узбекистан. Все изменения и дополнения к
                    Соглашению действительны, если они совершены в письменной форме и подписаны Сторонами или
                    их уполномоченными представителями.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2. Настоящее Соглашение может быть расторгнуто в следующих случаях: <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.1. По соглашению сторон;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.2. в одностороннем порядке по инициативе университета ( в случаях, предусмотренных п.
                    2.1.6 );<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.3. на основании юридически обязательного решения суда;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.4. в связи с истечением срока действия договора;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.5. В связи с успешным завершением обучения студента;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.2.6. В случае прекращения деятельности университета.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;4.3. В случае одностороннего расторжения договора по инициативе Университета на
                    юридический адрес или адрес электронной почты Заказчика направляется соответствующее
                    сообщение, о чем Заказчик уведомляется.<br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <div style="text-align: center">
                    <b>
                        V. ФОРС-Мажор
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;5.1. Обстоятельства, делающие невозможным полное или частичное исполнение договора одной
                    из сторон, в частности, пожар, стихийное бедствие, война, любые военные действия, замена
                    действующих правовых документов и иные возможные форс-мажорные обстоятельства , не
                    зависящие от сторон случаи, сроки исполнения обязательств по договору будут продлены в
                    соответствии со сроком действия этих случаев.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;5.2. Сторона, неспособная исполнить свои обязательства по настоящему договору, должна
                    немедленно уведомить другую сторону о наступлении или отмене обстоятельств, препятствующих
                    исполнению ею этих обстоятельств.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;Уведомление будет отправлено на юридический адрес, указанный в договоре и подтвержденный
                    почтовым отделением-отправителем.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;5.3. Если вследствие какого-либо события, стихийного бедствия, войны или чрезвычайного
                    положения в стране решение, принятое органом государственной власти, его исполнение, действия,
                    предпринятые в связи с ним (не ограничиваясь этими событиями), произошли вне контроля сторон В
                    случае возникновения договора одна сторона не несет ответственности перед другой стороной за
                    последствия неисполнения или просрочки исполнения настоящего договора. Сторона, исполнение
                    которой приостановлено таким образом, имеет право на соответствующее продление срока
                    исполнения таких обязательств.
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VI. ОТВЕТСТВЕННОСТЬ СТОРОН
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;6.1. Студент принимает на себя полную материальную ответственность перед Университетом в
                    случае причинения материального ущерба имуществу, оборудованию, учебным материалам и т.п.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2. В случае несвоевременной оплаты обучения в соответствии с пунктом 3.3 настоящего
                    договора:<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2.1. Доступ студента в Университет ограничен;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- Предоставление академических услуг Университетом;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- допуск студентов к экзаменам;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- предоставление любых академических справок/сертификатов;<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.2.3. Студент 2.1. В соответствии с пунктом 6 студенты могут быть отчислены.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.3. Университет не несет ответственности за источники оплаты контракта.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.4. Университет не несет ответственности за ошибки, допущенные при оформлении оплаты
                    контракта.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.5. Отчисление Обучающегося или исключение из рядов Обучающихся не освобождает
                    Заказчика и Обучающегося от уплаты платы за оказанные Обучающемуся образовательные услуги и иных обязательств по настоящему договору.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;6.6. Меры ответственности сторон, не предусмотренные настоящим Соглашением, определяются
                    действующим законодательством Республики Узбекистан.<br>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VII. ДОПОЛНИТЕЛЬНЫЕ УСЛОВИЯ
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;7.1. Приказ Университета о зачислении Студента выдается при условии предоставления
                    Студентом всех необходимых документов и внесения оплаты в соответствии с пунктом 3.3.1
                    договора.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.2. Университет не выплачивает стипендию студенту.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.3. Обязательства, предусмотренные пунктом 1 настоящего Соглашения, могут быть
                    предоставлены непосредственно или в режиме онлайн в соответствии с требованиями действующего
                    законодательства Республики Узбекистан. В случае оказания услуг академического образования в
                    режиме онлайн Обучающийся несет личную ответственность за качество состояния технических и
                    телекоммуникационных коммуникаций.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.4. Настоящее Соглашение заключается сроком на один учебный год до полного исполнения
                    Сторонами своих обязательств для целей, указанных в его предмете, но не позднее 1 июля 2025 года.
                    Истечение срока действия договора не освобождает сторон-должников от исполнения своих
                    обязательств.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.5. Оригиналы официальных документов, в том числе документов об окончании (аттестата/
                    диплома/аттестата) среднего или среднего специального учебного заведения, Студенту или иному
                    лицу в период обучения не выдаются.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.6. Университет не берет на себя никаких обязательств по трудоустройству Студента.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.7. Комиссии по контракту и все банковские сборы, связанные с их возвратом, оплачиваются
                    Заказчиком или Студентом.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.8. В случаях, когда денежные средства подлежат возврату Университетом по настоящему
                    договору, эти средства подлежат возврату в течение 30 (тридцати) дней со дня вступления в силу
                    соответствующего документа.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.9. Любые изменения и/или дополнения к настоящему Соглашению имеют силу только в том
                    случае, если они составлены в письменной форме и подписаны/запечатаны сторонами.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.10. Стороны соглашаются признать факсимиле Университета соответствующей подписью в
                    договоре.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;7.11. Любые споры и разногласия, возникающие из настоящего Соглашения, подлежат
                    разрешению путем переговоров между сторонами. В случае недостижения соглашения споры будут
                    рассматриваться судом по месту нахождения Университета в порядке, установленном
                    законодательством Республики Узбекистан.<br>

                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                <div style="text-align: center">
                    <b>
                        VIII. ЗАКЛЮЧИТЕЛЬНЫЕ ПРАВИЛА
                    </b>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div style="text-align: center">
                    &nbsp;&nbsp;&nbsp;&nbsp;8.1. Настоящее соглашение вступает в силу с момента его подписания Сторонами.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.2. В случае неудовлетворения Заказчика и Студента условиями договора они должны подать
                    заявку не позднее 30 ноября 2024 года, при этом Студент, не заключивший к этой дате договор с
                    Университетом, не будет принят к обучению.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.3. В соответствии с соответствующим постановлением Президента Республики Узбекистан при
                    изменении минимального размера оплаты труда или базовой расчетной суммы размер контрактной
                    оплаты увеличивается с начала следующего семестра.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.4. Переписка между Сторонами по вопросам подписания, изменения, исполнения, расторжения
                    настоящего соглашения может осуществляться через официальные адреса электронной почты
                    Сторон, указанные в соглашении, при этом Стороны признают, что сообщения, направляемые в
                    настоящем порядке, имеют юридическую силу. Сторона, не уведомившая другую сторону в
                    письменном виде об изменении адреса электронной почты, несет все связанные с этим риски.<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;8.5. Настоящее Соглашение составлено на узбекском и на русском языках в трех экземплярах,
                    имеющих одинаковую юридическую силу, по одному экземпляру для каждой стороны.<br>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;padding: 10px">
                <div style="text-align: center">
                    <b>
                        IX. ЮРИДИЧЕСКИЕ АДРЕСА И БАНКОВСКИЕ РЕКВИЗИТЫ СТОРОН
                    </b>
                </div>
            </td>
        </tr>
    </table>
    <table style="width: 100%; border-collapse: collapse">
        <tr>
            <td style="width: 50%">
                <div><b>Высшее учебное заведение «GLOBAL SOFTLINE»</b></div>

                <p>
                <div><b>Адрес: город Ташкент, Юнусабадский район,  <br> Посира МСГ, ул. Богишамол, дом 220</b></div>
                </p>
                <p>
                <div><b>Р/С:   2020 8000 0055 1905 4002</b></div>
                </p>
                <p>
                <div><b>Банк: Сергелийский филиал АКБ «KAPITALBANK»</b></div>
                </p>
                <p>
                <div><b>МФО:  01042  </b></div>
                </p>
                <p>
                <div><b>ОКЭД:  85420</b></div>
                </p>
                <p>
                <div><b>ИНН: 309477784</b></div>
                </p>
                <p>
                <div><b>Тел: +998 77 129-29-29</b></div>
                </p>
            </td>
            <td style="width: 50%">
                <div><b>СТУДЕНТ: <?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?></b></div>
                <p>
                <div><b>Паспортные данные: <?= $student->passport_serial.' '.$student->passport_number ?></b></div>
                <div><b>ПИНФЛ: <?= $student->passport_pin ?></b></div>
                </p>
                <p>
                <div><b>
                        <div><b>Тел: <?= Yii::$app->user->identity->username ?></b></div>
                    </b></div>
                </p>
                <p>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px">
                M.П.
            </td>
            <td style="padding: 10px">
                <?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px">
                Ректор_______________ Аскаралиев О.У.
            </td>
            <td style="padding: 10px">
                Студент:_________________
            </td>
        </tr>
    </table>
    <table style="text-align:center; width: 90%;border-collapse: collapse;" border="">
        <tr>
            <td style="width: 50%; text-align: center;"><img src="<?= $img ?>" width="100px"></td>
            <td style="width: 50%; text-align: center;">
                <img src="<?= $limg ?>" width="100px">
                <br>
                <b>Дата и номер выдачи лицензии</b><br>19.10.2022 &nbsp;&nbsp;№ 043951
            </td>
        </tr>
    </table>

    <div style="page-break-before:always">&nbsp;</div>
    <div>
        <div style="border: 2px solid black; padding: 10px;">
            <b>Дата:<?= $date;?></b>
            <div style=" width: 100%;">
                <b>НОМЕР ИНВОЙСА: <?= $con2 ?></b>
            </div>
            <div>
                <b>СУММА ОПЛАТЫ КОНТРАКТА: <?= number_format((int)$summa, 0, '', ' ') . ' (' . Contract::numRuStr($summa) . ')' ?>
                    сум
                </b><br>
            </div>
            <div>
                Способы оплаты: <br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Через юридические.лица и банковские кассы. В целях
                оплаты необходимо указать номер инвойса, ПИНФЛ, Ф.И.О. студента.<br>
            </div>
            <br>
            <div style="border:2px solid red; padding: 10px">
                <span style="padding: 10px"><?= $con2 ?> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= $student->passport_pin ?? '' ?> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <?= $student->first_name . ' ' . $student->last_name . ' ' . $student->middle_name ?></span>
            </div>
            <br>
            <div style="padding: 10px;color:red;">
                <small>Если назначение платежа не заполнено в установленном порядке, могут возникнуть
                    проблемы с приемом платежа. По этой причине назначение платежа необходимо указывать в
                    процессе оплаты!
                </small>
            </div>
            <br>
            <div>
                <small>= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
                    = = = = </small>
            </div>
            <br>
            <div style="color:#17406C">
                <b>Реквизиты банковского счета Университета для осуществления платежей:</b>
            </div>
            <br>
            <div>
                <table style="width:100%; border-collapse: collapse;" border="1">
                    <tr>
                        <td><b>Наименование принимающей организации: </b></td>
                        <td><b> OOO «GLOBAL SOFTLINE»</b></td>
                    </tr>
                    <tr>
                        <td><b>Расчётный счёт: </b></td>
                        <td><b>2020 8000 0055 1905 4002</b></td>
                    </tr>
                    <tr>
                        <td><b>МФО:</b></td>
                        <td><b>01042 </b></td>
                    </tr>
                    <tr>
                        <td><b>Наименование банка: </b></td>
                        <td><b>Сергелийский филиал АКБ «KAPITALBANK» </b></td>
                    </tr>
                    <tr>
                        <td><b>ИНН:</b></td>
                        <td><b>309477784 </b></td>
                    </tr>
                    <tr>
                        <td><b>ОКЭД:</b></td>
                        <td><b>85420 </b></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php endif; ?>