<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product, ProductImageService $imageService)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        try {
            // Process the image and get the base path
            $basePath = $imageService->processAndSave($request->file('image'), $product->id);

            // Check if this is the first image to make it primary automatically
            $isPrimary = $product->images()->count() === 0;

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $basePath,
                'sort_order' => $product->images()->max('sort_order') + 1,
                'is_primary' => $isPrimary,
            ]);

            return back()->with('success', __('messages.dashboard.products.images.uploaded_successfully'));
        } catch (\Exception $e) {
            Log::error('Something went wrong processing the image.', [
        'exception' => $e]);
            return back()->withErrors(['image' => __('messages.dashboard.products.images.upload_failed')]);
        }
    }

    public function destroy(Product $product, ProductImage $image, ProductImageService $imageService)
    {
        if ($image->product_id === $product->id) {
            $imageService->deleteImages($image->path);
            $image->delete();

            // If we deleted the primary, make the next one primary
            if ($image->is_primary) {
                $nextImage = $product->images()->orderBy('sort_order')->first();
                if ($nextImage) {
                    $nextImage->update(['is_primary' => true]);
                }
            }

            return back()->with('success', __('messages.dashboard.products.images.deleted_successfully'));
        }
        
        abort(403);
    }
}