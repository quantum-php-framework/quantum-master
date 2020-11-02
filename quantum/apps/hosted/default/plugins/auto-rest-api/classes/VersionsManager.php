<?php

namespace AutoRestApi;

use Quantum\ValueTree;
use Quantum\File;

class VersionsManager
{
    /**
     * @var ValueTree
     */
    private $versions;

    public function __construct(File $versions_folder)
    {
        $version_files = $versions_folder->getChildFiles();

        if (empty($version_files)) {
            throw_exception('no api version files found at:'.$versions_folder->getPath());
        }

        $this->versions = new_vt();

        foreach ($version_files as $version_file)
        {
            $declared_version = include $version_file->getRealPath();

            $this->versions->set('version'.$declared_version['version'], new ApiVersion($declared_version));
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