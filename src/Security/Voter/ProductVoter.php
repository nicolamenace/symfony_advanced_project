<?php

namespace App\Security\Voter;

use App\Entity\Product;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    public const MODIFY = 'product_modify';
    public const DELETE = 'product_delete';
    public const CREATE = 'product_create';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MODIFY, self::DELETE, self::CREATE])
            && ($subject instanceof Product || $subject === null);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Ensure the user is logged in
        if (!$user) {
            return false;
        }

        // Only allow users with ROLE_ADMIN
        return $this->security->isGranted('ROLE_ADMIN');
    }
}
