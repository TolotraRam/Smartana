<?php

use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    public $states = [
        [1, 'Antananarivo', 1],
        [2, 'Antsiranana', 1],
        [3, 'Fianarantsoa', 1],
        [4, 'Mahajanga', 1],
        [5, 'Toamasina', 1],
        [6, 'Toliary', 1],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $state = [];

        foreach ($this->states as $index) {
            $state[] = [
          'name' => $index[1],
          'enabled' => false,
          'country_id' => $index[2],
        ];
        }
        DB::table('states')->insert($state);
    }
}
