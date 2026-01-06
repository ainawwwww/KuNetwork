<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

include 'config.php';

define('PROFILE_IMAGE_UPLOAD_DIR', 'images/uploads/profile_images/');
if (!is_dir(PROFILE_IMAGE_UPLOAD_DIR)) {
    if (!mkdir(PROFILE_IMAGE_UPLOAD_DIR, 0775, true) && !is_dir(PROFILE_IMAGE_UPLOAD_DIR)) {
        error_log('Failed to create profile image upload directory: ' . PROFILE_IMAGE_UPLOAD_DIR);
    }
}

// ========== HELPER FUNCTIONS ==========


function getUserStage($user_id, $conn) {
    $sql = "SELECT s.* FROM user_stage_history ush 
            JOIN stages s ON ush.stage_id = s.stage_id 
            WHERE ush.user_id = ? 
            ORDER BY ush.assigned_at DESC 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("getUserStage prepare failed: " . $conn->error);
        return null;
    }
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stage = null;
    if ($result->num_rows > 0) {
        $stage = $result->fetch_assoc();
    }
    $stmt->close();
    return $stage;
}

function calculateReferralBonus($parent_user_id, $registration_amount, $conn) {
    $stage = getUserStage($parent_user_id, $conn);
    if (!$stage) {
        return 0;
    }
    $percentage = (float)$stage['referral_bonus'];
    return ($registration_amount * $percentage) / 100;
}

function assignStageToUser($user_id, $conn) {
    $stages = $conn->query("SELECT * FROM stages ORDER BY stage_id ASC");
    if (!$stages) {
        error_log("assignStageToUser query failed: " . $conn->error);
        return;
    }
    
    $assign_stage_id = null;
    
    while ($stage = $stages->fetch_assoc()) {
        $limit = (int)$stage['user_limit'];
        
        if ($limit == 0) { // Unlimited
            $assign_stage_id = $stage['stage_id'];
            break;
        }
        
        $count_sql = "SELECT COUNT(*) as count FROM user_stage_history WHERE stage_id = ?";
        $count_stmt = $conn->prepare($count_sql);
        if (!$count_stmt) {
            error_log("assignStageToUser count prepare failed: " . $conn->error);
            continue;
        }
        
        $count_stmt->bind_param("i", $stage['stage_id']);
        $count_stmt->execute();
        $res = $count_stmt->get_result()->fetch_assoc();
        $count_stmt->close();
        
        if ($res['count'] < $limit) {
            $assign_stage_id = $stage['stage_id'];
            break;
        }
    }
    
    if ($assign_stage_id === null) {
        error_log("No available stage found for user: " . $user_id);
        return;
    }
    
    $insert_sql = "INSERT INTO user_stage_history (user_id, stage_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    if (!$insert_stmt) {
        error_log("assignStageToUser insert prepare failed: " . $conn->error);
        return;
    }
    
    $insert_stmt->bind_param("ii", $user_id, $assign_stage_id);
    $insert_stmt->execute();
    $insert_stmt->close();
}

// ========== MAIN REGISTRATION LOGIC ==========

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $name = trim($_POST['name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';
    $country_id = filter_input(INPUT_POST, 'country_id', FILTER_VALIDATE_INT);
    $referral_code = isset($_POST['referral_code']) ? trim($_POST['referral_code']) : null;
    
    if (empty($referral_code)) {
        $referral_code = null;
    }

    $image_filename = 'default.png';

    // Profile Image Upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $profile_image_file = $_FILES['profile_image'];
        $image_name_original = basename($profile_image_file["name"]);
        $image_tmp_name = $profile_image_file["tmp_name"];
        $image_size = $profile_image_file["size"];
        $image_ext = strtolower(pathinfo($image_name_original, PATHINFO_EXTENSION));

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024;

        if (!in_array($image_ext, $allowed_extensions)) {
            $errors['profile_image'] = "Invalid image format. Only JPG, JPEG, PNG, GIF are allowed.";
        } elseif ($image_size > $max_file_size) {
            $errors['profile_image'] = "Image size exceeds the limit of 5MB.";
        } else {
            $new_image_filename = uniqid('userimg_', true) . '.' . $image_ext;
            $target_file_path = PROFILE_IMAGE_UPLOAD_DIR . $new_image_filename;

            if (move_uploaded_file($image_tmp_name, $target_file_path)) {
                $image_filename = $new_image_filename;
            } else {
                $errors['profile_image'] = "Sorry, there was an error uploading your profile image. Default image will be used.";
                error_log("Failed to move uploaded file to: " . $target_file_path);
            }
        }
    } elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] != UPLOAD_ERR_NO_FILE) {
        $errors['profile_image'] = "An error occurred with the image upload (Error code: " . $_FILES['profile_image']['error'] . "). Default image will be used.";
    }

    // Name Validation
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (preg_match('/\d/', $name)) {
        $errors['name'] = "Name cannot contain numbers.";
    } elseif (!preg_match("/^[a-zA-Z\s'-]+$/", $name)) {
        $errors['name'] = "Name contains invalid characters.";
    }

    // Username Validation
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Username must be at least 3 characters.";
    } else {
        $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $errors['username'] = "Username is already taken.";
        }
        $stmt_check->close();
    }

    // Email Validation
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } else {
        $stmt_check = $conn->prepare("SELECT email FROM users WHERE email = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $errors['email'] = "This email address is already registered.";
        }
        $stmt_check->close();
    }

    // Phone Validation
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match("/^\+?\d{7,15}$/", $phone)) {
        $errors['phone'] = "Invalid phone number format (e.g., +1234567890 or 03001234567).";
    }

    // Password Validation
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long.";
    }
    
    if ($password !== $re_password) {
        $errors['re_password'] = "Passwords do not match.";
    }

    // Country Validation
    if (empty($country_id)) {
        $errors['country_id'] = "Please select a country.";
    } else {
        $stmt_check_country = $conn->prepare("SELECT id FROM countries WHERE id = ?");
        if ($stmt_check_country) {
            $stmt_check_country->bind_param("i", $country_id);
            $stmt_check_country->execute();
            $stmt_check_country->store_result();
            if ($stmt_check_country->num_rows == 0) {
                $errors['country_id'] = "Invalid country selected.";
            }
            $stmt_check_country->close();
        } else {
            $errors['country_id'] = "Could not verify country.";
        }
    }

    // If there are validation errors, display them
    if (!empty($errors)) {
        $error_message = "Registration failed. Please correct the following:<br>";
        foreach ($errors as $field => $msg) {
            $error_message .= "- " . htmlspecialchars($msg) . "<br>";
        }
        die($error_message . "<br><a href='registerinterface.php'>Go back</a>");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert user into database
    $sql = "INSERT INTO users (name, user_id, email, password, phone, country_id, referral_code, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("SQL prepare error: " . $conn->error);
        die("Error preparing registration. Please try again later.");
    }

    $stmt->bind_param("sssssiss", $name, $username, $email, $hashed_password, $phone, $country_id, $referral_code, $image_filename);

    if ($stmt->execute()) {
        $new_user_auto_id = $stmt->insert_id;
        $stmt->close();

        // ========== ASSIGN STAGE TO NEW USER ==========
        assignStageToUser($new_user_auto_id, $conn);

        // ========== HANDLE REFERRAL CODE LOGIC ==========
        if (!empty($referral_code)) {
            $codeCheck = $conn->prepare("SELECT id, user_id FROM user_referal_codes WHERE referral_code = ? AND used_status = 0 AND expiration > NOW()");
            
            if ($codeCheck) {
                $codeCheck->bind_param("s", $referral_code);
                $codeCheck->execute();
                $result = $codeCheck->get_result();

                if ($result->num_rows > 0) {
                    $referralData = $result->fetch_assoc();
                    $referrer_user_pk_id = $referralData['user_id'];
                    $referral_code_table_id = $referralData['id'];

                    // Update referral code as used
                    $update_referral_code = $conn->prepare("UPDATE user_referal_codes SET used_status = 1 WHERE id = ?");
                    if ($update_referral_code) {
                        $update_referral_code->bind_param("i", $referral_code_table_id);
                        $update_referral_code->execute();
                        $update_referral_code->close();
                    } else {
                        error_log("Prepare failed (update_referral_code): " . $conn->error);
                    }

                    // Insert into referral teams
                    $insert_referral_team = $conn->prepare("INSERT INTO referal_teams (user_id, referral_userid, referral_code) VALUES (?, ?, ?)");
                    if ($insert_referral_team) {
                        $insert_referral_team->bind_param("iis", $referrer_user_pk_id, $new_user_auto_id, $referral_code);
                        $insert_referral_team->execute();
                        $insert_referral_team->close();
                    } else {
                        error_log("Prepare failed (insert_referral_team): " . $conn->error);
                    }

                    // ========== CALCULATE AND CREDIT REFERRAL BONUS ==========
                    // TODO: Set base_amount_for_bonus according to your business logic
                    // For now, it's 0, so no bonus will be credited
                    // You can set it to a fixed signup bonus or wait for first deposit
                    $base_amount_for_bonus = 0; 
                    
                    if ($base_amount_for_bonus > 0) {
                        $referral_bonus = calculateReferralBonus($referrer_user_pk_id, $base_amount_for_bonus, $conn);

                        if ($referral_bonus > 0) {
                            // Update wallet balance
                            $wallet_stmt = $conn->prepare("UPDATE wallet SET balance = balance + ? WHERE user_id = ?");
                            if ($wallet_stmt) {
                                $wallet_stmt->bind_param("di", $referral_bonus, $referrer_user_pk_id);
                                $wallet_stmt->execute();
                                $wallet_stmt->close();
                            } else {
                                error_log("Prepare failed (wallet_stmt): " . $conn->error);
                            }

                            // Optional: Log transaction in history table
                            /*
                            $log_stmt = $conn->prepare(
                                "INSERT INTO transactions (user_id, amount, type, description) VALUES (?, ?, 'credit', 'Referral bonus for new signup')"
                            );
                            if ($log_stmt) {
                                $log_stmt->bind_param("id", $referrer_user_pk_id, $referral_bonus);
                                $log_stmt->execute();
                                $log_stmt->close();
                            }
                            */
                        }
                    }
                }
                $codeCheck->close();
            } else {
                error_log("Prepare failed (codeCheck): " . $conn->error);
            }
        }

        // Start session and redirect
        session_start();
        $_SESSION['user_id'] = $new_user_auto_id;
        $_SESSION['username'] = $username;

        header("Location: account.php");
        exit();

    } else {
        error_log("Error executing registration: " . $stmt->error);
        $stmt->close();
        $conn->close();
        die("An error occurred during registration. Error: " . htmlspecialchars($stmt->error) . "<br><a href='registerinterface.php'>Go back</a>");
    }
    
} else {
    // Not a POST request
    header("Location: registerinterface.php");
    exit();
}
?>