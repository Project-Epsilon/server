<?php
namespace App\Classes\SocialUser;

interface SocialUser
{
    /**
     * Gets email.
     * @return mixed
     */
    public function getEmail();

    /**
     * Gets name.
     * @return mixed
     */
    public function getName();
}