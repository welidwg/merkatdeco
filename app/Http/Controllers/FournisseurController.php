<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Status;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    function index()
    {
        $subs = SubOrder::where("user_id", Auth::id())->get();
        return view("fournisseur.suborders", compact("subs"));
    }
    function statusUpdate(Request $req, $id)
    {
        try {
            $sub = SubOrder::find($id);
            if ($req->status == "done") {
                $status = Status::where("label", "like", "%Prête%")->first();
                $sub->update(["end_date" => date("Y-m-d")]);
                $count = count($sub->order->sub_orders);
                if ($count == 1) {
                    if ($status->label == "Prête")
                        $sub->order->update(["status_id" => $status->id]);
                } else {
                    $i = 0;
                    foreach ($sub->order->sub_orders as $sb) {
                        if ($sb->status->label == "Prête") $i++;
                    }
                    if ($i == $count - 1) {
                        $sub->order->update(["status_id" => $status->id]);
                        foreach (json_decode($sub->order->products) as $prod) {
                            $pr = Product::find($prod->id);
                            if ($pr) {
                                $qte = $prod->qte;
                                $pr->stock += $qte;
                                $pr->save();
                            }
                        }
                    }
                }
            } else
                $status = Status::where("label", "like", "%Annulée%")->first();
            $sub->update(["status_id" => $status->id]);
            return response(json_encode(["success" => "done"]), 201);
        } catch (\Throwable $th) {
            return response(json_encode(["error" => $th->getMessage()]), 500);
        }
    }
}
