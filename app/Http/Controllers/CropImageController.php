<?php

namespace App\Http\Controllers;

use App\Media;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image; // Import the Image class

class CropImageController extends Controller
{
    public function index()
    {
        return view('cromImage'); // Assuming you have a view named 'cropImage'
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image_base64' => 'required',
        ]);

        $width = 800; // Set the desired width
        $height = 600; // Set the desired height

        $input['name'] = $this->storeBase64($request->image_base64, $width, $height);
        Media::create($input);

        return back()->with('success', 'Image uploaded and resized successfully.');
    }

    private function storeBase64($imageBase64, $width, $height)
    {
        list($type, $imageBase64) = explode(';', $imageBase64);
        list(, $imageBase64) = explode(',', $imageBase64); // Fixed the typo here
        $imageBase64 = base64_decode($imageBase64);

        $imageName = time() . '.png';
        $path = public_path('uploads/' . $imageName);

        // Create an Intervention Image instance and resize it to the desired dimensions
        $image = Image::make($imageBase64);
        $image->fit($width, $height);

        // Save the image
        $image->save($path);

        return $imageName;
    }
}
