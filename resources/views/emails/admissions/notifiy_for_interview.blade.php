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

                We noticed you haven’t chosen a schedule. Any concerns we can help you with? Don’t worry the interview
                is for us to get to know you better so we can advise you better as well on how you can pursue the right
                career path that you will be happy and proud about! Don’t waste another minute and let your Game
                Changing journey begin, TODAY!

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