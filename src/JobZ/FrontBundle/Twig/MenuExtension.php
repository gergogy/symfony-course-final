<?php

namespace JobZ\FrontBundle\Twig;

use Doctrine\ORM\EntityManager;
use JobZ\FrontBundle\Entity\Menu;

/**
 * Class MenuExtension
 * @package JobZ\FrontBundle\Twig
 */
class MenuExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('get_menus', array($this, 'getMenus'))
        );
    }

    /**
     * @return array|Menu[]
     */
    public function getMenus()
    {
        $menus = $this->entityManager->getRepository(Menu::class)->findBy(array(), array(
            'id' => 'ASC'
        ));

        return $menus;
    }

    public function getName()
    {
        return 'menu_extension';
    }
}