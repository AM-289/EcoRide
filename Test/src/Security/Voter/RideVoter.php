<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class RideVoter extends Voter
{
    public const EDIT = 'RIDE_EDIT';
    public const VIEW = 'RIDE_VIEW';
    public const LIST = 'RIDE_LIST';
    public const LIST_ALL = 'RIDE_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::LIST, self::LIST_ALL]) || in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Ride;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getDriver()->getId() === $user->getUserIdentifier();
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
