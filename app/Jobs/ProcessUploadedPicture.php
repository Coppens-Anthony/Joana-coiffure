<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProcessUploadedPicture implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $full_path_to_original, public string $new_original_path_name) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $binary = Storage::disk(config('filesystems.default'))->get($this->full_path_to_original);

        $image = Image::decodeBinary(
            $binary
        );

        $sizes = config('pictures.sizes');
        $jpg_compression = config('pictures.jpeg_compression');
        $variant_pattern = config('pictures.variant_pattern');
        $extension = config('pictures.picture_type');

        foreach ($sizes as $size) {
            $variant = clone $image;
            $variant->scale($size['width']);

            $path = sprintf($variant_pattern, $size['width'], $size['height']);
            Storage::disk(config('filesystems.default'))->put(
                $path.'/'.$this->new_original_path_name,
                $variant->encodeUsingFileExtension($extension, quality: $jpg_compression)
            );
        }


    }
}
