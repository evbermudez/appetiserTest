<?php

use Faker\Generator as Faker;

$factory->define(App\People::class, function (Faker $faker) {
    return [
        'first_name' => $faker->word(12),
        'last_name' => $faker->word(12),
        'birthday' => $faker->date('Y-m-d','now')
    ];
});
