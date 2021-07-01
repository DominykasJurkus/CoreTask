<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# List all orders and be able to filter them by user_id, email and sort by created_at
$app->get('/orders', function (Request $request, Response $response) {

	$user_id = $_GET['user_id'];
	$email = $_GET['email'];
	$sort = $_GET['sort'];

	$sql = "SELECT * FROM Products.orders WHERE 1 = 1";
	
	if ($user_id)
		$sql .= " AND user_id=:user_id";
	if ($email)
		$sql .= " AND email=:email";
	if ($sort == "ASC")
		$sql .= " ORDER BY created_at ASC";
	if ($sort == "DESC")
		$sql .= " ORDER BY created_at DESC";
	
	try {
		$db = new DB();
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);

		if($user_id)
			$stmt->bindParam(':user_id', $user_id);
		if($email)
			$stmt->bindParam(':email', $email);

		$stmt->execute();

		$responseData = $stmt->fetchAll(PDO::FETCH_OBJ);

		$db = null;

		if(empty($responseData))
		{
			$db = null;
			
			$response->getBody()->write(json_encode(array(
				'error' =>  "There are no orders"
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