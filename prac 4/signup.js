
const form = document.getElementById("signupForm");
const message = document.getElementById("message");

form.addEventListener("submit", function (event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const course = document.getElementById("course").value;
    const terms = document.getElementById("terms").checked;

    if (name === "") {
        showMessage("Please enter your name.", false);
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showMessage("Please enter a valid email.", false);
    } else if (!/^\d{10}$/.test(mobile)) {
        showMessage("Mobile number must contain 10 digits.", false);
    } else if (password.length < 8) {
        showMessage("Password must contain at least 8 characters.", false);
    } else if (password !== confirmPassword) {
        showMessage("Passwords do not match.", false);
    } else if (course === "") {
        showMessage("Please select a course.", false);
    } else if (!terms) {
        showMessage("Please accept the terms.", false);
    } else {
        showMessage("Registration successful!", true);
        form.reset();
    }
});

function showMessage(text, success) {
    message.textContent = text;
    message.style.color = success ? "green" : "red";
}
