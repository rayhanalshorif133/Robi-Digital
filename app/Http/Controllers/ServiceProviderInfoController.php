<?php

namespace App\Http\Controllers;

use App\Models\ServiceProviderInfo;
use Illuminate\Http\Request;

class ServiceProviderInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $serviceProviderInfo = ServiceProviderInfo::first();
        return view('service-provider-info.index', compact('serviceProviderInfo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceProviderInfo  $serviceProviderInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $serviceProviderInfo)
    {
       try {
        $serviceProviderInfo = ServiceProviderInfo::find($serviceProviderInfo);
        $serviceProviderInfo->aoc_endpoint_url = $request->aoc_endpoint_url;
        $serviceProviderInfo->aoc_redirection_url = $request->aoc_redirection_url;
        $serviceProviderInfo->sp_username = $request->sp_username;
        $serviceProviderInfo->sp_api_key = $request->sp_api_key;
        $serviceProviderInfo->aoc_getAOCToken_url = $request->aoc_getAOCToken_url;
        $serviceProviderInfo->save();
        flash()->addSuccess('Service Provider Info updated successfully!');
        return redirect()->back();
       } catch (\Throwable $th) {
        flash()->addError($th->getMessage());
        return redirect()->back();
       }
    }

}
