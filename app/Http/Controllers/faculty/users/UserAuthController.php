<?php

namespace App\Http\Controllers\faculty\users;

use App\Http\Controllers\Controller;
use App\Models\faculty\users\facultyDb;
use App\Models\faculty\users\forgotPassword;
use App\Models\faculty\users\registeredUser;
use App\Models\UserFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    // method:POST for new registration
    function newRegister(){
        /*
        Input Must details:
            facultyId | password | department | email
            */
        $body = request();
        $faculty_id = $body->facultyId;
        $password = $body->password;

        if(registeredUser::where('facultyId', $faculty_id )->exists()){
            return ["status"=>"error", "error_message"=>"User already existed ."];
        }

        else{
            if(facultyDb::where('facultyId', $faculty_id)->exists()){
                $details_to_check = facultyDb::where('facultyId', $faculty_id)->get()[0];
                if (($details_to_check->department == $body->department) && ($details_to_check->email == $body->email)){
                    $new_registration = new registeredUser();
                    $new_registration->facultyId = $faculty_id;
                    $new_registration->password = Crypt::encryptString($password);
                    $new_registration->save();
                    return ["status"=>"ok", "message"=>"registered successfully !"];
                }
                else{
                    return ["status"=>"error", "error_message"=>"Not a authenticated usersss ."];
                }
            }
            else{
                return ["status"=>"error", "error_message"=>"Not a authenticated user ."];
            }
        }
    }

    // method:POST for login
    function userLogin(){
        /*
        Input Must details:
            facultyId | password
            */
        $body = request();
        $faculty_id = $body->facultyId; 
        $raw_password = $body->password;

        if(registeredUser::where('facultyId', $faculty_id)->exists()){
            $data = registeredUser::where('facultyId', $faculty_id)->get()[0];
            if(crypt::decryptString($data->password)==$raw_password){
                return ["status"=>"ok", "message"=>"Successfull login"];
            }
            else{
                return ["status"=>"error", "error_message"=>"Incorrect password"];
            }
        }
        else{
            return ["status"=>"error", "error_message"=>"Not a registered user ."];
        }
    }

    // method:POST for forgot password
    function forgotPassRequest(){
        /*
        Input Must details:
            facultyId | email
            */
        $body = request();
        if(registeredUser::where('facultyId', $body->facultyId)->exists()){
            $verified_mailId = facultyDb::where('facultyId', $body->facultyId)->get()[0]->email;
            if ($verified_mailId==$body->email){
                $otp = rand(100000, 999999);
                $forgotPassword_data = new forgotPassword();
                $forgotPassword_data->facultyId = $body->facultyId;
                $forgotPassword_data->otp = $otp;
                
                // Main sending             
                $mail_to = $body->email;
                $data = array('otp'=>$otp);
                Mail::send('mail', $data, function($message) use($mail_to) {
                   $message->to($mail_to)->subject
                      ('Forgot Password NSCET E-CONNECT');
                   $message->from('smk11500@gmail.com','iSPIN Team');
                });
                
                $forgotPassword_data->save();

                return ["status"=>"ok", "message"=>"OTP sent to mail"];
            }
            else{
                return ["status"=>"error", "error_message"=>"Not a registered mail Id ."];
            }
        }
        else{
            return ["status"=>"error", "error_message"=>"Not a registered user ."];
        }
    }

    // method:GET To check forgot password OTP from user
    function forgotPassOTPcheck($faculty_id, $otp){
        /*
        Input Must details:
            facultyId | otp entered by user
            */

        $data = forgotPassword::where("facultyId", $faculty_id)->get()[0];
        
        if ($data->otp == $otp){
            return ["status"=>"ok", "message"=>"OTP Verified"];
        }
        else{
            return ["status"=>"error", "error_message"=>"Incorrect OTP"];
        }
    }

    // method:POST To change forgoted password
    function PasswordChange(){
        /*
        Input Must details:
            facultyId | newPassword entered by user
            */   
        $body = request();
        registeredUser::where('facultyId', $body->facultyId)->update(['password' =>  crypt::encryptString($body->newPassword)]);

        return ["status"=>"ok", "message"=>"Password Changed"];
    }

    function FeedBack(){
        /*
        Input Must Details:
            facultyId | message
        */
        $body = request();

        if (registeredUser::where('facultyId', $body->facultyId)->exists()){
            $new_userFeedback = new UserFeedback();
            $new_userFeedback->facultyId = $body->facultyId;
            $new_userFeedback->message =  $body->message;
            $new_userFeedback->save();

            return ["status"=>"ok", "message"=>"feedback recieved"]; 
        }
        else{
            return ["status"=>"error", "error_message"=>"Not a registered user ."];
        }
    }
 }