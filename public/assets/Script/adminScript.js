
function adminLogIn() {

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
    form.append("rm", rememberMe);

    var request = new XMLHttpRequest();

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            response = request.responseText;

            if (response == "success") {
                window.location = "dashboard.php"
            } else {
                alert(response);
            }

        }

    }

    request.open("POST", "/timestore/api/user/logIn", true);
    request.send(form);

}



document.getElementById("profile-tab").addEventListener("click", () => {
    loadProducts();
    loadBrands();
});
document.getElementById("filerByBrand").addEventListener("change", loadProducts);
document.getElementById("sortByPrice").addEventListener("change", loadProducts);

var brands ={};

function loadBrands() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            var brands = document.getElementById("filerByBrand");
            var brandSelect = document.getElementById("brandSelect");

            
            var fragment = document.createDocumentFragment();
            // var defaultOption = document.createElement("option");
            // defaultOption.textContent="Filter by Brand";
            // fragment.appendChild(defaultOption);

            jsonObject.brands.forEach(brand => {
                var select = document.createElement("option");
                select.textContent=brand.name;
                select.value=brand.id;
                fragment.appendChild(select);
            });
            brands.appendChild(fragment.cloneNode(true));
            brandSelect.appendChild(fragment.cloneNode(true));
        }
    }
    request.open("POST", "/timestore/api/brand/load", true);
    request.send();
}

function loadProducts() {
    var brandId = document.getElementById("filerByBrand");
    var sort = document.getElementById("sortByPrice");

    var form = new FormData();
    if (brandId.selectedIndex > 0) {
        form.append("brand",brandId.value);
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
                            <img src="`+ i.img_path + `" class="product-thumb" style="max-width: 100%; max-height: 100%;" alt="Thumb">
                        </div>
                    </td>
                    <td>
                        <h6 class="fw-bold text-dark mb-0">`+ i.product_name + `</h6>
                        <small class="text-muted">`+ i.product_name + `</small>

                    </td>
                    <td><span class="badge bg-light text-dark border">`+ i.brand + `</span></td>
                    <td class="fw-bold">Rs.`+ i.price + ` </td>
                    <td>
                        <h6 class="fw-bold text-success mb-0">Rs.${i.revenue}</h6>
                        <small class="text-secondary fw-bold" style="font-size: 11px;">${i.order_qty} Sold</small>
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
                            <button  class="btn btn-sm btn-outline-dark border-0" data-bs-toggle="modal" data-bs-target="#productDetailsModal"
                                data-product_id="`+ i.product_id + `" > 
                                <i class="bi bi-eye-fill"></i>
                            </button>
                           
                            <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash-fill"></i></button>
                        </div>
                    </td>
                 </tr>`;


                fragment.appendChild(tr);
            });
            modelList.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/product/load", true);
    request.send(form);
};

const productDetailsModal = document.getElementById('productDetailsModal');

var updateProductBtn = document.getElementById("updateProductBtn");

productDetailsModal.addEventListener('show.bs.modal', (event) => {
    loadModels(event.relatedTarget.dataset.product_id);
});

function loadModels(product_id) {
    alert("loadMoels:" + product_id)

    document.getElementById("modalProductTitle").innerHTML;

    var variantsTableBody = document.getElementById("variantsTableBody");
    variantsTableBody.innerHTML = "";

    var modalSold = 0;
    var modalQty = 0;

    updateProductBtn.dataset.product_id = product_id;

    var form = new FormData();
    form.append("product_id", product_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {

            var jsonObject = JSON.parse(request.response);
            document.getElementById("modalImg").src = jsonObject.models[0].img_path;

            var fragment = document.createDocumentFragment();
            jsonObject.models.forEach(model => {
                modalSold += parseInt(model.order_qty);
                modalQty += parseInt(model.qty);
                var modelItem =
                    `<td class="ps-4">
                        <img src=`+ model.img_path + `
                            class="rounded border bg-white p-1"
                            width="40" height="40"
                            style="object-fit: contain;">
                    </td>

                    <td>
                        <span class="fw-bold text-dark small">`+ model.model_name + `</span>
                    </td>

                    <td class="small fw-bold">`+ model.order_qty + `</td>

                    <td class="small fw-bold">Rs.`+ model.price + `</td>

                    <td class="small fw-bold">`+ model.qty + `</td>

                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 10px;">In Stock</span>
                    </td>
                                  
                               
                    <td>
                        <button class="btn btn-sm btn-outline-secondary border-0" data-bs-toggle="modal" 
                                                            data-product_id="`+ model.product_id + `"
                                                            data-model_id = "`+ model.model_id + `"
                        data-bs-target="#updateProductModal"><i class="bi bi-pencil-fill"></i></button>
                        <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash-fill"></i></button>
                    </td>
                    `;

                var tr = document.createElement("tr");
                tr.innerHTML = modelItem;
                fragment.appendChild(tr);
            });
            variantsTableBody.appendChild(fragment);
            document.getElementById("modalSold").textContent = modalSold;
            document.getElementById("modalStock").textContent = modalQty;
            renderProductChart(product_id);
        }
    }
    request.open("POST", "/timestore/api/model/load", true);
    request.send(form);
};

const updateProductModal = document.getElementById('updateProductModal');

updateProductModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget;

    var form = new FormData();
    form.append("product_id", button.dataset.product_id);
    form.append("model_id", button.dataset.model_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            document.getElementById("product_id").value = jsonObject.models[0].product_id;
            document.getElementById("update_id").value = button.dataset.model_id;
            document.getElementById("update_model").value = jsonObject.models[0].model_name;
            document.getElementById("update_qty").value = jsonObject.models[0].qty;
            document.getElementById("update_price").value = jsonObject.models[0].price;


        }
    }
    request.open("POST", "/timestore/api/model/load", true);
    request.send(form);

});

updateProductBtn.addEventListener("click", (event) => {

    var product_id = document.getElementById("product_id");
    var update_id = document.getElementById("update_id");
    var update_price = document.getElementById("update_price");
    var update_qty = document.getElementById("update_qty");
    var update_img = document.getElementById("update_img");
    var update_desc = document.getElementById("update_desc");


    var form = new FormData();
    form.append("model_id", update_id.value);
    form.append("img", update_img.files[0]);
    form.append("model_name", update_model.value);
    form.append("price", update_price.value);
    form.append("qty", update_qty.value);
    form.append("desc", update_desc.value);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            loadModels(product_id.value);
            loadProducts();
            new bootstrap.Modal(productDetailsModal).show();
        }
    }
    request.open("POST", "/timestore/api/model/update", true);
    request.send(form);
});


function renderProductChart(id) {
    var productChart = null;
    const ctx = document.getElementById('productSalesChart').getContext('2d');

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
            gradient.addColorStop(0, 'rgba(220, 53, 69, 0.4)');
            gradient.addColorStop(1, 'rgba(220, 53, 69, 0)');

            productChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: jsonObject.date,
                    datasets: [{
                        label: 'Revenue Rs',
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
    request.open("POST", "/timestore/api/product/renderProductChart", true);
    request.send(form);
};



document.getElementById("orders-tab").addEventListener("click", loadOrders);

function loadOrders() {

    var orderTableBody = document.getElementById("orderTableBody");
    orderTableBody.innerHTML = "";

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);

            var jsonObject = JSON.parse(request.response);
            var fragment = document.createDocumentFragment();

            jsonObject.orders.forEach(order => {
                var tr = document.createElement("tr");
                tr.innerHTML = `<td class="ps-4 fw-bold text-danger">#ORD-${order.order_id}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-size: 0.8rem;">JS</div>
                                                                <div>
                                                                    <h6 class="mb-0 text-dark small fw-bold">${order.first_name + " " + order.last_name}</h6>
                                                                    <small class="text-muted" style="font-size: 0.75rem;">${order.user_email}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-secondary small fw-bold">${order.ordered_date}</td>
                                                        <td class="fw-bold">Rs.${order.total}</td>
                                                        <td><span class="badge bg-light text-secondary border">Card</span></td>
                                                       <td><span class="status-badge">${order.status}</span></td>
                                                        <td class="text-end pe-4">
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                                                    <i class="bi bi-three-dots-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                                                    <li><a class="dropdown-item small py-2" data-order_id="${order.order_id}" data-bs-toggle="modal" data-bs-target="#orderModal"><i class="bi bi-eye me-2"></i>View Details</a></li>
                                                                    <li><a class="dropdown-item small py-2" href="#"><i class="bi bi-truck me-2"></i>Mark as Shipped</a></li>
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li><a class="dropdown-item small py-2 text-danger" href="#"><i class="bi bi-x-circle me-2"></i>Cancel Order</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>` ;
                fragment.appendChild(tr)

            });
            orderTableBody.appendChild(fragment);

        }
    }
    request.open("POST", "/timestore/api/order/load", true);
    request.send();
}

document.getElementById("orderModal").addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var form = new FormData();
    form.append("order_id", button.dataset.order_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            var jsonObject = JSON.parse(request.response);

            if (jsonObject.state) {
                var data = jsonObject.data;

                document.getElementById("viewOrderId").innerHTML = data.order.order_id;
                document.getElementById("viewCustomerName").textContent = data.user.first_name + " " + data.user.last_name;
                document.getElementById("viewCustomerEmail").textContent = data.user.email;
                document.getElementById("viewCustomerPhone").textContent = data.user.mobile;
                document.getElementById("viewOrderDate").textContent = data.order.ordered_date;
                document.getElementById("viewCustomerAddressLine1").textContent = data.user.address_line1;
                document.getElementById("viewCustomerAddressLine2").textContent = data.user.address_line2;
                document.getElementById("viewCustomerCity").textContent = data.user.city;
                document.getElementById("viewCustomerProvince").textContent = data.user.province + "province";
                document.getElementById("viewCustomerDistrict").textContent = data.user.district;
                document.getElementById("viewDelivery").textContent = "Rs." + data.order.delivery_fee;
                document.getElementById("viewSubTotal").textContent = "Rs. " + data.order.sub_total;
                document.getElementById("viewGrandTotal").textContent = "Rs. " + data.order.grand_total;

                var orderItemsTable = document.getElementById("orderItemsTable");
                orderItemsTable.innerHTML = "";

                var fragment = document.createDocumentFragment();

                data.order_items.forEach(order => {
                    var tr = document.createElement("tr");
                    tr.innerHTML =
                        `
                                                        <td class="ps-4 py-3">
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-white border rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                                    <img src=${order.img_src} class="img-fluid p-1" style="max-height: 100%; object-fit: contain;" alt="Product">
                                                                </div>

                                                                <div>
                                                                    <h6 class="fw-bold text-dark mb-0 small">${order.model}</h6>
                                                                    <small class="text-muted" style="font-size: 11px;">${order.product_name}</small>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td class="text-center align-middle">
                                                            <span class="fw-bold text-secondary">${order.qty}</span>
                                                        </td>

                                                        <td class="text-end align-middle">
                                                            <span class="small fw-bold text-dark">Rs.${order.price}</span>
                                                        </td>

                                                        <td class="text-end align-middle pe-4">
                                                            <span class="fw-bold text-dark">Rs.${order.price * order.qty}</span>
                                                        </td>
                                                    `;
                    fragment.appendChild(tr);

                });

                orderItemsTable.appendChild(fragment);
            } else {
                alert("kk");
            }


        }
    }
    request.open("POST", "/timestore/api/order/details", true);
    request.send(form);
})


document.getElementById("customers-tab").addEventListener("click", loadUsers);
document.getElementById("userStatusFilter").addEventListener("change", loadUsers);

function loadUsers() {

    var status = document.getElementById("userStatusFilter").value;

    var form = new FormData();
    form.append("status", status);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            var jsonObject = JSON.parse(request.response);

            document.getElementById("totalUserCount").innerHTML = jsonObject.users.length;
            document.getElementById("activeUserCount").innerHTML = jsonObject.active;
            document.getElementById("blockedUserCount").innerHTML = jsonObject.blocked;

            var customerTableBody = document.getElementById("customerTableBody");
            customerTableBody.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.users.forEach(user => {
                var tr = document.createElement("tr");
                var statusBadgeClass = "bg-success bg-opacity-10 text-success";

                if (user.status === "Blocked") {
                    statusBadgeClass = "bg-success bg-opacity-10 text-danger";
                }

                tr.innerHTML = `
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-circle me-3"></div>
                                                                <div>
                                                                    <h6 class="fw-bold text-dark mb-0">${user.first_name + " " + user.last_name}</h6>
                                                                    <small class="text-muted">ID: #USR-<?php echo rand(100, 999); ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <small class="text-dark fw-bold mb-1"><i class="bi bi-envelope me-1 text-secondary"></i> ${user.email} </small>
                                                                <small class="text-secondary"><i class="bi bi-phone me-1"></i> ${user.mobile} </small>
                                                            </div>
                                                        </td>
                                                        
                                                        <td> 
                                                            <div class="d-flex flex-column">
                                                                <small class="text-dark fw-bold">${user.order_count} Orders</small>
                                                                <small class="text-success fw-bold">LKR ${new Intl.NumberFormat('en-LK').format(user.total_spent || 0)}</small>
                                                            </div>
                                                        </td>
                                                        <td class="text-secondary small fw-bold">${user.joined_date}</td>
                                                        <td class="text-center">
                                                            <span class="badge ${statusBadgeClass} rounded-pill px-3">${user.status}</span>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="modal" data-email=${user.email} data-bs-target="#userModal" title="View Details">
                                                                <i class="bi bi-eye-fill text-dark"></i>
                                                            </button>
                                                            
                                                                <button class="btn btn-sm btn-light border text-danger" title="Block User">
                                                                    <i class="bi bi-slash-circle"></i>
                                                                </button>
                                                           
                                                                <button class="btn btn-sm btn-light border text-success" title="Unblock User">
                                                                    <i class="bi bi-check-circle"></i>
                                                                </button>
                                                            
                                                        </td>
                                                    `;
                fragment.appendChild(tr);

            });

            customerTableBody.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/user/load", true);
    request.send(form);

}

document.getElementById("userModal").addEventListener("show.bs.modal", (event) => {
    var button = event.relatedTarget;

    var form = new FormData();
    form.append("email", button.dataset.email);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            document.getElementById("userEmail").textContent = jsonObject.user.email;
            document.getElementById("userName").textContent = jsonObject.user.first_name + " " + jsonObject.user.last_name;
            document.getElementById("totalSpend").textContent = "Rs. " + jsonObject.user.total_spent;
            document.getElementById("orderCount").textContent = jsonObject.user.order_count;
            document.getElementById("mobile").textContent = jsonObject.user.mobile ?? 'n/a';


            var shippingAddress = document.getElementById("shippingAddress");

            if (!jsonObject.address) {
                shippingAddress.innerHTML = `
                <span id="line_one">Address not available</span>`;
            } else {
                shippingAddress.innerHTML = `
                <span id="line_one">${jsonObject.address.line_one}</span> <span id="line_two">${jsonObject.address.line_two}</span><br>
                                                            <span id="city">${jsonObject.address.city}</span><br>
                                                            <span id="district">${jsonObject.address.district}</span><br>
                                                            <span id="province">${jsonObject.address.province}</span><br>
                                                            <span id="postalCode">${jsonObject.address.postal_code}</span>
               `;

                document.getElementById("line_one").textContent = jsonObject.address.line_one;
                document.getElementById("line_two").textContent = jsonObject.address.line_two;
                document.getElementById("city").textContent = jsonObject.address.city;
                document.getElementById("district").textContent = jsonObject.address.district;
                document.getElementById("province").textContent = jsonObject.address.province;
            }


            var recenOtrders = document.getElementById("recenOtrders");
            recenOtrders.innerHTML = "";

            var fragment = document.createDocumentFragment();

            if (!jsonObject.orders) {
                var div = document.createElement("div");
                div.classList.add("list-group-item", "px-0", "d-flex", "justify-content-center", "align-items-center", "border-bottom");
                div.innerHTML = `<span class="text-muted small">No orders found.</span>`;
                fragment.appendChild(div);
                recenOtrders.appendChild(fragment);

            } else {

                jsonObject.orders.forEach(order => {
                    var div = document.createElement("div");
                    div.classList.add("list-group-item", "px-0", "d-flex", "justify-content-between", "align-items-center", "border-bottom");
                    div.innerHTML = `
                
                                                            <div>
                                                                <p class="mb-0 fw-bold small">#${order.order_id}</p>
                                                                <small class="text-muted" style="font-size: 11px;">${order.ordered_date}</small>
                                                            </div>
                                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill">${order.status}</span>
                                                            <span class="fw-bold small">Rs. ${order.total}</span>
                                                        `;
                    fragment.appendChild(div);
                });

                recenOtrders.appendChild(fragment);
            }
        }
    }
    request.open("POST", "/timestore/api/user/details", true);
    request.send(form);

})


document.getElementById("messages-tab").addEventListener("click", loadMsgSenders);
var messageButtons;

function loadMsgSenders() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            var jsonObject = JSON.parse(request.response);
            var messageTableBody = document.getElementById("msgSenderTableBody");
            messageTableBody.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.forEach(user => {
                var div = document.createElement("div");
                div.className = "d-flex align-items-center p-2 mb-2 border-bottom hover-bg message_item";
                div.dataset.email = user.sender;
                div.dataset.first_name = user.fname;
                div.dataset.last_name = user.lname;
                div.dataset.new_msg = user.new_msg;
                div.innerHTML = `
    <div class="position-relative">
        <img src="https://ui-avatars.com/api/?name=John+Doe&background=dc3545&color=fff" class="rounded-circle me-3" width="45">
    </div>

    <div class="flex-grow-1 overflow-hidden">   
        <h6 class="mb-0 fw-bold text-dark text-truncate">${user.fname} ${user.lname}</h6>
         <small class="text-secondary d-block text-truncate">${user.sender}</small>
        </div>

    <div class="d-flex flex-column align-items-end ms-2">
        
        <small class="fw-bold text-secondary mb-1" style="font-size: 11px;">${user.date}</small>
         ${user.new_msg > 0 ? `
        <span class="badge rounded-pill text-bg-primary">
           ${user.new_msg}
        </span>` : ``}

    </div>`;
                fragment.appendChild(div);
            });
            messageTableBody.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/message/senders", true);
    request.send();
}

document.getElementById("msgSenderTableBody").addEventListener("click", (event) => {
    var button = event.target.closest(".message_item");
    loadMessageItems(button.dataset.email);
});

function loadMessageItems(email) {

    var form = new FormData();
    form.append("sender", email);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            document.getElementById("newMsgCount").textContent = jsonObject.sender.new_msg > 0 ? jsonObject.sender.new_msg + " new messages" : "No new messages";
            document.getElementById("msgSender").textContent = jsonObject.sender.fname + " " + jsonObject.sender.lname;

            var userMsgTableBody = document.getElementById("userMsgTableBody");
            userMsgTableBody.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.messages.forEach(message => {
                var div = document.createElement("div");

                div.className = "col-12";
                div.innerHTML = `
                    <div class="card msg-card shadow-sm rounded-3 border-0" 
                        data-subject="${message.subject}"
                        data-message_id="${message.message_id}"
                        data-message="${message.message}"
                        data-date="${message.date}"
                        data-time="${message.time}"
                        data-name="${jsonObject.sender.fname} ${jsonObject.sender.lname}"
                        data-sender="${jsonObject.sender.email}"
                        data-status="${message.status}"
                        >
                                                <div class="card-body p-4">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <h6 class="fw-bold ${message.status === '1' ? `text-secondary` : `text-dark`} mb-0">${message.subject}</h6>
                                                        <small class="text-muted">${message.date}</small>
                                                    </div>
                                                    <p class="text-muted small mb-0 text-truncate">
                                                        ${message.message}
                                                    </p>
                                                </div>
                                            </div>
                `;
                div.dataset.bsToggle = "modal";
                div.dataset.bsTarget = "#readMessageModal";

                fragment.appendChild(div);
            });
            userMsgTableBody.appendChild(fragment);

        }
    }
    request.open("POST", "/timestore/api/message/userMessages", true);
    request.send(form);
}

document.getElementById("userMsgTableBody").addEventListener("click", (event) => {
    var card = event.target.closest(".msg-card");

    document.getElementById("msgModalSubject").textContent = card.dataset.subject;
    document.getElementById("msgModalSender").textContent = card.dataset.name;
    document.getElementById("msgModalEmail").textContent = card.dataset.sender;
    document.getElementById("msgModalDate").textContent = card.dataset.date;
    document.getElementById("msgModalContent").textContent = card.dataset.message;
    document.getElementById("msgModalTime").textContent = card.dataset.time;

    if (card.dataset.status == 2) {
        var form = new FormData();
        form.append("message_id", card.dataset.message_id);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                var h6 = card.querySelector("h6");
                h6.classList.remove("text-dark");
                h6.classList.add("text-secondary");
                loadMsgSenders();
                loadMessageItems(card.dataset.sender);

            }
        }
        request.open("POST", "/timestore/api/message/changeState", true);
        request.send(form);
    }

})

document.getElementById("settings-tab").addEventListener("click", loadDeliveryDetails);

function loadDeliveryDetails() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response)
            var jsonObject = JSON.parse(request.response);

            var deliveryDetailsTableBody = document.getElementById("deliveryDetailsTableBody");
            deliveryDetailsTableBody.innerHTML = "";

            var fragment = document.createDocumentFragment();

            jsonObject.forEach(delivery => {
                var div = document.createElement("div");
                div.classList = "row g-3 align-items-end mb-3";
                div.dataset.id = delivery.id;
                div.innerHTML = `
                                           <div class=" gap-3">
    <label class="form-label fw-bold text-secondary small">${delivery.method}</label>
    <div class="row ">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0 text-secondary">Rs.</span>
                <input type="number" value="${delivery.price}" class="form-control border-start-0 fw-bold" name="new_price" id="price_input" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <input type="number" value="${delivery.delivery_days}" class="form-control border-end-0 fw-bold" name="new_days" id="days_input" required>
                <span class="input-group-text bg-light border-start-0 text-secondary">Est. Days</span>
            </div>
        </div>
        <div class="col-md-3 d-flex gap-1 justify-content-end  ">
            <button class="bi bi-bookmark-check-fill btn btn-sm btn-outline-secondary border-0 "></button>
            <button class="bi bi-trash-fill btn btn-sm btn-outline-danger border-0 "></button>
            <button class="btn btn-sm btn-outline-success bg-opacity-10 text-success my-1">Active</button>
        </div>
    </div>
</div>
                                            `;
                fragment.appendChild(div);
            });
            deliveryDetailsTableBody.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/delivery/load", true);
    request.send();
}

document.getElementById("deliveryDetailsTableBody").addEventListener("click", (event) => {
    var div = event.target.closest(".row.g-3.align-items-end.mb-3");
    if (event.target.classList.contains("btn-outline-secondary")) {

        let price, days;
        div.querySelectorAll("input").forEach(input => {
            input.id == "price_input" ? price = input.value : "";
            input.id == "days_input" ? days = input.value : "";
        })
        var deliveryId = div.dataset.id;

        var form = new FormData();
        form.append("id", deliveryId);
        form.append("new_price", price);
        form.append("new_days", days);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                alert(request.response);
            }
        }
        request.open("POST", "/timestore/api/delivery/update", true);
        request.send(form);

    } else if (event.target.classList.contains("btn-outline-danger")) {

        let form = new FormData();
        form.append("id", div.dataset.id);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                alert(request.response);
                loadDeliveryDetails();
            }
        }
        request.open("POST", "/timestore/api/delivery/delete", true);
        request.send(form);
    }
})










