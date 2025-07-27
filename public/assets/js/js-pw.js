function setupToggle(passwordId, toggleId) {
    document.getElementById(toggleId).addEventListener("click", function () {
        let passwordInput = document.getElementById(passwordId);
        let icon = this.querySelector("i");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    });
}

// Panggil fungsi untuk masing-masing input
setupToggle("password1", "togglePassword1");
// setupToggle("password2", "togglePassword2");
