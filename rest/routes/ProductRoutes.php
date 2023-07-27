<?php

/**
 * @OA\Get(path="/products", tags={"products"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all products from the API. ",
 *         @OA\Response( response=200, description="List of products.")
 * )
 */
Flight::route("GET /products", function(){
    $user = Flight::get('user');
    if(isset($user)){
        $category = Flight::query("category");
        $supplier = Flight::query("supplier");
        $order = Flight::query("order");
        Flight::json(Flight::product_service()->get_all_with_filters($category, $supplier, $order));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
 * @OA\Get(
 *     path="/product_by_id", tags={"products"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         in="query",
 *         name="id",
 *         description="Product ID",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *             example=15
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Fetch individual product"
 *     )
 * )
 */
Flight::route("GET /product_by_id", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::product_service()->get_by_id(Flight::request()->query['id']));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
  * @OA\Get(path="/products_by_name/{name}", tags={"products"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="name", example=15, description="Product name"),
  *     @OA\Response(response="200", description="Fetch individual product")
  * )
  */
Flight::route("GET /products_by_name/@name", function($name) {
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::product_service()->get_by_product_name($name));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
  * @OA\Get(path="/products/{id}", tags={"products"}, security={{"ApiKeyAuth": {}}},
  *     @OA\Parameter(in="path", name="id", example=18, description="Product ID"),
  *     @OA\Response(response="200", description="Fetch individual product")
  * )
  */
Flight::route("GET /products/@id", function($id){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::product_service()->get_by_id($id));
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
 * @OA\Get(path="/categories_and_suppliers", tags={"products"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all categories and suppliers from the API. ",
 *         @OA\Response( response=200, description="List of categories and suppliers.")
 * )
 */
Flight::route("GET /categories_and_suppliers", function(){
    $user = Flight::get('user');
    if(isset($user)){
        Flight::json(Flight::product_service()->get_categories_and_suppliers());
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


 /**
* @OA\Post(
*     path="/products", security={{"ApiKeyAuth": {}}},
*     description="Add product",
*     tags={"products"},
*     @OA\RequestBody(description="Add new product", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*                   @OA\Property(property="name", type="string", example="test product 2",	description="Product name"),
*    				@OA\Property(property="description", type="string", example="description",	description="Product description"),
*    				@OA\Property(property="price", type="int", example="5",	description="Product price" ),
*                   @OA\Property(property="category_id", type="int", example="1",	description="Category ID"),
*    				@OA\Property(property="supplier_id", type="int", example="1",	description="Supplier ID"),
*    				@OA\Property(property="image", type="int", example="499",	description="Product quantity" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Product has been added"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route("POST /products", function(){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "authorized"){
        $request = Flight::request()->data->getData();
        $product_name = $request['name'];
        $existing_products = Flight::product_service()->get_by_exact_product_name($product_name);
        if(count($existing_products) > 0){
            Flight::json(["message" => "Product with that name already exists. Please choose another name"], 404);
        } else {    
            Flight::json(['message' => "product added successfully", 'data' => Flight::product_service()->add($request)]);
        }
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});

/**
 * @OA\Put(
 *     path="/products/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Edit product",
 *     tags={"products"},
 *     @OA\Parameter(in="path", name="id", example=51, description="Product ID"),
 *     @OA\RequestBody(description="Student info", required=true,
 *       @OA\MediaType(mediaType="application/json",
 *    			@OA\Schema(
 *                   @OA\Property(property="name", type="string", example="test product 2",	description="Product name"),
*    				@OA\Property(property="description", type="string", example="description",	description="Product description"),
*    				@OA\Property(property="price", type="int", example="5",	description="Product price" ),
*                   @OA\Property(property="category_id", type="int", example="1",	description="Category ID"),
*    				@OA\Property(property="supplier_id", type="int", example="1",	description="Supplier ID"),
*    				@OA\Property(property="image", type="int", example="499",	description="Product quantity" ),
 *        )
 *     )),
 *     @OA\Response(
 *         response=200,
 *         description="Product has been edited"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error"
 *     )
 * )
 */
Flight::route("PUT /products/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "authorized"){
        $product = Flight::request()->data->getData();
        $product_name = $product['name'];
        $existing_products = Flight::product_service()->get_by_exact_product_name($product_name);
        if(count($existing_products) > 0 && $existing_products[0]['id'] != $id){
            Flight::json(["message" => "Product with that name already exists. Please choose another name"], 404);
        } else {
            Flight::json(['message' => "product edited successfully", 'data' => Flight::product_service()->update($product, $id)]);
        };
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});


/**
 * @OA\Delete(
 *     path="/products/{id}", security={{"ApiKeyAuth": {}}},
 *     description="Delete product",
 *     tags={"products"},
 *     @OA\Parameter(in="path", name="id", example=52, description="Product ID"),
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
Flight::route("DELETE /products/@id", function($id){
    $user = Flight::get('user');
    if(isset($user) && $user['authorization'] == "authorized"){
        Flight::product_service()->delete($id);
        +Flight::json(['message' => "product deleted successfully"]);
    } else {
        Flight::json(["message" => "User token doesn't exist."], 404);
    };
    
});

?>