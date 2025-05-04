<?php

namespace Bits\FlyUxBundle\Security\Voter;

use Contao\CoreBundle\Security\ContaoCorePermissions;
use Contao\CoreBundle\Security\DataContainer\CreateAction;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Contao\Input;

class ContentVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return
            $attribute === ContaoCorePermissions::DC_PREFIX . 'tl_content'
            && $subject instanceof CreateAction;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var CreateAction $subject */
//var_dump($token);exit;
        if ($subject->getDataSource() !== 'tl_content') {
            return false;
        }

        // PTable == tl_content erlauben (z.B. Nested Elements)
        if ((Input::get('ptable') ?? null) === 'tl_content') {
            return true;
        }

        // Weitere Spezialfälle erlauben? Dann hier ergänzen
        return false; // alle anderen Fälle dem nächsten Voter überlassen
    }
}
