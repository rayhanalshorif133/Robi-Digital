<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GetAOCToken;
use App\Models\HitLog;
use App\Models\Subscriber;
use App\Models\SubUnSubLog;
use App\Models\GetAOCTokenResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HitLogController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


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

    public function checkData(){
        $hitLogs = HitLog::orderBy('id', 'desc')->with('getAOCToken')
            ->whereBetween('id', [951,1000])
            ->get();

        

        foreach ($hitLogs as $hitLog){
            if(!$hitLog->getAOCToken->msisdn){
                $getAOCToken =  GetAOCToken::select()->where('id', $hitLog->getAOCToken->id)->first();
                $getAOCTokenResponse =  GetAOCTokenResponse::select()->where('get_aoc_token_id', $getAOCToken->id)->first();
                $url = url('api/chargeStatus/' . $getAOCTokenResponse->aocTransID);
                $res = Http::get($url);
                $res = $res->json();
                $hitLog->res = $res;
                if($res['code'] == '00'){
                    $msisdn = $res['data']['msisdn'];
                    if($msisdn){
                        $getAOCToken->msisdn = $msisdn;
                        Log::info('Msisdn: ' . $msisdn);
                        $getAOCToken->save();
                    }
                }
            }
        }

        dd($hitLogs);
    }

    public function chargeLog(){


        
        
        $hitLogs = HitLog::orderBy('id', 'desc')->with('getAOCToken')
            ->take(10)
            ->get();
        $start_date = '2023-09-26';
        $end_date = '2024-05-05';
        $hitLogs = DB::table('hit_logs')
                    ->join('get_a_o_c_tokens', 'hit_logs.get_aoc_token_id', '=', 'get_a_o_c_tokens.id')
                    ->whereBetween('hit_logs.date', [$start_date, $end_date])
                    ->select('hit_logs.keyword','get_a_o_c_tokens.isSubscription', DB::raw('COUNT(*) as total'))
                    ->groupBy('hit_logs.keyword','get_a_o_c_tokens.isSubscription')
                    ->get();
        dd($hitLogs);
        $uniqueKeywords = $hitLogs->pluck('keyword')->unique()->toArray();
        dd($uniqueKeywords);
        // sum as par unique keywords
        $data = [];
        foreach ($uniqueKeywords as $keyword) {
            $data[] = [
                'keyword' => $keyword,
                'subscount' => $subsAndUnsubs->where('keyword', $keyword)->sum('subscount'),
                'unsubscount' => $subsAndUnsubs->where('keyword', $keyword)->sum('unsubscount'),
            ];
        }
        // if (request()->ajax()) {
        //     return DataTables::of($query)
        //         ->rawColumns(['action'])
        //         ->toJson();
        // }
        // return view('hit_log.charge_log');
    }
}
