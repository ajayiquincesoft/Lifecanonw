Hello {{$user->name}},

<p>You have recently requested a link to change your password for your Escape Connection account. 
Please click on the button below to reset your password:
</p><br />
<?php
$url = url('/');
?>
<a style="color: #fff;background-color: #da3e4d;border-color: #da3e4d;box-shadow: none;padding: 10px;border-radius: 8px;text-decoration: none;" href="{{$url}}/reset_password/{{encrypt($user->id)}}">Reset Password</a><br /><br /><br/>

<p>If you did not request this, please ignore this email. Your account is secured and your password remains unchanged.</p>

Best Regards, <br />

Escape Connection team<br />