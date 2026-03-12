document.getElementById("settings-tab").addEventListener("click", loadDeliveryDetails);

// Handle updateDeliveryForm submission
const updateDeliveryForm = document.getElementById("updateDeliveryForm");
if (updateDeliveryForm) {
    updateDeliveryForm.addEventListener("submit", (event) => {
        event.preventDefault();
        
        const deliveryId = document.getElementById("delivery_selector").value;
        const newPrice = document.getElementById("price_input").value;
        const newDays = document.getElementById("days_input").value;
        
        if (!deliveryId || !newPrice || !newDays) {
            alert("Please fill in all fields");
            return;
        }
        
        const form = new FormData();
        form.append("id", deliveryId);
        form.append("new_price", newPrice);
        form.append("new_days", newDays);
        
        const request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {
                const response = JSON.parse(request.response);
                alert(response.msg || "Delivery updated successfully");
                loadDeliveryDetails();
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("deliveryModal"));
                if (modal) modal.hide();
            }
        }
        request.open("POST", "/api/delivery/update", true);
        request.send(form);
    });
}

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
                <input type="number" value="${delivery.price}" class="form-control border-start-0 fw-bold" name="new_price" data-field="price" required>
            </div>
        </div>

        <div class="col-md-4">
            <div class="input-group">
                <input type="number" value="${delivery.delivery_days}" class="form-control border-end-0 fw-bold" name="new_days" data-field="days" required>
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
    request.open("POST", "/api/delivery/load", true);
    request.send();
}

document.getElementById("deliveryDetailsTableBody").addEventListener("click", (event) => {
    var div = event.target.closest(".row.g-3.align-items-end.mb-3");
    if (event.target.classList.contains("btn-outline-secondary")) {

        const priceInput = div.querySelector('input[data-field="price"]');
        const daysInput = div.querySelector('input[data-field="days"]');
        const price = priceInput ? priceInput.value : "";
        const days = daysInput ? daysInput.value : "";
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
        request.open("POST", "/api/delivery/update", true);
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
        request.open("POST", "/api/delivery/delete", true);
        request.send(form);
    }
})