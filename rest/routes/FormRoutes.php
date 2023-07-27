<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


/**
 * @OA\Get(path="/forms", tags={"forms"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all forms from the API. ",
 *         @OA\Response( response=200, description="List of forms.")
 * )
 */
Flight::route("GET /forms", function(){
    $user = Flight::get('user');
    if(isset($user)) {
        Flight::json(Flight::form_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
    
});


/**
* @OA\Post(
*     path="/forms", security={{"ApiKeyAuth": {}}},
*     description="Add form",
*     tags={"forms"},
*     @OA\RequestBody(description="Add new form", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="name", type="string", example="Tijana Burazorovic",	description="User name"),
*    				@OA\Property(property="email", type="string", example="tijana.burazorovic@stu.ibu.edu.ba",	description="User email" ),
*                   @OA\Property(property="user_id", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk",	description="User token"),
*    				@OA\Property(property="subject", type="string", example="Subject",	description="Form subject"),
*    				@OA\Property(property="message", type="string", example="Message",	description="Form message" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Form has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /forms", function(){
    $user = Flight::get('user');
    if(isset($user)) {
        $request = Flight::request()->data->getData(); 
        $user_id = $request['user_id'];
        $decoded = (array)JWT::decode($user_id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];  
        $request['user_id'] = $decoded_user_id;
        Flight::json(['message' => "form added successfully", 'data' => Flight::form_service()->add($request)]);
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
    
});

?>