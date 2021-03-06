<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Interfaces\ProductInterface;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    protected $productInterface;
    public function __construct(ProductInterface $ProductInterface){
        $this->productInterface = $ProductInterface;
    }
    public function index(){
        return $this->productInterface->getAllProducts();
    }
    public function show($id){
        return $this->productInterface->getProductById($id);
    }
    public function store(ProductRequest $request){
        return $this->productInterface->requestProduct($request);
    }
    public function update(ProductRequest $request, $id){
        return $this->productInterface->requestProduct($request, $id);
    }
    public function deleteImage($id){
        return $this->productInterface->deleteProductImage($id);
    }
    public function destroy($id){
        return $this->productInterface->deleteProduct($id);
    }
    public function searchProductsByName(Request $request){
        return $this->productInterface->productLikeSearch($request);
    }

}
