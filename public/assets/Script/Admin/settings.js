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