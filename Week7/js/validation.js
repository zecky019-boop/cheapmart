// ============================================
// REGISTRATION FORM VALIDATION
// ============================================

/**
 * Validates the registration form before submission
 * Checks: empty fields, email format, password length, password match
 * @returns {boolean} - True if valid, false otherwise
 */
function validateForm() {
    let name = document.getElementById('fullname');
    let email = document.getElementById('email');
    let password = document.getElementById('password');
    let confirm = document.getElementById('confirm_password');

    // Check if fields exist (prevents errors on pages without these fields)
    if (!name || !email || !password || !confirm) {
        return true;
    }

    let fullname = name.value.trim();
    let emailVal = email.value.trim();
    let pass = password.value;
    let conf = confirm.value;

    // Check empty fields
    if (fullname === '' || emailVal === '' || pass === '') {
        alert('All fields are required');
        return false;
    }

    // Validate email format
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailVal)) {
        alert('Invalid email format');
        return false;
    }

    // Check password length
    if (pass.length < 6) {
        alert('Password must be at least 6 characters');
        return false;
    }

    // Check password match
    if (pass !== conf) {
        alert('Passwords do not match');
        return false;
    }

    return true;
}

// ============================================
// LIVE PREVIEW - Shows welcome message as user types
// ============================================

/**
 * Displays a live preview of the welcome message
 * Updates in real-time as the user types their name
 */
function livePreview() {
    let nameInput = document.getElementById('fullname');
    let preview = document.getElementById('livePreview');

    if (!nameInput || !preview) return;

    let name = nameInput.value.trim();
    if (name !== '') {
        preview.innerHTML = 'Preview: Welcome, ' + name + '!';
    } else {
        preview.innerHTML = '';
    }
}

// ============================================
// PASSWORD STRENGTH CHECKER
// ============================================

/**
 * Checks password strength in real-time as user types
 * Evaluates: length, lowercase, uppercase, numbers, special characters
 * Displays: Weak, Medium, or Strong with color coding
 */
function checkPasswordStrength() {
    let password = document.getElementById('password');
    let msg = document.getElementById('passwordStrength');

    if (!password || !msg) return;

    let pass = password.value;
    let strength = 0;

    // Check password criteria
    if (pass.length >= 8) strength++;
    if (pass.match(/[a-z]+/)) strength++;
    if (pass.match(/[A-Z]+/)) strength++;
    if (pass.match(/[0-9]+/)) strength++;
    if (pass.match(/[$@#&!]+/)) strength++;

    // Display feedback
    if (pass === '') {
        msg.innerHTML = '';
        msg.style.color = '';
    } else if (strength < 3) {
        msg.innerHTML = 'Weak password. Use 8+ chars, uppercase, number, special char.';
        msg.style.color = 'red';
    } else if (strength < 5) {
        msg.innerHTML = 'Medium password.';
        msg.style.color = 'orange';
    } else {
        msg.innerHTML = 'Strong password!';
        msg.style.color = 'green';
    }
}

// ============================================
// AUTO-HIDE MESSAGES (Runs on page load)
// ============================================

/**
 * Automatically hides success and error messages after 5 seconds
 * Creates a smoother user experience
 */
document.addEventListener('DOMContentLoaded', function() {
    let messages = document.querySelectorAll('.success-message, .error-message');
    messages.forEach(function(msg) {
        setTimeout(function() {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s ease';
            setTimeout(function() {
                msg.style.display = 'none';
            }, 500);
        }, 5000);
    });
});

// ============================================
// LOGIN FORM VALIDATION
// ============================================

/**
 * Validates the login form before submission
 * Checks that email and password fields are not empty
 */
function validateLogin() {
    let email = document.getElementById('email');
    let password = document.getElementById('password');

    if (!email || !password) return true;

    if (email.value.trim() === '' || password.value === '') {
        alert('Please enter both email and password.');
        return false;
    }

    return true;
}

// ============================================
// PASSWORD RESET VALIDATION
// ============================================

/**
 * Validates password reset form
 * Checks: password length, password match
 */
function validateResetPassword() {
    let password = document.getElementById('password');
    let confirm = document.getElementById('confirm_password');

    if (!password || !confirm) return true;

    let pass = password.value;
    let conf = confirm.value;

    if (pass.length < 6) {
        alert('Password must be at least 6 characters');
        return false;
    }

    if (pass !== conf) {
        alert('Passwords do not match');
        return false;
    }

    return true;
}