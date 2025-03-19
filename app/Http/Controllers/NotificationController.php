<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;

use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Requests\StoreNotificationRequest;

use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\NotificationCategory;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return  response()->json(['notifications' => Notification::paginate(5)], 200);
        //return  response()->json(['notifications' => Notification::all()], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationRequest $request)
    {
        //La notification category sera proposée dans le form
        
        $notification = Notification::create($request->validated());
        return response()->json($notification, 201);

        //
        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        
        return response()->json(['message' => 'Notification envoyée !', 'notification' => $notification], 201);


    }

    // Publier la notification
    public function publishNotification($id){
        $notification = Notification::find($id);
        // Diffuser la notification via WebSocket
        Broadcast(new \App\Events\NotificationSent($notification));


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $notification = Notification::findOrFail($id);
        return $notification;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationRequest $request, $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update($request->validated());
        return response()->json($notification);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(null, 204);

    }

    // Récupérer les notifications d’un utilisateur
    public function getUserNotifications($userId) {
        
        $userNotification = UserNotification::where('user_id', $userId)->orWhereNull('user_id')->orderBy('created_at', 'desc')->get();
        return response()->json($userNotification, 200);
    }

    // Marquer une notification comme lue
    public function markAsRead($id) {
        Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['message' => 'Notification marquée comme lue']);
    }

}
