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
            <div class="error"></div>
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
    
    
</body>
</html>