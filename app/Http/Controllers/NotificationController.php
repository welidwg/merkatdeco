<?php

namespace App\Http\Controllers;

use App\Events\NotifRole;
use App\Events\SendNotification;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view("notifications.index");
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
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
        $notification->delete();
        return response($notification->id, 201);
    }
    public function empty(Request $request)
    {
        try {
            if ($request->role == 0) {
                $notifs = Notification::where("to_role", 0)->get();
            } else {
                $notifs = Notification::where("user_id", $request->user_id)->get();
            }
            if ($notifs) {
                foreach ($notifs as $notif) {
                    $notif->delete();
                }
            }
            return response($request->all(), 201);
        } catch (\Throwable $th) {
            return response($th->getMessage(), 500);
        }
    }

    function sendNotif($data)
    {
        $notification = Notification::create($data);
        if ($notification) {
            if (array_key_exists("user_id", $data)) {
                event(new SendNotification($notification, $data["user_id"]));
            } else {
                event(new NotifRole($notification, $data["to_role"]));
            }
        }
    }
}
