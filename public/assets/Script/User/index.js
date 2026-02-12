window.addEventListener("load", loadProducts)

function loadProducts() {
    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);

            var populartItemsTable= document.getElementById("populartItemsBody");
            populartItemsTable.innerHTML = "";
            var fragment = document.createDocumentFragment();
            
            jsonObject.models.forEach(model => {
                var div = document.createElement("div");
                
                div.classList.add("col-12", "col-sm-6", "col-lg-3");
                div.innerHTML=`
                <a href="/timestore/viewProduct/${model.product_id}"  class="text-decoration-none">
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
            populartItemsTable.appendChild(fragment);
        }
    }
    request.open("POST", "/timestore/api/product/load", true);
    request.send();
}