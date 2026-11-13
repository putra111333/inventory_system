<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Symfony\Component\HttpFoundation\Response;

class ProductCodeController extends Controller
{
    public function show(Product $product): View
    {
        return view('products.codes', compact('product'));
    }

    public function qrCode(Request $request, Product $product): Response
    {
        $qrCode = QrCode::create($product->code)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Medium)
            ->setSize(300)
            ->setMargin(12);

        $svg = (new SvgWriter())->write($qrCode)->getString();

        return $this->imageResponse($request, $product, 'qr', $svg);
    }

    public function barcode(Request $request, Product $product): Response
    {
        $generator = new BarcodeGeneratorSVG();
        $svg = $generator->getBarcode(
            $product->code,
            BarcodeGenerator::TYPE_CODE_128,
            2,
            70,
            '#17221e',
        );

        return $this->imageResponse($request, $product, 'barcode', $svg);
    }

    private function imageResponse(Request $request, Product $product, string $type, string $svg): Response
    {
        $filename = Str::slug($product->code)."-{$type}.svg";
        $disposition = $request->boolean('download') ? 'attachment' : 'inline';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => "{$disposition}; filename=\"{$filename}\"",
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }
}
