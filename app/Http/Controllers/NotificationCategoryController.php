<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateNotificationCategoryRequest;
use App\Http\Requests\StoreNotificationCategoryRequest;

use App\Models\Notification;
use App\Models\NotificationCategory;
use App\Models\User;

class NotificationCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //return Category::all();
        return NotificationCategory::paginate(4);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotificationCategoryRequest $request)
    {
        //
        $notificationCategory = NotificationCategory::create($request->validated());
        return response()->json($notificationCategory, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id /*Category $notificationCategory*/)
    {
        $notificationCategory = NotificationCategory::findOrFail($id);
        return $notificationCategory;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotificationCategoryRequest $request, string $id /*Category $notificationCategory*/)
    {
        $notificationCategory = NotificationCategory::findOrFail($id);
        $notificationCategory->update($request->validated());
        return response()->json($notificationCategory);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id /*Category $notificationCategory*/)
    {
        $notificationCategory = NotificationCategory::findOrFail($id);
        $notificationCategory->delete();
        return response()->json(null, 204);

    }
}
