var FilterService = {
    getCategoryFilters: function () {
        $.ajax({
            url: 'rest/categories',
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
    
            success: function (data) {
                var html = `<div class="dropdown" style="margin-bottom: 20px;">
                <button id="button-category" class="btn btn-success"
                    style="border-color: white !important; background-color: white !important;">
                    <select class="form-select" id="dropdown-category"
                        style="border-color:white;">`;
                for (var i = 0; i < data.length; i++) {
                    html+= `<option value="` + data[i].id +`">` + data[i].name + `</option>`
                };

                html += `<option value="" selected disabled>Category</option>
                            </select>
                        </button>
                    </div>`;
                $("#categoryContainer").html(html);
                $("#categoryContainer").css({ "display": "block" });
            },
            error: function (err) {
                console.log(err.status);
                console.log("We have an error");
            }
        });
    },

    getSupplierFilters: function () {
        $.ajax({
            url: 'rest/suppliers',
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
    
            success: function (data) {
                var html = `<div class="dropdown" style="margin-bottom: 20px;">
                <button id="button-category" class="btn btn-success"
                    style="border-color: white !important; background-color: white !important;">
                    <select class="form-select" id="dropdown-supplier"
                        style="border-color:white;">`;
                for (var i = 0; i < data.length; i++) {
                    html+= `<option value="` + data[i].id +`">` + data[i].name + `</option>`
                };

                html += `<option value="" selected disabled>Supplier</option>
                            </select>
                        </button>
                    </div>`;
                $("#supplierContainer").html(html);
                $("#categoryContainer").css({ "display": "block" });
            },
            error: function (err) {
                console.log(err.status);
                console.log("We have an error");
            }
        });
    },
    getPriceFilters: function () {
        $.ajax({
            url: 'rest/categories',
            method: "GET",
            contentType: "application/json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader(
                  "Authorization",
                  localStorage.getItem("user_token")
                );
              },
    
            success: function (data) {
                var html = `<div class="dropdown" style="margin-bottom: 20px;">
                <button id="button-order" class="btn btn-success"
                    style="border-color: white !important; background-color: white !important;">
                    <select class="form-select" id="dropdown-order" style="border-color:white;">
                        <option value="desc">High to Low</option>
                        <option value="asc">Low to High</option>
                        <option value="" selected disabled>Price</option>
                </button>
                </select>
            </div>`;
                $("#orderContainer").html(html);
                $("#orderContainer").css({ "display": "block" });
            },
            error: function (err) {
                console.log(err.status);
                console.log("We have an error");
            }
        });
    },

    
}