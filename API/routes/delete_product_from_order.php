<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# Delete assigned product from order
$app->delete('/order/{order_id}/product/{product_id}', function (Request $request, Response $response, $args) {

	$order_id = $args['order_id'];
	$product_id = $args['product_id'];

	$sql = "DELETE FROM orders_products WHERE order_id = :order_id AND product_id = :product_id LIMIT 1";
	
	try {
		$db = new DB();
		$conn = $db->connect();
		
		$sqlForCheck = "SELECT id FROM orders_products WHERE order_id = :order_id AND product_id = :product_id LIMIT 1";
		$sqlcheck = $conn->prepare($sqlForCheck);
		$sqlcheck->bindParam(':order_id', $order_id);
		$sqlcheck->bindParam(':product_id', $product_id);
		$sqlcheck->execute();

		$result = $sqlcheck->fetchAll();

		# Return code 400 if order/product does not exist
		if(empty($result))
		{
			$db = null;

			$response->getBody()->write(json_encode(array(
				'error' =>  "Order or product does not exist"
			)));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		$stmt = $conn->prepare($sql);

		$stmt->bindParam(':order_id', $order_id);
		$stmt->bindParam(':product_id', $product_id);
		$stmt->execute();

		$db = null;

		$response->getBody()->write(json_encode(array(
			'deleted' =>  "From order ID " . $order_id . " product ID " . $product_id
		)));
		return $response
			->withHeader('content-type', 'application/json')
			->withStatus(200);		
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