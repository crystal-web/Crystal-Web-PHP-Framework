<?php
class ControllerRegister {

    static private $controller;
    static private $cacheController;

    /**
     * @param $controller
     */
    static public function setController($controller){
        self::$controller = $controller;
    }

    /**
     * Controle si le controller est enregistré
     * @return bool
     * @throws Exception
     */
    static function isRegistered() {
        if (!self::$controller){
            throw new Exception("Set controller before call self::isRegistered()");
        }

        if (!self::$cacheController) {
            self::$cacheController = new Cache('controllerRegister');
        }

        $cache = self::$cacheController->getCache();
        return (isset($cache[self::$controller]));
    }

    static function register($description = NULL) {
        if (!self::$controller){
            throw new Exception("Set controller before call self::isRegistered()");
        }

        if (!self::$cacheController) {
            self::$cacheController = new Cache('controllerRegister');
        }

        $cache = self::$cacheController->getCache();
        $cache[self::$controller] = array(
            'registerTime' => time(),
            'description' => $description
            );
        return self::$cacheController->setCache($cache);
    }
}