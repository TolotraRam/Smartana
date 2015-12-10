<?php

use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{

    public $countries = array(
        array(1, 'MG', 'Madagascar'),
    );

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {

    $country = [];
    
    foreach ($this->countries as $index) {
      $country[] = [
        'name' => $index[2],
        'code' => $index[1],
        'enabled' => false
      ];
    }
    DB::table('countries')->insert($country);
  }

}

