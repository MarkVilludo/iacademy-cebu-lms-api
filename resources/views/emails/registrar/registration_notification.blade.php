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
                Greetings {!! $information->first_name .' '. $information->last_name !!},
                <br><br>                
                <br><br>
                Click on the payment link below to pay tuition online: <br />
                <a href="http://103.225.39.200/cebu-iac-lms/site/student_tuition_payment/{{$information->slug}}"
                    style="text-align:center">
                    <button type="button"
                        style="text-decoration:none;display: inline-block;font-weight: 400;color: #fff;text-align: center;vertical-align: middle;border: 1px solid transparent;padding: 8px 12px;font-size: 16px;line-height: 1.6;border-radius: 0.25rem;background-color: #1c54a5;border: 1px solid #1c54a5;">
                        Pay Tuition Online</button>
                </a>                
                
                Thank you
                <br /><br /><br />
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>