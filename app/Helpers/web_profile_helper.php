<?php

use App\Models\WebProfileModel;

if (!function_exists('get_web_profile')) {
    /**
     * Mengambil data profil website.
     * Menggunakan static variable untuk caching sederhana per request.
     *
     * @return array
     */
    function get_web_profile(): array
    {
        static $profile = null;

        if ($profile === null) {
            $webProfileModel = new WebProfileModel();
            $profile = $webProfileModel->find(1) ?? [];
        }

        return $profile;
    }
}