<?php

namespace App\Repositories;

use App\Http\Requests\ProductRequest;
use App\Interfaces\ProductInterface;
use App\Traits\ResponseAPI;
use App\Models\Product;
use App\Models\ProductImage;
use \Illuminate\Support\Facades\DB;
use App\Traits\FileUpload;
use Illuminate\Support\Facades\Storage;


class ProductRepository implements ProductInterface
{
    // Use ResponseAPI Trait in this repository
    use ResponseAPI;
    // Use FileUpload Trait in this repository
    use FileUpload;


    public function getAllProducts()
    {
        try {
            $products = Product::with('productImages')->get();
            return $this->success("All Product", $products);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::find($id);
            // Check the post
            if(!$product) return $this->error("No product with ID $id", 404);
            return $this->success("Product Detail", $product);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getProductsByCategoryId($id){

        try {
            $products = Product::with('productImages')->where('category_id',$id)->latest()->get();
            // Check the post
            if( count($products) > 0 )   return $this->success("Product list", $products);
            return $this->error("No product with category ID $id", 404);

        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestProduct(ProductRequest $request, $id = null)
    {
        DB::beginTransaction();
        try {

            $product = $id ? Product::find($id) : new Product;
            // Check the Post
            if($id && !$product) return $this->error("No product with ID $id", 404);
            $product->category_id = (int)$request->category_id;
            $product->name = $request->name;
            //$post->slug = Str::slug($request->title);
            $product->unit_price = $request->unit_price;
            $product->no_of_available_products = $request->no_of_available_products;
            $product->description = $request->description;
            $product->author_id = auth()->id();
            // Save the Post
            $product->save();

            if($request->hasFile('images')){
                foreach($request->file('images') as $image){
                    $path = $this->FileUpload($image,'blog');
                    $productImage = new ProductImage();
                    $productImage->product_id =  $product->id;
                    $productImage->image =  $path;
                    $productImage->save();
                }
            }

            $product = Product::with('productImages')->where('id',$product->id)->first();
            DB::commit();
            return $this->success(
                $id ? "Product updated"
                    : "Product created",
                $product, $id ? 200 : 201);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deleteProduct($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);

            // Check the Product
            if(!$product) return $this->error("No product with ID $id", 404);

            // Delete the Product
            $product->delete();
            $productImages = ProductImage::where('product_id',$id)->get();
            foreach($productImages as $productImage){
                Storage::disk('public')->delete($productImage->image);
                $productImage->delete();
            }

            DB::commit();
            return $this->success("Product deleted", $product);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deleteProductImage($id)
    {
        DB::beginTransaction();
        try {
            $productImage = ProductImage::find($id);
            Storage::disk('public')->delete($productImage->image);
            if(!$productImage) return $this->error("No product image with ID $id", 404);
            $productImage->delete();

            DB::commit();
            return $this->success("Product Image deleted", $productImage);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
