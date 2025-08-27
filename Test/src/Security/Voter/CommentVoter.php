<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CommentVoter extends Voter
{
    public const EDIT = 'COMMENT_EDIT';
    public const VIEW = 'COMMENT_VIEW';
    public const CREATE = 'COMMENT_CREATE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [ self::CREATE]) || in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Comment;
    }

    /**
     * @param Comment $subject
     */
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
                return $subject->getAuthor()->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::CREATE:
                return true;
                break;
        }

        return false;
    }
}
