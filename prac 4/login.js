var form = document.querySelector("form");

form.addEventListener("submit", function(event) {

    event.preventDefault();

    var email = document.querySelector('input[type="email"]').value;
    var password = document.querySelector('input[type="password"]').value;

    if (email == "" || password == "") {
        alert("Please enter Email and Password");
    } else {
        alert("Login Successful!");
    }

});