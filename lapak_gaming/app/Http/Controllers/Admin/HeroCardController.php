<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HeroCardController extends Controller
{
    public function edit()
    {
        $heroCard = HeroCard::first();
        if (!$heroCard) {
            $heroCard = new HeroCard();
        }
        return view('dashboard.admin.hero-card', compact('heroCard'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'option1_value' => 'required|string|max:255',
            'option1_price' => 'required|string|max:255',
            'option2_value' => 'required|string|max:255',
            'option2_price' => 'required|string|max:255',
            'payment_text' => 'required|string|max:255',
            'region_text' => 'required|string|max:255',
            'promo_badge' => 'required|string|max:255',
        ]);

        $heroCard = HeroCard::first();
        if (!$heroCard) {
            $heroCard = new HeroCard();
        }

        $heroCard->fill($request->except(['cropped_image']));

        if ($request->filled('cropped_image')) {
            $imageParts = explode(";base64,", $request->cropped_image);
            if (count($imageParts) == 2) {
                $imageTypeAux = explode("image/", $imageParts[0]);
                $imageType = $imageTypeAux[1];
                $imageBase64 = base64_decode($imageParts[1]);
                $fileName = 'hero_card_' . Str::random(10) . '.' . $imageType;
                
                // Save to public storage
                Storage::disk('public')->put('images/hero/' . $fileName, $imageBase64);
                
                // Delete old image if exists
                if ($heroCard->image_path) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $heroCard->image_path));
                }

                $heroCard->image_path = 'storage/images/hero/' . $fileName;
            }
        }

        $heroCard->save();

        return redirect()->back()->with('success', 'Hero Card updated successfully!');
    }
}
