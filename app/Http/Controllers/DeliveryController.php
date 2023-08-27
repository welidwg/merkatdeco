<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Delivery_status;
use App\Models\Order;
use App\Models\Product;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliveries = Delivery::all();
        if (Auth::user()->role == 1) {
            $deliveries = Delivery::where("user_id", Auth::id())->get();
        }
        return view("delivery.index", compact("deliveries"));
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
            $status = Status::where("label", "like", "%En livraison%")->first();
            $status_delivery = Delivery_status::where("label", "like", "%En cours%")->first();

            foreach ($request->orders as  $id) {
                $order = Order::find($id);


                $delivery = Delivery::create([
                    "order_id" => $id,
                    "user_id" => $request->user_id,
                    "status_id" => $status_delivery->id,
                    "affected_date" => date("Y-m-d")
                ]);
                if ($delivery->id != null) {
                    $order->update(["status_id" => $status->id]);
                    foreach (json_decode($order->products) as $prod) {
                        $pr = Product::find($prod->id);
                        if ($pr) {
                            $qte = $prod->qte;
                            $pr->stock += $qte;
                            $pr->save();
                        }
                    }
                }
            }
            return response(json_encode(["success" => $request->orders]), 201);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
        $delivery->delete();
    }

    function statusUpdate($id, $status)
    {
        try {
            if ($status == "done") {
                $stat = Delivery_status::where("label", "like", "%Terminée%")->first();
                $stat_cmd = Status::where("label", "like", "%Livrée%")->first();
            } else {
                $stat = Delivery_status::where("label", "like", "%Annulée%")->first();
                $stat_cmd = Status::where("label", "like", "%Annulée%")->first();
            }
            $delivery = Delivery::find($id);
            $delivery->update(["status_id" => $stat->id]);
            if ($status == "done") {
                $delivery->update(["end_date" => date("Y-m-d")]);
            }
            $delivery->order->update(["status_id" => $stat_cmd->id]);
            return response(json_encode(["success" => "done"]), 200);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }
}
