<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ApiVersion;
use Quantum\Controller;


class IndexController extends Controller
{

    /**
     * @var ApiVersion
     */
    public $version;

    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    public function setApiRoutes($routes)
    {
        $this->api_routes = $routes;
    }

    public function setApiVersion(ApiVersion $version)
    {
        $this->version = $version;
    }


    public function execute()
    {
        $response = new_vt();
        $response->set('swagger', '2.0');

        $info = new_vt();
        $info->set('description', $this->version->getDescription());
        $info->set('version', $this->version->getVersion());
        $info->set('title', $this->version->getTitle());
        $info->set('terms_of_service', $this->version->getTermsOfService());

        $contact = new_vt();
        $contact->set('email', $this->version->getContactEmail());
        $info->set('contact', $contact->toStdArray());

        $license = new_vt();
        $license->set('name', $this->version->getLicenseName());
        $license->set('url', $this->version->getLicenseUrl());
        $info->set('license', $license->toStdArray());

        $response->set('info', $info->toStdArray());

        $response->set('host', get_current_environment_setting('domain'));
        $response->set('basePath', qs($this->version->getPrefix())->ensureLeft('/')->toStdString());

        $response->set('schemes', $this->request->isSSL() ? ['https', 'http'] : ['http']);



        $paths = new_vt();



        foreach ($this->api_routes as $api_route)
        {

            $model_description = $api_route['model_description'];

            if ($model_description == 'index') {
                continue;
            }

            $path = new_vt();


            $searchable_attributes = $model_description->getSearchableAttributes();

            if ($model_description->allowList())
            {

                $list_path = new_vt();
                $list_path->set('summary', 'List '.$model_description->getPluralForm());


                $params = new_vt();

                $param = new_vt();
                $param->set('name', 'limit')
                        ->set('type', 'integer')
                        ->set('format', 'int64')
                        ->set('required', false)
                        ->set('default', 25)
                        ->set('minimum', 1)
                        ->set('maximum', 1000)
                        ->set('in', 'query');
                $params->add($param->toStdArray());

                $param = new_vt();
                $param->set('name', 'page')
                    ->set('type', 'integer')
                    ->set('format', 'int64')
                    ->set('required', false)
                    ->set('default', 0)
                    ->set('minimum', 0)
                    ->set('in', 'query');
                $params->add($param->toStdArray());


                $param = new_vt();
                $param->set('name', 'order')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('default', 'DESC')
                    ->set('in', 'query');
                $params->add($param->toStdArray());


                $param = new_vt();
                $param->set('name', 'operator')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('in', 'query');
                $params->add($param->toStdArray());

                $param = new_vt();
                $param->set('name', 'format')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('default', 'json')
                    ->set('in', 'query');
                $params->add($param->toStdArray());


                if ($model_description->allowSearch())
                {
                    foreach ($searchable_attributes as $attribute_name => $param_name)
                    {
                        $param = new_vt();
                        $param->set('name', $param_name)
                            ->set('type', 'string')
                            ->set('format', 'string')
                            ->set('required', false)
                            ->set('in', 'query');
                        $params->add($param->toStdArray());
                    }

                    $param = new_vt();
                    $param->set('name', 'search')
                        ->set('type', 'string')
                        ->set('format', 'string')
                        ->set('required', false)
                        ->set('in', 'query');
                    $params->add($param->toStdArray());
                }

                $list_path->set('parameters', $params->toStdArray());

                $path->set('get', $list_path->toStdArray());

            }

            if ($model_description->allowCreate())
            {
                $params = new_vt();

                $list_path = new_vt();
                $list_path->set('summary', 'Create '.$model_description->getPluralForm());

                $editable_attributes = $model_description->getEditableAttributes();

                foreach ($editable_attributes as $attribute_name => $param_name)
                {
                    $param = new_vt();
                    $param->set('name', $param_name)
                        ->set('type', 'string')
                        ->set('format', 'string')
                        ->set('required', false)
                        ->set('in', 'query');
                    $params->add($param->toStdArray());
                }

                $list_path->set('parameters', $params->toStdArray());

                $path->set('post', $list_path->toStdArray());

            }

            if (!$path->isEmpty()) {
                $paths->set('/'.$model_description->getPluralForm(), $path->toStdArray());
            }

            $path = new_vt();

            if ($model_description->allowView())
            {
                $params = new_vt();

                $list_path = new_vt();
                $list_path->set('summary', 'View '.$model_description->getSingularForm());

                $param = new_vt();
                $param->set('name', 'id')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('in', 'path');

                $params->add($param->toStdArray());

                $list_path->set('parameters', $params->toStdArray());

                $path->set('get', $list_path->toStdArray());
            }

            if ($model_description->allowUpdate())
            {
                $params = new_vt();

                $list_path = new_vt();
                $list_path->set('summary', 'Update '.$model_description->getSingularForm());

                $editable_attributes = $model_description->getEditableAttributes();

                $param = new_vt();
                $param->set('name', 'id')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('in', 'path');

                $params->add($param->toStdArray());

                foreach ($editable_attributes as $attribute_name => $param_name)
                {
                    $param = new_vt();
                    $param->set('name', $param_name)
                        ->set('type', 'string')
                        ->set('format', 'string')
                        ->set('required', false)
                        ->set('in', 'query');

                    $params->add($param->toStdArray());
                }

                $list_path->set('parameters', $params->toStdArray());

                $path->set('put', $list_path->toStdArray());
            }

            if ($model_description->allowDelete())
            {
                $params = new_vt();

                $list_path = new_vt();
                $list_path->set('summary', 'Delete '.$model_description->getSingularForm());

                $param = new_vt();
                $param->set('name', 'id')
                    ->set('type', 'string')
                    ->set('format', 'string')
                    ->set('required', false)
                    ->set('in', 'path');

                $params->add($param->toStdArray());

                $list_path->set('parameters', $params->toStdArray());

                $path->set('delete', $list_path->toStdArray());
            }



            if (!$path->isEmpty()) {
                $paths->set('/'.$model_description->getPluralForm().'/{id}', $path->toStdArray());
            }

        }

        $paths = apply_filter('auto_rest_api_filter_apis_list', $paths->toStdArray());

        $response->set('paths', $paths);

        $this->output->adaptable($response);
    }



}
