<?php
/**
 * @OA\Get(path="/categories", tags={"categories"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all categories from the API. ",
 *         @OA\Response( response=200, description="List of categories.")
 * )
 */
Flight::route("GET /categories", function(){
    $user = Flight::get('user');
    if(isset($user)) {
        Flight::json(Flight::category_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


/**
  * @OA\Get(path="/categories/{id}", tags={"categories"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=1, description="Category ID"),
  *     @OA\Response(response="200", description="Fetch individual category")
  * )
  */
Flight::route("GET /categories/@id", function($id){
    $user = Flight::get('user');
    if(isset($user)) {
        Flight::json(Flight::category_service()->get_by_id($id));
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
});


 /**
* @OA\Post(
*     path="/categories", security={{"ApiKeyAuth": {}}},
*     description="Add category",
*     tags={"categories"},
*     @OA\RequestBody(description="Add new category", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="name", type="string", example="Test category",	description="Category name"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Category has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /categories", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == 'authorized') {
        $request = Flight::request()->data->getData(); 
        $category_name = $request['name'];
        $existing_categories = Flight::category_service()->get_category_by_name($category_name);
        if(count($existing_categories) > 0){
            Flight::json(["message" => "Category with that name already exists. Please choose another name"], 404);
        } else {   
            Flight::json(['message' => "Category added successfully.", 'data' => Flight::category_service()->add($request)]);
        };
    } else {
        Flight::json(["message" => "User token doesn't exist!"], 404);
    }
    
});


?>