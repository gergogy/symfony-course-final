<?php
/**
 * Created by PhpStorm.
 * User: blush
 * Date: 2016. 12. 14.
 * Time: 22:56
 */

namespace JobZ\FrontBundle\Form;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    protected $routes;

    /**
     * RouteType constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $routes = $router->getRouteCollection()->all();

        $this->routes = array();
        foreach($routes as $route => $params) {
            $this->routes[$route] = $route;
        }
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