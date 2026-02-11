<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\AdImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $ads = Ad::with(['user', 'category', 'city', 'images'])
                ->active()
                ->latest()
                ->paginate(20);

        return response()->json($ads);
    }

    public function store(AdRequest $request)
    {
        $ad = Ad::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'currency' => $request->currency ?? 'IRR',
            'category_id' => $request->category_id,
            'city_id' => $request->city_id,
            'type' => $request->type ?? 'normal',
            'status' => 'pending', // نیاز به تایید مدیر
            'expires_at' => $this->calculateExpiry($request->type),
        ]);

        // آپلود تصاویر
        if ($request->has('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('ads/' . $ad->id, 'public');
                $ad->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }

        return response()->json($ad->load('images'), 201);
    }

    private function calculateExpiry($type)
    {
        return match($type) {
            'vip1' => now()->addDay(),
            'vip3' => now()->addDays(3),
            'vip7' => now()->addDays(7),
            default => null,
        };
    }
}