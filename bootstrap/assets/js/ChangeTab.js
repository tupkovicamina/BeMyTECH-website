var ChangeTab = {

    goToShopPage: function () {
        $("#ShopDiv").css({ "display": "block" });
        $("#HomeDiv").css({ "display": "none" });
        $("#AboutDiv").css({ "display": "none" });
        $("#ContactDiv").css({ "display": "none" });
        $("#ProductDiv").css({ "display": "none" });
        $("#CartDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
        
    },
    
    goToHomePage: function () {
        $("#ShopDiv").css({ "display": "none" });
        $("#HomeDiv").css({ "display": "block" });
        $("#AboutDiv").css({ "display": "none" });
        $("#ContactDiv").css({ "display": "none" });
        $("#ProductDiv").css({ "display": "none" });
        $("#CartDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
    },

    goToAboutPage: function () {
        $("#AboutDiv").css({ "display": "block" });
        $("#HomeDiv").css({ "display": "none" });
        $("#ShopDiv").css({ "display": "none" });
        $("#ContactDiv").css({ "display": "none" });
        $("#ProductDiv").css({ "display": "none" });
        $("#CartDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
    },

    goToContactPage: function () {
        $("#ContactDiv").css({ "display": "block" });
        $("#AboutDiv").css({ "display": "none" });
        $("#HomeDiv").css({ "display": "none" });
        $("#ShopDiv").css({ "display": "none" });
        $("#ProductDiv").css({ "display": "none" });
        $("#CartDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
    },

    goToProductPage: function (productid) {
        ProductService.showProduct(productid);
        $("#ProductDiv").css({ "display": "block" });
        $("#ContactDiv").css({ "display": "none" });
        $("#AboutDiv").css({ "display": "none" });
        $("#HomeDiv").css({ "display": "none" });
        $("#ShopDiv").css({ "display": "none" });
        $("#CartDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
    },

    goToCartPage: function (user_id) {
        CartService.getUserProducts(user_id);
        $("#CartDiv").css({ "display": "block" });
        $("#ProductDiv").css({ "display": "none" });
        $("#ContactDiv").css({ "display": "none" });
        $("#AboutDiv").css({ "display": "none" });
        $("#HomeDiv").css({ "display": "none" });
        $("#ShopDiv").css({ "display": "none" });
        $("#UserDiv").css({ "display": "none" });
    },

    goToUserPage: function(user_id){
        UserService.getUserData(user_id);
        $("#UserDiv").css({ "display": "block" });
        $("#CartDiv").css({ "display": "none" });
        $("#ProductDiv").css({ "display": "none" });
        $("#ContactDiv").css({ "display": "none" });
        $("#AboutDiv").css({ "display": "none" });
        $("#HomeDiv").css({ "display": "none" });
        $("#ShopDiv").css({ "display": "none" });
    }


}