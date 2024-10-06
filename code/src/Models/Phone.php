<?php

namespace Geekbrains\Application1\Models;

class Phone
{
    private string $phone;

    public function __construct()
    {
        $this->phone = '7111222333';
    }

    public function getPhone(){
        return $this->phone;
    }
}