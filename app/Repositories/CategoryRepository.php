<?php

namespace App\Repositories;

use App\Http\Requests\CategoryRequest;
use App\Interfaces\CategoryInterface;
use App\Traits\ResponseAPI;
use App\Models\Category;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryRepository implements CategoryInterface
{
    // Use ResponseAPI Trait in this repository
    use ResponseAPI;
    public function getAllCategories()
    {
        try {
            $categories = Category::all();
            return $this->success("All Categories", $categories);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getCategoryById($id)
    {
        try {
            $category = Category::find($id);
            // Check the user
            if(!$category) return $this->error("No category with ID $id", 404);
            return $this->success("Category Detail", $category);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestCategory(CategoryRequest $request, $id = null)
    {
        DB::beginTransaction();
        try {
            // If Category exists when we find it
            // Then update the Category
            // Else create the new one.
            $category = $id ? Category::find($id) : new Category;
            // Check the category
            if($id && !$category) return $this->error("No category with ID $id", 404);
            $category->name = $request->name;
            $category->slug = Str::slug($request->name);
            $category->created_by = auth()->id();
            $category->updated_by = $id  ? auth()->id() : NULL;
            // Save the category
            $category->save();
            DB::commit();
            return $this->success(
                $id ? "Category updated"
                    : "Category created",
                $category, $id ? 200 : 201);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deleteCategory($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::find($id);
            // Check the category
            if(!$category) return $this->error("No category with ID $id", 404);
            // Delete the category
            $category->delete();
            DB::commit();
            return $this->success("Category deleted", $category);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
