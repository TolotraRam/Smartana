<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    public $cities = [
        [26816, 'Ambatolampy', 1],
        [26817, 'Anjozorobe', 1],
        [26818, 'Ankazobe', 1],
        [26819, 'Antananarivo', 1],
        [26820, 'Antanifotsy', 1],
        [26821, 'Antsirabe', 1],
        [26822, 'Arivonimamo', 1],
        [26823, 'Betafo', 1],
        [26824, 'Faratsiho', 1],
        [26825, 'Fenoarivo', 1],
        [26826, 'Manjakandriana', 1],
        [26827, 'Soavinandriana', 1],
        [26828, 'Tsiroanomandidy', 1],
        [26829, 'Ambanja', 2],
        [26830, 'Ambilobe', 2],
        [26831, 'Andapa', 2],
        [26832, 'Antalaha', 2],
        [26833, 'Antsirambazaha', 2],
        [26834, 'Antsiranana', 2],
        [26835, 'Sambava', 2],
        [26836, 'Ambalavao', 3],
        [26837, 'Ambatofinandrahana', 3],
        [26838, 'Ambositra', 3],
        [26839, 'Fandriana', 3],
        [26840, 'Farafangana', 3],
        [26841, 'Fianarantsoa', 3],
        [26842, 'Ifanadiana', 3],
        [26843, 'Ihosy', 3],
        [26844, 'Ikalamavony', 3],
        [26845, 'Ikongo', 3],
        [26846, 'Manakara', 3],
        [26847, 'Manandriana', 3],
        [26848, 'Mananjary', 3],
        [26849, 'Nosy Varika', 3],
        [26850, 'Vangaindrano', 3],
        [26851, 'Vondrozo', 3],
        [26852, 'Ambato Boina', 4],
        [26853, 'Antsohihy', 4],
        [26854, 'Bealanana', 4],
        [26855, 'Mahajanga', 4],
        [26856, 'Marovoay', 4],
        [26857, 'Tsaratanana', 4],
        [26858, 'Ambatondrazaka', 5],
        [26859, 'Ambodifototra', 5],
        [26860, 'Amparafaravola', 5],
        [26861, 'Andevoranto', 5],
        [26862, 'Andilamena', 5],
        [26863, 'Anosibe An\'ala', 5],
        [26864, 'Fenoarivo Atsinanana', 5],
        [26865, 'Mahanoro', 5],
        [26866, 'Mananara', 5],
        [26867, 'Maroantsetra', 5],
        [26868, 'Marolambo', 5],
        [26869, 'Moramanga', 5],
        [26870, 'Soanierana Ivongo', 5],
        [26871, 'Toamasina', 5],
        [26872, 'Vavatenina', 5],
        [26873, 'Vohibinany', 5],
        [26874, 'Amboasary', 6],
        [26875, 'Ambovombe', 6],
        [26876, 'Ampanihy', 6],
        [26877, 'Ankazoabo', 6],
        [26878, 'Beloha', 6],
        [26879, 'Belon\'i Tsiribihina', 6],
        [26880, 'Beroroha', 6],
        [26881, 'Betioky', 6],
        [26882, 'Miandrivazo', 6],
        [26883, 'Morondava', 6],
        [26884, 'Sakaraha', 6],
        [26885, 'Taolanaro', 6],
        [26886, 'Toliary', 6],
        [26887, 'Tsihombe', 6],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $city = [];

        foreach ($this->cities as $index) {
            $city[] = [
            'name'     => $index[1],
            'enabled'  => false,
            'state_id' => $index[2],
          ];
        }
        DB::table('cities')->insert($city);
    }
}
