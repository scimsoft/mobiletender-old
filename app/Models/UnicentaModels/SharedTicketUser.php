<?php
/**
 * Created by PhpStorm.
 * User: Gerrit
 * Date: 07/10/2020
 * Time: 11:18
 */

namespace App\Models\UnicentaModels;


class SharedTicketUser
{

    public $m_sId;
    public $m_sName;

    public function __construct(array $attributes = [])
    {
        $person = !empty(auth()->user())?$person = auth()->user()->name:'Guest';

        $this->m_sId = "1";
        $this->m_sName=$person;
    }
}