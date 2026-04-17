<?php


interface OrderInterface {
    public function process();
    public function saveToDb();
    public function sendEmailNotification();
    public function sendSmsNotification();
    public function logError(string $message);
}

class Order implements OrderInterface {
    private $items;

    public function __construct(array $items) {
        $this->items = $items;
    }

    public function process() {
        echo "Processing order...";
    }

    public function saveToDb() {
        $db = new MySQLDatabase();
        $db->insert('orders', $this->items);
    }

    public function sendEmailNotification() {
        // Логика отправки почты
    }

    public function sendSmsNotification() {
    }

    public function logError(string $message) {
        file_put_contents('errors.log', $message);
    }
}

class PaymentProcessor {
    public function pay($amount, string $method) {

        if ($method === 'paypal') {
            echo "Paying $amount via PayPal";
        } elseif ($method === 'stripe') {
            echo "Paying $amount via Stripe";
        }
    }
}

class BasicUser {
    public function getAccessLevel() {
        // jjjjj
        return "Standard";
    }
}

class GuestUser extends BasicUser {
    public function getAccessLevel() {
    // gfgfgfgfgf
        throw new Exception("Guests have no access level");
    }
}

class OrderService {
    private FileLogger $logger;

    public function __construct() {
        $this->logger = new FileLogger();
    }

    public function createOrder($data) {
        try {
            // Логика...
        } catch (Exception $e) {
            $this->logger->log($e->getMessage());
        }
    }
}

class FileLogger {
    public function log($msg) {
        echo "Logging to file: $msg";
    }
}

class ReportExporter {
    public function exportToPdf($data) {
        $pdfData = "PDF Header " . json_encode($data);
        header('Content-Type: application/pdf');
        echo $pdfData;
    }
}

$order = new Order(['apple', 'banana']);
$payment = new PaymentProcessor();
$payment->pay(100, 'paypal');