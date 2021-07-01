<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# Update product name or amount
$app->patch('/product/{product_id}', function (Request $request, Response $response, $args) {

	$product_id = $args['product_id'];

	$input_data = json_decode(file_get_contents('php://input'));

	$name = $input_data->{'name'};
	$amount = $input_data->{'amount'};

	$sql = "UPDATE products SET currency = products.currency";
	
	if ($name)
		$sql .= ", name = :name";
	if ($amount)
		$sql .= ", amount = :amount";

	$sql .= " WHERE id = :product_id";
	
	try {
		$db = new DB();
		$conn = $db->connect();

		$sqlForCheck = "SELECT id FROM products WHERE id = :product_id LIMIT 1";
		$sqlcheck = $conn->prepare($sqlForCheck);
		$sqlcheck->bindParam(':product_id', $product_id);
		$sqlcheck->execute();

		$result = $sqlcheck->fetchAll();

		if(empty($result))
		{
			$db = null;
			
			$response->getBody()->write(json_encode(array(
				'error' =>  "Product with ID: " . $product_id . " does not exist"
			)));
			return $response
			->withHeader('content-type', 'application/json')
			->withStatus(404);
		}

		$stmt = $conn->prepare($sql);

		if($name)
			$stmt->bindParam(':name', $name);
		if($amount)
			$stmt->bindParam(':amount', $amount);

		$stmt->bindParam(':product_id', $product_id);

		$stmt->execute();
		
		$responseData = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		
		return $response
			->withHeader('content-type', 'application/json')
			->withStatus(204);		
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