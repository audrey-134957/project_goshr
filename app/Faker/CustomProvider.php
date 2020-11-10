<?php

namespace App\Faker;

use Faker\Provider\Base;

class CustomProvider extends Base
{
    public function customName()
    {



        return $this->generator->text();
    }
}