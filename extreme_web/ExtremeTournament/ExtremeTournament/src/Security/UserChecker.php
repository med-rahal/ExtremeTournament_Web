<?php

namespace App\Security;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class UserChecker implements UserCheckerInterface
{

   // private $bansChecker;

    public function checkPreAuth(UserInterface $user)
    {
        if ($user->getBanned()) {
            throw new CustomUserMessageAuthenticationException("You're banned !");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        /*
        if ($this->bansChecker->isBanned($user)) {
            throw new User();
        }
        */
    }
}