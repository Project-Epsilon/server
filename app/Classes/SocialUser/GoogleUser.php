<?php
namespace App\Classes\SocialUser;

class GoogleUser implements SocialUser
{
    private $claims;

    public function __construct($claims)
    {
        $this->claims = $claims;
    }

    /**
     * Get email.
     * @return mixed
     */
    public function getEmail()
    {
        return $this->claims['email'];
    }

    /**
     * Get name.
     * @return mixed
     */
    public function getName()
    {
        return $this->claims['name'];
    }
}