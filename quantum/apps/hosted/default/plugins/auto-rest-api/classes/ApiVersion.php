<?php

namespace AutoRestApi;

class ApiVersion
{
    /**
     * @var RouteGenerator
     */
    private $route_generator;

    public function __construct($config)
    {
        $this->config = new_vt($config);

        $api_prefix = $this->getPrefix();

        $this->route_generator = new RouteGenerator(new ModelsManager($this->config['models']), $api_prefix);
    }

    public function getVersion()
    {
        return $this->config->get('version');
    }

    public function getAuthorizationMiddleware()
    {
        return $this->config->get('autorization_middleware');
    }

    public function getDescription()
    {
        return $this->config->get('description', 'REST API for '.get_current_environment_setting('domain'));
    }

    public function getTitle()
    {
        return $this->config->get('title', 'REST API');
    }

    public function getTermsOfService()
    {
        return $this->config->get('terms_of_service', '');
    }

    public function getContactEmail()
    {
        return $this->config->get('contact_email', 'site@example.com');
    }

    public function getLicenseName()
    {
        return $this->config->get('license_name', 'Apache 2.0');
    }

    public function getLicenseUrl()
    {
        return $this->config->get('license_url', 'Apache 2.0');
    }

    public function getAuthorizations()
    {
        $authorization_methods = $this->config->get('authorizations', '');

        if (!empty($authorization_methods))
            return qs($authorization_methods)->stripWhitespace()->explode(',');

        return [];
    }

    public function getRouteGenerator()
    {
        return $this->route_generator;
    }

    public function getModelsManager()
    {
        return $this->route_generator->models_manager;
    }

    public function getPrefix()
    {
        return $this->config->get('prefix', '').$this->getVersion();
    }

    public function getExtraData()
    {
        return $this->config->get('extra_data', []);
    }

    public function getHttpRequestMethodHeaderKeyOverride()
    {
        return $this->config->get('http_method_header_override_key', null);
    }

    public function shouldPrettyPrintJson()
    {
        return $this->config->get('pretty_print_json', true);
    }
}