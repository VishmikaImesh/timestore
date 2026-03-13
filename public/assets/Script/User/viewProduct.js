const models = {};
const OPEN_BUY_MODAL_KEY = "timestore:openBuyModal";
let buying_model_id = 0;
let isLoggedIn = false;

window.addEventListener("load", event => {
    const buyNowButton = document.getElementById("buyNow");
    isLoggedIn = buyNowButton && buyNowButton.dataset.auth === "1";

    var pathname = window.location.pathname;
    var pattern = /^\/viewProduct\/([0-9]+)$/;
    var matches = pathname.match(pattern);
    loadModels(matches[1]);

});

document.getElementById("modelsTable").addEventListener("click", function(event) {
    var button = event.target.closest(".btn");
    changeModel(button.dataset.model_id);
});
document.getElementById("buyNow").addEventListener("click", function () {
    if (!isLoggedIn) {
        return;
    }
    buying_product();
});

const checkoutForm = document.getElementById("checkoutSignInForm");
if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (event) {
        event.preventDefault();
        checkoutSignIn();
    });
}

function loadModels(product_id) {
    var form = new FormData();
    form.append("product_id", product_id);

    var request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var jsonObject = JSON.parse(request.response);
            var modelsTable = document.getElementById("modelsTable");
            modelsTable.innerHTML = "";
            var fragment = document.createDocumentFragment();

            jsonObject.models.forEach(model => {
                models[model.model_id] = model;
                var button = document.createElement("button");
                button.dataset.model_id=model.model_id;
                button.classList.add("btn", "border", "rounded-3");
                button.innerHTML=`<img src="${model.img_path}" width="50" alt="Side View">`;
                fragment.appendChild(button);
            });
            modelsTable.appendChild(fragment);
            changeModel(jsonObject.models[0].model_id);
            maybeOpenBuyModal();
        }
    }
    request.open("POST", "/api/model/load", true);
    request.send(form);
}

function changeModel(model_id) {
    buying_model_id = model_id;
    const model =models[model_id]
    document.getElementById("product_label").innerText = model.model_name;
    document.getElementById("model").innerText = model.model_name;
    document.getElementById("price").innerText = "Rs." + model.price;
    document.getElementById("vimg").src = model.img_path;
}

function buying_product(){
    const model = models[buying_model_id];
    const buyingProductId = document.getElementById("buying_product_id");
    const buyingProductBrand = document.getElementById("buying_product_brand");
    const buyingProductModel = document.getElementById("buying_product_model");
    const buyingProductPrice = document.getElementById("buying_product_price");
    const buyingProductImg = document.getElementById("mimg");

    if (!model || !buyingProductId || !buyingProductBrand || !buyingProductModel || !buyingProductPrice || !buyingProductImg) {
        return;
    }

    buyingProductId.value = buying_model_id;
    buyingProductBrand.textContent = model.brand_id;
    buyingProductModel.textContent = model.model_name;
    buyingProductPrice.textContent = "Rs." + model.price;
    buyingProductImg.src = model.img_path;
}

function toCheckout() {

    var buying_product_qty = document.getElementById("pqty");
    var buying_product_id = document.getElementById("buying_product_id");

    window.location = "/checkout/" + buying_product_id.value+"/"+buying_product_qty.value;

}

function checkoutSignIn() {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("pw");
    const rememberInput = document.getElementById("rememberMe");

    if (!emailInput || !passwordInput) {
        return;
    }

    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const rememberMe = rememberInput && rememberInput.checked ? 1 : 0;

    if (!email || !password) {
        alert("Please enter your email and password.");
        return;
    }

    const form = new FormData();
    form.append("email", email);
    form.append("password", password);
    form.append("rememberMe", rememberMe);

    const request = new XMLHttpRequest();
    request.onreadystatechange = function () {
        if (request.readyState === 4) {
            if (request.status !== 200) {
                alert("Sign in failed. Please try again.");
                return;
            }

            let response;
            try {
                response = JSON.parse(request.responseText);
            } catch (error) {
                alert("Sign in failed. Please try again.");
                return;
            }

            if (response.state) {
                localStorage.setItem(OPEN_BUY_MODAL_KEY, "1");
                window.location.reload();
            } else {
                alert(response.message || "Invalid email or password.");
            }
        }
    };

    request.open("POST", "/api/user/logIn", true);
    request.send(form);
}

function maybeOpenBuyModal() {
    if (!isLoggedIn) {
        localStorage.removeItem(OPEN_BUY_MODAL_KEY);
        return;
    }

    if (localStorage.getItem(OPEN_BUY_MODAL_KEY) !== "1") {
        return;
    }

    localStorage.removeItem(OPEN_BUY_MODAL_KEY);
    buying_product();

    const modalElement = document.getElementById("exampleModal");
    if (modalElement && window.bootstrap && window.bootstrap.Modal) {
        const modalInstance = window.bootstrap.Modal.getOrCreateInstance(modalElement);
        modalInstance.show();
    }
}

function qtyUp() {
    const pqty = document.getElementById("pqty");
    const qtyWarning = document.getElementById("qtyWarning");

    if (!pqty) {
        return;
    }

    pqty.value = parseInt(pqty.value || "1", 10) + 1;

    if (qtyWarning) {
        qtyWarning.textContent = "";
    }
}

function qtyDown() {
    const pqty = document.getElementById("pqty");
    const qtyWarning = document.getElementById("qtyWarning");

    if (!pqty) {
        return;
    }

    pqty.value = Math.max(1, parseInt(pqty.value || "1", 10) - 1);

    if (qtyWarning) {
        qtyWarning.textContent = "";
    }
}