<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\UserService;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Traits\FindsUser;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserWithTokenResource;

class AuthController extends Controller
{
    use FindsUser;

    /**
     * @OA\Post(
     *      path="/api/register",
     *      summary="Register a new user.",
     *      tags={"Auth"},
     *      operationId="register",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email", "name", "password", "password_confirmation"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="The new User email (unique)",
     *                      example="example@test.com",
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                      description="The new User name (unique, max:255)",
     *                      example="John Doe",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="The new User password (min:8)",
     *                      example="secret123",
     *                  ),
     *                  @OA\Property(
     *                      property="password_confirmation",
     *                      type="string",
     *                      description="The password confirmation (same as 'password')",
     *                      example="secret123",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/UserWithToken",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Failed to create a new user",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR_CREATE_FAILED",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Provided data is not valid",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR422",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR500",
     *              ),
     *          ),
     *      ),
     * ),
     *
     * @param  \App\Http\Requests\Api\V1\Auth\RegisterRequest $request
     * @return \App\Http\Resources\Api\V1\User\UserWithTokenResource
     */
    public function register(RegisterRequest $request)
    {
        try {
            $service = App::make(UserService::class);
            $user = $service->create($request->getData());
            $user->plainTextToken = $user->createToken('API Token')->plainTextToken;
        } catch (\Exception $exp) {
            throw new ApiException(
                ApiException::ACTION_FAILED,
                'actions.register',
                $exp->getMessage()
            );
        }

        return $this->response(UserWithTokenResource::make($user));
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      summary="Login the user.",
     *      tags={"Auth"},
     *      operationId="login",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"email", "password"},
     *                  @OA\Property(
     *                      property="email",
     *                      type="string",
     *                      description="The User email",
     *                      example="example@test.com",
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string",
     *                      description="The User password",
     *                      example="secret123",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User logged in successfully",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(
     *                      property="data",
     *                      ref="#/components/schemas/UserWithToken",
     *                  ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Failed to login",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR_CREATE_FAILED",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="the user not found",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR_NOT_FOUND",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Provided data is not valid",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR422",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR500",
     *              ),
     *          ),
     *      ),
     * ),
     *
     * @param  \App\Http\Requests\Api\V1\Auth\LoginRequest $request
     * @return \App\Http\Resources\Api\V1\User\UserWithTokenResource
     */
    public function login(LoginRequest $request)
    {
        $user = $this->findUserByEmail($request->getData('email'));

        try {
            $user->tokens()->delete();
            $user->plainTextToken = $user->createToken('API Token')->plainTextToken;
        } catch (\Exception $exp) {
            throw new ApiException(
                ApiException::ACTION_FAILED,
                'actions.login',
                $exp->getMessage()
            );
        }

        return $this->response(UserWithTokenResource::make($user));
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      summary="Logout the user.",
     *      tags={"Auth"},
     *      operationId="logout",
     *      @OA\Response(
     *          response=201,
     *          description="User logged out.",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Failed to create a new user",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR_CREATE_FAILED",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Token expired or not provided",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR401",
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  ref="#/components/schemas/ERR500",
     *              ),
     *          ),
     *      ),
     * ),
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (\Exception $exp) {
            throw new ApiException(
                ApiException::ACTION_FAILED,
                'actions.logout',
                $exp->getMessage()
            );
        }

        return $this->response([], 204);
    }
}
