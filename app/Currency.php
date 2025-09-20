<?php

namespace App;

enum Currency: string
{
    case QAR = 'QAR';
    case USD = 'USD';
    case EUR = 'EUR';
    // case GBP = 'GBP';
    case INR = 'INR';
    // case AED = 'AED';
    case SAR = 'SAR';
    // case KWD = 'KWD';
    // case BHD = 'BHD';
    // case OMR = 'OMR';
    // case JOD = 'JOD';
    // case LBP = 'LBP';
    // case EGP = 'EGP';
    // case PKR = 'PKR';
    // case BDT = 'BDT';
    // case LKR = 'LKR';
    // case NPR = 'NPR';
    // case AFN = 'AFN';
    // case IRR = 'IRR';
    // case IQD = 'IQD';
    // case SYP = 'SYP';
    // case YER = 'YER';
    // case TRY = 'TRY';

    public function getSymbol(): string
    {
        return match($this) {
            self::QAR => '﷼',
            self::USD => '$',
            self::EUR => '€',
            // self::GBP => '£',
            self::INR => '₹',
            // self::AED => 'د.إ',
            self::SAR => '﷼',
            // self::KWD => 'د.ك',
            // self::BHD => 'د.ب',
            // self::OMR => 'ر.ع.',
            // self::JOD => 'د.أ',
            // self::LBP => 'ل.ل',
            // self::EGP => '£',
            // self::PKR => '₨',
            // self::BDT => '৳',
            // self::LKR => '₨',
            // self::NPR => '₨',
            // self::AFN => '؋',
            // self::IRR => '﷼',
            // self::IQD => 'د.ع',
            // self::SYP => '£',
            // self::YER => '﷼',
            // self::TRY => '₺',
        };
    }

    public function getLabel(): string
    {
        return match($this) {
            self::QAR => 'Qatari Riyal',
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            // self::GBP => 'British Pound',
            self::INR => 'Indian Rupee',
            // self::AED => 'UAE Dirham',
            self::SAR => 'Saudi Riyal',
            // self::KWD => 'Kuwaiti Dinar',
            // self::BHD => 'Bahraini Dinar',
            // self::OMR => 'Omani Rial',
            // self::JOD => 'Jordanian Dinar',
            // self::LBP => 'Lebanese Pound',
            // self::EGP => 'Egyptian Pound',
            // self::PKR => 'Pakistani Rupee',
            // self::BDT => 'Bangladeshi Taka',
            // self::LKR => 'Sri Lankan Rupee',
            // self::NPR => 'Nepalese Rupee',
            // self::AFN => 'Afghan Afghani',
            // self::IRR => 'Iranian Rial',
            // self::IQD => 'Iraqi Dinar',
            // self::SYP => 'Syrian Pound',
            // self::YER => 'Yemeni Rial',
            // self::TRY => 'Turkish Lira',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($currency) => [$currency->value => $currency->getLabel()])
            ->toArray();
    }
}
