<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class DocumentsService
{
    use ConsumeExternalService, CoreService;

    public function getList($document)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/list', $this->request);
    }

    public function getTable($document)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/table', $this->request);
    }

    public function updateHeader($document)
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/' . $document . '/headers', $this->request);
    }

    public function updateDocumentNomenclaturesHeader()
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/document-nomenclatures-headers', $this->request);
    }

    public function updateDocumentWarehousesHeader()
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/document-warehouses-headers', $this->request);
    }

    public function updateDocumentAllHeader()
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/document-all-headers', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST', 'api/v1/documents/toArchive', $this->request);
    }

    public function storeDocument($document)
    {
        return $this->performRequestForm('POST', 'api/v1/documents/' . $document . '/store-document', $this->request);
    }

    public function storeCapitalizedDocument($document)
    {
        return $this->performRequestForm('POST', 'api/v1/documents/' . $document . '/store-capitalized-document', $this->request);
    }

    public function updateDocument($document, $id)
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/' . $document . '/update-document/' . $id, $this->request);
    }

    public function capitalizedDocument($document, $id)
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/' . $document . '/capitalized-document/' . $id, $this->request);
    }

    public function canceledCapitalizedDocument($document, $id)
    {
        return $this->performRequestForm('PUT', 'api/v1/documents/' . $document . '/canceled-capitalized-document/' . $id, $this->request);
    }

    public function getWarehouseDocuments($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/get-warehouse-documents/' . $id, $this->request);
    }

    public function getWarehouseDocumentsAll()
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/get-warehouse-documents-all', $this->request);
    }

    public function getDocumentsAll()
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/get-documents-all', $this->request);
    }

    public function getDocumentTable($document, $id)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/get-document/'. $id , $this->request);
    }

    public function getSelectDocumentTable($document, $id)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/get-select-document/'. $id , $this->request);
    }

    //nomenclatures add checkout (orders)
    public function openBasket($document)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/open-basket', $this->request);
    }

    public function generalCountBasket($document)
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/' . $document . '/general-count-basket', $this->request);
    }

    public function getActivityPurchases()
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/purchases/get-activity-purchases', $this->request);
    }

    public function getShipmentOrders()
    {
        return $this->performRequestQuery('GET', 'api/v1/documents/orders/get-shipment-orders', $this->request);
    }

    public function updateNomenclaturesInBasket($document)
    {
        return $this->performRequestForm('POST', 'api/v1/documents/' . $document . '/update-nomenclatures-in-basket', $this->request);
    }

    public function clearBasket($document)
    {
        return $this->performRequestForm('DELETE', 'api/v1/documents/' . $document . '/clear-basket', $this->request);
    }
}
