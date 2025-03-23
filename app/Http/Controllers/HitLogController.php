<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChargeLog;
use App\Models\HitLog;
use App\Models\RenewSubscription;
use App\Models\GetAOCTokenResponse;
use App\Models\Callback;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Http\Controllers\Api\NDTVController;


class HitLogController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function yesterdayLog(Request $request)
    {

        $startDate = Carbon::parse($request->start_date); // Start date
        $endDate = Carbon::parse($request->end_date);   // End date

        $data = [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {

            $sendOTP = DB::table('hit_logs')
                ->where('keyword', 'LIKE', 'BDGD')
                ->whereDate('date', $date->toDateString())
                ->get()->count();

            $totalBackFromOTP = DB::table('callbacks')
                ->where('keyword', 'LIKE', 'BDGD')
                ->whereDate('created_at', $date->toDateString())
                ->get()->count();

            $otpMatch = DB::table('callbacks')
                ->where('keyword', 'LIKE', 'BDGD')
                ->where('status', 1)
                ->whereDate('created_at', $date->toDateString())
                ->get()->count();

            $otpFailed = DB::table('callbacks')
                ->where('keyword', 'LIKE', 'BDGD')
                ->where('status', 0)
                ->whereDate('created_at', $date->toDateString())
                ->get()->count();

            $paymentSuccess = DB::table('charge_logs')
                ->where('keyword', 'LIKE', 'BDGD')
                ->where('type', 'LIKE', 'subs')
                ->whereDate('charge_date', $date->toDateString())
                ->get()->count();

            $insufficientCredit = DB::table('callbacks')
                ->where('keyword', 'LIKE', 'BDGD')
                ->where('code', 'POL1000')
                ->whereDate('created_at', $date->toDateString())
                ->get()->count();

            $data[] = [
                'date' => $date->toDateString(),
                'sendOTP' => $sendOTP,
                'totalBackFromOTP' => $totalBackFromOTP,
                'otpMatch' => $otpMatch,
                'otpFailed' => $otpFailed,
                'paymentSuccess' => $paymentSuccess,
                'insufficientCredit' => $insufficientCredit,
            ];
        }

        // Output or process the array
        return view('hit_log.report_table', compact('data'));
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



        dd("Testing data");


        $renew_subscriptions = DB::table('renew_subscriptions')
            ->where('created_at', 'like', '%2025-03-06%')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($renew_subscriptions as $renew) {

            $updated = DB::table('subscribers')
                ->where('msisdn', $renew->msisdn)
                ->update(['spTransID' => $renew->new_spTransID]);

            if ($updated) {
                echo "Updated spTransID for msisdn: {$renew->msisdn} with new_spTransID: {$renew->new_spTransID}";
            } else {
                echo "No matching subscriber found for msisdn: {$renew->msisdn}";
            }
        }

        dd('done');
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
