<?php

if (!function_exists('getConfig')) {
    function getConfig()
    {
        $configModel = new \App\Models\ConfigModel();
        return $configModel->getConfig();
    }
}
