<?php

namespace Database\Seeders;

use App\Models\Dua;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DuaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (test user) to assign duas to
        $user = \App\Models\User::whereEmail("ammarabdulaziz99@gmail.com")->first();

        $duas = [
            [
                'user_id' => $user->id,
                'title' => 'Seeking Refuge in Allah\'s Perfect Words',
                'arabic_text' => 'أَعُوذُ بِكَلِمَاتِ اللَّهِ التَّامَّاتِ مِنْ شَرِّ مَا خَلَقَ',
                'transliteration' => 'A\'udhu bikalimaatillahi at-taammaati min sharri ma khalaq',
                'english_translation' => 'I seek refuge in the perfect words of Allah from the evil of what He created.',
                'english_meaning' => 'This dua seeks protection from all forms of evil through Allah\'s perfect words.',
                'categories' => ['Protection', 'Daily Prayers'],
                'source' => 'Sahih Muslim',
                'reference' => 'Muslim 2708',
                'benefits' => 'Shields from harm caused by creation, jinn, and evil beings.',
                'recitation_count' => 1,
                'sort_order' => 1,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Asking for Knowledge, Provision, and Accepted Deeds',
                'arabic_text' => 'اللَّهُمَّ إِنِّي أَسْأَلُكَ عِلْمًا نَافِعًا، وَرِزْقًا طَيِّبًا، وَعَمَلًا مُتَقَبَّلًا',
                'transliteration' => 'Allahumma inni as\'aluka \'ilman naafi\'an, wa rizqan tayyiban, wa \'amalan mutaqabbalan',
                'english_translation' => 'O Allah, I ask You for beneficial knowledge, good provision, and accepted deeds.',
                'english_meaning' => 'A supplication for knowledge that benefits, lawful sustenance, and deeds accepted by Allah.',
                'categories' => ['Knowledge', 'Daily Prayers'],
                'source' => 'Sunan Ibn Majah',
                'reference' => 'Ibn Majah 925',
                'benefits' => 'Encourages seeking lawful provision and actions that are accepted by Allah.',
                'recitation_count' => 1,
                'sort_order' => 2,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Asking for Help in Worship',
                'arabic_text' => 'اللَّهُمَّ أَعِنِّي عَلَى ذِكْرِكَ وَشُكْرِكَ وَحُسْنِ عِبَادَتِكَ',
                'transliteration' => 'Allahumma a\'inni \'ala dhikrika wa shukrika wa husni \'ibaadatik',
                'english_translation' => 'O Allah, help me to remember You, thank You, and worship You well.',
                'english_meaning' => 'This dua asks Allah for support in remembering Him, being grateful, and perfecting worship.',
                'categories' => ['Gratitude', 'Worship'],
                'source' => 'Sunan Abu Dawood',
                'reference' => 'Abu Dawood 1522',
                'benefits' => 'Strengthens worship and mindfulness of Allah.',
                'recitation_count' => 1,
                'sort_order' => 3,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Asking Forgiveness and Purity',
                'arabic_text' => 'اللَّهُمَّ اغْفِرْ ذَنْبِي، وَطَهِّرْ قَلْبِي، وَقِنِي شَرَّ نَفْسِي، وَحَصِّنْ فَرْجِي',
                'transliteration' => 'Allahummaghfir dhanbi, wa tahhir qalbi, wa qini sharri nafsi, wa hassin farji',
                'english_translation' => 'O Allah, forgive my sin, purify my heart, protect me from my wicked self, and safeguard my chastity.',
                'english_meaning' => 'A comprehensive dua asking for forgiveness, purity, self-control, and chastity.',
                'categories' => ['Forgiveness', 'Protection'],
                'source' => 'Jami at-Tirmidhi',
                'reference' => 'Tirmidhi 3484',
                'benefits' => 'Helps cleanse sins and promotes moral purity.',
                'recitation_count' => 1,
                'sort_order' => 4,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Seeking Refuge from Affliction and Enemies',
                'arabic_text' => 'اللَّهُمَّ إِنِّي أَعُوذُ بِكَ مِنْ جَهْدِ الْبَلَاءِ، وَدَرَكِ الشَّقَاءِ، وَسُوءِ الْقَضَاءِ، وَشَمَاتَةِ الْأَعْدَاءِ',
                'transliteration' => 'Allahumma inni a\'udhu bika min jahdil-balaa\', wa darkish-shaqaa\', wa soo\'il-qadaa\', wa shamaatatil-a\'daa\'',
                'english_translation' => 'O Allah, I seek refuge in You from the hardship of affliction, from reaching misery, from an evil fate, and from the gloating of enemies.',
                'english_meaning' => 'This dua is a shield against trials, misfortune, and harm from enemies.',
                'categories' => ['Protection', 'Health & Healing'],
                'source' => 'Sahih Bukhari',
                'reference' => 'Bukhari 6616',
                'benefits' => 'Protects from hardships, bad destiny, and enemies\' harm.',
                'recitation_count' => 1,
                'sort_order' => 5,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Asking Allah for Paradise and Protection from Hellfire',
                'arabic_text' => 'اللَّهُمَّ إِنِّي أَسْأَلُكَ الْجَنَّةَ، وَمَا قَرَّبَ إِلَيْهَا مِنْ قَوْلٍ أَوْ عَمَلٍ، وَأَعُوذُ بِكَ مِنَ النَّارِ، وَمَا قَرَّبَ إِلَيْهَا مِنْ قَوْلٍ أَوْ عَمَلٍ',
                'transliteration' => 'Allahumma inni as\'aluka al-jannah wa maa qarraba ilayha min qawlin aw \'amal, wa a\'udhu bika min an-naar wa maa qarraba ilayha min qawlin aw \'amal',
                'english_translation' => 'O Allah, I ask You for Paradise and for whatever brings one closer to it in word or deed. And I seek refuge in You from the Fire and from whatever brings one closer to it in word or deed.',
                'english_meaning' => 'This dua combines the ultimate request for Paradise and protection from Hell.',
                'categories' => ['Paradise', 'Protection'],
                'source' => 'Sunan an-Nasa\'i',
                'reference' => 'Nasa\'i 5530',
                'benefits' => 'Focuses the believer on actions leading to Jannah and away from Hellfire.',
                'recitation_count' => 1,
                'sort_order' => 6,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Dua of Prophet Musa (Moses) - Acknowledging Need',
                'arabic_text' => 'رَبِّ إِنِّي لِمَا أَنْزَلْتَ إِلَيَّ مِنْ خَيْرٍ فَقِيرٌ',
                'transliteration' => 'Rabbi inni limaa anzalta ilayya min khairin faqeer',
                'english_translation' => 'My Lord, indeed I am, for whatever good You send down to me, in need.',
                'english_meaning' => 'A humble admission of one\'s dependence on Allah\'s blessings.',
                'categories' => ['Quran Verses', 'When in Need'],
                'source' => 'Quran',
                'reference' => 'Surah Al-Qasas 28:24',
                'benefits' => 'Instills humility and reliance on Allah\'s provision.',
                'recitation_count' => 1,
                'sort_order' => 7,
            ],
            [
                'user_id' => $user->id,
                'title' => 'Dua for Marketplace',
                'arabic_text' => 'بِسْمِ اللَّهِ، اللَّهُمَّ إِنِّي أَسْأَلُكَ خَيْرَ هَذِهِ السُّوقِ وَخَيْرَ مَا فِيهَا، وَأَعُوذُ بِكَ مِنْ شَرِّهَا وَشَرِّ مَا فِيهَا، اللَّهُمَّ إِنِّي أَعُوذُ بِكَ أَنْ أُصِيبَ فِيهَا صَفْقَةً خَاسِرَةً',
                'transliteration' => 'Bismillahi, Allahumma inni as\'aluka khayra hadhihis-suq wa khayra ma fiha, wa a\'udhu bika min sharriha wa sharri ma fiha, Allahumma inni a\'udhu bika an useeba fiha safqatan khasirah',
                'english_translation' => 'In the name of Allah. O Allah, I ask You for the good of this marketplace and the good that is in it, and I seek refuge in You from its evil and the evil that is in it. O Allah, I seek refuge in You from making a loss in it.',
                'english_meaning' => 'This dua is recited upon entering a marketplace to ask for blessings and protection from loss.',
                'categories' => ['Daily Prayers', 'Special Occasions'],
                'source' => 'Hisnul Muslim',
                'reference' => 'Hisnul Muslim 94',
                'benefits' => 'Seeks Allah\'s protection from loss and unlawful gains in trade.',
                'recitation_count' => 1,
                'sort_order' => 8,
            ],
        ];

        foreach ($duas as $dua) {
            Dua::create($dua);
        }
    }
}
