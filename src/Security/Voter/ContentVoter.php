<?php

namespace Bits\FlyUxBundle\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Contao\BackendUser;
use Contao\ContentModel;

class ContentVoter implements VoterInterface
{
    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === 'edit_content';
    }

    public function supportsClass(string $class): bool
    {
        return true;
    }

    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $user = $token->getUser();
        if (!$user instanceof BackendUser) {
            return VoterInterface::ACCESS_DENIED;
        }

        // Prüfe Artikel-Recht
        if (!$user->hasAccess('article', 'modules')) {
            return VoterInterface::ACCESS_DENIED;
        }

        // Prüfe Inhaltselement-Typ
        if ($subject instanceof ContentModel) {
            $type = $subject->type;
            if (\is_array($user->elements) && !\in_array($type, $user->elements, true)) {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_GRANTED;
    }
}
