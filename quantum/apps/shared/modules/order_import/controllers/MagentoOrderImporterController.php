<?

/*
 * class ExampleModuleController
 */

namespace OrderImport;


class MagentoOrderImporterController extends \Quantum\HMVC\ModuleController
{

    /**
     * Create a controller, no dependency injection has happened.
     */
    function __construct()
    {

    }

    /**
     * Called after dependency injection, all environment variables are ready.
     */
    protected function __post_construct()
    {

    }

    /**
     * Called before calling the main controller action, all environment variables are ready.
     */
    protected function __pre_dispatch()
    {
    }

    /**
     * Called after calling the main controller action, all vars set by controller are ready.
     */
    protected function __post_dispatch()
    {

    }

    /**
     * Called after calling the main controller action, before calling Quantum\Output::render
     */
    protected function __pre_render()
    {

    }

    /**
     * Called after calling Quantum\Output::render
     */
    protected function __post_render()
    {

    }


    /**
     * Public: index
     */
    public function index()
    {
        $this->setAutoRender(false);

        $this->import_magento_orders();

        //$this->create_projects_and_jobs();


    }

    public function index2()
    {
        $this->setAutoRender(false);

        //$this->import_magento_orders();

        //$this->create_projects_and_jobs();

        $cached_order = MagentoCachedOrder::last();

        pre($cached_order->restoreFullMageOrder());


    }

    public function import_magento_orders()
    {

        $this->setAutoRender(false);

        \Quantum\Logger::custom('Running import_magento_orders for app: '.\QM::config()->getActiveAppName(), "cron_exec");

        $last_fetch_date = \AppSettings::get('oim_last_magento_order_fetch', '2019-01-01 00:00:00');

        $date = new \DateTime("now", new \DateTimeZone("UTC"));
        $now =  $date->format('Y-m-d H:i:s');

        $orders = MagentoImporter::fetchOrdersFromAllServers($last_fetch_date, $now);

        $orders_count = count($orders);

        if ($orders_count > 0)
        {
            \AppSettings::set('oim_last_magento_order_fetch', $now);
            \Quantum\Mailer::notifyCreator('Magento Import finished',"Imported $orders_count orders from: $last_fetch_date, to $now");

            \Quantum\Logger::custom("Imported $orders_count orders from: $last_fetch_date, to $now", 'import_magento_orders');
        }
        else
        {
            \Quantum\Logger::custom("No new orders found from: $last_fetch_date, to $now", 'import_magento_orders');
        }

        echo "run import magento orders";

    }

    public function create_projects_and_jobs()
    {

        $orders = MagentoCachedOrder::find_all_by_imported(0);

        foreach ($orders as $order)
        {
            $order->createProject();
            $order->imported  = 1;
            $order->save();
        }


    }





}

?>