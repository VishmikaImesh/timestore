window.addEventListener("load", () => {
    loadProducts();
    loadBrands();
});

let filter = document.getElementById("filter");
filter.addEventListener("change", loadProducts);

var brands = document.getElementById("brands");
brands.addEventListener("change", loadProducts);

function loadProducts() {
    var form = new FormData();
    if (filter.selectedIndex != 0) {
        form.append("sort", filter.value);
    }

    var selectedBrands = [];
    brands.querySelectorAll("input").forEach(input => {
        
        if (input.checked) {
            selectedBrands.push(input.value);
        }
    });
    if (selectedBrands.length > 0) {
        form.append("brand", selectedBrands);
    }

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
           
            var jsonObject = JSON.parse(request.response);

            var productsTable = document.getElementById("modelsTable");
            productsTable.innerHTML = "";
            var fragment = document.createDocumentFragment();

            jsonObject.data.models.forEach(model => {
                var div = document.createElement("div");

                div.classList.add("col-12", "col-sm-6", "col-lg-3");
                div.innerHTML = `
                <a href="/viewProduct/${model.product_id}"  class="text-decoration-none">
                    <div class="card border-0 h-100">
                        <div class="bg-light rounded-3 p-4 text-center mb-3">
                            <img src="${model.img_path}" class="img-fluid" style="height: 180px; object-fit: contain;" alt="Watch">
                        </div>
                        <div class="card-body px-0 pt-0">
                            <small class="text-muted fw-semibold">${model.brand}</small>
                            <h6 class="card-title  fw-bold mb-1">${model.product_name}</h6>
                            <p class="fw-bold text-dark">Rs.${model.price}</p>
                        </div>
                    </div>
                </a>
                `;
                fragment.appendChild(div);
            });
            productsTable.appendChild(fragment);
        }
    }
    request.open("POST", "/api/product/load", true);
    request.send(form);
}

function loadBrands() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            var brands = document.getElementById("brands");
            brands.innerHTML = "";
            var fragment = document.createDocumentFragment();

            jsonObject.brands.forEach(brand => {
                var div = document.createElement("div");
                div.classList.add("form-check", "mb-2");
                div.innerHTML = `
                                <input class="form-check-input" type="checkbox" value="${brand.id}" >
                                <label class="form-check-label" for="brand${brand.id}">${brand.name}</label>
                `;
                fragment.appendChild(div);
            });
            brands.appendChild(fragment);
        }
    }
    request.open("POST", "/api/brand/load", true);
    request.send();
}