<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleVoter extends Voter
{
    // Define supported attributes
    const ACCESS_DASHBOARD = 'ACCESS_DASHBOARD';
    const MANAGE_USERS = 'MANAGE_USERS';
    const VIEW_PRODUCTS = 'VIEW_PRODUCTS';

    protected function supports(string $attribute, $subject): bool
    {
        // Only validate the defined attributes, no subject is required
        return in_array($attribute, [
            self::ACCESS_DASHBOARD,
            self::MANAGE_USERS,
            self::VIEW_PRODUCTS,
        ]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // User is not authenticated
            return false;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Check for specific roles based on the attribute
        switch ($attribute) {
            case self::ACCESS_DASHBOARD:
                return in_array('ROLE_ADMIN', $user->getRoles());
            case self::MANAGE_USERS:
                return in_array('ROLE_MANAGER', $user->getRoles());
            case self::VIEW_PRODUCTS:
                return in_array('ROLE_MANAGER', $user->getRoles()) || in_array('ROLE_ADMIN', $user->getRoles())|| in_array('ROLE_USER', $user->getRoles());
        }

        return false;
    }
}
