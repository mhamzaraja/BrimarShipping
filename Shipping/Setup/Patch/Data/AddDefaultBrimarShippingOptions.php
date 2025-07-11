<?php
declare(strict_types=1);

namespace Brimar\Shipping\Setup\Patch\Data;

use Brimar\Shipping\Api\BrimarShippingOptionsRepositoryInterface;
use Brimar\Shipping\Api\Data\BrimarShippingOptionsInterfaceFactory;
use Brimar\Shipping\Model\ResourceModel\BrimarShippingOptions\CollectionFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class AddDefaultBrimarShippingOptions implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private BrimarShippingOptionsInterfaceFactory $brimarShippingOptionsFactory;
    private BrimarShippingOptionsRepositoryInterface $brimarShippingOptionsRepository;
    private CollectionFactory $collectionFactory;
    private LoggerInterface $logger;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BrimarShippingOptionsInterfaceFactory $brimarShippingOptionsFactory,
        BrimarShippingOptionsRepositoryInterface $brimarShippingOptionsRepository,
        CollectionFactory $collectionFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->brimarShippingOptionsFactory = $brimarShippingOptionsFactory;
        $this->brimarShippingOptionsRepository = $brimarShippingOptionsRepository;
        $this->collectionFactory = $collectionFactory;
        $this->logger = $logger;
    }

    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $defaultOptions = [
            [
                'code' => 'residential',
                'label' => 'Residential Delivery',
                'price' => 5.00,
                'is_active' => 1,
            ],
            [
                'code' => 'scheduled',
                'label' => 'Scheduled Delivery',
                'price' => 10.00,
                'is_active' => 1,
            ]
        ];

        // Get existing codes to prevent duplicates
        $collection = $this->collectionFactory->create();
        $existingCodes = $collection->getColumnValues('code');

        foreach ($defaultOptions as $optionData) {
            // Skip if option already exists
            if (in_array($optionData['code'], $existingCodes)) {
                $this->logger->info('Brimar Shipping: Option already exists, skipping: ' . $optionData['code']);
                continue;
            }

            try {
                $option = $this->brimarShippingOptionsFactory->create();
                $option->setCode($optionData['code']);
                $option->setLabel($optionData['label']);
                $option->setPrice($optionData['price']);
                $option->setIsActive($optionData['is_active']);
                
                $this->brimarShippingOptionsRepository->save($option);
                
                $this->logger->info('Brimar Shipping: Created default option: ' . $optionData['code']);
            } catch (\Exception $e) {
                $this->logger->error('Brimar Shipping: Failed to create option ' . $optionData['code'] . ': ' . $e->getMessage());
            }
        }

        $this->moduleDataSetup->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}