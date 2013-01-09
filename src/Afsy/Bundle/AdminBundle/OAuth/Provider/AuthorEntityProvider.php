<?php

namespace Afsy\Bundle\AdminBundle\OAuth\Provider;

use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface, HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Afsy\Bundle\CoreBundle\Entity\Author;

/**
 * User provider for the ORM that loads users given a mapping between resource
 * owner names and the properties of the entities.
 */
class AuthorEntityProvider implements OAuthAwareUserProviderInterface
{
    /**
     * @var array
     */
    private $em;

    /**
     * Constructor.
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response) {
        $email = $response->getEmail();
        $user = $this->em->getRepository('AfsyCoreBundle:Author')->findOneBy(array('email' => $email));

        if (null === $user) {
            // create this user on the fly
            $user = new Author();
            $user->setEmail($email);
            $user->setName($response->getRealName());
            $user->setCity($response->getCity());
            $user->setIsEnabled(false);

            $this->em->persist($user);
            $this->em->flush();
        }

        if (!$user->getIsEnabled()) {
            throw new UsernameNotFoundException('You have not been allowed to access to this page. Please drop a mail to AFSY administrators.');
        }

        return $user;
    }
}
