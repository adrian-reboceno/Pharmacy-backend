<?php
# app/Application/Auth/Services/JwtAuthService.php
namespace App\Application\Auth\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User as UserEntity;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;
use App\Presentation\Exceptions\V1\Auth\UserNotAuthenticatedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtAuthService
{
    protected ?UserEntity $user = null;
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Intento de login con credenciales y retorno de token + roles + permisos
     *
     * @throws InvalidCredentialsException
     */
    public function attemptLogin(array $credentials): array
    {
        if (! $token = Auth::attempt($credentials)) {
            throw new InvalidCredentialsException('Credenciales inválidas');
        }

        $this->user = $this->userRepository->findByEmail(Auth::user()->email);

        return $this->respondWithToken($token);
    }

    /**
     * Retorna la Entity User siempre cargada.
     *
     * @throws UserNotAuthenticatedException
     */
    public function user(): UserEntity
    {
        if ($this->user) {
            return $this->user;
        }

        $eloquentUser = Auth::user();

        if (!$eloquentUser) {
            throw new UserNotAuthenticatedException('No hay usuario autenticado.');
        }

        $this->user = $this->userRepository->findByEmail($eloquentUser->email);

        if (!$this->user) {
            throw new \RuntimeException('No se pudo cargar la entidad User.');
        }

        return $this->user;
    }

    /**
     * Logout e invalidación del token actual
     */
    public function logout(): void
    {
        Auth::logout();
        $this->user = null;
    }

    /**
     * Refrescar token JWT y devolver estructura con user, roles y permisos
     */
    public function refreshToken(): array
    {
        $token = Auth::refresh();
        $this->user = $this->userRepository->findByEmail(Auth::user()->email);

        return $this->respondWithToken($token);
    }

    /**
     * Genera un token mínimo (solo user_id) para usar en front o cuando se requiere ligereza
     */
    public function createMinimalToken(UserEntity $user): string
    {
        $eloquentUser = new \App\Models\User(['id' => $user->id]);
        return JWTAuth::claims([])->fromUser($eloquentUser);
    }

    /**
     * Construye la respuesta de autenticación con token + roles + permisos
     */
    public function respondWithToken(string $token, string $message = 'Autenticación exitosa', bool $includeRoles = true, bool $includePermissions = true): array
    {
        $user = $this->user ?? $this->user();

        $roles = $includeRoles ? $user->role ? [$user->role] : [] : [];
        $permissions = $includePermissions ? $user->permissions : [];

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'message' => $message,
        ];
    }
}
