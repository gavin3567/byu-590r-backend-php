<?php
namespace App\Http\Controllers\API;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class UserController extends BaseController
{
    /**
     * Get authenticated user details
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);
        $user->avatar = $this->getS3Url($user->avatar);
        return $this->sendResponse($user, 'User');
    }

    /**
     * Upload user avatar to S3
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request)
    {
        // Validate the image file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Check if an image was uploaded
        if ($request->hasFile('image')) {
            $authUser = Auth::user();
            $user = User::findOrFail($authUser->id);

            // Get file extension
            $extension = $request->file('image')->getClientOriginalExtension();

            // Create unique image name using timestamp and user ID
            $image_name = time() .'_' . $authUser->id . '.' . $extension;

            // Store file in S3 bucket in 'images' directory
            $path = $request->file('image')->storeAs(
                'images',
                $image_name,
                's3'
            );

            // Update user record with new avatar path
            $user->avatar = $path;
            $user->save();

            // Prepare success response with avatar URL
            $success['avatar'] = null;
            if(isset($user->avatar)){
                $success['avatar'] = $this->getS3Url($path);
            }

            return $this->sendResponse($success, 'User profile avatar uploaded successfully!');
        }

        return $this->sendError('No image file provided', ['error' => 'Please provide an image file.']);
    }

    /**
     * Remove user avatar from S3
     *
     * @return \Illuminate\Http\Response
     */
    public function removeAvatar()
    {
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);

        // Delete file from S3 if avatar exists
        if($user->avatar) {
            Storage::disk('s3')->delete($user->avatar);
        }

        // Update user record to remove avatar path
        $user->avatar = null;
        $user->save();

        $success['avatar'] = null;
        return $this->sendResponse($success, 'User profile avatar removed successfully!');
    }

    /**
     * Send verification email to user
     *
     * @return \Illuminate\Http\Response
     */
    public function sendVerificationEmail()
    {
        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);

        // Create verification URL or token logic here
        // This is a placeholder - you'll need to implement your verification logic
        $verificationUrl = url('/verify-email/' . encrypt($user->id));

        // Send verification email
        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));

        return $this->sendResponse(null, 'Verification email sent successfully!');
    }

    /**
     * Change user email
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);

        // Update email
        $user->email = $request->email;
        $user->email_verified_at = null; // Reset verification status
        $user->save();

        // Return success
        return $this->sendResponse(['email' => $user->email], 'Email changed successfully!');
    }
}