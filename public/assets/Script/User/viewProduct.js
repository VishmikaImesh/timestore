const models = {};
let buying_model_id = 0;

window.addEventListener("load", event => {
    var pathname = window.location.pathname;
    var pattern = /^\/timestore\/viewProduct\/([0-9]+)$/;
    var matches = pathname.match(pattern);
    loadModels(matches[1]);

});

document.getElementById("modelsTable").addEventListener("click", function(event) {
    var button = event.target.closest(".btn");
    changeModel(button.dataset.model_id);
});
document.getElementById("buyNow").addEventListener("click", buying_product);

function loadModels(product_id) {
    var form = new FormData();
    form.append("product_id", product_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            alert(request.response);
            var jsonObject = JSON.parse(request.response);
            var modelsTable = document.getElementById("modelsTable");
            modelsTable.innerHTML = "";
            var fragment = document.createDocumentFragment();

            jsonObject.models.forEach(model => {
                models[model.model_id] = model;
                var button = document.createElement("button");
                button.dataset.model_id=model.model_id;
                button.classList.add("btn", "border", "p-2", "rounded-3");
                button.innerHTML=`<img src="${model.img_path}" width="50" alt="Side View">${model.model_name}`;
                fragment.appendChild(button);
            });
            modelsTable.appendChild(fragment);
            changeModel(jsonObject.models[1].model_id);
        }
    }
    request.open("POST", "/timestore/api/model/load", true);
    request.send(form);
}

function changeModel(model_id) {
    buying_model_id = model_id;
    const model =models[model_id]
    document.getElementById("model").innerText = model.model_name;
    document.getElementById("price").innerText = "Rs." + model.price;
    document.getElementById("vimg").src = model.img_path;
}

function buying_product(){
    const model =models[buying_model_id]
    document.getElementById("buying_product_id").value = buying_model_id;
    document.getElementById("buying_product_brand").value = model.brand_id;
    document.getElementById("buying_product_model").innerHTML = model.model_name;
    document.getElementById("buying_product_price").innerHTML = "Rs." + model.price;
    document.getElementById("mimg").src = model.img_path;
}

function toCheckout() {

    var buying_product_qty = document.getElementById("pqty");
    var buying_product_id = document.getElementById("buying_product_id");

    window.location = "/timestore/checkout/" + buying_product_id.value+"/"+buying_product_qty.value;

}