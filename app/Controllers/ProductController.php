<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getAllActive($limit, $offset);
        
        return \ResponseHelper::success('Products retrieved', [
            'products' => $products,
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    }
    
    public function show($id) {
        $product = $this->productModel->getDetailById($id);
        
        if (!$product) {
            return \ResponseHelper::notFound('Product not found');
        }
        
        // Increment view count
        $this->productModel->incrementView($id);
        
        return \ResponseHelper::success('Product detail', ['product' => $product]);
    }
    
    public function search() {
        $keyword = $_GET['q'] ?? '';
        $limit = $_GET['limit'] ?? 20;
        
        if (empty($keyword)) {
            return \ResponseHelper::error('Search keyword is required');
        }
        
        $products = $this->productModel->search($keyword, $limit);
        
        return \ResponseHelper::success('Search results', [
            'products' => $products,
            'keyword' => $keyword
        ]);
    }
    
    public function byCategory($slug) {
        $category = $this->categoryModel->findBySlug($slug);
        
        if (!$category) {
            return \ResponseHelper::notFound('Category not found');
        }
        
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 20;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getByCategory($category['id'], $limit, $offset);
        
        return \ResponseHelper::success('Products by category', [
            'category' => $category,
            'products' => $products,
            'page' => (int)$page,
            'limit' => (int)$limit
        ]);
    }
    
    public function create() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        if (AUTH_USER_ROLE !== 'seller') {
            return \ResponseHelper::forbidden('Only sellers can create products');
        }
        
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required',
            'product_type' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        // Generate slug
        $slug = \StringHelper::slug($input['name']);
        
        // Check if slug exists
        $existing = $this->productModel->findOne(['slug' => $slug]);
        if ($existing) {
            $slug .= '-' . time();
        }
        
        // Handle image upload (simplified for demo)
        $thumbnail = $input['thumbnail'] ?? null;
        $images = isset($input['images']) ? json_encode($input['images']) : null;
        
        $productId = $this->productModel->create([
            'seller_id' => AUTH_USER_ID,
            'category_id' => $input['category_id'],
            'name' => \SecurityHelper::sanitize($input['name']),
            'slug' => $slug,
            'description' => \SecurityHelper::sanitize($input['description']),
            'price' => $input['price'],
            'discount_price' => $input['discount_price'] ?? null,
            'product_type' => $input['product_type'],
            'delivery_method' => $input['delivery_method'] ?? 'manual',
            'stock_type' => $input['stock_type'] ?? 'limited',
            'stock_quantity' => $input['stock_quantity'] ?? 0,
            'thumbnail' => $thumbnail,
            'images' => $images,
            'is_active' => true
        ]);
        
        return \ResponseHelper::success('Product created successfully', [
            'product_id' => $productId
        ], 201);
    }
    
    public function update($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            return \ResponseHelper::notFound('Product not found');
        }
        
        if ($product['seller_id'] != AUTH_USER_ID && AUTH_USER_ROLE !== 'admin') {
            return \ResponseHelper::forbidden('You can only update your own products');
        }
        
        $input = $this->getInput();
        
        $updateData = [];
        
        if (isset($input['name'])) {
            $updateData['name'] = \SecurityHelper::sanitize($input['name']);
            $updateData['slug'] = \StringHelper::slug($input['name']);
        }
        
        if (isset($input['description'])) {
            $updateData['description'] = \SecurityHelper::sanitize($input['description']);
        }
        
        if (isset($input['price'])) {
            $updateData['price'] = $input['price'];
        }
        
        if (isset($input['discount_price'])) {
            $updateData['discount_price'] = $input['discount_price'];
        }
        
        if (isset($input['stock_quantity'])) {
            $updateData['stock_quantity'] = $input['stock_quantity'];
        }
        
        if (isset($input['is_active'])) {
            $updateData['is_active'] = $input['is_active'];
        }
        
        $this->productModel->update($id, $updateData);
        
        return \ResponseHelper::success('Product updated successfully');
    }
    
    public function delete($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            return \ResponseHelper::notFound('Product not found');
        }
        
        if ($product['seller_id'] != AUTH_USER_ID && AUTH_USER_ROLE !== 'admin') {
            return \ResponseHelper::forbidden('You can only delete your own products');
        }
        
        // Soft delete
        $this->productModel->softDelete($id);
        
        return \ResponseHelper::success('Product deleted successfully');
    }
    
    public function sellerProducts() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $products = $this->productModel->getBySeller(AUTH_USER_ID);
        
        return \ResponseHelper::success('Your products', [
            'products' => $products
        ]);
    }
}
