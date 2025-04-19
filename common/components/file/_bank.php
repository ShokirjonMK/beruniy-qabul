<?php

use common\models\Student;
use common\models\Branch;
use common\models\Consulting;

/** @var Student $student */
/** @var Branch $filial */
/** @var Consulting $cons */
/** @var $full_name */

?>




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
                    <td colspan="2" style="vertical-align: top">
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
                    <td colspan="2" style="vertical-align: top">
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