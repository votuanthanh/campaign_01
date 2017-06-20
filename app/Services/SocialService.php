<?php

namespace App\Services;
use App\Repositories\Contracts\SocialAccountInterface;
use App\Repositories\Contracts\UserInterface;
use Laravel\Socialite\Contracts\Provider;

class SocialService
{
    protected $socialAccountRepository;
    protected $userRepository;

    public function __construct(
        UserInterface $userRepository,
        SocialAccountInterface $socialAccountRepository
    ) {
        $this->userRepository = $userRepository;
        $this->socialAccountRepository = $socialAccountRepository;
    }

    public function createOrGetUser(Provider $provider)
    {
        $infoSocial = $this->getInforAccountSocial($provider);

        $account = $this->socialAccountRepository->getAccount($infoSocial['provider_id'], $infoSocial['provider']);

        if ($account) {
            $user = $this->userRepository->find($account->user_id);

            if (!empty($account->getDirty())) {
                $account->forceFill($infoSocial)->save();
                $user->fill($infoSocial)->save();
            }

            return $user;
        }

        $account = $this->socialAccountRepository->getModel()->forceFill($infoSocial);

        $user = $this->userRepository->where('email', $infoSocial['email'])->first();

        // updating information matching credentials data
        // if email exists that user have authenticated, initially update that is following with last social registered was
        // at the rest, normally create with provider that was produced
        if ($user) {
            $user->fill($infoSocial)->save();
        } else {
            $user = $this->userRepository->create($infoSocial);
        }

        $account->user()->associate($user);
        $account->save();

        return $user;
    }

    protected function getInforAccountSocial($provider)
    {
        $user = $provider->user();

        return [
            'provider_id' => $user->getId(),
            'provider' => class_basename($provider),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'url_file' => $user->getAvatar(),
        ];
    }
}
