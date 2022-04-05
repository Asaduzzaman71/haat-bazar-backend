<?php

namespace App\Interfaces;

use App\Http\Requests\CategoryRequest;

interface CategoryInterface
{

    public function getAllCategories();

    public function getCategoryById($id);

    public function requestCategory(CategoryRequest $request, $id = null);

    public function deleteCategory($id);
}
