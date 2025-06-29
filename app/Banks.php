<?php

namespace App;

enum Banks: string
{
    case BCA = 'Bank Central Asia (BCA)';
    case MANDIRI = 'Bank Mandiri';
    case BRI = 'Bank Rakyat Indonesia (BRI)';
    case BNI = 'Bank Negara Indonesia (BNI)';
    case BTN = 'Bank Tabungan Negara (BTN)';
    case CIMB = 'Bank CIMB Niaga';
    case DANAMON = 'Bank Danamon';
    case PERMATA = 'Bank Permata';
    case BSI = 'Bank Syariah Indonesia (BSI)';
    case OCBC = 'Bank OCBC NISP';
    case PANIN = 'Panin Bank';
    case UOB = 'Bank UOB Indonesia';
    case HSBC = 'HSBC Indonesia';
    case DBS = 'Bank DBS Indonesia';
    case BTPN = 'Bank BTPN';
    case MEGA = 'Bank Mega';
    case MAYBANK = 'Maybank Indonesia';
    case JABAR_BANTEN = 'Bank Pembangunan Daerah Jawa Barat dan Banten (BJB)';
    case DKI = 'Bank DKI';
    case JATENG = 'Bank Jateng';
    case JATIM = 'Bank Jatim';
    case SUMUT = 'Bank Sumut';
    case NAGARI = 'Bank Nagari';
    case RIAU_KEPRI = 'Bank Riau Kepri';
    case KALBAR = 'Bank Kalbar';
    case KALSEL = 'Bank Kalsel';
    case SULSELBAR = 'Bank Sulselbar';
    case BALI = 'Bank BPD Bali';
    case PAPUA = 'Bank Papua';
    case STANDARD_CHARTERED = 'Standard Chartered Bank';
    case COMMONWEALTH = 'Bank Commonwealth';
    case MUAMALAT = 'Bank Muamalat Indonesia';
    case LAINNYA = 'Lainnya';


    /**
     * Mengembalikan array yang diformat untuk opsi Select di Filament.
     * key => value
     */
    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->value;
        }
        return $array;
    }
}
