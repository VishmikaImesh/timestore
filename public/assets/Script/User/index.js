document.addEventListener("DOMContentLoaded", function () {
    loadPopularItems();
    loadNewItems();
});


function loadPopularItems() {
    var form = new FormData();
    form.append("sort", 3);
    form.append("page_size", 4);
    form.append("page", 1);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const jsonObject = JSON.parse(request.response);

            var newItemesBody = document.getElementById("popularItemesBody");
            newItemesBody.innerHTML = "";
            var fragment = document.createDocumentFragment();

            const models = jsonObject.data.models;


            models.forEach(model => {
                var div = document.createElement("div");

                div.classList.add("col-12", "col-sm-6", "col-lg-3");
                div.innerHTML = `
                <a href="/timestore/viewProduct/${model.product_id}" class="text-decoration-none text-dark">
    <div class="card h-100 border-0 shadow-sm transition-hover">
        <div class="m-2 bg-light rounded-3 p-4 text-center d-flex align-items-center justify-content-center" style="height: 240px;">
            <img src="${model.img_path}" 
                 class="img-fluid" 
                 style="max-height: 100%; object-fit: contain;" 
                 alt="${model.product_name}">
        </div>
        
        <div class="card-body px-3 pb-4">
            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.85rem; letter-spacing: 1.2px;">
                ${model.brand}
            </p>
            
            <h5 class="card-title fw-bold text-dark mb-2">
                ${model.product_name}
            </h5>
            
            <div class="mt-3">
                <span class="fs-4 fw-black fw-bold">
                    Rs.${model.price}
                </span>
            </div>
        </div>
    </div>
</a>            `;
                fragment.appendChild(div);
            });
            newItemesBody.appendChild(fragment);
        }
    }
    request.open("POST", "/api/product/load", true);
    request.send(form);
}

function loadNewItems() {
    var form = new FormData();
    form.append("sort", 4);
    form.append("page_size", 4);
    form.append("page", 1);
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            const jsonObject = JSON.parse(request.response);

            var newItemesBody = document.getElementById("newItemesBody");
            newItemesBody.innerHTML = "";
            var fragment = document.createDocumentFragment();

            const models = jsonObject.data.models;


            models.forEach(model => {
                var div = document.createElement("div");

                div.classList.add("col-12", "col-sm-6", "col-lg-3");
                div.innerHTML = `
                <a href="/timestore/viewProduct/${model.product_id}" class="text-decoration-none text-dark">
    <div class="card h-100 border-0 shadow-sm transition-hover">
        <div class="m-2 bg-light rounded-3 p-4 text-center d-flex align-items-center justify-content-center" style="height: 240px;">
            <img src="${model.img_path}" 
                 class="img-fluid" 
                 style="max-height: 100%; object-fit: contain;" 
                 alt="${model.product_name}">
        </div>
        
        <div class="card-body px-3 pb-4">
            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.85rem; letter-spacing: 1.2px;">
                ${model.brand}
            </p>
            
            <h5 class="card-title fw-bold text-dark mb-2">
                ${model.product_name}
            </h5>
            
            <div class="mt-3">
                <span class="fs-4 fw-black fw-bold">
                    Rs.${model.price}
                </span>
            </div>
        </div>
    </div>
</a> 
                `;
                fragment.appendChild(div);
            });
            newItemesBody.appendChild(fragment);
        }
    }
    request.open("POST", "/api/product/load", true);
    request.send(form);
}


