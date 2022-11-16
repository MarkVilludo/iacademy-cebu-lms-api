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
                {{ date("M j, Y") }}
                <br /><br />
                <span style="font-weight: bold">
                {{ $information->last_name }}, {{ $information->first_name }} {{ $information->middle_name }}<br />
                {{ $information->school }}
                </span>
                <br /><br />
                Hi Ms/Mr. {!! $information->last_name !!}!
                
                Congratulations! Based on your exam results and interview, we are pleased to inform you that you are
                admitted to {{ $information->desired_program }} for First Term for Academic Year 2021-2022.
                The next step of your application is to secure your slot for the next school year and submit the following requirements:
                <ol>                
                    <li>Original copy of Grade 10 Report Card with principal's signature and promotion to Grade 11.</li>
                    <li>A clear copy of birth certificate issued by the National Statistics Office (NSO)/Philippine
                    Statistics Authority (PSA)</li>
                    <li>Original copy of Certificate of Good Moral Character from the class adviser, guidance
                    counselor or principal stating that the student is currently enrolled/graduated from SY
                    2020-2021 (with school's dry seal & principal's signature)</li>
                    <li>Three (3) 2x2 identical ID pictures (white background with name tag below)</li>
                    <li>Official Receipt of Reservation (for those who applied early and have settled this fee ahead)
                    Failure to submit the above mentioned requirements on or before June 27, 2021 will give iACADEMY
                    the right to retract your acceptance and forfeit your application.</li>
                </ol>
                For questions, contact us at +63 917 521 6633 / +63 917 526 6633, send us a message on Facebook,
                or email us at admissionscebu@iacademy.edu.ph
                Once again, congratulations!

                <br><br>
                <a href="http://103.225.39.200/cebu-iac-lms/site/admissions_student_payment_reservation/{{$information->slug}}"
                    target="_blank">Reserve your slot TODAY</a>
                <br><br><br>
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/M9SntyB/footer.png"
                alt="">
        </div>
    </div>
</body>

</html>