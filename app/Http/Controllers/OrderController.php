<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        $status = Status::all();

        return view("orders.index", compact("orders", "status"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $govs = Governorate::all();
        $prods = Product::all();
        $status = Status::all();

        return view("orders.create", ["govs" => $govs, "prods" => $prods, "status" => $status]);
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
            Order::create($request->all());
            return response(json_encode(["success" => "done"]), 201);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        try {
            $old_status = $order->status->label;
            $order->update($request->all());
            $status = Status::find($request->status_id);
            if ($old_status != $status->label) {
                if ($old_status == "Livrée") {
                    $order->delivery->delete();
                }
            }
            if ($status && $status->label == "Prête") {
                foreach (json_decode($order->products) as $prod) {
                    $pr = Product::find($prod->id);
                    if ($pr) {
                        $qte = $prod->qte;
                        $pr->stock += $qte;
                        $pr->save();
                    }
                }
            }
            return response(json_encode(["success" => "done"]), 200);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
        try {
            $order->delete();
            return response(json_encode(["success" => "done"]), 200);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }
}
