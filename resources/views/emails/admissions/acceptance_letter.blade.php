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
                admitted to {{ $information->program }} for {{ @$activeSem->enumSem }} Term for Academic Year {{ @$activeSem->strYearStart }}-{{ @$activeSem->strYearEnd }}.
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
                <br /><br />
                Once again, congratulations!
                <br /><br />                
                Regards,                
                <br /><br />
                
                <span style="font-weight: bold">RAQUEL P. WONG</span><br />
                Chief Operating Officer<br />
                iACADEMY                
                <br /><br />
                The next step is to pay for your reservation, click on the button below:<br /><br />
                <a href="http://103.225.39.200/site/admissions_student_payment_reservation/{{$information->slug}}"
                    style="text-align:center">
                    <button type="button"
                        style="text-decoration:none;display: inline-block;font-weight: 400;color: #fff;text-align: center;vertical-align: middle;border: 1px solid transparent;padding: 8px 12px;font-size: 16px;line-height: 1.6;border-radius: 0.25rem;background-color: #1c54a5;border: 1px solid #1c54a5;">
                        Reserve your slot TODAY</button>
                </a>                
                <br /><br /><br />
            </div>
            <img style="display:block;margin:0 auto; width:800px" width="800" src="https://i.ibb.co/bWzLGKh/cebu-footer.png"
                alt="">
        </div>
    </div>
</body>

</html>