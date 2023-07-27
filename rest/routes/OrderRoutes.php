<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Get(path="/orders", tags={"orders"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all orders from the API. ",
 *         @OA\Response( response=200, description="List of orders.")
 * )
 */
Flight::route("GET /orders", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::order_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});

/**
  * @OA\Get(path="/orders/{id}", tags={"orders"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=55, description="Order ID"),
  *     @OA\Response(response="200", description="Fetch individual order")
  * )
  */
Flight::route("GET /orders/@id", function($id){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::order_service()->get_by_id($id));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


 /**
* @OA\Post(
*     path="/orders", security={{"ApiKeyAuth": {}}},
*     description="Add order",
*     tags={"orders"},
*     @OA\RequestBody(description="Add new order item", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="user_id", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk",	description="User token"),
*    				@OA\Property(property="order_date", type="string", example="2023-06-06", description="Order date"),
*                   @OA\Property(property="product_id", type="int", example=15,	description="Product ID"),
*    				@OA\Property(property="quantity", type="int", example=2,	description="quantity"),
*    				@OA\Property(property="address_id", type="int", example=16,	description="Address ID" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Order Item has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /orders", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "unauthorized"){
        $request = Flight::request()->data->getData();
        $user_id = $request['user_id'];
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];  
        $request['user_id'] = $decoded_user_id;    
    Flight::json(['message' => "order added successfully", 'data' => Flight::order_service()->add($request)]);
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
* @OA\Post(
*     path="/orders_by_cart",
*     security={{"ApiKeyAuth": {}}},
*     description="Add cart order",
*     tags={"orders"},
*     @OA\RequestBody(
*         description="Add new cart order",
*         required=true,
*         @OA\MediaType(
*             mediaType="application/json",
*             @OA\Schema(
*                 type="array",
*                 @OA\Items(
*                     @OA\Property(property="user_id", type="integer", example=17),
*                     @OA\Property(property="order_date", type="string", format="date", example="2023-06-06"),
*                     @OA\Property(property="product_id", type="integer", example=15),
*                     @OA\Property(property="quantity", type="integer", example=2),
*                     @OA\Property(property="address_id", type="integer", example=16),
*                     @OA\Property(property="product_name", type="string", example="test"),
*                     @OA\Property(property="product_image", type="string", example="test"),
*                     @OA\Property(property="product_price", type="integer", example=250),
*                     @OA\Property(property="id", type="integer", example=16)
*                 )
*             )
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Cart order has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /orders_by_cart", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "unauthorized"){
        $request = Flight::request()->data->getData();   
        Flight::json(['message' => "order added successfully", 'data' => Flight::order_service()->add_by_user_id($request)]);
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});

/**
  * @OA\Get(path="/orders_by_user_id/{user_id}", tags={"orders"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="user_id", example=17, description="User ID"),
  *     @OA\Response(response="200", description="Fetch individual user orders")
  * )
  */
Flight::route("GET /orders_by_user_id/@user_id", function($user_id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "unauthorized"){
        Flight::json(Flight::order_service()->get_by_user_id($user_id));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
});

?>