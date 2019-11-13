<?php


namespace App;

use App\Model\User;

interface Auth
{
    public function getUser(): User;
}
