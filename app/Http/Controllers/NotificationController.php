<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Requests\StoreNotificationRequest;

use App\Models\Notification;
use App\Models\NotificationCategory;
use App\Models\User;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return Notification::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        //
        $notification = Notification::create($request->validated());
        return response()->json($notification, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(/*string $id*/ Notification $notification)
    {
        //
        return $notification;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, /*string $id*/ Notification $notification)
    {
        //
        $notification->update($request->validated());
        return response()->json($notification);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(/*string $id*/ Notification $notification)
    {
        //
        $notification->delete();
        return response()->json(null, 204);

    }
}
