<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupDefaultImages extends Command
{
    protected $signature = 'setup:default-images';
    protected $description = 'Setup default images for the application';

    public function handle()
    {
        $publicPath = public_path('images');
        
        // Create images directory if it doesn't exist
        if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0755, true);
        }

        // Create no-image.jpg
        $noImage = imagecreatetruecolor(800, 400);
        $background = imagecolorallocate($noImage, 241, 245, 249); // bg-gray-100
        $textColor = imagecolorallocate($noImage, 71, 85, 105);   // text-gray-600
        
        // Fill background
        imagefill($noImage, 0, 0, $background);
        
        // Add icon-like rectangle
        $iconColor = imagecolorallocate($noImage, 148, 163, 184); // text-gray-400
        imagefilledrectangle($noImage, 300, 100, 500, 300, $iconColor);
        
        // Add text
        $text = 'GAMBAR TIDAK TERSEDIA';
        imagestring($noImage, 5, 310, 320, $text, $textColor);
        
        // Save the image
        imagejpeg($noImage, $publicPath . '/no-image.jpg', 90);
        
        // Create thumbnail version
        $noImageThumb = imagecreatetruecolor(400, 200);
        imagecopyresampled($noImageThumb, $noImage, 0, 0, 0, 0, 400, 200, 800, 400);
        imagejpeg($noImageThumb, $publicPath . '/no-image-thumb.jpg', 90);
        
        // Clean up
        imagedestroy($noImage);
        imagedestroy($noImageThumb);
        
        $this->info('Default images have been created successfully.');
    }
} 