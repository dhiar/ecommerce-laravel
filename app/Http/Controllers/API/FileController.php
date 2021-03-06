<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(Request $request) {
        $result = $request->file('file')->store('public/images');
        return ['result' => $result];
    }

    public function uploadProduct(Request $request) {
        $result = $request->file('file')->store('public/products');
        return ['result' => $result];
    }

    public function uploadSlide(Request $request) {
        $result = $request->file('file')->store('public/slides');
        return ['result' => $result];
    }

    public function uploadCategory(Request $request) {
        $result = $request->file('file')->store('public/categories');
        return ['result' => $result];
    }

    public function uploadBrand(Request $request) {
        $result = $request->file('file')->store('public/brands');
        return ['result' => $result];
    }
}