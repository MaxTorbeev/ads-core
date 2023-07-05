<?php

namespace Ads\WsdlClient\Services\Clients;

use Ads\WsdlClient\Models\UserWs;
use App\Models\User;

class UserWsDto
{
    private User|null $user;

    private string $login;

    private string $password;

    private string $url;

    public static function fromUserWs(UserWs $userWs): self
    {
        return (new self())
            ->setUser($userWs->user)
            ->setLogin($userWs->login)
            ->setPassword($userWs->password)
            ->setUrl($userWs->url);
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return UserWsDto
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
