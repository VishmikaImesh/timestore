function signIn() {

    var email = document.getElementById("email").value;
    var password = document.getElementById("pw").value;

    alert(email + " " + password);

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
            alert(request.response);
            response = request.responseText;

            if (response == "success") {
                window.location = "index.php"
            } else {
                alert(response);
            }

        }

    }

    request.open("POST", "/timestore/api/user/logIn", true);
    request.send(form);
}