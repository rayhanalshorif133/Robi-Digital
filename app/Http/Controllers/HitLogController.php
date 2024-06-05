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

    public function subUnsubLog(Request $request){


        if (request()->ajax()) {

            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $subsAndUnsubs = DB::table('sub_un_sub_logs')
                    ->whereBetween('sub_un_sub_logs.opt_date', [$start_date, $end_date])
                    ->select(
                        'sub_un_sub_logs.keyword',
                        'sub_un_sub_logs.status',
                        DB::raw('COUNT(*) as total'),
                        DB::raw('SUM(CASE WHEN sub_un_sub_logs.status = "1" THEN 1 ELSE 0 END) as subscount'),
                        DB::raw('SUM(CASE WHEN sub_un_sub_logs.status = "0" THEN 1 ELSE 0 END) as unsubscount')
                    )
                    ->groupBy('sub_un_sub_logs.keyword', 'sub_un_sub_logs.status')
                    ->get();
            $uniqueKeywords = $subsAndUnsubs->pluck('keyword')->unique()->toArray();

            // sum as par unique keywords
            $data = [];
            foreach ($uniqueKeywords as $keyword) {
                $data[] = [
                    'keyword' => $keyword,
                    'subscount' => $subsAndUnsubs->where('keyword', $keyword)->sum('subscount'),
                    'unsubscount' => $subsAndUnsubs->where('keyword', $keyword)->sum('unsubscount'),
                ];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('hit_log.sub_unsub_log');
    }
}
