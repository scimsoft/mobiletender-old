<?php

namespace App\Models\UnicentaModels;

use Carbon\Carbon;
use function GuzzleHttp\Psr7\copy_to_string;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use JsonSerializable;

class SharedTicket
{
    //
    public $m_sId ;
    public $tickettype;
    public $m_iTicketId;
    public $m_iPickupId;
    public $m_dDate;
    public $attributes;
    public $m_User;
    public $m_sActiveCash;
    public $m_aLines ;
    public $payments ;
    public $oldTicket ;
    public $tip ;
    public $m_isProcessed ;
    public $ticketstatus;



    public function __construct(array $attributes = [])
    {
        $this->m_sId = Str::uuid()->toString();
        $this->tickettype = 0;
        $this->m_iTicketId = 0;
        $this->m_iPickupId = 0;
        $this->m_dDate = Carbon::now()->format('M d, Y h:m:s A');
        $this->attributes = [];
        $this->m_User = [];
        $this->m_sActiveCash = Str::uuid();
        $this->m_aLines=array();
        $this->payments=[];
        $this->oldTicket=false;
        $this->tip=false;
        $this->m_isProcessed=false;
        $this->ticketstatus=0;
    }
    public function addTicketLine($sharedTicketLine){

    }






}
