
# CoreTask

Completed task for the Core PHP/GO position.


## Instructions

To run the project Docker needs to be installed within the system.

Steps:

1. Download the project from the Github.
2. Open the terminal within the downloaded project folder.
3. Type:

```bash 
  docker compose up
```
4. Wait until Docker finishes executing the command.

## RESTful API

Within this project there are 6 available RESTful endpoints.

These enpoints are:
```bash 
  POST http://127.0.0.1/orders 
```
Used to create a new order.

Required parameters within the request body - *user_id*, *title*, *email*.

```bash 
 POST http://127.0.0.1/order/product 
``` 
Used to assign a product to the order.

Required parameters within the request body - *order_id*, *product_id*.

```bash 
  GET http://127.0.0.1/orders 
```
Used to list all orders and filter them by user_id, email and sort by created_at.

Optional parameters within the URI - *user_id*, *email*, *sort*.

```bash 
  GET http://127.0.0.1/order/{order_id}
```

Used to show specific order with assigned products.

Required parameter within the URI - *order_id*.

```bash 
  PATCH http://127.0.0.1/product/{product_id}
```

Used to update product name or amount.

Required parameter within the URI - *product_id*.

Optional parameters within the request body - *name*, *amount*.

```bash 
  DELETE http://127.0.0.1/order/{order_id}/product/{product_id}
```

Used to delete assigned product from order.

Required parameters within the URI - *order_id*, *product_id*.


## Examples

![API call](https://github.com/DominykasJurkus/CoreTask/blob/master/Examples/AssignProduct.png?raw=true)

  
## Author

- [@DominykasJurkus](https://www.github.com/DominykasJurkus)

  