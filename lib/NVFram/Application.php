<?php

namespace NVFram;

abstract class Application
{
    protected $request;
    protected $response;
    protected $name;
    protected $config;

    public function __construct()
    {
        $this->request = new Request($this);
        $this->response = new Response($this);
        $this->config = new Config($this);

        $this->name = '';
    }

    public function getController()
    {
        $router = new Router;
        $routes = $this->config->getRoutes();

        foreach ($routes as $route) {
            $router->addRoute(new Route($route));
        }

        try {
            $route = $router->getRoute($request->requestURI());
        } catch (\RuntimeException $e) {
            if ($e->getCode() == Router::NO_ROUTE) {
                $this->response->redirect404();
            }
        }

        $route = $router->getRoute($this->request->requestURI());

        $controllerClass  = $this->name.'\\Controller\\'.ucfirst($route->getController().'Controller');

        if (class_exists($controllerClass)) {
            $_GET = array_merge($_GET, $route->getVars());
            $controller = new $controllerClass($this, $route->getAction());
            return $controller;
        }
        throw new \RuntimeException('The requested controller \''.$controllerClass.'\' does not exists');
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getName()
    {
        return $this->name;
    }

    abstract public function run();
}
