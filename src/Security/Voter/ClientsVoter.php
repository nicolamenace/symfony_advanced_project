<?php
namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ClientsVoter extends Voter
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // Only check access for 'clients' routes
        return $attribute === 'ACCESS_CLIENTS';
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
{
    $user = $token->getUser();

    if (!$user instanceof UserInterface) {
        return false;
    }

    // Get current request
    $request = $this->requestStack->getCurrentRequest();
    if (!$request) {
        return false;
    }

    $route = $request->attributes->get('_route');

    // Ensure route is a string and belongs to 'clients' section
    if (is_string($route) && str_starts_with($route, 'clients')) {
        // Allow only admin and manager
        return in_array('ROLE_ADMIN', $user->getRoles(), true) || in_array('ROLE_MANAGER', $user->getRoles(), true);
    }

    return false;
}

}
