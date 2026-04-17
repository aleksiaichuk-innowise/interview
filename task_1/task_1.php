<?php

class DB {
    public $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=localhost;dbname=shop", "root", "123456");
    }
}

class Product {
    public $id;
    public $price;
    public $name;

    public function __construct($id, $price, $name) {
        $this->id = $id;
        $this->price = $price;
        $this->name = $name;
    }
}

class ProductRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = " . $id;
        $result = $this->db->conn->query($sql);

        return $result->fetch();
    }
}

class Logger {

    public static function log($message) {
        @file_put_contents('log.txt', $message, FILE_APPEND);
    }
}

class DiscountManager {
    public function applyDiscount($price, $type) {
        if ($type == 'summer') {
            return $price * 0.9;
        } elseif ($type == 'winter') {
            return $price * 0.8;
        }
        return $price;
    }
}

class OrderService {
    private $repo;
    private $discount;

    public function __construct($repo, $discount) {
        $this->repo = $repo;
        $this->discount = $discount;
    }


    public function checkout($userId, $productId, $promo) {
        if (!isset($_SESSION['user'])) {
            die("User not logged in");
        }

        $productData = $this->repo->getProductById($productId);

        $total = $this->discount->applyDiscount($productData['price'], $promo);

        echo "Processing payment for " . $total . " USD...";

        $sql = "INSERT INTO orders (user_id, total) VALUES (?, ?)";
        $stmt = (new DB())->conn->prepare($sql);
        $stmt->execute([$userId, $total]);

        Logger::log("Order created for user " . $userId);

        return true;
    }
}

$db = new DB();
$repo = new ProductRepository($db);
$manager = new DiscountManager();
$service = new OrderService($repo, $manager);

$service->checkout(1, $_GET['id'], 'summer');

