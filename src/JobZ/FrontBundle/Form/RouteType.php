<?php

namespace JobZ\FrontBundle\Form;


use Doctrine\ORM\EntityManager;
use JobZ\FrontBundle\Entity\Information;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * RouteType constructor.
     * @param Router $router
     */
    public function __construct(Router $router, EntityManager $em)
    {
        $routes = $router->getRouteCollection()->all();

        $this->routes = $this->getInformationRoutes($router, $em);
        foreach($routes as $route => $params) {
            $this->routes[$route] = $route;
        }
    }

    private function getInformationRoutes(Router $router, EntityManager $entityManager)
    {
        $infos = $entityManager->getRepository(Information::class)->findAll();

        $infoRoutes = array();
        foreach ($infos as $info) {
            $route = $router->generate(
                'jobz_front_information_details',
                array(
                    'slug' => $info->getSlug()
                )
            );
            $infoRoutes[$route] = $route;
        }

        return $infoRoutes;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->routes,
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}