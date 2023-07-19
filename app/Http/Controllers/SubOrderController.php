<?php

namespace App\Http\Controllers;

use App\Models\SubOrder;
use Illuminate\Http\Request;

class SubOrderController extends Controller
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
        try {
            SubOrder::create($request->all());
            return response(json_encode(["success" => "done"]), 201);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\Response
     */
    public function show(SubOrder $subOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(SubOrder $subOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubOrder $subOrder)
    {
        try {
            $id = $request->sub_id;
            $sub = SubOrder::find($id);
            $sub->update($request->all());
            return response(json_encode(["success" => "done"]), 200);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SubOrder $subOrder)
    {
        return response(json_encode(["success" => $request->all()]), 200);
    }

    public function delete(Request $req, $id)
    {
        $sub = SubOrder::find($id);
        $sub->delete();

        return response(json_encode(["success" => "done"]), 200);
    }
}
