<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Application\Auth\Commands\LoginUseCase;
use App\Application\Auth\Commands\LogoutAllUseCase;
use App\Application\Auth\Commands\LogoutUseCase;
use App\Application\Auth\Commands\RequestPasswordResetLinkUseCase;
use App\Application\Auth\Commands\ResetPasswordUseCase;
use App\Application\Auth\Queries\MeQuery;
use App\Domain\Auth\DTOs\LoginCredentialsDTO;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\AuthTokenResource;
use App\Http\Resources\Auth\UserMeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class AuthController extends Controller
{
    public function login(LoginRequest $request, LoginUseCase $useCase): JsonResponse
    {
        $token = $useCase->execute(new LoginCredentialsDTO(
            email: $request->string('email')->lower()->toString(),
            password: $request->string('password')->toString(),
            deviceName: $request->input('device_name', 'api'),
        ));

        return (new AuthTokenResource($token))
            ->response()
            ->setStatusCode(200);
    }

    public function me(Request $request, MeQuery $query): JsonResponse
    {
        $user = $query->execute($request->user()->id);

        return (new UserMeResource($user))->response()->setStatusCode(200);
    }

    public function logout(Request $request, LogoutUseCase $useCase): JsonResponse
    {
        $useCase->execute(
            userId: $request->user()->id,
            tokenId: $request->user()->currentAccessToken()->id,
        );

        return response()->json(['data' => ['message' => 'Logged out.']], 200);
    }

    public function logoutAll(Request $request, LogoutAllUseCase $useCase): JsonResponse
    {
        $revoked = DB::transaction(fn (): int => $useCase->execute($request->user()->id));

        return response()->json([
            'data' => [
                'message' => 'All sessions revoked.',
                'tokens_revoked' => $revoked,
            ],
        ], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request, RequestPasswordResetLinkUseCase $useCase): JsonResponse
    {
        $useCase->execute($request->string('email')->lower()->toString());

        return response()->json([
            'data' => [
                'message' => 'If that email is registered, a reset link has been sent.',
            ],
        ], 202);
    }

    public function resetPassword(ResetPasswordRequest $request, ResetPasswordUseCase $useCase): JsonResponse
    {
        $useCase->execute(
            email: $request->string('email')->lower()->toString(),
            token: $request->string('token')->toString(),
            newPassword: $request->string('password')->toString(),
        );

        return response()->json([
            'data' => [
                'message' => 'Password has been reset.',
            ],
        ], 200);
    }
}
