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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceProviderInfo  $serviceProviderInfo
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceProviderInfo $serviceProviderInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceProviderInfo  $serviceProviderInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProviderInfo $serviceProviderInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceProviderInfo  $serviceProviderInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceProviderInfo $serviceProviderInfo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceProviderInfo  $serviceProviderInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceProviderInfo $serviceProviderInfo)
    {
        //
    }
}
