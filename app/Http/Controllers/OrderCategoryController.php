<?php

namespace App\Http\Controllers;

use App\Models\orderCategorie;
use Illuminate\Http\Request;

class OrderCategoryController extends Controller
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
        return view("order_category.create");
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
        orderCategorie::create($request->all());
        return response("tesrt", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\orderCategorie  $orderCategorie
     * @return \Illuminate\Http\Response
     */
    public function show(orderCategorie $orderCategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\orderCategorie  $orderCategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(orderCategorie $orderCategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\orderCategorie  $orderCategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, orderCategorie $orderCategorie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\orderCategorie  $orderCategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy(orderCategorie $orderCategorie)
    {
        //
    }
}
