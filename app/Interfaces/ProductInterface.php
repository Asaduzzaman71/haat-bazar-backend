<?php

namespace App\Interfaces;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

interface ProductInterface
{

    public function getAllProducts();

    public function getProductById($id);

    public function getProductsByCategoryId($id);

    public function requestProduct(ProductRequest $request, $id = null);

    public function productLikeSearch(Request $request);

    public function deleteProduct($id);

    public function deleteProductImage($id);
}
