<?php

namespace App\Interfaces;

use App\Http\Requests\ProductRequest;

interface ProductInterface
{

    public function getAllProducts();

    public function getProductById($id);

    public function getPostsByCategoryId($id);

    public function requestProduct(ProductRequest $request, $id = null);

    public function deleteProduct($id);

    public function deleteProductImage($id);
}
