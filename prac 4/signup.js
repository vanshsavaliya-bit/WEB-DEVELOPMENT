var form = document.querySelector("form");

form.addEventListener("submit", function(event) {

    event.preventDefault();

    var inputs = document.querySelectorAll("input");

    var name = inputs[0].value;
    var enrollment = inputs[1].value;
    var email = inputs[2].value;
    var mobile = inputs[3].value;
    var password = inputs[4].value;
    var confirmPassword = inputs[5].value;

    if (name == "" || enrollment == "" || email == "" ||
        mobile == "" || password == "" || confirmPassword == "") {

        alert("Please fill all fields");

    } else if (password != confirmPassword) {

        alert("Passwords do not match");

    } else {

        alert("Registration Successful!");

    }

});