<?php

require 'connect/DB.php';
require 'core/load.php';

if (isset($_POST['first-name']) && !empty($_POST['first-name'])) {
    $upFirst = $_POST['first-name'];
    $upLast = $_POST['last-name'];
    $upEmailMobile = $_POST['email-mobile'];
    $upPassword = $_POST['up-password'];
    $upBirthDay = $_POST['birth-day'];
    $upBirthMonth = $_POST['birth-month'];
    $upBirthYear = $_POST['birth-year'];
    $birth = "$upBirthYear-$upBirthMonth-$upBirthDay";

    if (isset($_POST['gen']) && !empty($_POST['gen'])) {
        $upGender = $_POST['gen'];
        // Additional logic can be added here if needed
    } // Added closing brace here

    if (empty($upFirst) || empty($upLast) || empty($upEmailMobile) || empty($upPassword) || empty($upBirthDay) || empty($upBirthMonth) || empty($upBirthYear) || empty($upGender)) {
        $error = 'All fields are required';
    } else if (strlen($upLast) < 2 || strlen($upLast) > 20) {
        $error = 'Last Name must be between 2 and 20 characters';
    } else {
        $firstName = $loadFromUser->checkInput($upFirst);
        $lastName = $loadFromUser->checkInput($upLast);
        $emailMobile = $loadFromUser->checkInput($upEmailMobile);
        $password = $loadFromUser->checkInput($upPassword);
        $screenName = $firstName . $lastName;

        if (DB::query(
            'SELECT screenName FROM users WHERE screenName = :screenName',
            array(':screenName' => $screenName)
        )) {
            $screenRand = rand();
            $userLink = $screenName . $screenRand;
        } else {
            $userLink = $screenName;
        }

        // Check if email or mobile number is valid via regex
        $emailPattern = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
        $mobilePattern = "/^\d{11}$/";

        if (!preg_match($emailPattern, $emailMobile)) {
            if (!preg_match($mobilePattern, $emailMobile)) {
                $error = 'Please enter a valid email address or an 11-digit mobile number.';
            }
        } elseif (!preg_match($mobilePattern, $emailMobile)) {
            $error = 'Invalid mobile number format. It should be 11 digits.';
        } else {
            if (!filter_var($emailMobile, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format. Please try again.';
            } else if (strlen($firstName) < 2 || strlen($firstName) > 20) {
                $error = 'Name must be between 2 and 20 characters';
            } else if (strlen($password) < 5 || strlen($password) > 60) {
                $error = 'Password must be between 5 and 60 characters';
            } else {
                if (filter_var($emailMobile, FILTER_VALIDATE_EMAIL) && $loadFromUser->checkEmail($emailMobile) === true) {
                    $error = 'Email is already in use';
                }
                // Additional code to handle successful registration can go here
            }
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
                    if(isset($error)){
                        echo $error;
                    }
                ?>
            </div>
            <h1 style="color: #212121;">Create an account</h1>
            <div style="color: #212121; font-size: 20px">It's not free and will never be!!!</div>
            <form action="sign.php" method="post" name="user-sign-up">
                <div class="sign-up-form">
                    <div class="sign-up-name">
                        <input type="text" name="first-name" id="first-name" class="text-field" placeholder="First Name">
                        <input type="text" name="last-name" id="last-name" class="text-field" placeholder="Last Name">
                    </div>
                    <div class="sign-wrap-mobile">
                        <input type="text" name="email-mobile" id="up-email" placeholder="Mobile number or email address" class="text-input">
                    </div>
                    <div class="sign-up-password">
                        <input type="password" name="up-password" id="up-password" class="text-input">
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