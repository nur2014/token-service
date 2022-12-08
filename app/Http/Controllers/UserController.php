<?php

namespace App\Http\Controllers;

use App\Library\NotificationSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Validations\UserValidation;
use App\Http\Validations\OtpVerifyValidation;
use App\Models\User;
use App\Models\UserOtp;
use App\Models\UserManagement\UserDetailFarmer;
use App\Library\SmsLibrary;
use App\Models\UserManagement\UserDetail;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validationResult = UserValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }
        DB::beginTransaction();
        try {
            if ($request->email) {
                $email = $request->email;
            } else {
                $email = "fm_".rand(10000000,99999999).'@gmail.com';
            }

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['username'] = $request->mobile_no;
            $input['mobile_no'] = $request->mobile_no;
            $input['status'] = 1;
            $input['email'] = $email;


            $user = User::create($input);

            $now_date    = new \DateTime();
            $expiry_date = $now_date->add(new \DateInterval('PT5M'))->format('Y-m-d H:i:s');

            $userOtp             = new UserOtp();
            $userOtp->user_id    = $user->id;
            $userOtp->mobile_no  = $request->mobile_no;
            $userOtp->otp        = rand(100000, 999999);
            $userOtp->expiry_date = $expiry_date;
            $userOtp->save();

                save_log([
                    'data_id'    => $user->id,
                    'table_name' => 'users'
                ]);

            DB::commit();

            $smsData['mobile'] = $userOtp->mobile_no;
            $smsData['message'] = "MoA Registration verification code ". $userOtp->otp . '.';
            $sms = new SmsLibrary();
            $sms->sendSingleSms($smsData);

            return response(['data' => [], 'message' => 'An OTP sent in your mobile number whose validity is 5 minutes!', 'success' => true, 'user_id' => $user->id]);
        } catch (\Exception $ex) {
            DB::rollback();
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : ""
            ]);
        }
    }

    public function otpVerify(Request $request)
    {
        $validationResult = OtpVerifyValidation::validate($request);

        if (!$validationResult['success']) {
            return response($validationResult);
        }

        DB::beginTransaction();

        try {
            $userOtp = UserOtp::whereUserId($request->id)->latest()->first();

            if ($userOtp) {
                $now_date = new \DateTime(date('Y-m-d H:i:s'));
                $expiry_date = new \DateTime($userOtp->expiry_date);
                $otp_code = $request->otp_code;

                if ($otp_code == $userOtp->otp) {
                    if ($expiry_date < $now_date){
                        return response(['data' => [], 'message' => 'Your otp time limit expired.', 'success' => false]);
                    } else {
                        $userOtp->is_active = '1';
                        $userOtp->save();

                        $user = User::find($request->id);
                        $user->status = 0;
                        $user->save();

                        DB::commit();

                        $message = 'Your registration has been successfully completed. Please login into your panel and update your profile information';
//                        $message2 = 'User registration has been successfully completed. Your approval is required';
//                        $admin = User::whereHas('userDetail', function ($query) {
//                            $query->where('role_id', 1);
//                        })->first();

                        // send notification to external user
                        $menuUrl = '/warehouse-farmer/profile';
                        $notification = NotificationSender::sendNotification(0, $user->id, $user->id, 'custom', $message, [1,2,3], [1], $menuUrl, 6);
                        // send notification to admin
//                        NotificationSender::sendNotification(0, $admin->id, 0, 'custom', $message2, [3], [2]);

                        return response([
                            'data' => [],
                            'message' => 'Account OTP verify successfully!',
                            'success' => true,
                            'notification' => $notification
                        ]);
                    }
                } else {
                    return response(['data' => [], 'message' => 'OTP is invalid!', 'success' => false]);
                }
            } else {
                return response(['data' => [], 'message' => 'Your account is invalid!', 'success' => false]);
            }

        } catch (\Exception $ex) {
            DB::rollback();

            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : ""
            ]);
        }
    }

   public function otepresend(Request $request) {
       $userOtp = UserOtp::whereUserId($request->id)->latest()->first();
       if($userOtp) {  

           $now_date = new \DateTime();
           $expiry_date =  $now_date->add(new \DateInterval('PT3M'))->format('Y-m-d H:i:s');
           $newOtp = rand(10000,99999);
           $userOtp->otp = $newOtp;
           $userOtp->expire_date = $expiry_date;
           $userOtp->update;


        }

   }







    public function otpResend(Request $request)
    {
        try {
            $userOtp = UserOtp::whereUserId($request->id)->latest()->first();

            if ($userOtp) {
                $now_date    = new \DateTime();
                $expiry_date = $now_date->add(new \DateInterval('PT3M'))->format('Y-m-d H:i:s');
                $userOtp->otp        = rand(100000, 999999);
                $userOtp->expiry_date = $expiry_date;
                $userOtp->update();
                $smsData['mobile'] = $userOtp->mobile_no;
                $smsData['message'] = "MoA Registration verification code ". $userOtp->otp . '.';
                $sms = new SmsLibrary();
                $sms->sendSingleSms($smsData);
                return response(['data' => [], 'message' => 'OTP resend successfully!', 'success' => true]);
            } else {
                return response(['data' => [], 'message' => 'Your account is invalid!', 'success' => false]);
            }

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : ""
            ]);
        }
    }

    // send otp for change mobile no
    public function otpChangeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no'     => 'required|unique:users,username',
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        try {
            $userOtp = UserOtp::where('mobile_no', $request->mobile_no)->where('is_active', 0)->latest()->first();

            $now_date    = new \DateTime();
            $expiry_date = $now_date->add(new \DateInterval('PT3M'))->format('Y-m-d H:i:s');

            if ($userOtp) {
                $userOtp->otp        = rand(100000, 999999);
                $userOtp->expiry_date = $expiry_date;
                $userOtp->update();
            } else {
                $userOtp             = new UserOtp();
                $userOtp->user_id    = $request->id;
                $userOtp->mobile_no  = $request->mobile_no;
                $userOtp->otp        = rand(100000, 999999);
                $userOtp->expiry_date = $expiry_date;
                $userOtp->save();
            }
            $smsData['mobile'] = $userOtp->mobile_no;
            $smsData['message'] = "MoA Registration verification code ". $userOtp->otp . '.';
            $sms = new SmsLibrary();
            $sms->sendSingleSms($smsData);
            return response(['data' => [], 'message' => 'OTP send successfully!', 'success' => true]);

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to send otp.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : ""
            ]);
        }
    }
    // send otp for change mobile no
    public function changePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no'     => 'required|unique:users,username',
        ]);

        if ($validator->fails()) {
            return ([
                'success' => false,
                'errors'  => $validator->errors()
            ]);
        }

        try {
            $userOtp = UserOtp::whereUserId($request->id)->where('is_active', 0)->latest()->first();

            if ($userOtp) {
                $now_date = new \DateTime(date('Y-m-d H:i:s'));
                $expiry_date = new \DateTime($userOtp->expiry_date);
                $otp_code = $request->otp;

                if ($otp_code == $userOtp->otp) {
                    if ($expiry_date < $now_date){
                        return response(['data' => [], 'message' => 'Your otp time limit expired.', 'success' => false]);
                    } else {
                        $userOtp->is_active = '1';
                        $userOtp->save();
                        $user = User::find($request->id);
                        $user->mobile_no = $request->mobile_no;
                        $user->username = $request->mobile_no;
                        $user->save();

                        return response(['data' => [], 'message' => 'Mobile number change successfully!', 'success' => true   ]);
                    }
                } else {
                    return response(['data' => [], 'message' => ' OTP is invalid!', 'success' => false]);
                }
            } else {
                return response(['data' => [], 'message' => ' OTP is invalid!', 'success' => false]);
            }

        } catch (\Exception $ex) {
            return response([
                'success' => false,
                'message' => 'Failed to save data.',
                'errors'  => env('APP_ENV') !== 'production' ? $ex->getMessage() : ""
            ]);
        }
    }

    //check username function return the email
    public function checkUsername(Request $request)
    {
        $model = User::where('username', $request->username)->where('status', 0)->first();

        if (!$model) {
            return ['success' => false, 'message' => 'username not found.'];
        }

        return [
            'success' => true,
            'data' => $model->email
        ];
    }

    public function checkUserExistence(Request $request)
    {
        $model = User::where('username', $request->username)->first();

        if (!$model) {
            return ['success' => false, 'message' => 'User not found.'];
        }

        return [
            'success' => true,
            'data' => $model->email
        ];
    }

    public function userUpdate(Request $request): array
    {
        $existing = $request->mobile_no;
        $new = $request->username;
        $password = $request->password;
        $existingUser = User::where('username', $existing)->first();

        if ($existingUser) {
            $parts = explode('_', $existingUser->email);
            $firstPart = $parts[0];
            $email  = $firstPart . '_' . $new ."@gmail.com";
            $data = ['username' => $new, 'mobile_no' => $new, 'email' => $email];
            if ($password) {
                $data = array_merge($data, ['password' => $password]);
            }
            $existingUser->update($data);

            return [
                'success' => true,
                'data' => $existingUser
            ];
        }

        return ['success' => false, 'message' => 'User not found.'];
    }

    public function notificationUser(Request $request) {
        $receiverId = $request->receiver_id;
        $senderId = $request->sender_id;

        $receiverUser = User::find($receiverId);
        $senderUser = null;

        if ($senderId) {
            $senderUser = User::find($senderId);
        }

        $success = false;

        if ($receiverUser) {
            $success = true;
        }

        if ($senderUser) {
            $success = true;
        }

        return response([
            'success' => $success,
            'data' => [
                'receiver' => $receiverUser,
                'sender' => $senderUser
            ]
        ]);
    }
}
