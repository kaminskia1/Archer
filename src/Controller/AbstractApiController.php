<?php

namespace App\Controller;


use App\Entity\Core\CoreModule;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class AbstractApiController extends AbstractFOSRestController
{

    /**
     * AbstractApiController constructor.
     */
    public function __construct()
    {
        if (!$GLOBALS['kernel']
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(CoreModule::class)
            ->isModuleLoaded('API')) {
            throw new ResourceNotFoundException("The global API is currently disabled");
        }

    }

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