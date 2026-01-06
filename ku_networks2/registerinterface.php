<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>KU Network - Register</title>
    <link rel="icon" href="images/icon.png" type="image/gif" sizes="16x16">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="KU Network" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="author" />
    <link id="bootstrap" href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-grid" href="css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link id="bootstrap-reboot" href="css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="css/animate.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="css/coloring.css" rel="stylesheet" type="text/css" />
    
    <style>
        /* Custom Header Styles */
        .custom-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand img {
            max-height: 50px;
        }

        .btn-login-nav {
            background-color: #f47656 !important; /* KU Network Orange */
            color: #fff !important;
            padding: 10px 30px !important;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            border: none;
            box-shadow: 0 4px 15px rgba(244, 118, 86, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-login-nav:hover {
            background-color: #e06040 !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(244, 118, 86, 0.5);
            color: #fff !important;
        }

        /* Form Error Styles */
        .error-text {
            color: red;
            font-size: 0.875em;
            display: block;
            margin-top: 4px;
        }

        .success, .error {
            display: none;
            margin-top: 10px;
        }

        .success { color: green; }
        .error { color: red; }
    </style>
</head>

<body>
    <div id="wrapper">

        <header class="navbar navbar-expand-lg fixed-top custom-navbar">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.png" onerror="this.src='images/icon.png'; this.style.height='40px';" alt="KU Network">
                </a>

                <div class="ms-auto">
                    <a href="loginInterface.php" class="btn-login-nav">Login</a>
                </div>
            </div>
        </header>
        <div class="no-bottom no-top" id="content">
            <div id="top"></div>

            <section id="subheader" class="text-light" data-bgimage="url(images/background/bg.png) top">
                <div class="center-y relative text-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h1>Register</h1>
                                <p>Join the KU Network Community</p>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </section>
            <section aria-label="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <h3>Don't have an account? Register now.</h3>
                            <p>Create your account to start your journey. Please fill in the details below accurately.</p>

                            <div class="spacer-10"></div>

                            <form name="contactForm" id="contact_form" class="form-border" method="post"
                                action="register.php" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="name">Name:</label>
                                            <input type="text" name="name" id="name" class="form-control" required>
                                            <small class="error-text" id="name_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="username">Choose a Username:</label>
                                            <input type="text" name="username" id="username" class="form-control"
                                                required>
                                            <small class="error-text" id="username_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="email">Email Address:</label>
                                            <div style="display: flex; gap: 10px;">
                                                <input type="email" name="email" id="email" class="form-control"
                                                    required>
                                            </div>
                                            <small class="error-text" id="email_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="phone">Phone:</label>
                                            <input type="text" name="phone" id="phone" class="form-control" required>
                                            <small class="error-text" id="phone_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="password">Password:</label>
                                            <input type="password" name="password" id="password" class="form-control"
                                                required>
                                            <small class="error-text" id="password_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="re_password">Re-enter Password:</label>
                                            <input type="password" name="re_password" id="re_password"
                                                class="form-control" required>
                                            <small class="error-text" id="re_password_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="country_id">Country:</label>
                                            <select name="country_id" id="country_id" class="form-control" required>
                                                <option value="">Select Country</option>
                                            </select>
                                            <small class="error-text" id="country_id_error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="field-set">
                                            <label for="referral_code">Referral Code (Optional):</label>
                                            <input type="text" name="referral_code" id="referral_code"
                                                class="form-control">
                                            <small class="error-text" id="referral_code_error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="field-set">
                                            <label for="profile_image">Profile Image:</label>
                                            <input type="file" name="profile_image" id="profile_image"
                                                class="form-control" accept="image/png, image/jpeg, image/gif">
                                            <small class="error-text" id="profile_image_error"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <small class="error-text" id="form_general_error"
                                            style="font-weight: bold; margin-bottom: 10px;"></small>
                                        <div id="submit" class="pull-left">
                                            <input type="submit" id="send_message" value="Register Now"
                                                class="btn btn-main color-2">
                                        </div>
                                        <div id="mail_success" class="success">Your registration was successful.
                                            Redirecting...</div>
                                        <div id="mail_fail" class="error">Sorry, an error occurred during registration.
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <div class="spacer-single"></div>
                                        <div class="text-center mt-3">
                                            Already have an account? <a href="loginInterface.php" style="color: var(--accent-orange); font-weight: bold;">Login Now</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </section>


        </div>
        <a href="#" id="back-to-top"></a>

        <?php include 'Assets/footer.php'; ?>
         
        </div>



    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/wow.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/owl.carousel.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/enquire.min.js"></script>
    <script src="js/jquery.plugin.js"></script>
    <script src="js/jquery.countTo.js"></script>
    <script src="js/jquery.countdown.js"></script>
    <script src="js/jquery.lazy.min.js"></script>
    <script src="js/jquery.lazy.plugins.min.js"></script>
    <script src="js/designesia.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // ===== Auto-fill referral code from URL =====
            const urlParams = new URLSearchParams(window.location.search);
            const refCodeFromURL = urlParams.get('ref');
            if (refCodeFromURL) {
                const referralInput = document.getElementById('referral_code');
                if (referralInput) {
                    referralInput.value = refCodeFromURL;
                }
            }
            // Fetch countries
            fetch('fetch_options.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    const countrySelect = document.getElementById('country_id');
                    if (!countrySelect) {
                        console.error('Country select element not found!');
                        return;
                    }
                    if (Array.isArray(data.countries)) {
                        data.countries.forEach(function (country) {
                            let option = document.createElement('option');
                            option.value = country.id;
                            option.textContent = country.name;
                            countrySelect.appendChild(option);
                        });
                    } else {
                        console.error('Countries data is not array:', data.countries);
                        document.getElementById('country_id_error').textContent = 'Could not load countries.';
                    }
                })
                .catch(error => {
                    console.error('Error fetching options:', error);
                    document.getElementById('country_id_error').textContent = 'Error loading countries.';
                });

            // Form Validation
            const form = document.getElementById('contact_form');
            const nameInput = document.getElementById('name');
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const phoneInput = document.getElementById('phone');
            const passwordInput = document.getElementById('password');
            const rePasswordInput = document.getElementById('re_password');
            const countrySelect = document.getElementById('country_id');

            const mailSuccess = document.getElementById('mail_success');
            const mailFail = document.getElementById('mail_fail');

            // Helper to display errors
            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId + '_error');
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }

            // Helper to clear a specific error
            function clearError(elementId) {
                const errorElement = document.getElementById(elementId + '_error');
                if (errorElement) {
                    errorElement.textContent = '';
                }
            }

            // Helper to clear all errors
            function clearAllErrors() {
                const errorElements = document.querySelectorAll('.error-text');
                errorElements.forEach(el => el.textContent = '');
                mailSuccess.style.display = 'none';
                mailFail.style.display = 'none';
            }

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                clearAllErrors();
                let isValid = true;
                let focusField = null;

                // --- Name Validation ---
                const nameValue = nameInput.value.trim();
                if (nameValue === '') {
                    showError('name', 'Name is required.');
                    isValid = false; if (!focusField) focusField = nameInput;
                } else if (/\d/.test(nameValue)) {
                    showError('name', 'Name cannot contain numbers.');
                    isValid = false; if (!focusField) focusField = nameInput;
                } else if (!/^[a-zA-Z\s'-]+$/.test(nameValue)) {
                    showError('name', 'Name can only contain letters, spaces, hyphens, and apostrophes.');
                    isValid = false; if (!focusField) focusField = nameInput;
                }

                // --- Username Validation ---
                const usernameValue = usernameInput.value.trim();
                if (usernameValue === '') {
                    showError('username', 'Username is required.');
                    isValid = false; if (!focusField) focusField = usernameInput;
                } else if (usernameValue.length < 3) {
                    showError('username', 'Username must be at least 3 characters long.');
                    isValid = false; if (!focusField) focusField = usernameInput;
                } else {
                    try {
                        const response = await fetch(`check_availability.php?field=username&value=${encodeURIComponent(usernameValue)}`);
                        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
                        const data = await response.json();
                        if (!data.available) {
                            showError('username', data.message || 'Username is already taken.');
                            isValid = false; if (!focusField) focusField = usernameInput;
                        }
                    } catch (error) {
                        console.error('Error checking username:', error);
                        showError('username', 'Could not verify username. Please try again.');
                        isValid = false; if (!focusField) focusField = usernameInput;
                    }
                }

                // --- Email Validation ---
                const emailValue = emailInput.value.trim();
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailValue === '') {
                    showError('email', 'Email is required.');
                    isValid = false; if (!focusField) focusField = emailInput;
                } else if (!emailPattern.test(emailValue)) {
                    showError('email', 'Please enter a valid email address.');
                    isValid = false; if (!focusField) focusField = emailInput;
                } else {
                    try {
                        const response = await fetch(`check_availability.php?field=email&value=${encodeURIComponent(emailValue)}`);
                        if (!response.ok) throw new Error(`HTTP error ${response.status}`);
                        const data = await response.json();
                        if (!data.available) {
                            showError('email', data.message || 'This email address is already registered.');
                            isValid = false; if (!focusField) focusField = emailInput;
                        }
                    } catch (error) {
                        console.error('Error checking email:', error);
                        showError('email', 'Could not verify email. Please try again.');
                        isValid = false; if (!focusField) focusField = emailInput;
                    }
                }

                // --- Phone Validation ---
                const phoneValue = phoneInput.value.trim();
                const phonePattern = /^\+?\d{7,15}$/; // Allows optional + and 7-15 digits
                if (phoneValue === '') {
                    showError('phone', 'Phone number is required.');
                    isValid = false; if (!focusField) focusField = phoneInput;
                } else if (!phonePattern.test(phoneValue)) {
                    showError('phone', 'Enter a valid phone (e.g., +1234567890 or 03001234567).');
                    isValid = false; if (!focusField) focusField = phoneInput;
                }

                // --- Password Validation ---
                const passwordValue = passwordInput.value; // No trim for password
                if (passwordValue === '') {
                    showError('password', 'Password is required.');
                    isValid = false; if (!focusField) focusField = passwordInput;
                } else if (passwordValue.length < 6) {
                    showError('password', 'Password must be at least 6 characters long.');
                    isValid = false; if (!focusField) focusField = passwordInput;
                }

                // --- Re-enter Password Validation ---
                const rePasswordValue = rePasswordInput.value;
                if (rePasswordValue === '') {
                    showError('re_password', 'Please re-enter your password.');
                    isValid = false; if (!focusField) focusField = rePasswordInput;
                } else if (passwordValue !== rePasswordValue) {
                    showError('re_password', 'Passwords do not match.');
                    isValid = false; if (!focusField) focusField = rePasswordInput;
                }

                // --- Country Validation ---
                if (countrySelect.value === '') {
                    showError('country_id', 'Please select a country.');
                    isValid = false; if (!focusField) focusField = countrySelect;
                }

                if (focusField) {
                    focusField.focus();
                }

                if (isValid) {
                    // Form is valid, submit it
                    console.log('Form is valid, submitting with native form submission...');
                    form.submit();
                } else {
                    showError('form_general', 'Please correct the errors above.');
                    console.log('Form has errors.');
                }
            });

            // Optional: Real-time validation on blur
            async function validateFieldOnBlur(inputEl, errorId, type) {
                clearError(errorId);
                const value = inputEl.value.trim();
                if (value === '') return; // Don't validate if empty on blur, submit will catch it

                switch (type) {
                    case 'name':
                        if (/\d/.test(value)) showError(errorId, 'Name cannot contain numbers.');
                        else if (!/^[a-zA-Z\s'-]+$/.test(value)) showError(errorId, 'Invalid characters in name.');
                        break;
                    case 'username':
                        if (value.length < 3) {
                            showError(errorId, 'Username must be at least 3 characters.');
                        } else {
                            try {
                                const response = await fetch(`check_availability.php?field=username&value=${encodeURIComponent(value)}`);
                                if (!response.ok) return; // Fail silently on blur for server errors
                                const data = await response.json();
                                if (!data.available) showError(errorId, data.message || 'Username taken.');
                            } catch (e) { console.warn('Blur check error (username)', e); }
                        }
                        break;
                    case 'email':
                        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                            showError(errorId, 'Invalid email format.');
                        } else {
                            try {
                                const response = await fetch(`check_availability.php?field=email&value=${encodeURIComponent(value)}`);
                                if (!response.ok) return;
                                const data = await response.json();
                                if (!data.available) showError(errorId, data.message || 'Email registered.');
                            } catch (e) { console.warn('Blur check error (email)', e); }
                        }
                        break;
                    case 'phone':
                        if (!/^\+?\d{7,15}$/.test(value)) showError(errorId, 'Invalid phone format.');
                        break;
                    case 're_password':
                        if (passwordInput.value && value !== passwordInput.value) {
                            showError(errorId, 'Passwords do not match.');
                        }
                        break;
                }
            }

            nameInput.addEventListener('blur', () => validateFieldOnBlur(nameInput, 'name', 'name'));
            usernameInput.addEventListener('blur', () => validateFieldOnBlur(usernameInput, 'username', 'username'));
            emailInput.addEventListener('blur', () => validateFieldOnBlur(emailInput, 'email', 'email'));
            phoneInput.addEventListener('blur', () => validateFieldOnBlur(phoneInput, 'phone', 'phone'));
            rePasswordInput.addEventListener('blur', () => validateFieldOnBlur(rePasswordInput, 're_password', 're_password'));
            passwordInput.addEventListener('blur', () => { // Also check re_password if password changes
                if (rePasswordInput.value) validateFieldOnBlur(rePasswordInput, 're_password', 're_password');
            });

        });
    </script>

</body>

</html>