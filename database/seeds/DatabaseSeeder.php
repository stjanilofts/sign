<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;


function getImages($number = 0) {
    $images = [];

    $dir = scandir(public_path('prods'));

    $repository = [];

    foreach($dir as $k => $v) {
        if($v != '.' && $v != '..') {
            if($number >= 0) $number++;
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
            $collections = (array)\App\Product::collections();
            $num = mt_rand(0, (count($collections) - 1));
            $i = 0;
            $collection = '';
            foreach($collections as $k => $v) {
                if($i == $num) {
                    $collection = $k;
                }
                $i++;
            }

            $page['slug'] = isset($page['slug']) ? $page['slug'] : str_slug($page['title']);
            $page['images'] = isset($page['images']) ? $page['images'] : imgs();
            $page['tech'] = isset($page['tech']) ? $page['tech'] : '';
            $page['collection'] = $collection;
            $page['karlar'] = (mt_rand(0,1));
            $page['konur'] = (mt_rand(0,1));
            $page['product_type'] = \App\Product::product_types()[(mt_rand(0, count(\App\Product::product_types()) - 1))];

            return factory(\App\Product::class)->create($page);
        }

        function makeCategory($page = []) {
            $page['slug'] = isset($page['slug']) ? $page['slug'] : str_slug($page['title']);
            $page['images'] = isset($page['images']) ? $page['images'] : imgs();

            return factory(\App\Category::class)->create($page);
        }

        makePage([
            'title' => 'Vefverslun',
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Bæklingur',
            'topmenu' => 0,
        ]);

        makePage([
            'title' => 'Saga Sign',
            'content' => '
{image|align:right|size:m}
<p>Ingi Bjarnason er yngstur í stórum systkynahópi hönnuða og listamanna.  Rekinn áfram af ríkri sköpunarþörf með sitt frjóa og listræna ímyndarafl fann Ingi að gullsmíðin væri hans rétta hlutskipti í lífinu. Kornungur gerðist hann lærlingur hjá færum gullsmiði. Ingi náði snemma á ferlinum góðum tökum á smíði og hönnun skartgripa og stofnaði eigið gullsmíðafyrirtæki árið 2001. Í fallegu umhverfi smábátahafnarinnar í Hafnarfirði rekur Ingi nú fyrirtæki sitt SIGN sem er eitt af helstu kennileitunum í íslenskri hönnun og smíði skartgripa.</p>


<p>Á vinnustofu SIGN er mikill erill því auk Inga starfar þar hópur hæfileikaríkra gullsmiða við smíði skartgripa. Ekki veitir af því hönnun Inga hefur skilað sjö vinsælustu skartgripalínunum sem seldar eru hérlendis í dag. Í hönnuninni birtist gjarnan dulúð íslenskrar náttúru og frumkraftar hennar eldurinn og ísinn. Skartgripirnir frá SIGN eru seldir í sérverslunum víða um land og auk þess um borð í flugvélum Icelandair. Auk þess sinnir SIGN smíði á sérpöntunum meðal annars á stærri gripum fyrir einstaklinga, félög og fyrirtæki. Verkin frá SIGN hafa sannarlega unnið hug Íslendinga og sama má segja um erlenda gesti okkar. Framsækni í hönnun og fegurð gripanna eru lykilorðin í velgengni SIGN en það orðspor ætla Ingi og samstarfsmenn hans að varðveita með áframhaldandi nýsköpun.</p>
            ',
            'images' => [
                [
                    'name' => 'ingi_stor.jpg',
                    'title' => 'Ingi',
                ]
            ],
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Sölustaðir',
            'topmenu' => 1,
            'content' => '
<div class="uk-grid">
    <div class="uk-width-medium-1-2 uk-text-center">
        <p><strong>Sign Gallery</strong><br>
        Fornubúðum 12, Hafnarfjörður<br>
        Tel: +354 555 0800</p>

        <p><strong>Úr og Gull</strong><br>
        Fjarðargötu 13-15, 220 Hafnarfjörður<br>
        Tel: +354 565 4666</p>

        <p><strong>Gallery Hilton</strong><br>
        Suðurlandbraut 2, Reykjavík<br>
        Tel: +354 869 1218</p>

        <p><strong>Hótel Natura</strong><br>
        Hlíðarfæti, Reykjavík<br>
        Tel: +354 444 4500</p>

        <p><strong>Saga Boutique / Icelandair</strong><br>
        Keflavíkurflugvelli, Reykjanesbær<br>
        Tel: +354 425 0345</p>

        <p><strong>Karl R. Guðmundsson</strong><br>
        Austurvegi 11, Selfoss<br>
        Tel: +354 482 1433</p>

        <p><strong>Model</strong><br>
        Þjóðbraut 1, Akranes<br>
        Tel: +354 431 3333</p>

        <p><strong>Húsgagnaval</strong><br>
        Hrísbraut 2, Höfn í Hornarfirði<br>
        Tel: +354 478 2535</p>

        <p><strong>Bláa Lónið</strong><br>
        Svartsengi, 240 Grindavík<br>
        Tel: +354 420 8800</p>

        <p><strong>Blómstuvellir</strong><br>
        Munaðarhóli 25-27, 360 Hellissandi<br>
        Tel: +354 436 6655</p>

        <p><strong>Carat</strong><br>
        Smáralind - 201 Kópavogi<br>
        Tel: +354 557 7740</p>

        <p><strong>Hótel Cabin</strong><br>
        Borgartúni 32, 105 Reykjavík<br>
        Tel: +354 511 6030</p>
         
        <p><strong>Hársnyrtistofan Capello</strong><br>
        Aðalgötu 20b, 550 Sauðárkróki<br>
        Tel: +354 453 6800</p>
         
        <p><strong>Snorrastofa</strong><br>
        í Reykholti<br>
        Tel: +354 433 8000</p>

        <p><strong>Epal</strong><br>
        Fríhöfninni, Flugstöð Leifs Eiríkssonar<br>
        235 Reykjanesbæ<br>
        Tel: +354 568 7733</p>
         
        <p><strong>Fjörukráin</strong><br>
        Strandgötu 55, 220 Hafnarfirði<br>
        Tel: +354 565 1213</p>
         
        <p><strong>Sædís</strong><br>
        Geirsgata 5b, 101 Reykjavík</p>
    </div>
    <div class="uk-width-medium-1-2 uk-text-center">
        <p><strong>Grand Hótel</strong><br>
        Sigtúni 38, 105 Reykjavík<br>
        Tel: +354 514 8000</p>
         
        <p><strong>Leonard</strong><br>
        Kringlunni, Reykjavík<br>
        Tel: +354 510 4000</p>

        <p><strong>GÞ Skartgripir og úr</strong><br>
        Bankastræti 12, Reykjavík<br>
        Tel: +354 551 4007</p>

        <p><strong>Halldór Ólafsson úrsmiður</strong><br>
        Glerártorg, Akureyri<br>
        Tel: +354 462 2509</p>

        <p><strong>Palóma föt og skart</strong><br>
        Víkurbraut 62, Grindavík<br>
        Tel: +354 426 8711</p>

        <p><strong>Hótel Saga</strong><br>
        Hagatorgi, 107 Raykjavík<br>
        Tel: +354 570 7744</p>

        <p><strong>Klassík</strong><br>
        Selási 1, 700 Egilsstöðum<br>
        Tel: +354 471 1886</p>

        <p><strong>Póley</strong><br>
        Heiðartúni 1, 900 Vestmanneyjar<br>
        Tel: +354 481 1155</p>

        <p><strong>Rammagerðin</strong><br>
        Hafnarstræti 19, 101 Reykjavík<br>
        Tel: +354 551 1122</p>

        <p><strong>Siglósport</strong><br>
        Aðalgötu 32, 580 Siglufirði<br>
        Tel: +354 467 1866</p>

        <p><strong>Töff Föt</strong><br>
        Garðsbraut 62, 640 Húsavík<br>
        Tel: +354 464 2727</p>
         
        <p><strong>Hárstofa Sigríðar</strong><br>
        Austurvegi 20a, 730 Reyðarfirði<br>
        Tel: 354 474 1417</p>
         
        <p><strong>Klukkan</strong><br>
        Hamraborg 10, 200 Kópavogi<br>
        Tel: +354 554 4320</p>
         
        <p><strong>Georg V. Hannah</strong><br>
        Hafnargötu 49, 230 Reykjanesbæ<br>
        Tel: +354 421 5757</p>
         
        <p><strong>Scandinavian House</strong><br>
        58th Park Avenue,<br>
        10016 New York, USA<br>
        Tel: +1 212 686 2115</p>
    </div>
</div>
            '
        ]);

        makePage([
            'title' => 'Vertu í bandi',
            'topmenu' => 0,
        ]);

        $pics = ['slide1.jpg', 'slide2.jpg', 'slide3.jpg'];
        $forsidumyndir = makePage(['title' => 'Forsíðumyndir', 'slug' => '_forsidumyndir', 'status' => 0]);
        foreach($pics as $k => $v) {
            makePage(['title' => $v, 'parent_id' => $forsidumyndir->id, 'images' => [['name'=>$v]]]);
        }

        $flokkur1 = makeCategory(['title' => 'Hringar', 'images' => getImages(3)]);
            $flokkur1_1 = makeCategory(['parent_id' => $flokkur1->id, 'title' => 'Flokkur 1-1', 'images' => getImages(3)]);
            $flokkur1_2 = makeCategory(['parent_id' => $flokkur1->id, 'title' => 'Flokkur 1-2', 'images' => getImages(3)]);
            $flokkur1_3 = makeCategory(['parent_id' => $flokkur1->id, 'title' => 'Flokkur 1-3', 'images' => getImages(3)]);
        $flokkur2 = makeCategory(['title' => 'Hálsmen', 'images' => getImages(3)]);
        $flokkur3 = makeCategory(['title' => 'Eyrnalokkar', 'images' => getImages(3)]);
        $flokkur4 = makeCategory(['title' => 'Skart', 'images' => getImages(3)]);
            $flokkur4_1 = makeCategory(['parent_id' => $flokkur4->id, 'title' => 'Flokkur 4-1', 'images' => getImages(3)]);
            $flokkur4_2 = makeCategory(['parent_id' => $flokkur4->id, 'title' => 'Flokkur 4-2', 'images' => getImages(3)]);
                $flokkur4_2_1 = makeCategory(['parent_id' => $flokkur4_2->id, 'title' => 'Flokkur 4-2-1', 'images' => getImages(3)]);
                $flokkur4_2_2 = makeCategory(['parent_id' => $flokkur4_2->id, 'title' => 'Flokkur 4-2-2', 'images' => getImages(3)]);
            $flokkur4_3 = makeCategory(['parent_id' => $flokkur4->id, 'title' => 'Flokkur 4-3', 'images' => getImages(3)]);
            $flokkur4_4 = makeCategory(['parent_id' => $flokkur4->id, 'title' => 'Flokkur 4-4', 'images' => getImages(3)]);
        $flokkur5 = makeCategory(['title' => 'Skraut', 'images' => getImages(3)]);

        foreach(getImages() as $image) {
            $imgs = array_merge([0 => $image], getImages());
            makeProduct([
                'title' => $faker->name,
                'category_id' => (mt_rand(0,1) == 1 ? $flokkur1->id : $flokkur2->id),
                'images' => $imgs,
            ]);
        }

        Model::reguard();
    }
}