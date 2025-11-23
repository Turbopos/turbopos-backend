<?php

namespace App\Http\Controllers\Traits;

use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCode128;

trait BarcodeController
{
    public function generateBarcode($data)
    {
        $barcode = (new TypeCode128())->getBarcode($data);
        $renderer = new PngRenderer();

        $randomFilename = uniqid() . '-' . time() . '.png';

        mkdir(public_path('barcodes'), 0777, true);
        $filePath = '/barcodes/' . $randomFilename;

        file_put_contents(public_path($filePath), $renderer->render($barcode, $barcode->getWidth() * 3, 50));

        return $filePath;
    }
}
