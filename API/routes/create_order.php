<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

# Create new order
$app->post('/orders', function (Request $request, Response $response) {

	$user_id = $_POST['user_id'];
	$title = $_POST['title'];
	$email = $_POST['email'];

	$sql = "INSERT INTO orders (user_id, title, email) values(:user_id, :title, :email)";

	try {
		$db = new DB();
		$conn = $db->connect();

		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':user_id', $user_id);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		$db = null;

		return $response
			->withHeader('content-type', 'application/json')
			->withHeader('Location', '/orders')
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