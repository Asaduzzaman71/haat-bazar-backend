<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Interfaces\CategoryInterface;

class CategoryController extends Controller
{
    protected $CategoryInterface;
    public function __construct(CategoryInterface $categoryInterface)
    {
        $this->categoryInterface = $categoryInterface;
    }

    public function index(){
        return $this->categoryInterface->getAllCategories();
    }

    public function store(CategoryRequest $request){
        return $this->categoryInterface->requestCategory($request);
    }
    public function update(CategoryRequest $request, $id)
    {
        return $this->categoryInterface->requestCategory($request, $id);
    }
    public function destroy($id)
    {
        return $this->categoryInterface->deleteCategory($id);
    }

}
