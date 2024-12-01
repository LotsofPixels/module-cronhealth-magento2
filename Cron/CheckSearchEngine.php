<?php

declare(strict_types=1);

namespace Lotsofpixels\Cronhealthmonitor\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\Validator\Url;

class CheckSearchEngine
{
    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    protected $_curl;

    /**
     * @var ScopeConfigInterface
     */
    protected $storeConfig;

    /**
     * @var Url
     */
    protected $url;

    protected $logger;


    /**
     * Data constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param ScopeConfigInterface $storeConfig
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        ScopeConfigInterface                $storeConfig,
        Url                                 $url,
        LoggerInterface $logger
    )
    {
        $this->storeConfig = $storeConfig;
        $this->_curl = $curl;
        $this->url = $url;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        $port = $this->storeConfig->getValue('cronhealth/seachengine/port_search');
        $url = 'http://localhost:' . $port . '/_cluster/health?pretty';
        $ch = curl_init($url);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = (string)$response;
        if ($response === '1') {
            $serviceUrl1 = $this->storeConfig->getValue('cronhealth/seachengine/ping_url_search');
            $this->_curl->get($serviceUrl1);
        }
    }
}