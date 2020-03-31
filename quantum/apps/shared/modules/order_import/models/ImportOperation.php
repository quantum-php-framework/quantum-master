<?php

namespace OrderImport;

class ImportOperation extends \Quantum\ActiverecordModel
{
    static $table_name = 'oim_import_operations';


    public function countImportedRecords()
    {
        $projects_count = count(Project::find_all_by_operation_id($this->id));
        $jobs_count = count(Job::find_all_by_operation_id($this->id));
        $items_count = count(JobItem::find_all_by_operation_id($this->id));
        $customers_count = count(Customer::find_all_by_operation_id($this->id));

        $total_records_count = $projects_count + $jobs_count + $items_count + $customers_count;

        $this->total_records_count = $total_records_count;
        $this->projects_count = $projects_count;
        $this->jobs_count = $jobs_count;
        $this->customers_count = $customers_count;
        $this->save();

    }


}