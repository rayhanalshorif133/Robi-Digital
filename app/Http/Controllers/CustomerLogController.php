<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GetAOCToken;
use App\Models\Service;
use App\Models\RenewSubscription;
use App\Models\GetAOCTokenResponse;
use App\Models\ChargeLog;
use App\Models\ServiceProviderInfo;
use App\Models\Subscriber;
use App\Models\SubUnSubLog;
use Carbon\Carbon;

class CustomerLogController extends Controller
{
    public function index(Request $request)
    {





        if ($request->phone) {
            $subscriber_details = Subscriber::select('subscribers.id as id', 'name', 'subscribers.keyword as keyword', 'msisdn', 'subs_date', 'unsubs_date', 'status','spTransID')
                ->where('msisdn', 'like', '%' . $request->phone . '%')
                ->join('services', 'services.keyword', '=', 'subscribers.keyword')
                ->get();

            $subs_logs = SubUnSubLog::select()
                ->orderBy('id', 'desc')
                ->where('msisdn', 'like', '%' . $request->phone . '%')
                ->get()->take(50);

            $chargeLogs = ChargeLog::select()
                ->orderBy('id', 'desc')
                ->where('msisdn', 'like', '%' . $request->phone . '%')
                ->get();
            $data = [
                'subscriber_details' => $subscriber_details,
                'subs_logs' => $subs_logs,
                'chargeLogs' => $chargeLogs,
            ];

            return $this->respondWithSuccess("Successfully fetched customer log", $data);
        }
        return view('customer-log');
    }
}
