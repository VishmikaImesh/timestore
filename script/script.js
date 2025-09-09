function showSideNav() {
    var sideNar = document.getElementById("sidenav");
    sideNar.classList.toggle("d-none");
}

function hideSideNav() {
    var sideNav = document.getElementById("sidenav");
    sideNav.classList.toggle("d-none");
}

function signUp() {

    var fname = document.getElementById("fname");
    var lname = document.getElementById("lname");
    var email = document.getElementById("email");
    var mobile = document.getElementById("mobile");
    var pw = document.getElementById("password");
    var pwa = document.getElementById("rtpassword");

    if (document.getElementById("male").checked) {
        gender = 1;

    }
    else if (document.getElementById("female").checked) {
        gender = 2;
    }

    var form = new FormData();
    form.append("f", fname.value);
    form.append("l", lname.value);
    form.append("e", email.value);
    form.append("m", mobile.value);
    form.append("pw", pw.value);
    form.append("pwa", pwa.value);
    form.append("g", gender);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            response = request.responseText;
            if (response == "success") {
                window.location = "signin.php";
            } else {

                alert(response);

                if (response.includes("a")) {
                    document.getElementById("fw").innerHTML = ("Please Enter Your First Name");
                }
                else if (response.includes("b")) {
                    document.getElementById("fw").innerHTML = ("First Name should include lower than 50 latters")
                } else {
                    document.getElementById("fw").innerHTML = ("");
                }

                if (response.includes("c")) {
                    document.getElementById("lw").innerHTML = ("Plsease Enter Your Last Name");
                }
                else if (response.includes("d")) {
                    document.getElementById("lw").innerHTML = ("Last Name should include lower than 50 latters");
                } else {
                    document.getElementById("lw").innerHTML = ("");
                }

                if (response.includes("e")) {
                    document.getElementById("pw").innerHTML = ("Please Enter Your Password")
                }
                else if (response.includes("f")) {
                    document.getElementById("pw").innerHTML = ("Your Password must include more than 5 and less than 20 latters");
                } else {
                    document.getElementById("pw").innerHTML = ("");
                }

                if (response.includes("g")) {
                    document.getElementById("rpw").innerHTML = ("Please Retype Your Password");
                }
                else if (response.includes("h")) {
                    document.getElementById("rpw").innerHTML = ("Password Does not Match");
                } else {
                    document.getElementById("rpw").innerHTML = ("");
                }

                if (response.includes("i")) {
                    document.getElementById("ew").innerHTML = ("Please Enter a valid Email");
                }
                else {
                    document.getElementById("ew").innerHTML = ("");
                }

                if (response.includes("j")) {
                    document.getElementById("mw").innerHTML = ("Please Your Mobile");
                } else {
                    document.getElementById("mw").innerHTML = ("");
                }

            }
        }
    }

    request.open("POST", "signupProcess.php", true);
    request.send(form);
}

function signIn() {

    var email = document.getElementById("email").value;
    var password = document.getElementById("pw").value;

    var rememberMe;

    if (document.getElementById("rememberMe").checked) {
        rememberMe = 1
    } else {
        rememberMe = 0
    }

    var form = new FormData();
    form.append("e", email);
    form.append("p", password);
    form.append("r", rememberMe);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            response = request.responseText;

            if (response == "success") {
                window.location = "index.php"
            } else {
                alert(response);
            }

        }

    }

    request.open("POST", "signinProcess.php", true);
    request.send(form);
}


function addToCart(id) {

    var qty = document.getElementById("pqty");

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            if (response == "success") {
                window.location.reload();
                alert("Product added to Cart Successfully...!");
            } else {
                alert(response);
            }
        }
    }

    request.open("GET", "addToCartProcess.php?id=" + id + "&qty=" + qty.value, true);
    request.send();
}


function addToWishlist(id) {


    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            alert(response);
        }
    }

    request.open("GET", "addToWishlistProcess.php?id=" + id, true);
    request.send();
}



function qtyUp(maxQty) {

    var pqty = document.getElementById("pqty");
    var qtyWarning = document.getElementById("qtyWarning");

    pqty.value = parseInt(pqty.value) + 1;

    if (pqty.value >= maxQty) {
        pqty.value = maxQty;
        qtyWarning.innerHTML = "You have reached maximam order quantity";

    } else {
        qtyWarning.innerHTML = " ";
    }
}

function qtyDown(maxQty) {
    var pqty = document.getElementById("pqty");
    pqty.value = parseInt(pqty.value) - 1;

    if (pqty.value < 1) {
        pqty.value = 1;
    } else if (pqty.value == maxQty - 1) {
        qtyWarning.innerHTML = " ";
    }

}

function checkQty(maxQty) {

    var pqty = document.getElementById("pqty");

    if (pqty.value <= 0) {
        pqty.value = 1;

    } else if (pqty.value > maxQty) {
        pqty.value = maxQty;
    }

}

function getQty() {
    var getQty = document.getElementById("getQty");
    var pqty = document.getElementById("pqty").value;
    getQty.value = pqty;
}

function removeFromCart(cid, pid) {

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            window.location.reload();
            alert(response);
        }
    }

    request.open("GET", "removeFromCart.php?cartId=" + cid + "&pid=" + pid, true);
    request.send();
}

function removeFromWatchlist(id) {

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            window.location.reload();
            alert(response);
        }
    }

    request.open("GET", "removeFromwatchlist.php?id=" + id, true);
    request.send();

}

function updateProfile() {

    var request = new XMLHttpRequest();

    var lname = document.getElementById("lname");
    var fname = document.getElementById("fname");
    var email = document.getElementById("email");
    var mobile = document.getElementById("mobile");
    var ad1 = document.getElementById("ad1");
    var ad2 = document.getElementById("ad2");
    var city = document.getElementById("city");
    // var pw=document.getElementById("pw");

    var form = new FormData();

    form.append("ln", lname.value);
    form.append("fn", fname.value);
    form.append("e", email.value);
    // form.append("pw",pw.value);
    form.append("ad1", ad1.value);
    form.append("ad2", ad2.value);
    form.append("mb", mobile.value);
    form.append("city", city.value);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            // window.location.reload();
            alert(response);
        }
    }

    request.open("POST", "updateProfile.php", true);
    request.send(form);

}

function logOut() {

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            window.location.reload();
        }
    }

    request.open("GET", "logout.php", true);
    request.send();

}

function searchText() {

    var search = document.getElementById("search");
    window.location = "search.php?search=" + search.value;

}

function search() {

    var searchText = document.getElementById("productSearch");
    var searchResults = document.getElementById("searchResults");

    var gender;
    var mt;
    var type;

    if (document.getElementById("male").checked) {
        gender = 1;

    }
    else if (document.getElementById("female").checked) {
        gender = 2;
    }

    if (document.getElementById("steel").checked) {
        mt = 4;

    }
    else if (document.getElementById("leather").checked) {
        mt = 3;

    }

    if (document.getElementById("digital").checked) {
        type = 5;

    }
    else if (document.getElementById("analog").checked) {
        type = 6;

    }

    var form = new FormData();
    form.append("g", gender);
    form.append("mt", mt);
    form.append("type", type);
    form.append("search", searchText.value);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;

            searchResults.innerHTML = response;

        }

    }
    request.open("POST", "searchprocess.php", true);
    request.send(form);
}

function removeFromHistory(id) {

    var form = new FormData();
    form.append("id", id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            window.location.reload();
        }
    }

    request.open("POST", "removeFromHistory.php", true);
    request.send(form);
}

function changeModel(id) {

    var img = document.getElementById("vimg");
    var mimg = document.getElementById("mimg");
    var price = document.getElementById("price");
    var model = document.getElementById("model");
    var buying_product_id = document.getElementById("buying_product_id");
    buying_product_id.value = id;

    var form = new FormData();
    form.append("id", id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var model_data = JSON.parse(request.response);

            img.src = model_data.img_path;
            mimg.src = model_data.img_path;
            price.innerHTML = "Rs." + model_data.price;
            model.innerHTML = model_data.model;

        }
    }

    request.open("POST", "loadModelinfo.php", true);
    request.send(form);

}

function changeDeliveryOption(option, price, fee) {

    var checkboxStandard = document.getElementById("checkDeliveryMethod1");
    var checkboxExpress = document.getElementById("checkDeliveryMethod2");
    var deliverytoption1 = document.getElementById("deliverytoption1");
    var deliverytoption2 = document.getElementById("deliverytoption2");
    var deliveryFee = document.getElementById("deliveryFee");
    var total = document.getElementById("total");

    var resetBtnClasses = " btn m-1 p-0  ";
    var defualtBtnClasses = "btn m-1 p-0 border-secondary-subtle";

    if (deliverytoption1.id == option) {
        checkboxExpress.checked = false;
        checkboxStandard.checked = true;
        deliverytoption1.className = resetBtnClasses;
        deliverytoption1.className = deliverytoption1.className + " border-2 border-primary ";
        deliverytoption2.className = defualtBtnClasses;
        deliveryFee.innerHTML = "Delivery Fee: Rs." + fee;
        total.innerHTML = "Total : Rs." + (price + fee);
    } else {
        checkboxStandard.checked = false;
        checkboxExpress.checked = true;
        deliverytoption2.className = resetBtnClasses;
        deliverytoption2.className = deliverytoption2.className + " border-2 border-danger ";
        deliverytoption1.className = defualtBtnClasses;
        deliveryFee.innerHTML = "Delivery Fee: Rs." + fee;
        total.innerHTML = "Total : Rs." + (price + fee);
    }
}

function toCheckout() {

    var buying_product_qty = document.getElementById("pqty");
    var buying_product_id = document.getElementById("buying_product_id");

    window.location = "checkout.php?id=" + buying_product_id.value + "&qty=" + buying_product_qty.value;
}

function paynow() {

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var hash = request.responseText;

            // Payment completed. It can be a successful failure.
            payhere.onCompleted = function onCompleted(orderId) {
                console.log("Payment completed. OrderID:" + orderId);
                // Note: validate the payment and show success or failure page to the customer
            };

            // Payment window closed
            payhere.onDismissed = function onDismissed() {
                // Note: Prompt user to pay again or show an error page
                console.log("Payment dismissed");
            };

            // Error occurred
            payhere.onError = function onError(error) {
                // Note: show an error page
                console.log("Error:" + error);
            };

            // Put the payment variables here
            var payment = {
                "sandbox": true,
                "merchant_id": "1226402",    // Replace your Merchant ID
                "return_url": "http://localhost/timestore/index.php",
                "cancel_url": "http://localhost/timestore/index.php", 
                "notify_url": "http://sample.com/notify",
                "order_id": "566546",
                "items": "Door bell wireles",
                "amount": "42222",
                "currency": "LKR",
                "hash": hash, // *Replace with generated hash retrieved from backend
                "first_name": "Saman",
                "last_name": "Perera",
                "email": "samanp@gmail.com",
                "phone": "0771234567",
                "address": "No.1, Galle Road",
                "city": "Colombo",
                "country": "Sri Lanka",
                "delivery_address": "No. 46, Galle road, Kalutara South",
                "delivery_city": "Kalutara",
                "delivery_country": "Sri Lanka",
                "custom_1": "",
                "custom_2": ""
            };

            // Show the payhere.js popup, when "PayHere Pay" is clicked

            payhere.startPayment(payment);
        }
    }
    request.open("POST", "hash.php", true);
    request.send();

}