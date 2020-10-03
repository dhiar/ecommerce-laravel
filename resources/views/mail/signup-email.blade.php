Hello <b>{{ $email_data['name'] }}</b> ,
<br><br>
Welcome to my website.
<br>
Please click the below link to verify your email, and activate your account.
<br><br>
<a href="http://ecommerce-laravel.test/verify?code={{$email_data['verification_code']}}">Click Here!</a>

<br><br>
Thank you!
