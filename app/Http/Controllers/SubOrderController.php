<?php

namespace App\Http\Controllers;

use App\Events\NotifRole;
use App\Events\SendNotification;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Status;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $sub = SubOrder::create($request->all());
            if ($sub) {
                $title = "Nouvelle tache";
                $content = "Vous avez une nouvelle tâche";
                $user_id = $sub->user_id;
                $notif = Notification::create(["title" => $title, "content" => $content, "user_id" => $user_id]);

                event(new SendNotification($notif, $user_id));
            }
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
            $role = Auth::user()->role;
            $id = $request->sub_id;
            $sub = SubOrder::find($id);
            $data = $request->all();
            $status = Status::find($request->status_id);
            if ($status->label == "Prête") {

                $data["end_date"] = date("Y-m-d");
                $count = count($sub->order->sub_orders);
                if ($count == 1) {
                    $sub->order->update(["status_id" => $request->status_id]);
                } else {
                    $i = 0;
                    foreach ($sub->order->sub_orders as $sb) {
                        if ($sb->status_id == $status->id) $i++;
                    }
                    if ($i == $count - 1) {
                        $sub->order->update(["status_id" => $request->status_id]);
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
            }
            if ($sub->update($data)) {
                $title = "Prestation modifié";
                $role == 0 ? $content = "L'administrateur a modifié la prestation #" . $sub->id
                    : $content = "Le founisseur " . Auth::user()->login . " a mis à jour sa prestation liée à la commande #" . $sub->order->id;

                $ncr = new NotificationController();
                if ($role == 0) {
                    $ncr->sendNotif(["title" => $title, "content" => $content, "user_id" => $sub->user->id]);
                } else {
                    $ncr->sendNotif(["title" => $title, "content" => $content, "to_role" => 0]);
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
