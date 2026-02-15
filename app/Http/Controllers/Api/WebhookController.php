<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|string',
            'status' => 'required|in:success, failed'
        ]);

        $enrollment = Enrollment::where('order_id', $data['order_id'])->first();
        if(!$enrollment){
            return response()->json(['error' => 'order not found'], 404);
        }

        $enrollment->payment_status = $data['status'];
        $enrollment->save();
        return response()->noContent();
    }
}
