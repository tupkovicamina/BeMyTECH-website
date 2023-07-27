var CartService = {
    getUserProducts: function (user_id) {
        //make an ajax request to get the products
        $.ajax({
            url: 'rest/carts_by_user_id/' + user_id,
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
    
            success: function (data) {
                var html = `<div class="shopping-cart" style="margin-left: 50px; margin-right: 50px;"> 
                <div class="cart-title">
                    <b>Your Cart</b>
                </div>`;
                var total_price = 0;
                 for (var i = 0; i < data.length; i++) {
                    total_price += data[i].product_price * data[i].quantity;
                    html+= ` 
                    <div class="row">
                        <div class="card mb-4 rounded-3" style="margin-left: 50px; margin-right: 50px;">
                            <div class="row align-items-center">
                                <div class="col">
                                    <img style="width: auto" class="card-img rounded-0 img-fluid cart-img-size" src=` + data[i].product_image + `>
                                </div>
                                <div class="col">
                                    <p style="width: auto" class="mb-0">` + data[i].product_name + `</p>
                                </div>
                                <div class="col">
                                    <p style="width: auto" class="mb-0">$` + data[i].product_price + `</p>
                                </div>
                                <div class="col">
                                    <p style="width: auto" class="mb-0">Quantity: ` + data[i].quantity + `</p>
                                </div>
                                <div class="col">
                                    <button type="submit" style="font-size: small; margin: 10px;" onclick="CartService.deleteCartItem(${data[i].id})" class="dugme btn btn-success">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>`
                };
                if (data.length > 0) {
                    html += `<div class="row" style="float: right;">
                    <div class="row">
                                    <p style="width: auto" class="mb-0">Total Price: $` + total_price.toFixed(2) + `</p>
                    </div>
                    <button type="submit" style="width: auto; font-size: small; margin-left: 10px;" onclick="showSelectCartAddressDialog()" class="btn btn-success">Purchase Items from Cart</button>
                    <button type="submit" style="width: auto; font-size: small; margin: 10px;" onclick="CartService.emptyCart()" class="btn btn-success">Empty Cart</button>
                </div>
                `
                }
                
                

                $("#cartContainer").html(html);
                $("#cartContainer").css({ "display": "block" })
            },
            error: function (err) {
                console.log(err.status);
                console.log("We have an error");
            }
        });
    
    },

    deleteCartItem: function(cart_id){

        $.ajax({
            url: 'rest/carts/' + cart_id,
            method: "DELETE",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
            success: function (result) {
                toastr.success("Item has been deleted successfully");
                ChangeTab.goToCartPage(localStorage.getItem('user_token'));
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("Error! Item has not been deleted.");
            },
        });

    },

    emptyCart: function(){
        let user_id = localStorage.getItem('user_token');

        $.ajax({
            url: 'rest/carts_by_user_id/' + user_id,
            method: "DELETE",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
            success: function (result) {
                toastr.success("Your cart is now empty");
                ChangeTab.goToCartPage(localStorage.getItem('user_token'));
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("Error! Cart has not been emptied.");
            },
        });

    },

    selectAddress: function() {
        let user_id = localStorage.getItem('user_token');
        let options2 = "";
    
        $.ajax({
          url: "rest/addresses_by_user_token/" + user_id,
          type: "GET",
          beforeSend: function (xhr) {
            xhr.setRequestHeader(
              "Authorization",
              localStorage.getItem("user_token")
            );
          },
          success: function(data) {
            for (let i = 0; i < data.length; i++) {
              options2 += `<option value="${data[i].id}">${data[i].alias}</option>`;
            }
            document.getElementById("select-cart-address").innerHTML = options2;
    
            // Update the address ID when the user selects an option
            document.getElementById("select-cart-address").addEventListener("change", function() {
              const selectedAddressId = this.value;
              document.getElementById("selected-cart-address-id").value = selectedAddressId;
            });
    
            // Check if the user has addresses
            if (data.length === 0) {
              document.getElementById("selectCartAddressForm").onsubmit = function(event) {
                event.preventDefault(); // Prevent form submission
                alert("You have no addresses saved. Go to the user profile and add an address you would like your purchase to be shipped to.");
              };
            } else {
              // Check if the event listener is already added
              if (!document.getElementById("completePurchaseBtn2").hasEventListener) {
                // Add event listener to the "Complete Purchase" button
                document.getElementById("completePurchaseBtn2").addEventListener("click", function(event) {
                    CartService.completePurchase(event, user_id);
                });
    
                // Set flag to indicate the event listener is added
                document.getElementById("completePurchaseBtn2").hasEventListener = true;
              }
            }
          },
        });
      },
    
    completePurchase: function(event, user_id) {
        event.preventDefault();
        $.ajax({
            url: 'rest/carts_by_user_id/' + user_id,
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
    
            success: function (data) {
                const selectedAddressId = document.getElementById("select-cart-address").value;
                CartService.buyFromCart(data, selectedAddressId);
            },
            error: function (err) {
                console.log(err.status);
                console.log("We have an error");
            }
        });
        
    },

    buyFromCart: function(data, selectedAddressId){
        data.forEach(function(item) {
            item.order_date = new Date().toJSON().slice(0, 10);
            item.address_id = selectedAddressId;
        });

        $.ajax({
            url: 'rest/orders_by_cart',
            method: "POST",
            data: JSON.stringify(data),
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
            success: function (result) {
                toastr.success("Your order has been complete");
                ChangeTab.goToCartPage(localStorage.getItem('user_token'));
                $('#selectCartAddressModal').modal('hide'); // Close the modal
    
                // Remove the event listener after a successful purchase
                document.getElementById("completePurchaseBtn2").removeEventListener("click", CartService.completePurchase(data));
                delete document.getElementById("completePurchaseBtn2").hasEventListener;
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("Sorry, there was a problem with your order. Please try again.");
                $('#selectCartAddressModal').modal('hide'); // Close the modal
    
                // Remove the event listener after a successful purchase
                document.getElementById("completePurchaseBtn2").removeEventListener("click", CartService.completePurchase(data));
                delete document.getElementById("completePurchaseBtn2").hasEventListener;
            },
        });

    }
}