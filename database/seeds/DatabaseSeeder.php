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

        makePage([
            'title' => 'Vefverslun',
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Bæklingur',
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Saga Sign',
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Sölustaðir',
            'topmenu' => 1,
        ]);

        makePage([
            'title' => 'Vertu í bandi',
            'topmenu' => 1,
        ]);

        $pics = ['slide1.jpg', 'slide2.jpg', 'slide3.jpg'];
        $forsidumyndir = makePage(['title' => 'Forsíðumyndir', 'slug' => '_forsidumyndir', 'status' => 0]);
        foreach($pics as $k => $v) {
            makePage(['title' => $v, 'parent_id' => $forsidumyndir->id, 'images' => [['name'=>$v]]]);
        }

        $flokkur1 = makeCategory(['title' => 'Flokkur 1', 'images' => getImages(3)]);
        $flokkur2 = makeCategory(['title' => 'Flokkur 2', 'images' => getImages(3)]);

        foreach(getImages() as $image) {
            makeProduct([
                'title' => $faker->name,
                'category_id' => (mt_rand(0,1) == 1 ? $flokkur1->id : $flokkur2->id),
                'images' => [
                    $image
                ],
            ]);
        }

        Model::reguard();
    }
}