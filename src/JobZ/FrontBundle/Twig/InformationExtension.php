<?php

namespace JobZ\FrontBundle\Twig;

use Doctrine\ORM\EntityManager;
use JobZ\FrontBundle\Entity\Information;

/**
 * Class InformationExtension
 * @package JobZ\FrontBundle\Twig
 */
class InformationExtension extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * BullshitExtension constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_informations', array($this, 'getInformations'))
        );
    }

    /**
     * @return array|Information[]
     */
    public function getInformations()
    {
        $informations = $this->entityManager->getRepository(Information::class)->findBy(array(), array(
            'id' => 'ASC'
        ));

        return $informations;
    }

    public function getName()
    {
        return 'information_extension';
    }
}