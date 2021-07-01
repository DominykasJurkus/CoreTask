<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# Assign product to the order
$app->post('/order/product', function (Request $request, Response $response) {

	$order_id = $_POST['order_id'];
	$product_id = $_POST['product_id'];

	$sql = "INSERT INTO orders_products (order_id, product_id) VALUES 
	((SELECT id FROM orders WHERE id = :order_id), 
	(SELECT id FROM products WHERE id = :product_id))";

	try {
		$db = new DB();
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':order_id', $order_id);
		$stmt->bindParam(':product_id', $product_id);
		$stmt->execute();

		$db = null;

		return $response
			->withHeader('content-type', 'application/json')
			->withHeader('Location', '/order/' . $order_id)
			->withStatus(201);		
	} catch (PDOException $e)
	{
		$error = array(
			"message" => $e->getMessage()
		);

		$response->getBody()->write(json_encode($error));
		return $response
			->withHeader('content-type', 'application/json')
			->withStatus(500);	
	}
});