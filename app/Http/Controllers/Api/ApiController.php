<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;


/**
 * @OA\Info(
 *        title="Star Wars APIs",
 *        version="1.0.0"
 *    )
 */
class ApiController extends Controller
{

    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "password_confirmation"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    /**
     *   Method : POST
     *   Parameters : name, email, password
     *   Activity : Register a new user.
     */
    public function register(Request $request)
    {

        // Validate the incoming request data
        $validatedData = $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed",
        ]);

        try {
            // Create a new user record
            $user = User::create([
                "name" => $validatedData['name'],
                "email" => $validatedData['email'],
                "password" => bcrypt($validatedData['password']),
            ]);

            // Return a JSON response indicating successful registration
            return response()->json([
                "status" => true,
                "message" => "User registered successfully",
                "data" => $user,
            ]);
        } catch (\Exception $e) {
            // Return a JSON response indicating registration failure
            return response()->json([
                "status" => false,
                "message" => "Registration failed. Please try again later.",
                "error" => $e->getMessage(),
            ], 500);
        }
    }




    /*
        Method : POST
        Parameters : email, password
        Activity : Authenticate a user's login request.
    */
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            "email" => "required|email|string",
            "password" => "required",
        ]);

        // Check if the user exists
        $user = User::where("email", $request->email)->first();

        if (!empty($user)) {
            // User exists
            if (Hash::check($request->password, $user->password)) {
                // Password matched
                $token = $user->createToken("token")->accessToken;

                // Return a JSON response indicating successful login
                return response()->json([
                    "status" => true,
                    "message" => "User authenticated successfully",
                    "token" => $token,
                    "data" => [],
                ]);
            } else {
                // Invalid password

                // Return a JSON response indicating login failure due to invalid password
                return response()->json([
                    "status" => false,
                    "message" => "Incorrect password",
                    "data" => [],
                ]);
            }
        } else {
            // User not found

            // Return a JSON response indicating login failure due to non-existent email
            return response()->json([
                "status" => false,
                "message" => "User not found",
                "data" => [],
            ]);
        }
    }




    /*
        Method : GET
        Parameters : token
        Activity : Retrieve the profile of the authenticated user.
    */
    public function profile(Request $request)
    {
        // Retrieve user information
        $user = auth()->user();

        // Return a JSON response of user profile
        return response()->json([
            "status" => true,
            "message" => "User Profile",
            "data" => $user
        ]);
    }




    /*
        Method : GET
        Parameters : token
        Activity : Log out the authenticated user.
    */
    public function logout(Request $request)
    {
        // Retrieve the token associated with the authenticated user
        $token = auth()->user()->token();

        // Revoke the token, effectively logging out the user
        $token->revoke();

        // Return a JSON response indicating successful logout
        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}
