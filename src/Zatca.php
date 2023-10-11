<?php

namespace Egyjs\FatooraZatca;

use Egyjs\FatooraZatca\Classes\DocumentType;
use Egyjs\FatooraZatca\Helpers\ConfigHelper;
use Egyjs\FatooraZatca\Objects\Client;
use Egyjs\FatooraZatca\Objects\Invoice;
use Egyjs\FatooraZatca\Objects\Seller;
use Egyjs\FatooraZatca\Objects\Setting;
use Egyjs\FatooraZatca\Services\ReportInvoiceService;
use Egyjs\FatooraZatca\Services\SettingService;

class Zatca
{
    /**
     * generate zatca setting.
     *
     * @param  \Egyjs\FatooraZatca\Objects\Setting $setting
     * @return object
     */
    public static function generateZatcaSetting(Setting $setting): object
    {
        return (new SettingService($setting))->generate();
    }

    /**
     * report standard invoice.
     *
     * @param  \Egyjs\FatooraZatca\Objects\Setting   $seller
     * @param  \Egyjs\FatooraZatca\Objects\Invoice   $invoice
     * @param  \Egyjs\FatooraZatca\Objects\Client    $client
     * @return array
     */
    public static function reportStandardInvoice(Seller $seller, Invoice $invoice, Client $client): array
    {
        if(ConfigHelper::isProduction()) {
            return (new ReportInvoiceService($seller, $invoice, $client))->clearance();
        }
        else {
            return (new ReportInvoiceService($seller, $invoice, $client))->test(DocumentType::STANDARD);
        }
    }

    /**
     * report simplified invoice.
     *
     * @param  \Egyjs\FatooraZatca\Objects\Setting   $seller
     * @param  \Egyjs\FatooraZatca\Objects\Invoice   $invoice
     * @param  \Egyjs\FatooraZatca\Objects\Client    $client
     * @return array
     */
    public static function reportSimplifiedInvoice(Seller $seller, Invoice $invoice, Client $client = null): array
    {
        if(ConfigHelper::isProduction()) {
            return (new ReportInvoiceService($seller, $invoice, $client))->reporting();
        }
        else {
            return (new ReportInvoiceService($seller, $invoice, $client))->test(DocumentType::SIMPILIFIED);
        }
    }

    /**
     * calculate simplified invoice.
     *
     * @param  \Egyjs\FatooraZatca\Objects\Setting   $seller
     * @param  \Egyjs\FatooraZatca\Objects\Invoice   $invoice
     * @param  \Egyjs\FatooraZatca\Objects\Client    $client
     * @return array
     */
    public static function calculateSimplifiedInvoice(Seller $seller, Invoice $invoice, Client $client = null): array
    {
        return (new ReportInvoiceService($seller, $invoice, $client))->calculate(DocumentType::SIMPILIFIED);
    }
}
