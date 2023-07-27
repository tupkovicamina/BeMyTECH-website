<?php
/**
 * @OA\Get(path="/suppliers", tags={"suppliers"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all suppliers from the API. ",
 *         @OA\Response( response=200, description="List of suppliers.")
 * )
 */
Flight::route("GET /suppliers", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::supplier_service()->get_all());
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});

/**
  * @OA\Get(path="/suppliers/{id}", tags={"suppliers"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=1, description="Supplier ID"),
  *     @OA\Response(response="200", description="Fetch individual supplier")
  * )
  */
Flight::route("GET /suppliers/@id", function($id){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::supplier_service()->get_by_id($id));
    } else {
        Flight::json(["message" => $user], 404);
    };
    
});


 /**
* @OA\Post(
*     path="/suppliers", security={{"ApiKeyAuth": {}}},
*     description="Add supplier",
*     tags={"suppliers"},
*     @OA\RequestBody(description="Add new supplier", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="name", type="string", example="Test supplier",	description="Supplier name"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Supplier has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /suppliers", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "authorized"){
        $request = Flight::request()->data->getData();
        $supplier_name = $request['name'];
        $existing_suppliers = Flight::supplier_service()->get_supplier_by_name($supplier_name);
        if(count($existing_suppliers) > 0){
            Flight::json(["message" => "Supplier with that name already exists. Please choose another name"], 404);
        } else {    
            Flight::json(['message' => "Supplier added successfully.", 'data' => Flight::supplier_service()->add($request)]);
        };
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


?>