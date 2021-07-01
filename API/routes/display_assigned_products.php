<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# Show specific order with assigned products
$app->get('/order/{order_id}', function (Request $request, Response $response, $args) {

	$order_id = $args['order_id'];

	$sql = "SELECT a.id, a.name, a.amount, a.currency, count(*) AS quantity FROM products AS a
	INNER JOIN orders_products AS b ON b.product_id = a.id
	WHERE b.order_id = :order_id
	GROUP BY id, name, amount, currency";
	
	try {
		$db = new DB();
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':order_id', $order_id);
		$stmt->execute();

		$responseData = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;

		if(empty($responseData))
		{
			$db = null;

			$response->getBody()->write(json_encode(array(
				'error' =>  "Order with id " . $order_id . " does not exist"
			)));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		$response->getBody()->write(json_encode($responseData));
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