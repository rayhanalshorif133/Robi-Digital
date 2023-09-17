<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HitLog;
use Yajra\DataTables\Facades\DataTables;

class HitLogController extends Controller
{
    public function sent()
    {
        if (request()->ajax()) {
            $query = HitLog::orderBy('id', 'desc')->get();
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
