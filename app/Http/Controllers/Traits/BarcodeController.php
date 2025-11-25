<?php

namespace App\Http\Controllers\Traits;

use Picqer\Barcode\Renderers\PngRenderer;
use Picqer\Barcode\Types\TypeCode128;

class BarcodeItem
{
    public $id;
    public $type;

    const PRODUCT = 'product';

    public function __construct($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
        ];
    }
}

trait BarcodeController
{
    public function generateBarcode(BarcodeItem $item)
    {
        $barcode = (new TypeCode128())->getBarcode(json_encode($item->toArray()));
        $renderer = new PngRenderer();

        $randomFilename = uniqid() . '-' . time() . '.png';

        mkdir(public_path('barcodes'), 0777, true);
        $filePath = '/barcodes/' . $randomFilename;

        file_put_contents(public_path($filePath), $renderer->render($barcode, $barcode->getWidth() * 3, 50));

        return $filePath;
    }
}
