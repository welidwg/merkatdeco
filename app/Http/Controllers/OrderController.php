<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Delivery;
use App\Models\Governorate;
use App\Models\Order;
use App\Models\orderCategorie;
use App\Models\Product;
use App\Models\Source;
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
        $prods = Product::all();
        $categs = orderCategorie::all();
        $govs = Governorate::all();



        return view("orders.index", compact("orders", "status", "prods", "categs", "govs"));
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
        $sources = Source::all();
        $categs = orderCategorie::all();

        return view("orders.create", ["govs" => $govs, "prods" => $prods, "status" => $status, "sources" => $sources, "categs" => $categs]);
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
                if ($old_status == "Livrée" && $order->delivery != null) {
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
            if ($status && $status->label == "Livrée") {
                Delivery::create(["order_id" => $order->id, "delivery_date" => date("Y-m-d")]);
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


    function table($cat, $stat, $reg, $search)
    {
        if ($cat == 0 && $stat == 0 && $reg == 0 && $search == 'all') {
            $orders = Order::all();
        } else if ($cat == 0 || $stat == 0 || $reg == 0 || $search == 'all') {
            $query = Order::query();

            if ($cat != 0) {
                $query->where("category_id", $cat);
            }

            if ($stat != 0) {
                $query->where("status_id", $stat);
            }

            if ($reg != 0) {
                $query->where("governorate_id", $reg);
            }
            if ($search != 'all') {
                $query->where("client", "like", "%$search%")->orWhere("phone", $search)->orWhere("id", $search);
            }

            $orders = $query->get();
        } else {
            $query = Order::query();
            $query->where("category_id", $cat)->where("status_id", $stat)->where("governorate_id", $reg);
            $query->where("client", "like", "%$search%")->orWhere("phone", $search)->orWhere("id", $search);
            $orders = $query->get();
        }


        $status = Status::all();
        $users = new  Account;
        $subcs = $users->getSubContractor();

        return view("orders.table", compact("orders", "status", "cat", "stat", "reg", "search", "subcs"));
    }
}
