<?php

namespace AutoRestApi;

use Quantum\ValueTree;

class VersionsManager
{
    /**
     * @var ValueTree
     */
    private $versions;

    public function __construct($versions_file, $plugin_folder)
    {
        $declared_versions = include $versions_file->getRealPath();

        $this->versions = new_vt();
        foreach ($declared_versions as $declared_version)
        {
            $models_file_name = qs($declared_version['models_file'])->ensureRight('.php')->toStdString();
            $models_file = $plugin_folder->getChildFile('etc/config/versions/'.$models_file_name);

            if (!$models_file->existsAsFile()) {
                throw_exception('api version models file not found at:'.$models_file->getPath());
            }

            $this->versions->set('version'.$declared_version['version'], new ApiVersion($declared_version, $models_file));
        }
    }

    public function getVersions()
    {
        return $this->versions;
    }

    public function getVersion($version)
    {
        if ($this->versions->has('version'.$version)) {
            return $this->versions->get('version'.$version);
        }

        return false;
    }
}