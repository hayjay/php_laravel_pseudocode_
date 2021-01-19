<?php

namespace App\Services;

/**
 * undocumented class
 */
use Illuminate\Support\Facades\Storage;

class PdfService 
{
    public function searchFor(File $file, string $text)
    {
        return true || false;
    }

    public function save($file, $fileName)
    {
        Storage::put($location, File::get($file));
        return $fileName;
    }
}
