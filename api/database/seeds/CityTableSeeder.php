<?php

use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
	public $cities = array(
        array(26816, 'Ambatolampy', 1),
        array(26817, 'Anjozorobe', 1),
        array(26818, 'Ankazobe', 1),
        array(26819, 'Antananarivo', 1),
        array(26820, 'Antanifotsy', 1),
        array(26821, 'Antsirabe', 1),
        array(26822, 'Arivonimamo', 1),
        array(26823, 'Betafo', 1),
        array(26824, 'Faratsiho', 1),
        array(26825, 'Fenoarivo', 1),
        array(26826, 'Manjakandriana', 1),
        array(26827, 'Soavinandriana', 1),
        array(26828, 'Tsiroanomandidy', 1),
        array(26829, 'Ambanja', 2),
        array(26830, 'Ambilobe', 2),
        array(26831, 'Andapa', 2),
        array(26832, 'Antalaha', 2),
        array(26833, 'Antsirambazaha', 2),
        array(26834, 'Antsiranana', 2),
        array(26835, 'Sambava', 2),
        array(26836, 'Ambalavao', 3),
        array(26837, 'Ambatofinandrahana', 3),
        array(26838, 'Ambositra', 3),
        array(26839, 'Fandriana', 3),
        array(26840, 'Farafangana', 3),
        array(26841, 'Fianarantsoa', 3),
        array(26842, 'Ifanadiana', 3),
        array(26843, 'Ihosy', 3),
        array(26844, 'Ikalamavony', 3),
        array(26845, 'Ikongo', 3),
        array(26846, 'Manakara', 3),
        array(26847, 'Manandriana', 3),
        array(26848, 'Mananjary', 3),
        array(26849, 'Nosy Varika', 3),
        array(26850, 'Vangaindrano', 3),
        array(26851, 'Vondrozo', 3),
        array(26852, 'Ambato Boina', 4),
        array(26853, 'Antsohihy', 4),
        array(26854, 'Bealanana', 4),
        array(26855, 'Mahajanga', 4),
        array(26856, 'Marovoay', 4),
        array(26857, 'Tsaratanana', 4),
        array(26858, 'Ambatondrazaka', 5),
        array(26859, 'Ambodifototra', 5),
        array(26860, 'Amparafaravola', 5),
        array(26861, 'Andevoranto', 5),
        array(26862, 'Andilamena', 5),
        array(26863, 'Anosibe An\'ala', 5),
        array(26864, 'Fenoarivo Atsinanana', 5),
        array(26865, 'Mahanoro', 5),
        array(26866, 'Mananara', 5),
        array(26867, 'Maroantsetra', 5),
        array(26868, 'Marolambo', 5),
        array(26869, 'Moramanga', 5),
        array(26870, 'Soanierana Ivongo', 5),
        array(26871, 'Toamasina', 5),
        array(26872, 'Vavatenina', 5),
        array(26873, 'Vohibinany', 5),
        array(26874, 'Amboasary', 6),
        array(26875, 'Ambovombe', 6),
        array(26876, 'Ampanihy', 6),
        array(26877, 'Ankazoabo', 6),
        array(26878, 'Beloha', 6),
        array(26879, 'Belon\'i Tsiribihina', 6),
        array(26880, 'Beroroha', 6),
        array(26881, 'Betioky', 6),
        array(26882, 'Miandrivazo', 6),
        array(26883, 'Morondava', 6),
        array(26884, 'Sakaraha', 6),
        array(26885, 'Taolanaro', 6),
        array(26886, 'Toliary', 6),
        array(26887, 'Tsihombe', 6),
    );
	/**
   * Run the database seeds.
   *
   * @return void
   */
  	public function run() {

	    $city = [];

		foreach ($this->cities as $index) {
	      $city[] = [
	        'name' => $index[1],
	        'enabled' => false,
	        'state_id' => $index[2]
	      ];
	    }
	    DB::table('cities')->insert($city);
	}
}