<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Get(path="/addresses", tags={"addresses"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all addresses from the API. ",
 *         @OA\Response( response=200, description="List of addresses.")
 * )
 */
Flight::route("GET /addresses", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::json(Flight::address_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});

/**
  * @OA\Get(path="/addresses/{id}", tags={"addresses"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=1, description="Address ID"),
  *     @OA\Response(response="200", description="Fetch individual address")
  * )
  */
Flight::route("GET /addresses/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::json(Flight::address_service()->get_by_id($id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


 /**
* @OA\Post(
*     path="/addresses", security={{"ApiKeyAuth": {}}},
*     description="Add address",
*     tags={"addresses"},
*     @OA\RequestBody(description="Add new student", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="alias", type="string", example="Home address",	description="Address alias"),
*    				@OA\Property(property="street", type="string", example="street 1",	description="Address street"),
*    				@OA\Property(property="zip_code", type="int", example="71000",	description="Address zip code" ),
*                   @OA\Property(property="city", type="string", example="Sarajevo",	description="Address city" ),
*                   @OA\Property(property="country", type="string", example="Bosnia & Herzegovina",	description="Address country" ),
*                   @OA\Property(property="user_id", type="int", example="17",	description="Address user ID" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Address has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /addresses", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $request = Flight::request()->data->getData(); 
        $user_id = $request['user_id'];
        $alias = $request['alias'];
        $existing_alias = Flight::address_service()->get_address_by_user_id_and_alias($user_id, $alias);  
        if(count($existing_alias) > 0){
            Flight::json(["message" => "You already have address with that alias. Please choose another alias"], 404);
        } else { 
            Flight::json(['message' => "address added successfully", 'data' => Flight::address_service()->add($request)]);
        }
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
 * @OA\Put(
 *     path="/addresses/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Edit address",
 *     tags={"addresses"},
 *     @OA\Parameter(in="path", name="id", example=1, description="Address ID"),
 *     @OA\RequestBody(description="Student info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *                   @OA\Property(property="alias", type="string", example="Home address",	description="Address alias"),
 *    				 @OA\Property(property="street", type="string", example="street 1",	description="Address street"),
 *    				 @OA\Property(property="zip_code", type="int", example="71000",	description="Address zip code" ),
 *                   @OA\Property(property="city", type="string", example="Sarajevo",	description="Address city" ),
 *                   @OA\Property(property="country", type="string", example="Bosnia & Herzegovina",	description="Address country" ),
 *                   @OA\Property(property="user_id", type="int", example="1",	description="Address user ID" ),
 *        )
 *     )),
 *     @OA\Response(
 *         response=200,
 *         description="Address has been edited"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("PUT /addresses/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $address = Flight::request()->data->getData();
        $user_id = $address['user_id'];
        $alias = $address['alias'];
        $existing_alias = Flight::address_service()->get_address_by_user_id_and_alias($user_id, $alias);
        if(count($existing_alias) > 0 && $existing_alias[0]['id'] != $id){
            Flight::json(["message" => "You already have address with that alias. Please choose another alias"], 404);
        } else {
            Flight::json(['message' => "address edited successfully", 'data' => Flight::address_service()->update($address, $id)]);
        }
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }   
    
});


/**
 * @OA\Delete(
 *     path="/addresses/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Delete address",
 *     tags={"addresses"},
 *     @OA\Parameter(in="path", name="id", example=14, description="Address ID"),
 *     @OA\Response(
 *         response=200,
 *         description="Address deleted"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("DELETE /addresses/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::address_service()->delete($id);
        Flight::json(['message' => "address deleted successfully"]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
  * @OA\Get(path="/addresses_by_user_id/{user_id}", tags={"addresses"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="user_id", example=17, description="User ID"),
  *     @OA\Response(response="200", description="Fetch addresses for individual user")
  * )
  */
Flight::route("GET /addresses_by_user_id/@user_id", function($user_id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        Flight::json(Flight::address_service()->get_address_by_user_id($user_id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
  * @OA\Get(path="/addresses_by_user_token/{user_token}", tags={"addresses"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="user_token", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk", description="User token"),
  *     @OA\Response(response="200", description="Fetch addresses for individual user token")
  * )
  */
Flight::route("GET /addresses_by_user_token/@user_id", function($user_id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'unauthorized') {
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];
        Flight::json(Flight::address_service()->get_address_by_user_id($decoded_user_id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});
?>