<?php


namespace Quantum\Plugins;


class PluginFolder extends \Quantum\File
{
    private $plugin_entry_headers;

    public function __construct($path)
    {
        parent::__construct($path->path);
    }

    public function getPluginEntryFile()
    {
        return $this->getChildFile('Plugin.php')->getRealPath();
    }

    public function isValid()
    {
        $valid = qf($this->getPluginEntryFile())->existsAsFile();

        return $valid;
    }

    public function getPluginEntryHeader($key, $fallback = false)
    {
        $headers = $this->getPluginEntryHeaders();

        return $headers->get($key, $fallback);
    }


    public function getPluginEntryHeaders()
    {
        if (isset($this->plugin_entry_headers)) {
            return $this->plugin_entry_headers;
        }

        $file = $this->getPluginEntryFile();
        $default_headers = array(
            'name'        => 'Plugin Name',
            'resource_url'   => 'Plugin URI',
            'version'     => 'Version',
            'description' => 'Description',
            'author'      => 'Author',
            'author_url'   => 'Author URI',
            'text_domain'  => 'Text Domain',
            'domain_path'  => 'Domain Path',
            'network'     => 'Network',
            'min_qm_version'  => 'Requires at least',
            'min_php_version' => 'Requires PHP',
            'namespace' => 'Namespace'
        );

        $fp = fopen( $file, 'r' );

        $file_data = fread( $fp, 8 * 1024);

        fclose( $fp );

        $file_data = str_replace( "\r", "\n", $file_data );

        $all_headers = $default_headers;

        foreach ( $all_headers as $field => $regex ) {
            if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] ) {
                $all_headers[ $field ] = $this->cleanupHeaderComment( $match[1] );
            } else {
                $all_headers[ $field ] = '';
            }
        }

        $this->plugin_entry_headers = new_vt($all_headers);

        return $this->plugin_entry_headers;
    }

    private function cleanupHeaderComment( $str )
    {
        return trim( preg_replace( '/\s*(?:\*\/|\?>).*/', '', $str ) );
    }

}