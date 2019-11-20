<?php

namespace Quantum;

/**
 * Class Config
 * @package Quantum
 */
class Config extends Singleton
{

    /**
     * @var
     */
    public $environment;

    /**
     * Config constructor.
     * @throws \Exception
     */
    function __construct()
    {
        $this->domainBasedAutoEnvConfig();
        $this->domainBasedAutoAppConfig();
    }

    /**
     * @return bool
     */
    public function getEnvironment() {

       if (!empty($this->environment)) {
	    return $this->environment;
       }

       return false;

    }

    /**
     * @param $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @throws \Exception
     */
    public function domainBasedAutoEnvConfig()
    {

        $this->config_root = InternalPathResolver::getInstance()->config_root;

        $cfg_file = $this->config_root.'environment.php';

        if (!is_file($cfg_file))
            trigger_error("environment.php not found in config directory", E_USER_ERROR);

        require_once($cfg_file);

        if (!isset($QUANTUM_ENVIRONMENTS))
            trigger_error("QUANTUM_ENVIRONMENTS are not set", E_USER_ERROR);

        if (isset($_SERVER['SERVER_NAME']))
        {
            $current_domain = $_SERVER['SERVER_NAME'];

            $current_env = '';

            foreach ($QUANTUM_ENVIRONMENTS as $key => $environment)
            {
                if ($environment['domain'] == $current_domain)
                {
                    $current_env = (object)$environment;
                }

            }

            if (is_object($current_env))
            {
                $this->setEnvironment($current_env);
            }
            else if (!Request::getInstance()->isCommandLine())
            {
                throw new \Exception("Detected domain ".$current_domain." does not match any domain defined in environment.php", E_USER_ERROR);
            }
        }

    }


    /**
     *
     */
    public function domainBasedAutoAppConfig()
    {
        if (!isset($_SERVER["HTTP_HOST"]))
            return;

        $urlParts = explode('.', $_SERVER["HTTP_HOST"]);

        if (empty($urlParts))
            return;

        $subdomain_value = $urlParts[0];

        $apps = $this->getHostedApps();

        if (empty($apps))
        {
            throw new \Exception("No hosted apps found", E_USER_ERROR);
        }

        foreach ($apps as $app)
        {
            if ($app['uri'] == $subdomain_value)
            {
                $this->setAppConfig($app);
                return;
            }
        }

        if (!Request::getInstance()->isCommandLine())
        {
            throw new \Exception("No HostedApp found in apps.php to handle: ".$subdomain_value, E_USER_ERROR);
        }

    }

    /**
     * @return ValueTree
     */
    public function getGlobalRoutes()
    {
        $this->config_root = InternalPathResolver::getInstance()->config_root;

        require_once($this->config_root.'routes.php');

        if (isset($QUANTUM_CONTROLLER_ROUTES) && empty($this->controller_routes))
        {
            $this->controller_routes = new_vt();

            foreach ($QUANTUM_CONTROLLER_ROUTES as $key => $app)
            {
                $QUANTUM_CONTROLLER_ROUTES[$key] = new_locked_vt($app);
            }

            $this->controller_routes->replaceProperties($QUANTUM_CONTROLLER_ROUTES);
        }

        return $this->controller_routes;
    }


    /**
     * @return ValueTree
     */
    public function getActiveAppRoutes()
    {
        return $this->getApprovedAppRoutes();
    }


    /**
     * @param $uri
     * @return bool|mixed
     */
    public function getRouteForUri($uri)
    {
        $routes = $this->getApprovedAppRoutes();

        if (empty($routes))
            return false;

        foreach ($routes as $route)
        {
            $route_uri = $route->get('uri');

            if (str_contains($route_uri, "*"))
            {
                $route_uri = str_replace("*", "", $route_uri);

                if (starts_with($uri, $route_uri))
                    return $route;
            }

            if ($uri == $route_uri)
                return $route;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getHostedApps()
    {
        if (empty($this->hosted_apps))
        {
            $this->config_root = InternalPathResolver::getInstance()->config_root;

            require_once($this->config_root.'apps.php');

            if (isset($QUANTUM_HOSTED_APPS))
            {
                foreach ($QUANTUM_HOSTED_APPS as $key => $app)
                {
                    $QUANTUM_HOSTED_APPS[$key] = new_locked_vt($app);
                }

                $this->hosted_apps = $QUANTUM_HOSTED_APPS;

            }
        }

        return $this->hosted_apps;
    }

    /**
     * @return ValueTree
     */
    public function getApprovedAppRoutes()
    {
        if (empty($this->approved_app_routes))
        {
            $this->config_root = InternalPathResolver::getInstance()->app_config_root;

            require_once(qf($this->config_root . 'routes.php')->getRealPath());

            if (isset($QUANTUM_APP_ROUTES))
            {
                $this->approved_app_routes = new_vt();

                foreach ($QUANTUM_APP_ROUTES as $key => $app)
                {
                    $QUANTUM_APP_ROUTES[$key] = new_locked_vt($app);
                }

                $this->approved_app_routes->replaceProperties($QUANTUM_APP_ROUTES);
            }

        }

        return $this->approved_app_routes;
    }


    /**
     * @param $uri
     * @return bool
     */
    public function hasHostedApp($uri)
    {
        $apps = $this->getHostedApps();

        foreach ($apps as $app)
        {
            if ($app['uri'] == $uri)
                return true;
        }

        return false;
    }

    /**
     * @param $uri
     * @return mixed|null
     */
    public function getHostedApp($uri)
    {
        $apps = $this->getHostedApps();

        foreach ($apps as $app)
        {
            if ($app['uri'] == $uri)
                return $app;
        }

        return null;
    }

    /**
     * @param ValueTree $app
     */
    public function setAppConfig(ValueTree $app)
    {
        $this->hosted_app_config = $app;
        InternalPathResolver::getInstance()->updateAppRoot($this->hosted_app_config->get('dir'));
        Autoloader::getInstance()->initDirectories();
        //echo "Operating on:".$this->hosted_app_config->get('dir').PHP_EOL;
    }

    /**
     * @return Database
     */
    public function getDatabase()
    {
        if (!isset($this->database))
            $this->database = new Database($this->environment->db_name, $this->environment->db_host, $this->environment->db_user, $this->environment->db_password);

        return $this->database;
    }

    /**
     * @return mixed
     */
    public function getEnvironmentInstance()
    {
	    return $this->environment->instance;
    }

    /**
     * @return mixed
     */
    public function getEnvironmentDomain()
    {
        return $this->environment->domain;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
	    return $this->environment->path;
    }

    /**
     * @return mixed
     */
    public function getSystemSalt()
    {
	    return $this->environment->system_salt;
    }

    /**
     * @return bool
     */
    public function getHostedAppConfig()
    {
        if (!empty($this->hosted_app_config))
            return $this->hosted_app_config;

        return false;
    }

    /**
     * @param $key
     * @param bool $fallback
     * @return mixed
     */
    public function getHostedAppProperty($key, $fallback = false)
    {
        return $this->hosted_app_config->get($key, $fallback);
    }

    /**
     * @return mixed
     */
    public function getHostedAppUri()
    {
        return $this->getHostedAppProperty('uri');
    }

    /**
     * @return mixed
     */
    public function getHostedAppDir()
    {
        return $this->getHostedAppProperty('dir');
    }

    /**
     * @return bool|mixed
     */
    public function getActiveAppName()
    {
        return $this->getActiveAppConfig()->get('name');
    }

    /**
     * @return bool|mixed
     */
    public function getActiveAppDeveloper()
    {
        return $this->getActiveAppConfig()->get('developer');
    }

    /**
     * @return bool|mixed
     */
    public function getActiveAppSharedSecret()
    {
        return $this->getActiveAppConfig()->get('shared_secret');
    }

    /**
     * @return ValueTree
     */
    public function getActiveAppConfig()
    {
        if (empty($this->private_app_config))
        {
            $uri = InternalPathResolver::getInstance()->app_config_root;

            $file = $uri."config.php";

            if (!file_exists($file))
                throw new \RuntimeException("File not found: ". $file);

            require_once($file);

            if (isset($QUANTUM_APP_CONFIG))
            {
                $this->private_app_config = new_vt($QUANTUM_APP_CONFIG);
            }
        }

        return $this->private_app_config;
    }

    /**
     * @return ValueTree
     */
    public function getSystemEncryptionKeys()
    {
        if (empty($this->system_encryption_keys))
        {
            $configDir = new File(InternalPathResolver::getInstance()->config_root);

            $keysFile = $configDir->getChildFile("keys.json");

            if (!$keysFile->exists())
                throw new \RuntimeException("File not found: ". $keysFile->getPath());

            $keys = $keysFile->loadAsJson();
            if (!empty($keys))
                $this->system_encryption_keys = new_vt($keys);

        }

        return $this->system_encryption_keys;
    }

    /**
     * @return bool|mixed
     */
    public function getMasterEncryptionKey()
    {
        $keys = $this->getSystemEncryptionKeys();

        if (!empty($keys))
            return $keys->get('master_encryption_key');

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function getPublicRsaKey()
    {
        $keys = $this->getSystemEncryptionKeys();

        if (!empty($keys))
            return $keys->get('public_encrypted_rsa_key');

        return false;
    }

    /**
     * @return bool|mixed
     */
    public function getPrivateRsaKey()
    {
        $keys = $this->getSystemEncryptionKeys();

        if (!empty($keys))
            return $keys->get('private_encrypted_rsa_key');

        return false;
    }


    /**
     * @return ValueTree
     */
    public function getKernelConfig()
    {
        if (empty($this->kernel_config))
        {
            $uri = InternalPathResolver::getInstance()->config_root;

            $file = $uri."config.php";

            if (!file_exists($file))
                throw new \RuntimeException("File not found: ". $file);

            require_once($file);

            if (isset($QUANTUM_KERNEL_CONFIG))
            {
                $this->kernel_config = new_vt($QUANTUM_KERNEL_CONFIG);
            }
        }

        return $this->kernel_config;
    }

    /**
     * @return bool|mixed
     */
    public function getCurrentRoute()
    {
        if (empty($this->current_route))
        {
            $uri = Request::getInstance()->getUri();

            $uri = $this->checkForIdInUriAndTokenize($uri);

            $route = $this->getRouteForUri($uri);

            if (empty($route))
                return false;

            $this->current_route = $route;
        }

        return $this->current_route;
    }

    /**
     * @param $uri
     * @return QString
     */
    private function checkForIdInUriAndTokenize($uri)
    {
        $request = Request::getInstance();

        $id = $request->findId();

        $uri = qs($uri)->replace($id, '{id}');

        return $uri->toStdString();
    }

    /**
     * @return bool
     */
    public function isCurrentRouteWildcard()
    {
        $uri = $this->getCurrentRouteUri();

        if (empty($uri))
            return false;

        return qs($uri)->contains('*');
    }

    /**
     * @return bool
     */
    public function currentRouteRequiresId()
    {
        $uri = $this->getCurrentRouteUri();

        if (empty($uri))
            return false;

        return qs($uri)->contains('{id}');
    }

    /**
     * @return bool|mixed
     */
    public function getCurrentRouteUri()
    {
        if (empty($this->current_route_uri))
        {
            $route = $this->getCurrentRoute();

            if (empty($route))
                return false;

            $this->current_route_uri = $route['uri'];
        }

        return $this->current_route_uri;
    }

    /**
     * @return bool
     */
    public function getCurrentRouteMinAccessLevel()
    {
        $route = $this->getCurrentRoute();

        if ($route === false)
            return false;

        return $route->get('min_access_level', false);
    }

    /**
     * @return bool
     */
    public function getCurrentRouteMaxAccessLevel()
    {
        $route = $this->getCurrentRoute();

        if ($route === false)
            return false;

        return $route->get('max_access_level', false);
    }

    /**
     * @return array|bool
     */
    public function getCurrentRouteStrictAccessLevels()
    {
        $route = $this->getCurrentRoute();

        if ($route === false)
            return false;

        if ($route->has('strict_access_levels'))
        {
            $levels = $route->get('strict_access_levels');
            return qs($levels)->explode(',');
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCurrentRoutePublic()
    {
        $level = qs($this->getCurrentRouteMinAccessLevel());

        if ($level->isNotEmpty() && $level->equals('public'))
            return true;

        return false;
    }

    /**
     * @return bool
     */
    public function isProductionEnvironment()
    {
        return $this->environment->instance === 'production';
    }

    /**
     * @return bool
     */
    public function isDevelopmentEnvironment()
    {
        return $this->environment->instance === 'development';
    }








}



?>