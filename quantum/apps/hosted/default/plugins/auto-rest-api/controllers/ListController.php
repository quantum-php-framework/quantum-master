<?php

namespace AutoRestApi\Controllers;

use AutoRestApi\ModelDescription;
use Quantum\ApiException;
use Quantum\Controller;

class ListController extends Controller
{
    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    private function buildSearchCriteria(ModelDescription $modelDescription)
    {
        if (!$modelDescription->allowSearch()) {
            return [];
        }

        $attributes = [];
        $params = [];
        $glue = '';

        $searchable_attributes = $modelDescription->getSearchableAttributes();

        foreach ($searchable_attributes as $attribute_name => $param_name)
        {
            if ($this->request->hasParam($param_name) || $this->request->hasParam('search'))
            {
                $attributes[] = "$attribute_name LIKE ?";

                if ($this->request->hasParam('search')) {
                    $param = $this->request->getParam('search');
                    $glue = 'OR';
                }
                else {
                    $param = $this->request->getParam($param_name);
                    $glue = 'AND';
                }

                $params[] = "%".$param."%";
            }
         }

        if ($this->request->hasParam('operator'))
        {
            $glue = $this->request->getParam('operator');
            if (!is_string($glue)) {
                ApiException::invalidParameters();
            }

            $glue = qs($glue)->toUpperCase()->toStdString();

            if (!in_array($glue, $modelDescription->getAllowedOperators())) {
                ApiException::invalidParameters();
            }
        }

        $left = new_vt($attributes)->implode(" $glue ");

        $criteria[] = $left;

        $criteria = array_merge($criteria, $params);

        return apply_filter('auto_rest_api_filter_search_criteria', $criteria);
    }

    public function execute(ModelDescription $modelDescription)
    {
        $ipp = $this->request->getParam('limit', $modelDescription->getDefaultLimit());

        if (!qs($ipp)->isNumber()) {
            ApiException::invalidParameters();
        }

        $ipp = min($ipp, $modelDescription->getMaxLimit());

        $offset = $this->request->getParam('page', 0);

        if (!qs($offset)->isNumber()) {
            ApiException::invalidParameters();
        }

        $order = $this->request->getParam('order', $modelDescription->getDefaultOrder());
        if (!is_string($order)) {
            ApiException::invalidParameters();
        }

        $order = qs($order)->toUpperCase()->toStdString();

        if (!in_array($order, $modelDescription->getAllowedOrders())) {
            ApiException::invalidParameters();
        }

        $className = $modelDescription->getClassName();

        if (!class_exists($className)) {
            ApiException::custom('invalid_model', '404 Model not found', 'Model Class not found:'.$className);
        }

        $search_criteria = $this->buildSearchCriteria($modelDescription);

        $total_objects_count = $className::count();
        $results_count = $className::count(['conditions' => $search_criteria]);

        $objects =  $className::find('all', array(
            'limit' => $ipp,
            'offset' => $offset,
            'order' => $modelDescription->getOrderAttributeKey().' '.$order,
            'conditions' => $search_criteria));

        $total_pages = round($results_count/$ipp);

        $visible_attributes = $modelDescription->getVisibleAttributes();

        $data = new_vt();

        foreach ($objects as $object)
        {
            $datum = new_vt();

            foreach ($visible_attributes as $attribute_name => $value)
            {
                if (qs($value)->contains('()')) {
                    $value = call_user_func([$object, qs($value)->removeCharacters('()')->toStdString()]);
                }
                else {
                    $value = $object->$value;
                }

                $datum->set($attribute_name, $value);
            }

            $extra_data = $modelDescription->getExtraData();
            if (!empty($extra_data))
            {
                foreach ($extra_data as $key => $extra_datum)
                {
                    $datum->set($key, $extra_datum);
                }
            }

            $data->add($datum->toStdArray());
        }





        $response = new_vt();
        $response->set('total_count', $total_objects_count);
        $response->set('results_count', $results_count);
        $response->set('pages_count', $total_pages);
        $response->set('limit', $ipp);
        $response->set('order', $order);
        $response->set('page', $offset);
        $response->set('total_pages', $total_pages);




        $next_page_index = $offset+1;
        $current_page_index = $offset;

        $base_url = \QM::buildURL(qs($_SERVER['REQUEST_URI'])->dropFirstCharacters(1)->toStdString());

        if ($next_page_index <= $total_pages)
        {
            $next_page_url = qurl($base_url)
                ->withParameter('limit', $ipp)
                ->withParameter('page', $next_page_index)
                ->withParameter('order', $order);
            $response->set('next_page', $next_page_url->toString());
        }
        else
        {
            $response->set('next_page', '');
        }

        if ($current_page_index == 0)
        {
            $response->set('prev_page', '');
        }
        else
        {
            $prev_page_url = qurl($base_url)
                ->withParameter('limit', $ipp)
                ->withParameter('page', $current_page_index-1)
                ->withParameter('order', $order);
            $response->set('previous_page', $prev_page_url->toString());
        }



        $data = apply_filter('auto_rest_api_filter_models_list', $data->toStdArray());

        $response->set($modelDescription->getPluralForm(), $data);

        return $response->toStdArray();

        //$this->output->adaptable($response);
    }


}