<?php

declare(strict_types=1);

namespace Lotsofpixels\Cronhealthmonitor\Cron;


use Exception;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\Validator\Url;

/**
 *
 */
class SendHeartbeat
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

    /**
     * @var LoggerInterface
     */
    protected $logger;


    protected $cronitor;


    /**
     * Data constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param ScopeConfigInterface $storeConfig
     */
    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        ScopeConfigInterface                $storeConfig,
        LoggerInterface                     $logger,
        Url                                 $url
    )
    {
        $this->storeConfig = $storeConfig;
        $this->_curl = $curl;
        $this->logger = $logger;
        $this->url = $url;

    }


    /**
     * @return void
     */
    public function execute(): void
    {
        if ($this->storeConfig->getValue('cronhealth/cronjob/cron_enabled')) {
            $serviceUrl = $this->storeConfig->getValue('cronhealth/cronjob/ping_url');
            $this->_curl->get($serviceUrl);
            $this->_curl->addHeader("Agent", "CronHealthMonitor 1.0");
            $response = $this->_curl->getBody();
        }

    }
}