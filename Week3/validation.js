// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]+/)) strength++;
    if (password.match(/[A-Z]+/)) strength++;
    if (password.match(/[0-9]+/)) strength++;
    if (password.match(/[$@#&!]+/)) strength++;
    return strength;
}

document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let valid = true;

    // Clear previous errors
    document.querySelectorAll('.error').forEach(el => el.innerText = '');

    // Full name validation
    let fullname = document.getElementById('fullname').value.trim();
    if (fullname === '') {
        document.getElementById('nameError').innerText = 'Full name is required';
        valid = false;
    }

    // Email validation
    let email = document.getElementById('email').value.trim();
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('emailError').innerText = 'Email is required';
        valid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').innerText = 'Enter a valid email';
        valid = false;
    }

    // Password validation
    let password = document.getElementById('password').value;
    if (password === '') {
        document.getElementById('passwordError').innerText = 'Password is required';
        valid = false;
    } else {
        let strength = checkPasswordStrength(password);
        if (strength < 3) {
            document.getElementById('passwordError').innerHTML = 'Weak password. Use 8+ chars, uppercase, number, special char.';
            valid = false;
        }
    }

    // Confirm password
    let confirm = document.getElementById('confirmPassword').value;
    if (confirm !== password) {
        document.getElementById('confirmError').innerText = 'Passwords do not match';
        valid = false;
    }

    if (valid) {
        document.getElementById('formResult').innerHTML = '<span class="success">Form is valid! Ready to submit to server.</span>';
        // In a real scenario, you would submit data to a PHP script
    }
});

// Live preview (DOM manipulation)
let nameInput = document.getElementById('fullname');
let previewDiv = document.createElement('div');
previewDiv.style.marginTop = '10px';
previewDiv.style.fontStyle = 'italic';
nameInput.parentNode.insertBefore(previewDiv, nameInput.nextSibling);
nameInput.addEventListener('input', function() {
    if (nameInput.value.trim() !== '') {
        previewDiv.innerText = 'Preview: Welcome, ' + nameInput.value.trim() + '!';
    } else {
        previewDiv.innerText = '';
    }
});