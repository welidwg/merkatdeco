<?php

namespace App\Http\Controllers;

use App\Models\Delivery_status;
use Illuminate\Http\Request;

class DeliveryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view("deliveries_status.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Delivery_status::create($request->all());
        return response(json_encode(["success" => "done"]), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery_status  $delivery_status
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery_status $delivery_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery_status  $delivery_status
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery_status $delivery_status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery_status  $delivery_status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery_status $delivery_status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery_status  $delivery_status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery_status $delivery_status)
    {
        //
    }

    
}
