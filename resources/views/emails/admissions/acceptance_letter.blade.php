<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body>
    <div class="" style="width:100%;margin:0 auto;display:block;">
        <div class="" style="width:800px;display:block;margin:0 auto;">
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/tPGck0d/Header.png"
                alt="">
            <div class="" style="padding:20px;font-family:verdana;font-size:14px;">
                Hi Ms/Mr. {!! $information->first_name .' '. $information->last_name !!}!
                Acceptance Letter here.....

                <br><br>
                <a href="http://localhost:3310/cebu-iac-lms/site/admissions_student_payment_reservation/{{$information->slug}}"
                    target="_blank">Reserve your slot TODAY</a>
                <br><br><br>
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/M9SntyB/footer.png"
                alt="">
        </div>
    </div>
</body>

</html>