<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

use Laravel\Socialite\Facades\Socialite;
use Throwable;


class SocialAuthController extends Controller
{
    /**
     * @var array<int, string>
     */
    private const PROVIDERS = ['facebook', 'google', 'twitter'];

    public function redirectToProvider(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::PROVIDERS), 404);
        
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::PROVIDERS), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                            ->withErrors(['email' => 'Xác thực qua ' . ucfirst($provider) . ' thất bại hoặc phiên làm việc đã hết hạn. Vui lòng thử lại.',
            ]);
        }

        // kiểm tra provider đã liên kết chưa
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            if ($denied = $this->denyIfAdmin($socialAccount->user)) {
                return $denied;
            }

            $socialAccount->update(['access_token' => $socialUser->token]);

            return $this->login($socialAccount->user);
        }

        // kiểm tra email
        $email = $socialUser->getEmail();

        if((empty($email))){
            $username = $socialUser->getNickname() ?? $socialUser->getId();
            $email = "{$username}@twitter.local";
        }

        $user = User::where('email', $email)->first();

        if ($user && ($denied = $this->denyIfAdmin($user))) {
            return $denied;
        }

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: Str::before($email, '@')),
                'email' => $email,
                'password' => bcrypt(Str::random(40)),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'access_token' => $socialUser->token,
        ]);

        return $this->login($user);
    }

    private function denyIfAdmin(User $user): ?RedirectResponse {
        if ($user->role !== 'admin') {
            return null;
        }

        return redirect()->route('login')->withErrors([
            'email' => 'Tài khoản quản trị viên không thể đăng nhập qua mạng xã hội.',
        ]);
    }

    private function login(User $user): RedirectResponse {
        Auth::login($user);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
