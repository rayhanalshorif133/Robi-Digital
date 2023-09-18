<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HitLog;
use Yajra\DataTables\Facades\DataTables;

class HitLogController extends Controller
{
    public function sent($id = null)
    {

        if($id){
            $hitLog = HitLog::where('id', $id)
            ->with('getAOCToken')
            ->first();
            $hitLog->postBackSendData = json_decode($hitLog->postBack_send_data);
            return view('hit_log.sent_details', compact('hitLog'));
        }

        if (request()->ajax()) {
            $query = HitLog::orderBy('id', 'desc')->with('getAOCToken')->get();
            return DataTables::of($query)
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('hit_log.sent');
    }
    
    public function received()
    {
        dd('received');
    }
}
