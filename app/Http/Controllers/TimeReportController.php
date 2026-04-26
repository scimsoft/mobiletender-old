<?php

namespace App\Http\Controllers;

use App\Models\TimeReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function is_null;
use function redirect;
use function route;

class TimeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
        if(Auth::user()->isAdmin()){
            $timereports= TimeReport::get();
        }else{
            $timereports= TimeReport::where('userId',Auth::user()->id)->get();
        }

       $lastChecking= TimeReport::where('userId',Auth::user()->id)->where('endtime',null)->first();
       $isChecking = count(TimeReport::where('userId',Auth::user()->id)->where('endtime',null)->get())>0;
      // dd($isChecking);
       return view('admin.timereport.index',compact('timereports','isChecking','lastChecking'));
    }

    public function enter(){

        $timereport = new TimeReport();
        $timereport->userid = Auth::user()->id;
        $timereport->starttime = Carbon::now('GMT+2');
        $timereport->save();
        return redirect(route('admin'));

    }

    public function exit(){

        $timereport = TimeReport::whereNull('endtime')
            ->first();

        $timereport->endtime = Carbon::now('GMT+2');
        $timereport->save();

        return redirect(route('admin'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeReport  $controlPresencial
     * @return \Illuminate\Http\Response
     */
    public function show(TimeReport $controlPresencial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeReport  $controlPresencial
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeReport $controlPresencial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeReport  $controlPresencial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeReport $controlPresencial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeReport  $controlPresencial
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeReport $controlPresencial)
    {
        //
    }
}
