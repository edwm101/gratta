<?php
// show error reporting E_ALL
error_reporting(E_ALL);
define("SHOW_PDO_EXCEPTION", true);
define("DO_TRANSACTION", true); // X20 Speed (Use App::db->commit() to stop the transaction)


define("DB_DSN", "mysql:host=localhost;dbname=gratta");
define("DB_USER", "root");
define("DB_PASS", "");


define('APP_NAME', "gratta");
define('BASE_URL', "http://localhost/gratta/api/v1/");


define('USER_AGENT', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');

//The upload folder path
define('UPLOAD_PATH', "static/image/");

//JWT CONTENT
define("TOKEN_STATEMENT", "id,first_name,last_name,phone,email,role,is_verified_email,image,balance,referral_code,sms_count");


//The secret key in JWT
define('JWT_KEY', "FjGGGGFHiNDghjERghjghjtn1010hjghjgh10101ghj0101");

//The token key that is used in the header
define('ID_TOKEN_KEY', "X-Token");


//GOOGLE
//ReCAPTCHA Keys
define('RECAPTCHA_KEY', "6Ld5SgEVAAAAAMHlSd2HnCV4kUYEHbX3M_6or2hi");
