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
                {!! $information->first_name .' '. $information->last_name !!} has scheduled an interview.
                <br><br>
                Click on the link below to check details.
                <br><br>
                <a href="http://103.225.39.200/cebu-iac-lms/admissionsV1/view_lead/{{$information->slug}}" target="_blank">View Applicant Details</a>
                <br><br><br>

                Thank you,
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>