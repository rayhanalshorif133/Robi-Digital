<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenewSubscription;
use Yajra\DataTables\Facades\DataTables;

class RenewController extends Controller
{
    public function index($id = null)
    {
        // if($id){
        //     $hitLog = RenewSubscription::where('id', $id)->first();
        //     $hitLog->postBackSendData = json_decode($hitLog->postBack_send_data);
        //     return view('renew.sent_details', compact('hitLog'));
        // }

        if (request()->ajax()) {
            $query = RenewSubscription::orderBy('id', 'desc')->get();
            return DataTables::of($query)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('renew.index');
    }
}
