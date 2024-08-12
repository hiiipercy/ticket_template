<?php

define('USER_AVATAR_PATH','user/');
define('HERO_AVATAR_PATH','hero/');
define('ABOUT_AVATAR_PATH','about/');
define('LOGO_PATH','logo/');
define('TABLE_PAGE_LENGTH','15');
define('MAIL_MAILER',['smtp','sendmail','mail']);
define('MAIL_ENCRYPTION',['none'=>'null','tls'=>'tls','ssl'=>'ssl']);
define('STATUS',[1=>'Active',2=>'Inactive']);
define('ROLE',[1=>'Admin',2=>'User']);
define('STATUS_LABEL',[
    1=>'<span class="badge badge-sm badge-success">Active</span>',
    2=>'<span class="badge badge-sm badge-danger">Inctive</span>'
]);

define('GENDER',[
    1=>'Male',
    2=>'Female',
    3=>'Other',
]);

define('MERITAL_STATUS',[
    1=>'Married',
    2=>'Unmarried',
    3=>'Separated',
]);

define('CALL_TYPE',[
    1=>'Inbound',
    2=>'Outbound'
]);


if (!function_exists('table_checkbox')) {
    function table_checkbox($row_id){
        return '<div class="form-checkbox">
            <input type="checkbox" class="form-check-input select_data" id="checkbox-'.$row_id.'" value="'.$row_id.'" onClick="select_single_item('.$row_id.')">
            <label class="form-check-label" for="checkbox-'.$row_id.'"></label>
        </div>';
    }
}

if (!function_exists('table_image')) {
    function table_image($path, $image, $name,$class = null, $style = null){
        $style_data = $style != null ? $style : 'width: 60px';
        return $image ? "<img src='".asset('/')."uploads/".$path.$image."' class='".$class."' alt='".$name."' style='".$style_data."'/>"
        : "<img src='".asset('/')."img/default.svg' alt='Default Image' style='width:40px;'/>";
    }
}

if (!function_exists('user_image')) {
    function user_image($gender, $path, $image, $name, $class=null, $style=null){
        if ($image){
            return '<img src="'.asset('/').'uploads/'.$path.$image.'" alt="'.$name.'" style="'.$style.'" class="'.$class.'">';
        }else{
            if ($gender) {
                $img = $gender == '1' ? 'male' : 'female';
            }else{
                $img = 'default';
            }
            return '<img src="'.asset('/').'img/'.$img.'.svg" alt="'.$name.'" style="'.$style.'" class="'.$class.'">';
        }
    }
}
if (!function_exists('change_status')) {
    function change_status(int $id,int $status,string $name = null){
        return $status == 1 ? '<span class="badge badge-success change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="cursor:pointer;">Active</span>' :
        '<span class="badge badge-danger change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1" style="cursor:pointer;">Inactive</span>';
    }
}

if (!function_exists('tooltip')) {
    function tooltip($title,$direction = 'top'){
        return 'data-toggle="tooltip" data-placement="'.$direction.'" title="'.$title.'"';
    }
}
if (!function_exists('validate_phone_number')) {
    function validate_phone_number($phone_no){
        if (strlen($phone_no) > 11) {
            return substr($phone_no, 2,11);
        }else{
            return $phone_no;
        }
    }
}

function validBDPhoneNumber($phone_number) {
    $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
    $phone_number = ltrim($phone_number, '88');
    $phone_number = $phone_number[0] != '0' ? '0'.$phone_number : $phone_number;

    // Remove any non-digit characters (optional, based on your use case)
    $phone_number = preg_replace('/\D/', '', $phone_number);

    // Regex pattern for Bangladeshi mobile numbers
    $pattern = '/^01[3-9]\d{8}$/';

    // Check if the number matches the pattern
    if (preg_match($pattern, $phone_number)) {
        return $phone_number;
    } else {
        return $phone_number;
    }
}
