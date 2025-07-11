<?php
declare(strict_types=1);

namespace Brimar\Shipping\Model\Carrier;

use Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions\CollectionFactory;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class Brimar extends AbstractCarrier implements CarrierInterface
{
    protected $_code = 'brimar';
    protected $_isFixed = true;
    
    protected $rateResultFactory;
    protected $rateMethodFactory;
    protected $shippingOptionCollectionFactory;
    protected $checkoutSession;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        CollectionFactory $shippingOptionCollectionFactory,
        Session $checkoutSession,
        array $data = []
    ) {
        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
        $this->shippingOptionCollectionFactory = $shippingOptionCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $result = $this->rateResultFactory->create();
        $method = $this->rateMethodFactory->create();

        // Set basic shipping method info
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title') ?: 'Brimar Shipping');
        $method->setMethod($this->_code);
        
        // Default values
        $methodTitle = $this->getConfigData('name') ?: 'Brimar Shipping';
        $price = 0.00;
        
        // Check if there's a selected option in the quote
        try {
            $quote = $this->checkoutSession->getQuote();
            $brimarOptions = $quote->getBrimarShippingOptions();
            $brimarFee = $quote->getBrimarShippingFee();
            
            if ($brimarOptions && $brimarFee > 0) {
                // Parse selected option
                $selectedOption = json_decode($brimarOptions, true);
                if ($selectedOption && isset($selectedOption['label'])) {
                    $methodTitle = $selectedOption['label'];
                    $price = (float) $brimarFee;
                    
                    $this->_logger->info('Brimar Carrier: Using saved option', [
                        'option' => $selectedOption,
                        'price' => $price
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->error('Brimar Carrier: Session error: ' . $e->getMessage());
        }
        
        $method->setMethodTitle($methodTitle);
        $method->setPrice($price);
        $method->setCost($price);
        
        $this->_logger->info('Brimar Carrier: Rate collected', [
            'method_title' => $methodTitle,
            'price' => $price
        ]);

        $result->append($method);
        return $result;
    }

    public function getAllowedMethods(): array
    {
        return [$this->_code => $this->getConfigData('name') ?: 'Brimar Shipping'];
    }

    public function getShippingOptions(): array
    {
        try {
            $collection = $this->shippingOptionCollectionFactory->create();
            $collection->addActiveFilter()->setSortOrder();

            $options = [];
            foreach ($collection as $option) {
                $options[] = [
                    'code' => $option->getCode(),
                    'label' => $option->getLabel(),
                    'price' => (float) $option->getPrice()
                ];
            }

            return $options;
        } catch (\Exception $e) {
            $this->_logger->error('Brimar Shipping Options Error: ' . $e->getMessage());
            return [];
        }
    }
}