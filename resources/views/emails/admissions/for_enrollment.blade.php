<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body>
    <div class="" style="width:100%;margin:0 auto;display:block;">
        <div class="" style="width:800px;display:block;margin:0 auto;">
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/R6pS725/cebu-header.png"
                alt="">
            <div class="" style="padding:20px;font-family:verdana;font-size:14px;">
                Hi Ms/Mr. {!! $information->first_name .' '. $information->last_name !!}!
                <br><br>

                How have you been? We hope that you have been attending our workshops and are excited to
                fully immerse yourself in iACADEMY. Fortunately, that is sooner that we think. Early enrollment
                is now on going and you can select the program and schedule that applies to you.                
                
                <br><br><br>
                <a href="http://103.225.39.200/cebu-iac-lms/unity/confirm_program/{{$information->slug}}">Click Here</a> to confirm selected program.</a>
                <!---Link to confirm program selected--->
                Thank you,
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>