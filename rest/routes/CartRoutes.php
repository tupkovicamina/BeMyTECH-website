<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Get(path="/carts", tags={"carts"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all carts from the API. ",
 *         @OA\Response( response=200, description="List of carts.")
 * )
 */
Flight::route("GET /carts", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::json(Flight::cart_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
  * @OA\Get(path="/carts/{id}", tags={"carts"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=18, description="Cart ID"),
  *     @OA\Response(response="200", description="Fetch individual cart")
  * )
  */
Flight::route("GET /carts/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::json(Flight::cart_service()->get_by_id($id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


 /**
* @OA\Post(
*     path="/carts", security={{"ApiKeyAuth": {}}},
*     description="Add cart",
*     tags={"carts"},
*     @OA\RequestBody(description="Add new cart item", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="user_id", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk",	description="User token"),
*    				@OA\Property(property="product_id", type="string", example="15",	description="Product ID"),
*    				@OA\Property(property="quantity", type="int", example="5",	description="Product quantity" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Cart Item has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /carts", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $request = Flight::request()->data->getData(); 
        $user_id = $request['user_id'];
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];  
        $request['user_id'] = $decoded_user_id;
        Flight::json(['message' => "cart added successfully", 'data' => Flight::cart_service()->add($request)]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
 * @OA\Put(
 *     path="/carts/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Edit Cart",
 *     tags={"carts"},
 *     @OA\Parameter(in="path", name="id", example=18, description="Address ID"),
 *     @OA\RequestBody(description="Student info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *                   @OA\Property(property="user_id", type="string", example="1",	description="User ID"),
 *    				 @OA\Property(property="product_id", type="string", example="15",	description="Product ID"),
 *    				 @OA\Property(property="quantity", type="int", example="5",	description="Product quantity" ),
 *        )
 *     )),
 *     @OA\Response(
 *         response=200,
 *         description="Cart has been edited"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("PUT /carts/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $address = Flight::request()->data->getData();    
        Flight::json(['message' => "cart edited successfully", 'data' => Flight::cart_service()->update($address, $id)]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
 * @OA\Delete(
 *     path="/carts/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Delete cart",
 *     tags={"carts"},
 *     @OA\Parameter(in="path", name="id", example=14, description="Cart ID"),
 *     @OA\Response(
 *         response=200,
 *         description="Cart deleted"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("DELETE /carts/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::cart_service()->delete($id);
        Flight::json(['message' => "cart deleted successfully"]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});

/**
  * @OA\Get(path="/carts_by_user_id/{id}", tags={"carts"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk", description="User token"),
  *     @OA\Response(response="200", description="Fetch carts for individual user")
  * )
  */
Flight::route("GET /carts_by_user_id/@user_id", function($user_id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];
        Flight::json(Flight::cart_service()->get_by_user_id($decoded_user_id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});

/**
 * @OA\Delete(
 *     path="/carts_by_user_id/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Delete cart",
 *     tags={"carts"},
 *     @OA\Parameter(in="path", name="id", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk", description="User ID"),
 *     @OA\Response(
 *         response=200,
 *         description="User Cart deleted"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("DELETE /carts_by_user_id/@user_id", function($user_id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];
        Flight::cart_service()->delete_by_user_id($decoded_user_id);
        Flight::json(['message' => "cart deleted successfully"]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});

?>