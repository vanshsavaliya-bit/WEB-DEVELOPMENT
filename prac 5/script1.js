// Get form
const form = document.getElementById("registrationForm");

// Get input fields
const nameInput = document.getElementById("name");
const emailInput = document.getElementById("email");
const mobileInput = document.getElementById("mobile");
const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirmPassword");
const courseInput = document.getElementById("course");
const yearInput = document.getElementById("year");
const termsInput = document.getElementById("terms");


// Regular Expressions

// Name: only alphabets and spaces
const nameRegex = /^[A-Za-z ]{3,50}$/;

// Email validation
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Indian mobile number: starts from 6-9 and contains 10 digits
const mobileRegex = /^[6-9][0-9]{9}$/;

// Password: minimum 8 characters,
// one uppercase, one lowercase, one number and one special character
const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;


// Function to show error
function showError(input, errorId, message) {

    input.classList.add("input-error");
    input.classList.remove("input-success");

    document.getElementById(errorId).textContent = message;
}


// Function to show success
function showSuccess(input, errorId) {

    input.classList.remove("input-error");
    input.classList.add("input-success");

    document.getElementById(errorId).textContent = "";
}


// Validate Name
function validateName() {

    const name = nameInput.value.trim();

    if (name === "") {
        showError(nameInput, "nameError", "Name is required.");
        return false;
    }

    if (!nameRegex.test(name)) {
        showError(
            nameInput,
            "nameError",
            "Name must contain only letters and spaces."
        );
        return false;
    }

    showSuccess(nameInput, "nameError");
    return true;
}


// Validate Email
function validateEmail() {

    const email = emailInput.value.trim();

    if (email === "") {
        showError(emailInput, "emailError", "Email is required.");
        return false;
    }

    if (!emailRegex.test(email)) {
        showError(emailInput, "emailError", "Enter a valid email address.");
        return false;
    }

    showSuccess(emailInput, "emailError");
    return true;
}


// Validate Mobile
function validateMobile() {

    const mobile = mobileInput.value.trim();

    if (mobile === "") {
        showError(mobileInput, "mobileError", "Mobile number is required.");
        return false;
    }

    if (!mobileRegex.test(mobile)) {
        showError(
            mobileInput,
            "mobileError",
            "Enter a valid 10-digit Indian mobile number."
        );
        return false;
    }

    showSuccess(mobileInput, "mobileError");
    return true;
}


// Password strength meter
function checkPasswordStrength() {

    const password = passwordInput.value;

    const strengthBar = document.getElementById("strengthBar");
    const strengthText = document.getElementById("strengthText");

    if (password.length === 0) {

        strengthBar.style.width = "0%";
        strengthText.textContent = "Password strength";
        return;
    }

    let score = 0;

    // Length
    if (password.length >= 8) {
        score++;
    }

    // Lowercase
    if (/[a-z]/.test(password)) {
        score++;
    }

    // Uppercase
    if (/[A-Z]/.test(password)) {
        score++;
    }

    // Number
    if (/[0-9]/.test(password)) {
        score++;
    }

    // Special character
    if (/[@$!%*?&]/.test(password)) {
        score++;
    }


    if (score <= 2) {

        strengthBar.style.width = "40%";
        strengthText.textContent = "Weak password";

    } else if (score <= 4) {

        strengthBar.style.width = "70%";
        strengthText.textContent = "Medium password";

    } else {

        strengthBar.style.width = "100%";
        strengthText.textContent = "Strong password";
    }
}


// Validate Password
function validatePassword() {

    const password = passwordInput.value;

    if (password === "") {
        showError(
            passwordInput,
            "passwordError",
            "Password is required."
        );
        return false;
    }

    if (!passwordRegex.test(password)) {

        showError(
            passwordInput,
            "passwordError",
            "Password must contain 8+ characters, uppercase, lowercase, number and special character."
        );

        return false;
    }

    showSuccess(passwordInput, "passwordError");
    return true;
}


// Validate Confirm Password
function validateConfirmPassword() {

    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    if (confirmPassword === "") {

        showError(
            confirmPasswordInput,
            "confirmPasswordError",
            "Please confirm your password."
        );

        return false;
    }

    if (password !== confirmPassword) {

        showError(
            confirmPasswordInput,
            "confirmPasswordError",
            "Passwords do not match."
        );

        return false;
    }

    showSuccess(confirmPasswordInput, "confirmPasswordError");
    return true;
}


// Validate Course
function validateCourse() {

    if (courseInput.value === "") {

        showError(
            courseInput,
            "courseError",
            "Please select a course."
        );

        return false;
    }

    showSuccess(courseInput, "courseError");
    return true;
}


// Validate Year
function validateYear() {

    if (yearInput.value === "") {

        showError(
            yearInput,
            "yearError",
            "Please select your year."
        );

        return false;
    }

    showSuccess(yearInput, "yearError");
    return true;
}


// Validate Gender
function validateGender() {

    const gender = document.querySelector(
        'input[name="gender"]:checked'
    );

    const error = document.getElementById("genderError");

    if (!gender) {

        error.textContent = "Please select your gender.";
        return false;
    }

    error.textContent = "";
    return true;
}


// Validate Terms
function validateTerms() {

    const error = document.getElementById("termsError");

    if (!termsInput.checked) {

        error.textContent =
            "You must accept the Terms and Conditions.";

        return false;
    }

    error.textContent = "";
    return true;
}


// Real-time validation

nameInput.addEventListener("input", validateName);

emailInput.addEventListener("input", validateEmail);

mobileInput.addEventListener("input", validateMobile);

passwordInput.addEventListener("input", function () {

    checkPasswordStrength();
    validatePassword();

    if (confirmPasswordInput.value !== "") {
        validateConfirmPassword();
    }
});

confirmPasswordInput.addEventListener(
    "input",
    validateConfirmPassword
);

courseInput.addEventListener("change", validateCourse);

yearInput.addEventListener("change", validateYear);

termsInput.addEventListener("change", validateTerms);

document.querySelectorAll('input[name="gender"]').forEach(
    function (radio) {

        radio.addEventListener("change", validateGender);

    }
);


// Form Submit
form.addEventListener("submit", function (event) {

    event.preventDefault();

    const validName = validateName();
    const validEmail = validateEmail();
    const validMobile = validateMobile();
    const validPassword = validatePassword();
    const validConfirmPassword = validateConfirmPassword();
    const validCourse = validateCourse();
    const validYear = validateYear();
    const validGender = validateGender();
    const validTerms = validateTerms();


    if (
        validName &&
        validEmail &&
        validMobile &&
        validPassword &&
        validConfirmPassword &&
        validCourse &&
        validYear &&
        validGender &&
        validTerms
    ) {

        const successMessage =
            document.getElementById("successMessage");

        successMessage.textContent =
            "Registration successful! All details are valid.";

        successMessage.style.display = "block";

    } else {

        document.getElementById("successMessage").style.display =
            "none";

        // Move focus to first invalid field
        const firstError =
            document.querySelector(".input-error");

        if (firstError) {
            firstError.focus();
        }
    }
});


// Reset form
form.addEventListener("reset", function () {

    setTimeout(function () {

        document.querySelectorAll(".error").forEach(
            function (error) {
                error.textContent = "";
            }
        );

        document.querySelectorAll(
            "input, select"
        ).forEach(function (input) {

            input.classList.remove("input-error");
            input.classList.remove("input-success");

        });

        document.getElementById("strengthBar").style.width =
            "0%";

        document.getElementById("strengthText").textContent =
            "Password strength";

        document.getElementById("successMessage").style.display =
            "none";

    }, 0);
});