<?php

namespace App\Model;

use App\Model\User;
use JsonSerializable;

class Medecin extends User implements JsonSerializable
{

    // protected $roles= 'MEDCIN';

    protected $user_id;

    /**
     * Undocumented variable
     *
     * @var Specialty
     */
    protected $specialty;

    protected $specialty_id;

    /**
     * Get the value of user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }


    public function getSpecialty()
    {
        return $this->specialty;
    }


    public function setSpecialty(Specialty $specialty)
    {
        $this->specialty = $specialty;

        return $this;
    }


    public function getSpecialtyId()
    {
        return (int) $this->specialty_id;
    }

    public function setSpecialtyId(int $specialty_id)
    {
        $this->specialty_id = $specialty_id;

        return $this;
    }
}
