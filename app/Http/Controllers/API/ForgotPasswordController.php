<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends BaseController
{
    /**
     * Send a reset password link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->sendError('User not found', ['error' => 'No user found with this email address']);
        }

        // Create a new reset token
        $token = Str::random(60);

        // Store the token in password_resets table
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        // Send a simple email notification
        Mail::send('emails.reset_password', ['token' => $token, 'user' => $user], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Password Reset Notification');
        });

        return $this->sendResponse(['email' => $request->email], 'Password reset link sent to your email');
    }

    /**
     * Reset user password with token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Verify token
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return $this->sendError('Invalid Token', ['error' => 'Invalid or expired token']);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($tokenData->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return $this->sendError('Expired Token', ['error' => 'Token has expired']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Delete the token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return $this->sendResponse([], 'Password has been reset successfully');
    }
}
