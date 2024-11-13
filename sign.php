<?php

require 'connect/DB.php';
require 'core/load.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Collect all form data
    $inputFirstName = $_POST['first-name'] ?? '';
    $inputLastName = $_POST['last-name'] ?? '';
    $inputEmailMobile = $_POST['email-mobile'] ?? '';
    $inputPassword = $_POST['up-password'] ?? '';
    $inputBirthDay = $_POST['birth-day'] ?? '';
    $inputBirthMonth = $_POST['birth-month'] ?? '';
    $inputBirthYear = $_POST['birth-year'] ?? '';
    $inputGender = $_POST['gen'] ?? '';
    $inputBirth = $inputBirthYear.'-'.$inputBirthMonth.'-'.$inputBirthDay;

    //Check if all required fields are filled
    $errors = [];
    $required_fields = ['first-name', 'last-name', 'email-mobile', 'up-password', 
                       'birth-day', 'birth-month', 'birth-year', 'gen'];
    
    foreach($required_fields as $field) {
        if(!isset($_POST[$field]) || empty($_POST[$field])) {
            $errors[] = "All fields are required";
            break;
        }
    }

    //Basic security check using checkInput
    if(empty($errors)) {
        $firstName = $loadFromUtils->checkInput($inputFirstName);
        $lastName = $loadFromUtils->checkInput($inputLastName);
        $emailMobile = $loadFromUtils->checkInput($inputEmailMobile);
        $password = $loadFromUtils->checkInput($inputPassword);
        
        // 4. Advanced validation using Utils class
        if(!$loadFromUtils->validateName($firstName)) {
            $errors[] = "First name must be between 2 and 25 characters";
        }
        
        if(!$loadFromUtils->validateName($lastName)) {
            $errors[] = "Last name must be between 2 and 25 characters";
        }
        
        // First, determine if input is valid email or valid mobile
        $isValidEmail = $loadFromUtils->validateEmail($emailMobile);
        $isValidMobile = $loadFromUtils->validateMobile($emailMobile);

        // Show error only if NEITHER validation passes
        if(!$isValidEmail && !$isValidMobile) {
            $errors[] = "Please enter a valid email address or mobile number";
        }
        
        if(!$loadFromUtils->validatePassword($password)) {
            $errors[] = "Password must be between 8 and 32 characters";
        }
    }

    // Add after collecting form data
    if(!checkdate($birthMonth, $birthDay, $birthYear)) {
        $errors[] = "Invalid birth date";
    }

    
    if(empty($errors)) {
        // Generate screen name
        $screenName = $firstName.'_'.$lastName;
        
        // Check if screen name exists and modify if needed
        if(DB::query('SELECT screenName FROM users WHERE screenName = :screenName', 
            array(':screenName' => $screenName))) {
            $screenRand = rand();
            $userLink = $screenName.$screenRand;
        } else {
            $userLink = $screenName;
        }

        // Create new user
        try {
            // Check if email/mobile already exists FIRST
            if($isValidEmail) {
                if(DB::query('SELECT email FROM users WHERE email = :email', array(':email' => $emailMobile))) {
                    $errors[] = "Email already registered";
                }
            } else if($isValidMobile) {
                if(DB::query('SELECT mobile FROM users WHERE mobile = :mobile', array(':mobile' => $emailMobile))) {
                    $errors[] = "Mobile number already registered";
                }
            }

            if(empty($errors)) {
                $user_data = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'screenName' => $screenName,
                    'userLink' => $userLink,
                    'email' => $isValidEmail ? $emailMobile : null,    // Store as email if valid email
                    'mobile' => $isValidMobile ? $emailMobile : null,  // Store as mobile if valid mobile
                    'password' => password_hash($password, PASSWORD_BCRYPT),
                    'birthday' => $birth,
                    'gender' => $upgen
                ];
                
                $user_id = $loadFromUser->create('users', $user_data);
                // if($user_id) {
                //     header('Location: login.php?registered=success');
                //     exit();
                // } else {
                //     $errors[] = "Registration failed";
                // }
            }
        } catch(Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $errors[] = "An error occurred during registration";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>facebook</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="header"></div>
    <div class="main">
        <div class="left-side">
           <img src="assets/image/sign-in-img.png" alt="">
        </div>
        <div class="right-side">
            <div class="error">
                <?php 
                    if(isset($errors)){
                        echo implode('<br>', $errors);
                    }
                ?>
            </div>
            <h1 style="color: #212121;">Create an account</h1>
            <div style="color: #212121; font-size: 20px">It's not free and will never be!!!</div>
            <form action="sign.php" method="POST" name="user-sign-up">
                <div class="sign-up-form">
                    <div class="sign-up-name">
                        <input type="text" name="first-name" id="first-name" class="text-field" placeholder="First Name">
                        <input type="text" name="last-name" id="last-name" class="text-field" placeholder="Last Name">
                    </div>
                    <div class="sign-wrap-mobile">
                        <input type="text" name="email-mobile" id="up-email" placeholder="Mobile number or email address" class="text-input">
                    </div>
                    <div class="sign-up-password">
                        <input type="password" name="up-password" id="up-password" class="text-input" placeholder="Password">
                    </div>
                    <div class="sign-up-birthday">
                        <div class="bday">Birthday</div>
                        <div class="form-birthday">
                            <select name="birth-day" id="days" class="select-body"></select>
                            <select name="birth-month" id="months" class="select-body"></select>
                            <select name="birth-year" id="years" class="select-body"></select>
                            </div>         
                    </div>

                    <div class="gender-wrap">
                                <input type="radio" name="gen" id="fem" value="female" class="m0">
                                <label for="fem" class="gender">Female</label>
                                <input type="radio" name="gen" id="male" value="male" class="m0">
                                <label for="male" class="gender">Male</label>
                            </div>
                            <div class="term">
                                By clicking Sign Up, you agree to our terms, Data policy and Cookie policy. You may receive
                                SMS notifications from us and can opt out at any time.
                            </div>
                            <input type="submit" value="Sign Up" class="sign-up">
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<script>
    //placeholder for the dropdowns
    $("#days").append($('<option disabled selected/>').html("Day"));
    $("#months").append($('<option disabled selected/>').html("Month"));
    $("#years").append($('<option disabled selected/>').html("Year"));

    // Year dropdown
    for (let i = new Date().getFullYear(); i > 1900; i--) {
        $("#years").append($('<option/>').val(i).html(i));
    }

    // Month dropdown with shortened month names
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    months.forEach((month, index) => {
        $("#months").append($('<option/>').val(index + 1).html(month)); 
    });

    // Dynamic day dropdown based on selected month and year
    function updateNumberOfDays() {
        $('#days').html(''); // Clear existing options
        const month = $('#months').val();
        const year = $('#years').val();
        const daysInMonth = new Date(year, month, 0).getDate();

        for (let day = 1; day <= daysInMonth; day++) {
            $('#days').append($('<option/>').val(day).html(day));
        }
    }

    // Initialize days and attach change event listeners
    $('#years, #months').on('change', updateNumberOfDays);
    updateNumberOfDays(); // Initial population for days
</script>

    
    
</body>
</html>