<?php

/*
 * Model for reading configuration files.
 */


class ModelConfiguration {

    private $configurationData;


    public function __construct($configurationFile) {
        $this->configurationData = parse_ini_file($configurationFile, true);
    }


    public function getConfigurationData() {
        return $this->configurationData;
    }


    public function getValue($section, $key) {
        $value = null;
        if (isset($this->configurationData[$section][$key])) {
            $value = $this->configurationData[$section][$key];
        }
        return $value;
    }

}

?>