<?php
namespace Cogipix\CogimixCommonBundle\Tests\Utils;

use Cogipix\CogimixCommonBundle\Entity\User;
class UserFactory
{


    public static function generateUser($enabled = true)
    {
        $generator = \Faker\Factory::create();

        $user = new User();
        $userName = $generator->userName;
        $user->setUsername($userName);
        $user->setUsernameCanonical($userName);
        $email = $generator->email;
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        $user->setEnabled($enabled);
        $user->setPassword('password');
        $user->setAcceptNews(true);

        return $user;

    }
}