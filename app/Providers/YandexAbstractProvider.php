<?php

/*
* App version of SocialiteProviders\Yandex\Provider
* @url https://socialiteproviders.netlify.com/providers/yandex.html
* Added in composer.json
* "autoload": {
*      "exclude-from-classmap": ["vendor/socialiteproviders/yandex/Provider.php"],
*      "files": ["app/Providers/YandexAbstractProvider.php"]
* Remember to run composer dump-autoload after editing the composer.json.
*/

namespace SocialiteProviders\Yandex;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use Illuminate\Support\Arr;
use App\User;
use Carbon\Carbon;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'YANDEX';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://oauth.yandex.ru/authorize',
            $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://oauth.yandex.ru/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(
            'https://login.yandex.ru/info?format=json',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        $attribs["oauth_id"] = Arr::get($user, 'id');
        $attribs["login"] = Arr::get($user, 'login');
        $attribs["name"] = Arr::get($user, 'real_name');
        $attribs["email"] = Arr::get($user, 'default_email');
        $attribs["avatar"] = 'https://avatars.yandex.net/get-yapic/' . Arr::get($user, 'default_avatar_id') . '/islands-200';
        $attribs["birthday"] = Arr::get($user, 'birthday');
        $attribs["sex"] = (Arr::get($user, 'sex') == 'female') ? '0' : '1';
        $attribs["updated_at"] = Carbon::now();

        $user = User::updateOrCreate(['oauth_id' => $attribs['oauth_id']], $attribs);
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
