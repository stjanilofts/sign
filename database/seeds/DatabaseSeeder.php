<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;


function getImages($number) {
    $images = [];

    $dir = scandir(public_path('tmp'));

    $repository = [];

    foreach($dir as $k => $v) {
        if($v != '.' && $v != '..') {
            $arr = explode('.', $v);
            $extension = end($arr);
            $name = $arr[0];

            if(is_numeric($name)) {
                $repository[] = $v;
            }
        }
    }

    for($i = 1; $i < $number; $i++) {
        $repoCount = count($repository);

        $rand = (mt_rand(1, $repoCount) - 1);

        $filename = $repository[$rand];

        $file = public_path('tmp/'.$filename);

        $images[] = [
            'name' => $filename,
            'title' => $filename
        ];
    }

    return $images;
}


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Notendur
        \App\User::create([
            'name' => 'Netvistun',
            'email' => 'vinna@netvistun.is',
            'password' => bcrypt(env('NETVISTUN')),
            'remember_token' => str_random(10),
        ]);


        
        
        function imgs() {
            $_images = [];
            for($i = 1; $i <= 16; $i++) {
                $_images[] = [
                    'name' => 'm'.$i.'.jpg',
                    'title' => 'm'.$i.'.jpg',
                ];
            }
            shuffle($_images);
            return $_images;
        }



        $faker = Faker\Factory::create();

        function makePage($page = []) {
            //dd(imgs());
            $page['slug'] = isset($page['slug']) ? $page['slug'] : str_slug($page['title']);
            $page['images'] = isset($page['images']) ? $page['images'] : imgs();
            $imgs = imgs();
            $banner = $imgs[mt_rand(0, (count($imgs) - 1))];
            $page['banner'] = $banner['name'];

            return factory(\App\Page::class)->create($page);
        }

        function makeProduct($page = []) {
            $page['slug'] = isset($page['slug']) ? $page['slug'] : str_slug($page['title']);
            $page['images'] = isset($page['images']) ? $page['images'] : imgs();
            $page['tech'] = isset($page['tech']) ? $page['tech'] : '';

            return factory(\App\Product::class)->create($page);
        }

        function makeCategory($page = []) {
            $page['slug'] = isset($page['slug']) ? $page['slug'] : str_slug($page['title']);
            $page['images'] = isset($page['images']) ? $page['images'] : imgs();

            return factory(\App\Category::class)->create($page);
        }

        /*foreach($pages as $page) {
            makePage(['title' => $page]);
        }*/

        makePage([
            'title' => 'Vörur',
            'topmenu' => 1,
            'content' => '<p>Dún og fiður ehf framleiðir sjálf allar söluvörur sínar s.s sængur, kodda, púða og pullur að undanskildum utanyfirverum og lökum. Öll framleiðslan er merkt Dún og fiður og ársstimpluð þannig að sjá megi framleiðsluárið þegar komið er með vöruna í hreinsun eða endurnýjun. Við endurnýjun á framleiðsluvöru Dún- og fiðurhreinsunarinnar er vörunni gefinn nýr ársstimpill.</p>
<p>Dún og fiður framleiðir vörur sínar úr mismunandi dún og fiðri þ.e. æðardún, snjógæsadún, svanadún, andardún og gæsadún og eru gæðin í þeirri röð sem hér er talið. Þannig fer í sæng sem er með 1 kg. af æðardún, snjógæsadún eða svanadún 1.1 kg. af andardún og 1.25 kg. af gæsadún.</p>
{image:'.mt_rand(0,10).'|align:'.(mt_rand(0,1) == 0 ? 'left' : 'right ').'|size:m}
<p>Dún og fiður framleiðir æðardúnssængur eingöngu eftir pöntunum. Aðrar dúnsængur eru fyrirliggjandi á lager í stærðunum 200X220, 140X220, 140X200, 100X140 og 80X100 en öll mál eru í cm. Aðrar stærðir eru framleiddar samkvæmt óskum viðskiptavinar.</p>
<p>Koddar eru fyrirliggjandi á lager í stærðunum 60X80, 50X70, 40X50 og 35X40 cm og pullur í stærð 18X50 cm. Eins og með sængur eru koddar framleiddir í öðrum stærðum að ósk viðskiptavinar. Púðar eru einungis framleiddir samkvæmt pöntunum.</p>
<p>Hægt er að smella á allar þessar myndir til að stækka þær.</p>'
        ]);

        makePage([
            'title' => 'Hreinsun',
            'topmenu' => 1,
            'content' => '<p>Meginmarkmiðið með hreinsun á dúnsængum er að ná öllum svita og utanaðkomandi raka úr dúninum. Dúnninn lyftist upp inní sænginni og ef þetta er gert á um það bil 3 ára fresti þá lengist endingartími sængurinnar um mörg ár.</p>
{image:'.mt_rand(0,10).'|align:'.(mt_rand(0,1) == 0 ? 'left' : 'right ').'|size:m}
<p>Efnið sem notað er við hreinsunina er vistvænt og brotnar niður í náttúrunni á innan við einum sólarhring. Þetta er auðvitað undirstaða heilbrigðis og þess að þú sofir betur.</p>
<p>Við hreinsum sængur og kodda og einnig bætum við nýjum dún í anda-, svana-, snjógæsa- og æðardúnsængur.</p>
<p>Skiptum einnig um utanyfir-ver ef þess er þörf.</p>
<p>Við höfum 50 ára reynslu í faginu</p>'
        ]);

$um_okkur = makePage(['title' => 'Fyrirtækið', 'slug' => 'fyrirtaekid', 'topmenu' => 1,
            'content' => '<p>Dún og fiður er leiðandi fyrirtæki í framleiðslu, endurnýjun og hreinsun á sængum, koddum, púðum, pullum og skyldum vörum úr náttúrlegum dún og fiðri.</p>
<p>Dún og fiður byggir á um 50 ára gömlum merg, í eigu sömu fjölskyldu allan tímann. Á þessum tíma hefur safnast saman mjög mikilvæg þekking og reynsla á öllu sem lýtur að dún og fiðri, efnum því tengdu og meðhöndlun sængurfatnaðar.</p>
{image:'.mt_rand(0,10).'|size:l}
<p>Dún og fiður var stofnað 1. febrúar 1959 og var fyrst til húsa að Kirkjuteig 29 í Reykjavík, en 3. ágúst 1963 flutti fyrirtækið í eigið húsnæði að Vatnsstíg 3, Reykjavík. Dún og fiður er nú til húsa á Laugavegi 86.</p>
<p>Dún og fiður hefur með áratuga starfsemi skapað sér fastan sess í hugum borgarbúa og annarra landsmanna. Því hefur það verið stefna fyrirtækisins að breyta engu þar um né að vera með útsölur eða útibú á öðrum stöðum.</p>
        ']);

        makePage(['slug' => 'thjonusta', 'title' => 'Þjónusta', 'parent_id' => $um_okkur->id,
            'content' => '<p>Dún og fiður er með þjónustu sína á Laugavegi 86. Er verslunin og móttaka á sængurfatnaði til endurnýjunar eða þvotta opin á mánudögum til föstudaga frá kl. 10:00 til 18:00. Aðeins er opið á laugardögum milli 11:00 og 16:00. Undantekningar eru í desember mánuði.</p>
<p>Hægt er að fá sængur og kodda, sem komið er með í þvott eða endurnýjun, eftir sólarhring ef komið er með það fyrir hádegi á mánudögum til föstudags.</p>
{image:'.mt_rand(0,10).'|align:'.(mt_rand(0,1) == 0 ? 'left' : 'right ').'|size:m}
<p>Dún og fiður tekur að sér að hreinsa og endurnýja sængur og kodda óháð því hvar varan er framleidd. Vörur sem fyrirtækið endurnýjar fá nýjan ársstimpil.</p>
<p>Einfaldasta hreinsunin á sængum er þvottur og þurrkun í öflugum þurrkara. Hún er fullnægjandi ef dúnninn er óskemmdur og ver heilt og lítið slitið. Sé dúnninn hins vegar farinn að rýrna og/eða ver orðið slitið er boðið uppá að skipta um ver og bæta í dún eftir þörfum.</p>'
        ]);

        makePage([
            'slug' => 'stadsetning',
            'title' => 'Staðsetning',
            'parent_id' => $um_okkur->id,
            'content' => '<p>Dún og fiður er við Laugavegi 86.</p>
            <iframe width="100%" height="400" frameborder="0" src="http://ja.is/kort/embedded/?zoom=8&x=357994&y=407785&layer=map&q=D%C3%BAn+og+fi%C3%B0ur+ehf%2C+Laugavegi+86"></iframe>',
        ]);


        makePage([
            'slug' => 'hafa-samband',
            'title' => 'Hafa samband',
            'parent_id' => 0,
            'topmenu' => 0,
            'content' => '<center><p>Sendu okkur skilaboð og við munum vera í bandi við þig.</p></center>',
        ]);

        $pics = ['myndBjarni01.jpg', '12.jpg', '10.jpg'];
        $forsidumyndir = makePage(['title' => 'Forsíðumyndir', 'slug' => '_forsidumyndir', 'status' => 0]);
        foreach($pics as $k => $v) {
            makePage(['title' => $v, 'parent_id' => $forsidumyndir->id, 'images' => [['name'=>$v]]]);
        }

        /*$flokkur1 = makeCategory(['title' => 'Flokkur 1', 'images' => getImages(3)]);
        $flokkur2 = makeCategory(['title' => 'Flokkur 2', 'images' => getImages(3)]);*/

        $dunpoki_myndir = [];
        $dunpoki_myndir[] = [
            'name' => 'dunpoki-raudur.jpg',
            'title' => 'Dúnpoki',
        ];
        $myndir = array_merge($dunpoki_myndir, imgs());
        
        makeProduct([
            'title' => 'Dúnpoki',
            'content' => 'Hágæða dúnpoki í kerru / vagn',
            'category_id' => 0,
            'fillimage' => 1,
            'options' => [
                [
                    "text" => "Litur",
                    "type" => "select",
                    "values" => [
                        [
                            "text" => "Rauður",
                            "value" => "",
                            "modifier" => "0"
                        ],
                        [
                            "text" => "Dökkblár",
                            "value" => "",
                            "modifier" => "0"
                        ],
                    ]
                ],
            ],
            'price' => 14900,
            'images' => $myndir,
        ]);

        for($i = 0; $i <= 6; $i++) {
            makeProduct([
                'title' => $faker->name,
                'fillimage' => 1,
            ]);
        }

        Model::reguard();
    }
}