<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class AbstractApiController extends AbstractFOSRestController
{
    /**
     * Encrypt the provided data
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @param string $method
     * @return string
     */
    public static function encrypt(string $data, string $key, string $iv, $method = 'AES-256-CFB'): string
    {
        return openssl_encrypt($data, $method, $key, 0, $iv);
    }

    /**
     * Decrypt the provided data
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @param string $method
     * @return string
     */
    public static function decrypt(string $data, string $key, string $iv, $method = 'AES-256-CFB'): string
    {
        return openssl_decrypt($data, $method, $key, 0, $iv);
    }
}