// ============================================
// LOGOUT CONFIRMATION
// ============================================

/**
 * Confirms user wants to logout before proceeding
 * Prevents accidental logout
 * @returns {boolean} - True if user confirms, false if canceled
 */
function confirmLogout() {
    return confirm('Are you sure you want to logout?');
}

// ============================================
// DELETE CONFIRMATION
// ============================================

/**
 * Confirms user wants to delete a product
 * Prevents accidental deletion
 * @returns {boolean} - True if user confirms, false if canceled
 */
function confirmDelete() {
    return confirm('Delete this product?');
}

// ============================================
// PASSWORD VISIBILITY TOGGLE
// ============================================

/**
 * Toggles password field visibility between text and password
 * Improves user experience by showing/hiding password
 * @param {string} fieldId - The ID of the password field
 */
function togglePasswordVisibility(fieldId) {
    let field = document.getElementById(fieldId);
    if (!field) return;

    if (field.type === 'password') {
        field.type = 'text';
        // Optional: Change button text or icon here
    } else {
        field.type = 'password';
    }
}

// ============================================
// FORM SUBMISSION INDICATOR
// ============================================

/**
 * Disables submit button and shows loading text
 * Prevents double submission
 * @param {string} buttonId - The ID of the submit button
 * @param {string} loadingText - Text to show while loading
 */
function disableButton(buttonId, loadingText) {
    let button = document.getElementById(buttonId);
    if (!button) return;

    button.disabled = true;
    button.innerHTML = loadingText || 'Processing...';
}

// ============================================
// ENABLE BUTTON (Helper function)
// ============================================

/**
 * Enables a disabled submit button
 * @param {string} buttonId - The ID of the submit button
 * @param {string} originalText - Original button text to restore
 */
function enableButton(buttonId, originalText) {
    let button = document.getElementById(buttonId);
    if (!button) return;

    button.disabled = false;
    button.innerHTML = originalText || 'Submit';
}

// ============================================
// ATTACH CONFIRMATION TO DELETE LINKS
// ============================================

/**
 * Automatically attaches delete confirmation to all delete links
 * Runs when page loads
 */
document.addEventListener('DOMContentLoaded', function() {
    // Delete product links
    let deleteLinks = document.querySelectorAll('a.delete-link, a[href*="delete"]');
    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirmDelete()) {
                e.preventDefault();
            }
        });
    });

    // Logout links
    let logoutLinks = document.querySelectorAll('a[href*="logout.php"]');
    logoutLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (!confirmLogout()) {
                e.preventDefault();
            }
        });
    });
});

// ============================================
// SCROLL TO TOP FUNCTION
// ============================================

/**
 * Smoothly scrolls to the top of the page
 * Useful after form submissions or navigation
 */
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// ============================================
// AUTO-REFRESH DASHBOARD (Optional)
// ============================================

/**
 * Automatically refreshes dashboard every 5 minutes
 * Keeps user session active and updates data
 */
function autoRefreshDashboard() {
    if (document.querySelector('.dashboard-content')) {
        setTimeout(function() {
            location.reload();
        }, 300000); // 5 minutes
    }
}

// ============================================
// KEYBOARD SHORTCUTS
// ============================================

/**
 * Adds keyboard shortcuts for better UX
 * Ctrl+Shift+L: Logout (if on dashboard)
 * Ctrl+Shift+H: Go to Home
 */
document.addEventListener('keydown', function(e) {
    // Ctrl+Shift+L = Logout
    if (e.ctrlKey && e.shiftKey && (e.key === 'L' || e.key === 'l')) {
        if (confirmLogout()) {
            window.location.href = 'logout.php';
        }
        e.preventDefault();
    }

    // Ctrl+Shift+H = Home
    if (e.ctrlKey && e.shiftKey && (e.key === 'H' || e.key === 'h')) {
        window.location.href = 'index.php';
        e.preventDefault();
    }
});