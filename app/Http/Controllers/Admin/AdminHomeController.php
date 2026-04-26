<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Traits\UnicentaPayedTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function redirect;

class AdminHomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    use UnicentaPayedTrait;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.admin');
    }

    public function appconfig(){

//        Log::debug('load eatin:' . Config::get('customoptions.eatin'));
//        Log::debug('load takeaway:' . Config::get('customoptions.takeaway'));
//        Log::debug('load eatin_prepay:' . Config::get('customoptions.eatin_prepay'));
//        Log::debug('load clean_table_after_order:' . Config::get('customoptions.clean_table_after_order'));

        return view('admin.appconfig');
    }

    public function updateconfig(Request $req){


        $eatin = $req->has('eatin') ? "true" : "false";
        $takeaway = $req->has('takeaway') ? "true" : "false";
        $eatin_prepay = $req->has('eatinprepay') ? "true" : "false";
        $cleantableafterorder = $req->has('cleantableafterorder') ? "true" : "false";
        $this->setEnvironmentValue('EATIN', $eatin);
        $this->setEnvironmentValue('TAKE_AWAY', $takeaway);
        $this->setEnvironmentValue('EATIN_PREPAY', $eatin_prepay);
        $this->setEnvironmentValue('CLEAN_TABLE_AFTER_ORDER', $cleantableafterorder);
        $this->reloadConfigCache();
//        Log::debug('eatin:' . Config::get('customoptions.eatin'));
//        Log::debug('takeaway:' . Config::get('customoptions.takeaway'));
//        Log::debug('eatin_prepay:' . Config::get('customoptions.eatin_prepay'));
//        Log::debug('clean_table_after_order:' . Config::get('customoptions.clean_table_after_order'));




        return "<a href='admin'>success</a>";
    }

    private function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

    private function reloadConfigCache(): void
    {
// Reload the cached config
        if (file_exists(App::getCachedConfigPath())) {
            Artisan::call("optimize");

        }
    }

    public function selectTableNr(){
        $places = DB::select('select id,name from places order by ABS(id)');
        $opentickets = DB::select('select * from sharedtickets');
        $openTicket = [];
        foreach ( $opentickets as $openticket) {
            $openTicket[] = $openticket->id;
        }
        foreach($places as $place){
            $openTicketSum[] = $this->getSumTicketLines($place->id);
            $ticketWithUnorderdItems[]= $this->hasUnprintedTicketLines($place->id);
        }

        return view ('admin.table',compact(['places','openTicket','openTicketSum','ticketWithUnorderdItems']));
    }


}
