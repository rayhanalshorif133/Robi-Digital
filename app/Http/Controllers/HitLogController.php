<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChargeLog;
use App\Models\HitLog;
use App\Models\RenewSubscription;
use App\Models\GetAOCTokenResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class HitLogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function sent(Request $request, $id = null)
    {


        if ($id) {
            $hitLog = HitLog::where('id', $id)
                ->with('getAOCToken')
                ->first();
            $hitLog->postBackSendData = json_decode($hitLog->postBack_send_data);
            return view('hit_log.sent_details', compact('hitLog'));
        }

        if (request()->ajax()) {
            $query = HitLog::orderBy('id', 'desc')
                ->with('getAOCToken')
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->get();
            return DataTables::of($query)
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('hit_log.sent');
    }


    public function chargeLog(Request $request)
    {
        if (request()->ajax()) {
            $start_date = new \DateTime($request->start_date);
            $end_date = new \DateTime($request->end_date);


            $chargeLogs = DB::table('charge_logs')
                ->whereBetween('charge_logs.charge_date', [$start_date, $end_date])
                ->select(
                    'charge_logs.keyword',
                    'charge_logs.type',
                    'charge_logs.charge_date',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN charge_logs.type = "subs" THEN 1 ELSE 0 END) as subscount'),
                    DB::raw('SUM(CASE WHEN charge_logs.type = "renew" THEN 1 ELSE 0 END) as renewcount')
                )
                ->groupBy('charge_logs.keyword', 'charge_logs.type', 'charge_logs.charge_date')
                ->orderBy('charge_logs.charge_date', 'asc')
                ->get();

            $uniqueKeywords = $chargeLogs->pluck('keyword')->unique()->toArray();

            // sum as par unique keywords
            $data = [];
            foreach ($uniqueKeywords as $keyword) {
                $data[] = [
                    'keyword' => $keyword,
                    'subscount' => $chargeLogs->where('keyword', $keyword)->sum('subscount'),
                    'renewcount' => $chargeLogs->where('keyword', $keyword)->sum('renewcount'),
                ];
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('hit_log.charge');
    }

    public function checkData()
    {

        dd('it is used for development');
        $renew = RenewSubscription::select()->where('keyword', 'BDGD')->where('response_code', null)->get();

        foreach ($renew as $item) {
            $chargeLog  = new ChargeLog();
            $chargeLog->spTransID = $item->new_spTransID;
            $chargeLog->msisdn = $item->msisdn;
            $chargeLog->keyword = $item->keyword;
            $chargeLog->amount = $item->amount;
            $chargeLog->type = 'renew';
            $chargeLog->charge_date = date('Y-m-d');  // Make sure this matches the date format in the database
            $chargeLog->save();
        }

        // $hitLogs = HitLog::orderBy('id', 'desc')->with('getAOCToken')
        //     ->whereBetween('id', [951,1000])
        //     ->get();



        // foreach ($hitLogs as $hitLog){
        //     if(!$hitLog->getAOCToken->msisdn){
        //         $getAOCToken =  GetAOCToken::select()->where('id', $hitLog->getAOCToken->id)->first();
        //         $getAOCTokenResponse =  GetAOCTokenResponse::select()->where('get_aoc_token_id', $getAOCToken->id)->first();
        //         $url = url('api/chargeStatus/' . $getAOCTokenResponse->aocTransID);
        //         $res = Http::get($url);
        //         $res = $res->json();
        //         $hitLog->res = $res;
        //         if($res['code'] == '00'){
        //             $msisdn = $res['data']['msisdn'];
        //             if($msisdn){
        //                 $getAOCToken->msisdn = $msisdn;
        //                 Log::info('Msisdn: ' . $msisdn);
        //                 $getAOCToken->save();
        //             }
        //         }
        //     }
        // }

    }


    public function subsBased(Request $request)
    {
        $date = $request->date;

        if ($date == null) {
            $subBasedLogs = DB::table('subscribers')
                ->select(
                    'subscribers.keyword',
                    DB::raw('COUNT(*) as total'),
                )
                ->where('subscribers.status', '1')
                ->groupBy('subscribers.keyword')
                ->get();
        } else {
            $subBasedLogs = DB::table('subscribers')
                ->select(
                    'subscribers.keyword',
                    DB::raw('COUNT(*) as total'),
                )
                ->where('subscribers.subs_date', '<=', $date)
                ->where('subscribers.status', '1')
                ->groupBy('subscribers.keyword')
                ->get();
        }


        return view('hit_log.subs-based', compact('subBasedLogs'));
    }

    public function subUnsubLog(Request $request)
    {

        if (request()->ajax()) {

            $start_date = $request->start_date;
            $end_date = $request->end_date;
            $end_date_plus_one = Carbon::parse($end_date)->addDay()->format('Y-m-d');
            $subsAndUnsubs = DB::table('sub_un_sub_logs')
                ->whereBetween('sub_un_sub_logs.opt_date', [$start_date, $end_date_plus_one])
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
