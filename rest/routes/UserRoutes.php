<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * @OA\Get(path="/users", tags={"users"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all users from the API. ",
 *         @OA\Response( response=200, description="List of users.")
 * )
 */
Flight::route("GET /users", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::user_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});



/**
 * @OA\Get(
 *     path="/user_by_id", tags={"users"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         in="query",
 *         name="id",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *             example=17
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fetch individual user"
 *     )
 * )
 */
Flight::route("GET /user_by_id", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::user_service()->get_by_id(Flight::request()->query['id']));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
  * @OA\Get(path="/users/{id}", tags={"users"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MTcsIm5hbWUiOiJ0ZXN0MiIsImF1dGhvcml6YXRpb24iOiJ1bmF1dGhvcml6ZWQifQ.vxPlB1CdBVWTwmP_cAc8EimiVVrkxkeT4cqwLviKxNk", description="User Token"),
  *     @OA\Response(response="200", description="Fetch individual user")
  * )
  */
Flight::route("GET /users/@id", function($id){
    $user = Flight::get('user');
    if(isset($user)){
        $decoded = (array)JWT::decode($id, new Key(Config::JWT_SECRET(), 'HS256'));
        $decoded_user_id = $decoded['id'];
        Flight::json(Flight::user_service()->get_by_id($decoded_user_id));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


 /**
* @OA\Post(
*     path="/login",
*     description="Log in user",
*     tags={"users"},
*     @OA\RequestBody(description="Login user", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="email", type="string", example="test2@gmail.com",	description="User email"),
*    				@OA\Property(property="password", type="string", example="test123",	description="User password"),
                    @OA\Property(property="name", type="string", example="amina",	description="User name"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="User has been logged in"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::user_service()->get_user_by_email($login['email']);
    if(count($user) > 0){
        $user = $user[0];
    }
    if (isset($user['id'])){
      if($user['password'] == md5($login['password'])){
        unset($user['password']);
        unset($user['phone_number']);
        unset($user['email_address']);
        $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Incorrect username or password"], 404);
      }
    }else{
      Flight::json(["message" => "Incorrect username or password"], 404);
  }
});


 /**
* @OA\Post(
*     path="/signup",
*     description="Sign up user",
*     tags={"users"},
*     @OA\RequestBody(description="Signup user", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="email", type="string", example="user2@gmail.com",	description="User email"),
*    				@OA\Property(property="password", type="string", example="test123",	description="User password"),
*                   @OA\Property(property="full_name", type="string", example="Test User",	description="User name"),
*                   @OA\Property(property="phone", type="string", example="+000000",	description="User phone"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="User has been signed up"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('POST /signup', function(){
    $signup = Flight::request()->data->getData();
    $user = Flight::user_service()->get_user_by_email($signup['email']);
    if(count($user) > 0){
        Flight::json(["message" => "User with that email is already registered. Please choose a different email or log in instead."], 404);
    } elseif(strlen($signup['password']) < 6 || !preg_match('/[A-Za-z]/', $signup['password']) || !preg_match('/\d/', $signup['password'])){
        Flight::json(["message" => "Password should contain at least 6 characters and contain at least one letter and one number."], 404);
    }else {
        $new_user = new stdClass();
        $new_user->name = $signup['full_name'];
        $new_user->email_address = $signup['email'];
        $new_user->phone_number = $signup['phone'];
        $new_user->password = md5($signup['password']);
        $new_user->authorization = 'unauthorized';
        $new_user_array = (array) $new_user;
        $added_user = Flight::user_service()->add($new_user_array);
        $logged_user = Flight::user_service()->get_user_by_email($added_user['email_address'])[0];
        unset($logged_user['email_address']);
        unset($logged_user['password']);
        unset($logged_user['phone_number']);
        $jwt = JWT::encode($logged_user, Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);


    }
});

?>