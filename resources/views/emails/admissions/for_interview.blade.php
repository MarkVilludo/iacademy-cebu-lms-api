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

                We have evaluated your initial requirements and you are qualified to proceed to the next step. Please
                select in the Calendar below a schedule that you can conduct your interview with our Admissions Officer.
                It is not required but we encourage you to let your parent/s join the interview as well.

                <br><br>
                <a href="http://cebu.iacademy.edu.ph/site/applicant_calendar/{{$information->slug}}" target="_blank">calendar link
                    here</a>
                <br><br><br>

                Thank you,
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>