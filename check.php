<?php
//calling the function
checkForm();

function checkForm(){

    //checking if POST variable is empty
    if(!empty($_POST)){
        $username = $_POST['username'];
        $email = $_POST['email'];
        $captcha = $_POST['g-recaptcha-response'];

        //API request for email and captcha validation
        $emailResponse = file_get_contents('https://open.kickbox.com/v1/disposable/'.$email);
        $captchaResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.urlencode('6LfFl7EUAAAAADgfvjUvQZkYN7tgoukT8cAR_6YU').'&response='.urlencode($captcha));

        $emailResponse = json_decode($emailResponse, true);
        $captchaResponse = json_decode($captchaResponse, true);

        if(!$emailResponse['disposable'] && $captchaResponse['success']){

            $link = mysqli_connect("localhost", "root", "", "registerPage");

            // Checking the connection
            if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }

            // Attempt insert query execution
            $sql = "INSERT INTO users (username, email) VALUES ('".$username."', '".$email."')";
            if(mysqli_query($link, $sql)){
                header('Location: success.html');
            } else{
                echo "ERROR: The command could not be executed $sql. " . mysqli_error($link);
            }
        }
        else{
            //redirection to register page due to error in reCaptcha or email address
            header('Location: home.html');

        }
    }

}




