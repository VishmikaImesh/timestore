var id;
var qty;
var address;


window.addEventListener("load", function () {
    let pathname = window.location.pathname;
    let pattern = /^\/timestore\/checkout\/([0-9]+)\/([0-9]+)$/;
    let matches = Array.from(pathname.match(pattern));
    id = matches[1];
    qty = matches[2];

    loadUserDetails();
    loadModels();
    loadDeliveryDetails();
});

var previous_method = null;

var deliveryDetails = document.getElementById("deliveryDetails");
deliveryDetails.addEventListener("click", function (event) {

    var method = event.target.closest(".btn");
    method.classList.add("bg-primary-subtle", "border-2", "border-primary");

    method.querySelector("input").checked = true;

    if (previous_method != null) {
        previous_method.classList.remove("bg-primary-subtle", "border-2", "border-primary");
        previous_method.querySelector("input").checked = false;
    }

    let subTotal = document.getElementById("subTotal").innerHTML.replace("Rs.", "");

    document.getElementById("deliveryFee").innerHTML = "Rs." + method.dataset.price;
    document.getElementById("grandTotal").innerHTML = "Rs." + (parseFloat(method.dataset.price) + parseFloat(subTotal));

    previous_method = method;
    delivery_method_warning.innerHTML = "";

});

var payhere_payment = document.getElementById("payhere-payment");
payhere_payment.addEventListener("click", function () {
    paynow();
});


var delivery_method_id;

function loadUserDetails() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);
            var user = jsonObject.user;

            document.getElementById("name").innerHTML = user.first_name + " " + user.last_name;
            document.getElementById("email").innerHTML = user.email;
            address = jsonObject.address;
        }
    }
    request.open("POST", "/timestore/api/user/userProfile", true);
    request.send();
}

function loadModels() {
    var form = new FormData();
    form.append("model_id", id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            jsonObject.models.forEach(model => {
                document.getElementById("modelImg").src = model.img_path;
                document.getElementById("name").innerHTML = model.model_name;
                document.getElementById("brand").innerHTML = model.brand_name;
                document.getElementById("price").innerHTML = "Rs." + model.price + " For each iteme(s) ";
                document.getElementById("qty").innerHTML = qty + " items";
                document.getElementById("subTotal").innerHTML = "Rs." + (parseFloat(model.price) * parseFloat(qty));
                document.getElementById("total").innerHTML = "Rs." + (parseFloat(model.price) * parseFloat(qty));
            });

        }
    }
    request.open("POST", "/timestore/api/model/load", true);
    request.send(form);
}

function loadDeliveryDetails() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            deliveryDetails = document.getElementById("deliveryDetails");
            deliveryDetails.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.forEach(delivery => {
                var div = document.createElement("div");
                div.classList = "card col-10 col-md-6 border-0";
                div.innerHTML = `
                            <button class="btn  m-1 p-0 border-secondary-subtle" data-price="${delivery.price}" data-id="${delivery.id}" >
                                <div class="card-body  row  text-start">
                                    <div class="col-1">
                                        <input value="${delivery.id}" class="form-check-input" type="checkbox"></input>
                                    </div>

                                    <div class="col-10">
                                        <h5 class="text-primary fw-bold">${delivery.method}</h5>
                                        <p class="card-title text-success fw-bold">Rs.${delivery.price}</p>
                                        </p>
                                        <p class="card-title fw-bold">Guaranteed by</p>
                                        <p class="card-title text-secondary fw-bolder">${delivery.delivery_days}</p>

                                    </div>

                                </div>
                            </button>`;
                fragment.appendChild(div);
            });
            deliveryDetails.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/delivery/load", true);
    request.send();
}

function paynow() {

    if (address == null || address.length == 0) {
        document.getElementById("address_warning").innerHTML = " * Please add a delivery address";
        return;
    }

    if (previous_method == null) {
        var delivery_method_warning = document.getElementById("delivery_method_warning");
        delivery_method_warning.innerHTML = " * Please choose a delivery method";
        return;
    }

    var form = new FormData();
    form.append("id", id);
    form.append("qty", qty);
    form.append("delivery_method_id", previous_method.dataset.id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            
            var jsonObject = JSON.parse(request.responseText);

            // Payment completed. It can be a successful failure.
            payhere.onCompleted = function onCompleted() {

            };

            // Payment window closed
            payhere.onDismissed = function onDismissed() {
                cancelOrder(jsonObject.order_id);
            };

            // Error occurred
            payhere.onError = function onError(error) {
                // Note: show an error page
                alert("Error:" + error);
            };

            // Put the payment variables here
            var payment = {
                "sandbox": true,
                "merchant_id": jsonObject.merchant_id,    // Replace your Merchant ID
                "return_url": "http://localhost/timestore/index.php",
                "cancel_url": "http://localhost/timestore/index.php",
                "notify_url": "http://localhost/timestore/index.php",
                "order_id": jsonObject.order_id,
                "items": jsonObject.items,
                "amount": jsonObject.amount,
                "currency": jsonObject.currency,
                "hash": jsonObject.hash, // *Replace with generated hash retrieved from backend
                "first_name": jsonObject.first_name,
                "last_name": jsonObject.last_name,
                "email": jsonObject.email,
                "phone": jsonObject.phone,
                "address": jsonObject.address,
                "city": jsonObject.city,
                "country": jsonObject.country,
                "delivery_address": jsonObject.address,
                "delivery_city": jsonObject.city,
                "delivery_country": jsonObject.country,
                "custom_1": "",
                "custom_2": ""
            };
            // Show the payhere.js popup, when "PayHere Pay" is clicked
            payhere.startPayment(payment);
        }
    }
    request.open("POST", "/timestore/api/order/new", true);
    request.send(form);

}





