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
                Alert for {!! $information->student->first_name .' '. $information->student->last_name !!},
                <br><br>              
                {!! $information->message !!}
                <br><br>
                Click on the button below to view profile: <br /> <br />
                <a href="http://103.225.39.200//admissionsV1/view_lead/{{$information->student->slug}}" target="_blank">View Applicant Details</a>                
                <br /><br /><br />
                
                Thank you
                <br /><br /><br />
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>