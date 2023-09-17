<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (request()->ajax()) {
            $query = Service::orderBy('id', 'desc')->get();
            return DataTables::of($query)
                ->rawColumns(['action'])
                ->toJson();
        }
        return view('service.index');
    }

    // create 
    public function store(Request $request)
    {
        $isValidator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'validity' => 'required',
            'purchase_category_code' => 'required',
            'reference_code' => 'required',
            'channel' => 'required',
            'on_behalf_of' => 'required',
        ]);


        if ($isValidator->fails()) {
            flash()->addError($isValidator->errors()->first());
            return redirect()->route('service.index');
        }

        try {
            $service = new Service();
            $service->service_key = $this->getServiceKey();
            $service->name = $request->name;
            $service->keyword = $request->keyword;
            $service->type = $request->type;
            $service->validity = $request->validity;
            $service->purchase_category_code = $request->purchase_category_code;
            $service->reference_code = $request->reference_code;
            $service->channel = $request->channel;
            $service->on_behalf_of = $request->on_behalf_of;
            $service->save();
            flash()->addSuccess('Service created successfully!');
        } catch (\Throwable $th) {
            flash()->addError('Something went wrong!');
        }
        return redirect()->route('service.index');
    }

    // edit
    public function show($id)
    {
        $service = Service::find($id);
        return $this->respondWithSuccess('Service fetched successfully!', $service);

    }
    
    public function edit($id)
    {
        $service = Service::find($id);
        return $this->respondWithSuccess('Service fetched successfully!', $service);
    }


    public function getServiceKey()
    {
        $key = $this->generateRandomString(6);
        $service = Service::where('service_key', $key)->first();
        if ($service) {
            $this->getServiceKey();
        }
        return $key;
    }


    // update

    public function update(Request $request, $id)
    {
        $isValidator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'keyword' => 'required',
            'validity' => 'required',
            'purchase_category_code' => 'required',
            'reference_code' => 'required',
            'channel' => 'required',
            'on_behalf_of' => 'required',
        ]);


        if ($isValidator->fails()) {
            flash()->addError($isValidator->errors()->first());
            return redirect()->route('service.index');
        }



        try {
            $service = Service::find($id);
            $service->name = $request->name;
            $service->type = $request->type;
            $service->keyword = $request->keyword;
            $service->validity = $request->validity;
            $service->purchase_category_code = $request->purchase_category_code;
            $service->reference_code = $request->reference_code;
            $service->channel = $request->channel;
            $service->on_behalf_of = $request->on_behalf_of;
            $service->save();
            flash()->addSuccess('Service updated successfully!');
        } catch (\Throwable $th) {
            flash()->addError('Something went wrong!');
        }
        return redirect()->route('service.index');
    }


    // delete

    public function destroy($id)
    {
        try {
            $service = Service::find($id);
            $service->delete();
            flash()->addSuccess('Service deleted successfully!');
            return $this->respondWithSuccess('Service deleted successfully!');
        } catch (\Throwable $th) {
            flash()->addError('Something went wrong!');
            return $this->respondWithError('Something went wrong!');
        }
    }


    public function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
