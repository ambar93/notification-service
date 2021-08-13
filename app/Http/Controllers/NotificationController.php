<?php

namespace App\Http\Controllers;

use App\Models\NotificationProducer;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function createNotification()
    {
        $producer = new NotificationProducer();

        $producer->produce();
        $i = rand(1,10000);

        return response()->json([
            "message" => "notification sent"
        ], 201);
    }

}
