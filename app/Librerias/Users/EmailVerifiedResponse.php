<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 10/07/2018
 * Time: 04:25 PM
 */

namespace App\Librerias\Users;


use App\Models\User\UserProfile;

class EmailVerifiedResponse
{
    private $verified;
    private $message;
    private $url;
    private $content;

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setUser(UserProfile $user)
    {
        $this->content = $user;
    }

    public function setVerified(bool $verified)
    {
        $this->verified = $verified;
    }

    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function getContent()
    {
        return $this->content;
    }
}
