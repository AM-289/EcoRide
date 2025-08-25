<?php

namespace App\Security\Voter;

use App\Entity\Car;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CarVoter extends Voter
{
    public const EDIT = 'CAR_EDIT';
    public const VIEW = 'CAR_VIEW';
    public const LIST = 'CAR_LIST';
    public const LIST_ALL = 'CAR_ALL';

    public function __construct(private AccessDecisionManagerInterface $accessDecisionManager, private readonly Security $security)
    {
        
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::LIST, self::LIST_ALL]) || in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Car;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if ($this->accessDecisionManager->decide($token, ['ROLE_ADMIN', $user->getRoles()])) {
                    return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getDriver()->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::LIST:
                return true;
                break;

        }

        return false;
    }
}
