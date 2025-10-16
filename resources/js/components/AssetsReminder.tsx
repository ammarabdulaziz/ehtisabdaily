import { Alert, AlertDescription } from "@/components/ui/alert"

export default function AssetsReminder() {
  return (
    <Alert className="bg-amber-50/70 dark:bg-amber-900/10 border-amber-200 dark:border-amber-800">
      <AlertDescription>
        <div className="space-y-2">
          <p className="text-xs uppercase tracking-wide text-amber-700 dark:text-amber-400">Reminder</p>
          <div className="grid gap-3 sm:grid-cols-2">
            <div className="space-y-1">
              {/* <p className="text-sm font-medium">18:39-40</p> */}
              <p className="text-xl font-arabic pt-5 pb-3" style={{ fontFamily: 'Amiri, Scheherazade New, Traditional Arabic, Arabic Typesetting, serif' }}>
                وَلَوْلَا إِذْ دَخَلْتَ جَنَّتَكَ قُلْتَ <b>مَا شَاءَ ٱللَّهُ لَا قُوَّةَ إِلَّا بِٱللَّهِ</b>
              </p>
              <p className="text-sm text-muted-foreground">If only, when you entered your garden, you had said, “What Allah wills; there is no power except with Allah.”</p>
              <p className="text-sm text-muted-foreground">നിങ്ങൾ നിങ്ങളുടെ താഴ്വരയിൽ പ്രവേശിക്കുമ്പോൾ പറഞ്ഞിരുന്നെങ്കിൽ യാഥാർത്ഥ്യത്തിൽ അല്ലാഹുവിന്റെ ഇഷ്ടം മാത്രമാണ് ഉണ്ടായിരിക്കുന്നതെന്നും അല്ലാഹുവിനെ ഒഴികെ യാതൊരു ശക്തിയും ഇല്ലെന്നും.</p>
            </div>
            <div className="space-y-1">
              {/* <p className="text-sm font-medium">18:46</p> */}
              <p className="text-xl font-arabic pt-5 pb-3" style={{ fontFamily: 'Amiri, Scheherazade New, Traditional Arabic, Arabic Typesetting, serif' }}>الْمَالُ وَالْبَنُونَ زِينَةُ الْحَيَاةِ الدُّنْيَا وَالْبَاقِيَاتُ الصَّالِحَاتُ خَيْرٌ عِندَ رَبِّكَ ثَوَابًا وَخَيْرٌ أَمَلًا</p>
              <p className="text-sm text-muted-foreground">Wealth and children are the adornment of worldly life, but lasting righteous deeds are better with your Lord for reward and for hope.</p>
              <p className="text-sm text-muted-foreground">സമ്പത്തും സന്താനങ്ങളും ഈ ലോകജീവിതത്തിലെ അലങ്കാരങ്ങളായിരിക്കും. പക്ഷെ നിനക്ക് പരമാധികാരിയുമായുള്ള നല്ല സൽകാര്യങ്ങൾ ഹിതകരമായ പ്രതിഫലവും ദീര്‍ഘകാല പ്രതീക്ഷയും ഉണ്ടായിരിക്കും.</p>
            </div>
          </div>
          
          {/* Barakah in Wealth Dua */}
          <div className="mt-6 p-4 bg-green-50/70 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div className="space-y-1">
              <p className="text-xl font-arabic pt-5 pb-3" style={{ fontFamily: 'Amiri, Scheherazade New, Traditional Arabic, Arabic Typesetting, serif' }}>
                اللَّهُمَّ بَارِكْ لَنَا فِيمَا رَزَقْتَنَا وَقِنَا عَذَابَ النَّارِ
              </p>
              <p className="text-sm text-muted-foreground">O Allah, bless us in what You have provided us and protect us from the punishment of the Fire</p>
              <p className="text-sm text-muted-foreground">അല്ലാഹുവേ, നീ ഞങ്ങൾക്ക് നൽകിയതിൽ ബറക്കത്ത് നൽകുകയും നരകാഗ്നിയിൽ നിന്ന് ഞങ്ങളെ സംരക്ഷിക്കുകയും ചെയ്യേണമേ</p>
            </div>
          </div>
        </div>
      </AlertDescription>
    </Alert>
  )
}


