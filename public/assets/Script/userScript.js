window.addEventListener("load", function () {
    this.alert("Script Loaded");
});
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

    request.open("POST", "/api/user/signUp", true);
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
    form.append("email", email);
    form.append("password", password);
    form.append("rememberMe", rememberMe);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response;
            try {
                response = JSON.parse(request.responseText);
            } catch (error) {
                alert("Sign in failed. Please try again.");
                return;
            }

            if (response.state) {
                window.location = "/timestore/Home";
            } else {
                alert(response.message || "Invalid email or password.");
            }
        }

    }

    request.open("POST", "/api/user/logIn", true);
    request.send(form);
}

function checkoutSignIn() {

    var email = document.getElementById("email").value;
    var password = document.getElementById("pw").value;

    var rememberMe;

    if (document.getElementById("rememberMe").checked) {
        rememberMe = 1
    } else {
        rememberMe = 0
    }

    var form = new FormData();
    form.append("email", email);
    form.append("password", password);
    form.append("rememberMe", rememberMe);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response;
            try {
                response = JSON.parse(request.responseText);
            } catch (error) {
                alert("Sign in failed. Please try again.");
                return;
            }

            if (response.state) {
                window.location.reload();
            } else {
                alert(response.message || "Invalid email or password.");
            }
        }

    }

    request.open("POST", "/api/user/logIn", true);
    request.send(form);
}


function addToCart(id) {

    var qty = document.getElementById("pqty");

    var form = new FormData();
    form.append("id", id);
    form.append("qty", qty.value);

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

    request.open("POST", "/api/cart/add", true);
    request.send(form);
}


function addToWishlist(id) {

    var request = new XMLHttpRequest();

    var form = new FormData();
    form.append("id", id);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            alert(response);
        }
    }

    request.open("POST", "/api/wishlist/toggle", true);
    request.send(form);
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

function removeFromCart(pid) {

    var request = new XMLHttpRequest();

    var form = new FormData();
    form.append("model_id", pid);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            window.location.reload();
        }
    }

    request.open("POST", "/api/cart/remove", true);
    request.send(form);
}

function removeFromWatchlist(id) {

    var request = new XMLHttpRequest();

    var form = new FormData();
    form.append("id", id);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            window.location.reload();
            alert(response);
        }
    }

    request.open("POST", "/api/wishlist/remove", true);
    request.send(form);

}

function updateProfile() {

    var request = new XMLHttpRequest();

    var lname = document.getElementById("lname");
    var fname = document.getElementById("fname");
    var mobile = document.getElementById("mobile");
    // var pw=document.getElementById("pw");

    var form = new FormData();

    form.append("first_name", fname.value);
    form.append("last_name", lname.value);
    form.append("mobile", mobile.value);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var response = request.responseText;
            // window.location.reload();
            alert(response);
        }
    }

    request.open("POST", "/api/user/updateProfile", true);
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
        mt = "steel";
    }
    else if (document.getElementById("leather").checked) {
        mt = "leather";
    }

    if (document.getElementById("digital").checked) {
        type = "digital";
    }
    else if (document.getElementById("analog").checked) {
        type = "analog";
    }

    var form = new FormData();
    form.append("g", gender);
    if (mt !== "") {
        form.append("mt", mt);
    }
    if (type !== "") {
        form.append("type", type);
    }
    if (searchText.value && searchText.value.trim() !== "") {
        form.append("search", searchText.value.trim());
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4) {
            var response;
            try {
                response = JSON.parse(request.responseText);
            } catch (error) {
                searchResults.innerHTML = "<div class='alert alert-danger'>Search failed. Please try again.</div>";
                return;
            }

            if (!response.state) {
                var message = response.message || "No results found.";
                searchResults.innerHTML = "<div class='alert alert-warning'>" + message + "</div>";
                return;
            }

            if (!response.data || response.data.length === 0) {
                searchResults.innerHTML = "<div class='alert alert-info'>No products found matching your criteria.</div>";
                return;
            }

            var html = "";
            response.data.forEach(function (item) {
                html += "<div class='col'>";
                html += "<a href='/timestore/viewProduct/" + item.product_id + "' class='card h-100 text-decoration-none'>";
                html += "<img src='" + item.img_path + "' class='card-img-top' alt='" + item.title + "'>";
                html += "<div class='card-body'>";
                html += "<div class='justify-content-center d-flex'>";
                html += "<ul class='list-group list-group-flush d-block'>";
                html += "<li class='list-group-item'><h5 class='card-title'>" + item.title + "</h5></li>";
                html += "<li class='list-group-item fw-bold'>Rs." + item.price + ".00</li>";
                html += "</ul>";
                html += "</div>";
                html += "<div class='justify-content-center d-flex m-2'></div>";
                html += "</div>";
                html += "</a>";
                html += "</div>";
            });

            searchResults.innerHTML = html;

        }

    }
    request.open("POST", "/api/search", true);
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

    request.open("POST", "/api/history/remove", true);
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
    form.append("model_id", id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var model_data = JSON.parse(request.responseText);
            if (model_data.models && model_data.models.length > 0) {
                var selected = model_data.models[0];
                img.src = selected.img_path;
                mimg.src = selected.img_path;
                price.innerHTML = "Rs." + selected.price;
                model.innerHTML = selected.model_name;
            }
        }
    }

    request.open("POST", "/api/model/load", true);
    request.send(form);
}



var previous_option;
var previous_method;

var delivery_method_id;

function changeDeliveryOption(option, productPrice) {

    delivery_method_warning.innerHTML = "";

    delivery_method_id = option;

    if (previous_option != null) {
        previous_option.classList = " btn  m-1 p-0 border-secondary-subtle ";
        previous_method.checked = false;
    }

    var delivery_option = document.getElementById('deliverytoption' + option);
    var delivery_method = document.getElementById('checkDeliveryMethod' + option);

    delivery_option.className = delivery_option.className + " bg-primary-subtle border-2  border-primary ";
    delivery_method.checked = true;

    previous_option = delivery_option;
    previous_method = delivery_method;

    document.getElementById("deliveryFee").innerHTML = "Rs." + delivery_method.value;
    document.getElementById("total").innerHTML = "Rs." + (parseFloat(delivery_method.value) + parseFloat(productPrice));

}



function cancelOrder(orderId){
    var form = new FormData();
    form.append("orderId",orderId);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status==200){

        }
    }
    request.open("POST","/api/order/cancel",true);
    request.send(form);
}

function loadOrderDetails(orderId) {

    var orderList = document.getElementById("orderList");
    var ordereDetailsId = document.getElementById("ordereDetailsId");

    var form = new FormData();
    form.append('order_id', orderId);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.responseText);

            if (jsonObject.state && jsonObject.data) {
                var orders = jsonObject.data.order_items || [];
                var order = jsonObject.data.order || {};
                ordereDetailsId.innerHTML = order.order_id || "";

                orderList.innerHTML = ``;
                for (const item of orders) {
                    var imgSrc = item.img_src || item.img_path || "";
                    orderList.innerHTML = orderList.innerHTML + `
                                                    </li><li class="list-group-item">
                                                        <div class="row align-items-center">
                                                            <div class="col-3 mt-3">
                                                                <img src="`+ imgSrc + `" id="modelImg" class="img-fluid rounded-start" alt="...">
                                                            </div>
                                                            <div class="col">
                                                                <h6 class="fw-bold mb-1">`+ item.product_name + `</h6>
                                                                <p id="qty" class="text-muted small mb-0">Quantity: `+ item.qty + ` | Model ID: ` + item.model + ` </p>
                                                            </div>
                                                            <div class="col-auto text-end">
                                                                <p id="price" class="fw-bold mb-0">Rs.`+ item.price + `.00</p>
                                                            </div>
                                                        </div>
                                                    </li>`;
                }
            }

        }
    }

    request.open("POST", "/api/order/details", true);
    request.send(form);
}

window.addEventListener("load", loadRevenue);

document.getElementById("revenuePeriod").addEventListener("change", loadRevenue);

let revenueChart = null;

function loadRevenue() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenuePeriod = document.getElementById("revenuePeriod");

    var form = new FormData();
    form.append('revenuePeriod', revenuePeriod.value)

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            if (revenueChart) {
                revenueChart.destroy();
            }

            var jsonObject = JSON.parse(request.responseText)

            // Create Red Gradient to match your theme
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(220, 53, 69, 0.2)'); // Bootstrap Danger Color (Low Opacity)
            gradient.addColorStop(1, 'rgba(220, 53, 69, 0)'); // Fade out

            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jsonObject.dates,
                    datasets: [{
                        label: 'Revenue (Rs)',
                        data: jsonObject.revenues,
                        borderColor: '#dc3545', // Your Red Accent
                        backgroundColor: gradient,
                        borderWidth: 3,
                        tension: 0.3, // Slightly less curve for a cleaner look
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#dc3545',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#000',
                            titleFont: {
                                size: 13
                            },
                            bodyFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: '#f1f1f1'
                            }, // Very light grid
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    weight: 'bold'
                                },
                                callback: function (value) {
                                    return 'Rs ' + value / 1000 + 'k';
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#6c757d',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    request.open("POST", "loadRevenueData.php", true);
    request.send(form)

}

const productDetailsModal = document.getElementById('productDetailsModal');

productDetailsModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById("modalImg").src = button.dataset.test;
    document.getElementById("modalPrice").textContent = "Rs." + button.dataset.price;
    document.getElementById("modalStock").textContent = button.dataset.qty
    var variantsTableBody = document.getElementById("variantsTableBody");
    variantsTableBody.innerHTML = "";
    renderProductChart(button.dataset.product_id);


    var form = new FormData();
    form.append("product_id", button.dataset.product_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            var fragment = document.createDocumentFragment();
            jsonObject.models.forEach(model => {
                var modelItem =
                    `<td class="ps-4">
                        <img src=../`+ model.img_path + `
                            class="rounded border bg-white p-1"
                            width="40" height="40"
                            style="object-fit: contain;">
                    </td>

                    <td>
                        <span class="fw-bold text-dark small">`+ model.model_name + `</span>
                    </td>

                    <td class="small fw-bold">Rs.`+ model.price + `</td>

                    <td class="small fw-bold">`+ model.qty + `</td>

                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">In Stock</span>
                    </td>`;

                var tr = document.createElement("tr");
                tr.innerHTML = modelItem;
                fragment.appendChild(tr);
            });
            variantsTableBody.appendChild(fragment);
        }

    }
    request.open("POST", "loadModels.php", true);
    request.send(form);
});

let productChart = null; // Store chart instance to destroy it later
function renderProductChart(id) {
    const ctx = document.getElementById('productSalesChart').getContext('2d');

    // Destroy previous chart if it exists (prevents glitching when reopening modal)
    if (productChart) {
        productChart.destroy();
    }

    var form = new FormData();
    form.append("id", id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.responseText);

            document.getElementById("modalTotalSales").textContent = "Rs." + jsonObject.total;

            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(220, 53, 69, 0.4)'); // Red top
            gradient.addColorStop(1, 'rgba(220, 53, 69, 0)'); // Transparent bottom

            productChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jsonObject.date,
                    datasets: [{
                        label: 'Units Sold',
                        data: jsonObject.revenue, // Replace with dynamic data
                        borderColor: '#dc3545',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#dc3545',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [5, 5]
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

        }
    }
    request.open("POST", "loadProductHistory.php", true);
    request.send(form);
}

document.getElementById("filerByBrand").addEventListener("change", loadProducts);
document.getElementById("sortByPrice").addEventListener("change", loadProducts);

function loadProducts() {
    var brandId = document.getElementById("filerByBrand");
    var sort = document.getElementById("sortByPrice");

    var form = new FormData();
    if (brandId.selectedIndex > 0) {
        form.append("id", brandId.value);
    }
    if (sort.selectedIndex > 0) {
        form.append("sort", sort.value);
    }


    var modelList = document.getElementById("modelTable");
    modelList.innerHTML = "";

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            const fragment = document.createDocumentFragment()
            jsonObject.models.forEach(i => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td class="ps-4">
                        <div class="bg-white border rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <img src="../`+ i.img_path + `" class="product-thumb" style="max-width: 100%; max-height: 100%;" alt="Thumb">
                        </div>
                    </td>
                    <td>
                        <h6 class="fw-bold text-dark mb-0">`+ i.product_name + `</h6>
                        <small class="text-muted">`+ i.product_name + `</small>

                    </td>
                    <td><span class="badge bg-light text-dark border">`+ i.brand + `</span></td>
                    <td class="fw-bold">Rs.`+ i.price + ` </td>
                    <td>
                        <h6 class="fw-bold text-success mb-0">Rs. 1000</h6>
                        <small class="text-secondary fw-bold" style="font-size: 11px;">5 Sold</small>
                    </td>
                    <td>
                        <span class="fw-bold">
                            `+ i.qty + `
                        </span>
                    </td>
                    
                    <td>
                        ${i.qty > 0
                        ? `<span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">In Stock</span>`
                        : `<span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Out of Stock</span>`
                    }

                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-dark border-0" data-bs-toggle="modal" data-bs-target="#productDetailsModal"
                                data-test="../`+ i.img_path + `" data-price="` + i.price + `" data-qty="` + i.qty + `"
                                data-id="`+ i.id + `">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="modal" 
                            data-test="<?php echo '../' . $product_data['img_path']; ?>" 
                                                                data-id=`+ i.product_id + `
                                                                data-brand=`+ i.brand + ` 
                                                                data-product=`+ i.product_name + `
                                                                data-model=`+ i.name + ` 
                                                                data-price=`+ i.price + ` 
                                                                data-qty=`+ i.qty + `
                            data-bs-target="#updateProductModal"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                 </tr>`;


                fragment.appendChild(tr);
            });
            modelList.appendChild(fragment);
        }
    }
    request.open("POST", "loadProducts.php", true);
    request.send(form);
}

const updateProductModal = document.getElementById('updateProductModal');

updateProductModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById("update_id").value = button.dataset.id;
    document.getElementById("update_brand").value = button.dataset.brand;
    document.getElementById("update_product").value = button.dataset.product;
    document.getElementById("update_model").value = button.dataset.model;
    document.getElementById("update_price").value = button.dataset.price;
    document.getElementById("update_qty").value = button.dataset.qty;
    document.getElementById("update_desc").value = button.dataset.desc;

});

document.getElementById("updateProductBtn").addEventListener("click", function () {

    var update_id = document.getElementById("update_id");
    var update_brand = document.getElementById("update_brand");
    var update_product = document.getElementById("update_product");
    var update_model = document.getElementById("update_model");
    var update_price = document.getElementById("update_price");
    var update_qty = document.getElementById("update_qty");
    var update_desc = document.getElementById("update_desc");

    var form = new FormData();
    alert(update_brand.value);
    form.append("id", update_id.value);
    form.append("brand", update_brand.value);
    form.append("product", update_product);
    form.append("model", update_model.value);
    form.append("price", update_price.value);
    form.append("qty", update_qty.value);
    form.append("desc", update_desc.value);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            console.log(request.response);
        }
    }
    request.open("POST", "updateProductDetails.php", true);
    request.send(form);
});

