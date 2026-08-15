<?php

namespace App\Http\Controllers\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Throwable;


class SocialAuthController extends Controller
{
    private array $providers = ['facebook', 'google', 'twitter'];

    public function redirectToProvider($provider)
    {
        abort_unless(in_array($provider, $this->providers), 404);
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        abort_unless(in_array($provider, $this->providers), 404);

        // $socialUser = Socialite::driver($provider)->user();

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors([
                'email' => 'Xác thực qua ' . ucfirst($provider) . ' thất bại hoặc phiên làm việc đã hết hạn. Vui lòng thử lại.',
            ]);
        }

        // kiểm tra provider đã liên kết chưa
        $socialAccount = SocialAccount::where([
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ])->first();

        if ($socialAccount) {

            Auth::login($socialAccount->user);

            return redirect()->route('home');
        }

        // kiểm tra email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {

            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'user',
            ]);
        }

        SocialAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'access_token' => $socialUser->token,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
