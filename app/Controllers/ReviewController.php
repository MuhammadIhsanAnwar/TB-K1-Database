<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;

class ReviewController extends Controller {
    private $reviewModel;
    private $orderModel;
    private $productModel;
    
    public function __construct() {
        $this->reviewModel = new Review();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }
    
    public function create() {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $input = $this->getInput();
        
        $errors = $this->validate($input, [
            'order_id' => 'required',
            'rating' => 'required'
        ]);
        
        if ($errors !== true) {
            return \ResponseHelper::validationError($errors);
        }
        
        // Validate rating
        if ($input['rating'] < 1 || $input['rating'] > 5) {
            return \ResponseHelper::error('Rating must be between 1 and 5');
        }
        
        // Get order
        $order = $this->orderModel->getDetailById($input['order_id']);
        
        if (!$order || $order['buyer_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        // Check if order is completed
        if ($order['status'] !== 'completed') {
            return \ResponseHelper::error('Can only review completed orders');
        }
        
        // Check if already reviewed
        if (!$this->reviewModel->canReview($input['order_id'])) {
            return \ResponseHelper::error('Order already reviewed');
        }
        
        // Get product
        $items = $this->orderModel->getItems($order['id']);
        $productId = $items[0]['product_id'];
        
        // Create review
        $reviewId = $this->reviewModel->createReview(
            $order['id'],
            $productId,
            AUTH_USER_ID,
            $order['seller_id'],
            $input['rating'],
            \SecurityHelper::sanitize($input['comment'] ?? '')
        );
        
        // Update product rating
        $this->productModel->updateRating($productId);
        
        return \ResponseHelper::success('Review submitted successfully', [
            'review_id' => $reviewId
        ], 201);
    }
    
    public function productReviews($productId) {
        $limit = $_GET['limit'] ?? 20;
        $reviews = $this->reviewModel->getByProduct($productId, $limit);
        
        return \ResponseHelper::success('Product reviews', [
            'reviews' => $reviews
        ]);
    }
    
    public function sellerResponse($id) {
        if (!defined('AUTH_USER_ID')) {
            return \ResponseHelper::unauthorized();
        }
        
        $review = $this->reviewModel->findById($id);
        
        if (!$review || $review['seller_id'] != AUTH_USER_ID) {
            return \ResponseHelper::forbidden('Access denied');
        }
        
        $input = $this->getInput();
        
        if (empty($input['response'])) {
            return \ResponseHelper::error('Response cannot be empty');
        }
        
        $this->reviewModel->addSellerResponse(
            $id,
            \SecurityHelper::sanitize($input['response'])
        );
        
        return \ResponseHelper::success('Response added successfully');
    }
}
