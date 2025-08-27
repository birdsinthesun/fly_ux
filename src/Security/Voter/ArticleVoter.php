<?php

namespace Bits\FlyUxBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Contao\BackendUser;

class ArticleVoter implements VoterInterface
{
    public function supportsAttribute(string $attribute): bool
    {
        return in_array($attribute, ['edit_article', 'change_hierarchy', 'delete_article'], true);
    }

    public function supportsClass(string $class): bool
    {
        return true; // alle Klassen zulassen
    }

    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (!$user instanceof BackendUser) {
            return VoterInterface::ACCESS_DENIED;
        }

        foreach ($attributes as $attribute) {
            switch ($attribute) {
                case 'edit_article':
                    if (!$user->hasAccess('article', 'modules')) {
                        return VoterInterface::ACCESS_DENIED;
                    }
                    break;
                case 'change_hierarchy':
                    if (!$user->hasAccess('changeArticle', 'modules')) {
                        return VoterInterface::ACCESS_DENIED;
                    }
                    break;
                case 'delete_article':
                    if (!$user->hasAccess('deleteArticle', 'modules')) {
                        return VoterInterface::ACCESS_DENIED;
                    }
                    break;
            }
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
