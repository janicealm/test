<?php
session_start();

// Load or initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$xmlFile = 'cart.xml';

function loadCartFromXML() {
    global $xmlFile;
    if (file_exists($xmlFile)) {
        $cartXML = simplexml_load_file($xmlFile);
        $cart = json_decode(json_encode($cartXML), true);
        return $cart['item'] ?? [];
    }
    return [];
}

function saveCartToXML($cart) {
    global $xmlFile;
    $cartXML = new SimpleXMLElement('<cart/>');
    foreach ($cart as $item) {
        $itemXML = $cartXML->addChild('item');
        $itemXML->addChild('id', $item['id']);
        $itemXML->addChild('name', $item['name']);
        $itemXML->addChild('price', $item['price']);
    }
    $cartXML->asXML($xmlFile);
}

$action = $_POST['action'];
$productId = $_POST['id'];

if ($action == 'add') {
    $productName = $_POST['name'];
    $productPrice = $_POST['price'];
    
    // Add product to cart
    $_SESSION['cart'][$productId] = [
        'id' => $productId,
        'name' => $productName,
        'price' => $productPrice
    ];
} elseif ($action == 'remove') {
    // Remove product from cart
    unset($_SESSION['cart'][$productId]);
}

// Save the cart to XML
saveCartToXML($_SESSION['cart']);

// Return the cart as a JSON response
header('Content-Type: application/json');
echo json_encode(array_values($_SESSION['cart']));
?>
