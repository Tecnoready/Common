<?php

namespace Tecnoready\Common\Service\ConfigurationService\Adapter;

interface ScopedConfigurationAdapterInterface extends ConfigurationAdapterInterface
{
    public function findByKeyAndWrapper($key, $wrapperName);
}