<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }

    /**
     * Get S3 URL for a file path.
     *
     * @param string $path
     * @param int|null $minutes
     * @return string|null
     */
    public function getS3Url($path, $minutes = 10)
    {
        if(!$path) {
            return null;
        }
        $s3 = Storage::disk('s3');
        if($minutes === null){
            $s3->setVisibility($path, "public");
            // Use the direct URL creation approach
            return Storage::disk('s3')->url($path);
        }
        // Create a presigned/temporary URL
        return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes($minutes));
    }
}