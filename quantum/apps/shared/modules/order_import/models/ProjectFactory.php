<?php


namespace OrderImport;


class ProjectFactory
{


    public function __construct()
    {

    }

    private function getContactGroup($cachedOrder, ImportOperation $operation)
    {
        $store_name = $cachedOrder->store_name;

        $group = ContactGroup::find_by_name($store_name);

        if (empty($group))
        {
            $group = new ContactGroup();
            $group->operation_id = $operation->id;
            $group->name = $store_name;
            $group->active = 1;
            $group->save();
        }

        return $group;
    }

    private function getCustomer($cachedOrder, ImportOperation $operation)
    {
        $group = $this->getContactGroup($cachedOrder, $operation);

        $customer = Customer::find_by_email($cachedOrder->customer_email);

        if (empty($customer))
        {
            $customer = new Customer();
            $customer->operation_id = $operation->id;
            $customer->contact_group_id = $group->id;
            $customer->name = $cachedOrder->customer_firstname;
            $customer->lastname = $cachedOrder->customer_lastname;
            $customer->email = $cachedOrder->customer_email;

            if (isset($cachedOrder->customer_dob))
                $customer->dob = $cachedOrder->customer_dob;

            $customer->save();
        }

        return $customer;
    }

    public function processMagentoOrder(MagentoCachedOrder $cachedOrder, ImportOperation $operation)
    {
        $real_order = $cachedOrder->restoreFullMageOrder();

        if (empty($real_order))
            return;

        $project = Project::find_by_magento_server_id_and_external_id($cachedOrder->server_id, $cachedOrder->magento_id);

        if (empty($project))
        {
            $customer = $this->getCustomer($real_order, $operation);

            if (empty($customer))
                return;

            $description = 'Magento Order '. $cachedOrder->magento_id;

            $project = new Project();
            $project->importer_id = 1;
            $project->operation_id = $operation->id;
            $project->external_id = $cachedOrder->magento_id;
            $project->description = $description;
            $project->magento_server_id = $cachedOrder->server_id;
            $project->total;
            $project->save();

            $job = new Job();
            $job->operation_id = $operation->id;
            $job->customer_id = $customer->id;
            $job->description = $description;
            $job->total = $real_order->grand_total;
            $job->external_order_id = $cachedOrder->magento_id;
            $job->tax_total = $real_order->tax_amount;
            $job->shipping_total = $real_order->shipping_amount;
            $job->discount_total = $real_order->discount_amount;
            $job->project_id = $project->id;
            $job->save();

            $items = $real_order->items;

            foreach ($items as $item)
            {
                $jobItem = new JobItem();
                $jobItem->operation_id = $operation->id;
                $jobItem->job_id = $job->id;
                $jobItem->title = $item['name'];
                $jobItem->internal_sku = $item['sku'];
                $jobItem->quantity = $item['qty_ordered'];
                $jobItem->unit_price = $item['price'];

                if (isset($item['description']))
                    $jobItem->description = $item['description'];

                $jobItem->save();
            }

        }

    }
}