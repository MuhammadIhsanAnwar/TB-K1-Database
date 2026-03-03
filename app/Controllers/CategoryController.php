<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller {
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $categories = $this->categoryModel->getAllActive();
        
        // Group by parent
        $parentCategories = [];
        foreach ($categories as $category) {
            if ($category['parent_id'] === null) {
                $parentCategories[$category['id']] = $category;
                $parentCategories[$category['id']]['subcategories'] = [];
            }
        }
        
        foreach ($categories as $category) {
            if ($category['parent_id'] !== null && isset($parentCategories[$category['parent_id']])) {
                $parentCategories[$category['parent_id']]['subcategories'][] = $category;
            }
        }
        
        return \ResponseHelper::success('Categories', [
            'categories' => array_values($parentCategories)
        ]);
    }
    
    public function show($id) {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            return \ResponseHelper::notFound('Category not found');
        }
        
        $subcategories = $this->categoryModel->getSubcategories($id);
        $category['subcategories'] = $subcategories;
        
        return \ResponseHelper::success('Category details', [
            'category' => $category
        ]);
    }
}
