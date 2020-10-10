<?php
/**
 * AccessLevel
*/
class ShopifyCxmlTransfer extends ActiveRecord\Model {

  	static $table_name = 'shopify_cxml_transfers';

    static $belongs_to = array(
        array('receiver', 'class_name' => 'ShopifyCxmlReceiver', 'foreign_key' => 'receiver_id'),
        array('order', 'class_name' => 'ShopifyCachedOrder', 'foreign_key' => 'order_id')
    );

    public function attempt()
    {
        $server = $this->receiver->server;
        $username = $this->receiver->username;
        $password = $this->receiver->password;

        $filename = $this->receiver->consumer->uri.'_'.$this->order->external_id.".cxml";

        $file = Quantum\File::createTempFile($filename, $this->payload);

        $conn_id = ftp_connect($server);

        $login_result = ftp_login($conn_id, $username, $password);

        if ($login_result)
        {
            if (ftp_put($conn_id, "orders/shopify/".$filename, $file, FTP_ASCII))
            {
                $this->delivered = 1;
                $this->save();
            }
            else
            {
                $error = error_get_last();

                if (!empty($error))
                {
                    if (isset($error['message']))
                    {
                        $this->response = $error['message'];
                        $this->save();

                        Quantum\Mailer::notifyCreator('cxml ftp transfer error', json_encode($error));
                    }
                }
            }

            ftp_close($conn_id);
        }
        else
        {
            $this->response = "Invalid login";
            $this->save();
        }

        $file->delete();
    }




}

?>