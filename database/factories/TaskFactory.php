<?php

use App\Project;
use Faker\Generator as Faker;

$factory->define(App\Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence,
        // ini sama dengan
        'project_id' => factory(App\Project::class),
        // ini :
        // 'project_id' => function () {
        //     return factory(App\Project::class)->create()->id;
        // },
    ];
});
