<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use ReCaptcha\ReCaptcha;
use ReCaptcha\RequestMethod\CurlPost;

class Captcha implements Rule
{
    private $secret = null;

    private $ipAddress = null;

    /**
     * Captcha constructor.
     *
     * @param $secret
     * @param $ipAddress
     */
    public function __construct($secret, $ipAddress)
    {
        $this->secret    = $secret;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $captcha  = new ReCaptcha($this->secret,new CurlPost());
        $response = $captcha
            //->setExpectedHostname($_SERVER['SERVER_NAME'])
            ->setExpectedAction('register')
            ->setScoreThreshold(0.5)
            ->verify($value, $this->ipAddress);

        return $response->isSuccess();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.captcha-missing');
    }
}
