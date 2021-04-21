<?php
//bu class yarım bırakıldı devamını getirisin istersen


/**
 * Class DopigoCustomer
 * account_type:2 seçenek var person,company string olacak yani şahıs yada tüzel kişi
 */
class DopigoCustomer
{
    //<editor-fold desc="getters setters">
    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->_account_type;
    }

    /**
     * @param string $account_type
     */
    public function setAccountType($account_type)
    {
        $this->_account_type = $account_type;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->_full_name;
    }

    /**
     * @param string $full_name
     */
    public function setFullName($full_name)
    {
        $this->_full_name = $full_name;
    }

    /**
     * @return DopigoAddress | null
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * @param DopigoAddress | null $address
     */
    public function setAddress($address)
    {
        $this->_address = $address;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->_phone_number;
    }

    /**
     * @param string $phone_number
     */
    public function setPhoneNumber($phone_number)
    {
        $this->_phone_number = $phone_number;
    }

    /**
     * @return int long int64 $citizen_id
     */
    public function getCitizenId()
    {
        return $this->_citizen_id;
    }

    /**
     * @param int long int64 $citizen_id
     */
    public function setCitizenId($citizen_id)
    {
        $this->_citizen_id = $citizen_id;
    }

    /**
     * @return int
     */
    public function getTaxId()
    {
        return $this->_tax_id;
    }

    /**
     * @param int $tax_id
     */
    public function setTaxId($tax_id)
    {
        $this->_tax_id = $tax_id;
    }

    /**
     * @return string
     */
    public function getTaxOffice()
    {
        return $this->_tax_office;
    }

    /**
     * @param string $tax_office
     */
    public function setTaxOffice($tax_office)
    {
        $this->_tax_office = $tax_office;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->_company_name;
    }

    /**
     * @param string $company_name
     */
    public function setCompanyName($company_name)
    {
        $this->_company_name = $company_name;
    }

    //</editor-fold>


    /**
     * @return stdClass olumlu ise raw customer döndürür olumsuz ise hata sebebini döndürür
     */
    public function push_customer_to_dopigo()
    {
        include_once "dopigo.php";
        return (new dopigo())->createCustomer($this);
    }

    /**
     * @return bool customer dopigoda var mı ?
     */
    public function dopigo_check_user_exists()
    {
        include_once "dopigo.php";
        $dopigo = new dopigo();
        $rawCustomer = $dopigo->checkCustomer($this->_id);
        if (isset($rawCustomer->id)) {
            return true;
        }
        return false;
    }

    private $_id, $_account_type, $_full_name, $_address, $_email, $_phone_number, $_citizen_id, $_tax_id, $_tax_office,
        $_company_name;

    /**
     * DopigoCustomer constructor.
     * @param int $_id :müşteri id
     * @param string $_account_type :{'person':'şahıs','company':'şirket(tüzel kişi)'} keylerden biri olmak zorunda
     * @param string $_full_name : tam ad
     * @param DopigoAddress|null $_address : class object
     * @param string $_email : posta adresi
     * @param string $_phone_number :telefon no
     * @param int $_citizen_id :tc kimlik no
     * @param int $_tax_id : vergi no
     * @param string $_tax_office :vergi dairesi
     * @param string $_company_name : şirket adı
     */
    public function __construct($_id = 0, $_account_type = "company", $_full_name = "", DopigoAddress $_address = null,
                                $_email = "", $_phone_number = "", $_citizen_id = 0, $_tax_id = 0, $_tax_office = "",
                                $_company_name = "")
    {
        $this->_id = $_id;
        $this->_account_type = $_account_type;
        $this->_full_name = $_full_name;
        $this->_address = $_address;
        $this->_email = $_email;
        $this->_phone_number = $_phone_number;
        $this->_citizen_id = $_citizen_id;
        $this->_tax_id = $_tax_id;
        $this->_tax_office = $_tax_office;
        $this->_company_name = $_company_name;
    }

}

/**
 * Class DopigoBillingAddress: Esasen standart address base from dopigo address
 */
class DopigoBillingAddress extends DopigoAddress
{
    public function __construct($_id = 0, $_full_address = "", $_contact_full_name = "", $_contact_phone_number = null, $_city = "", $_district = "", $_zip_code = "")
    {
        parent::__construct($_id, $_full_address, $_contact_full_name, $_contact_phone_number, $_city, $_district, $_zip_code);

    }
}

/**
 * Class DopigoBillingAddress: Esasen standart address base from dopigo address
 */
class DopigoShippingAddress extends DopigoAddress
{
    public function __construct($_id = 0, $_full_address = "", $_contact_full_name = "", $_contact_phone_number = null, $_city = "", $_district = "", $_zip_code = "")
    {
        parent::__construct($_id, $_full_address, $_contact_full_name, $_contact_phone_number, $_city, $_district, $_zip_code);

    }
}

/**
 * Class DopigoAddress base for shipping | billing addresses
 */
class DopigoAddress
{
    //<editor-fold desc="getters setters">
    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getFullAddress()
    {
        return $this->_full_address;
    }

    /**
     * @param string $full_address
     */
    public function setFullAddress($full_address)
    {
        $this->_full_address = $full_address;
    }

    /**
     * @return string
     */
    public function getContactFullName()
    {
        return $this->_contact_full_name;
    }

    /**
     * @param string $contact_full_name
     */
    public function setContactFullName($contact_full_name)
    {
        $this->_contact_full_name = $contact_full_name;
    }

    /**
     * @return string|null
     */
    public function getContactPhoneNumber()
    {
        return $this->_contact_phone_number;
    }

    /**
     * @param string|null $contact_phone_number
     */
    public function setContactPhoneNumber($contact_phone_number)
    {
        $this->_contact_phone_number = $contact_phone_number;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->_city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->_city = $city;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->_district;
    }

    /**
     * @param string $district
     */
    public function setDistrict($district)
    {
        $this->_district = $district;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->_zip_code;
    }

    /**
     * @param string $zip_code
     */
    public function setZipCode($zip_code)
    {
        $this->_zip_code = $zip_code;
    }
    //</editor-fold>


    /**
     * DopigoAddress constructor.
     * @param int $_id :adres id
     * @param string $_full_address :açık adres tamamıyla en detaylı
     * @param string $_contact_full_name :ad ve soyad
     * @param null | string $_contact_phone_number :telefon numarası
     * @param string $_city :şehir
     * @param string $_district :eyalet
     * @param string $_zip_code :posta kodu
     */
    public function __construct($_id = 0, $_full_address = "", $_contact_full_name = "", $_contact_phone_number = null, $_city = "",
                                $_district = "", $_zip_code = "")
    {
        $this->_id = $_id;
        $this->_full_address = $_full_address;
        $this->_contact_full_name = $_contact_full_name;
        $this->_contact_phone_number = $_contact_phone_number;
        $this->_city = $_city;
        $this->_district = $_district;
        $this->_zip_code = $_zip_code;
    }

    private $_id, $_full_address, $_contact_full_name, $_contact_phone_number, $_city, $_district, $_zip_code;
}


/**
 * Class DopigoOrder
 */
class DopigoOrder
{
    //<editor-fold desc="getters setters">
    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     * @param int $service
     */
    public function setService($service)
    {
        $this->_service = $service;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->_service_name;
    }

    /**
     * @param string $service_name
     */
    public function setServiceName($service_name)
    {
        $this->_service_name = $service_name;
    }

    /**
     * @return string
     */
    public function getServiceLogo()
    {
        return $this->_service_logo;
    }

    /**
     * @param string $service_logo
     */
    public function setServiceLogo($service_logo)
    {
        $this->_service_logo = $service_logo;
    }

    /**
     * @return string
     */
    public function getSalesChannel()
    {
        return $this->_sales_channel;
    }

    /**
     * @param string $sales_channel
     */
    public function setSalesChannel($sales_channel)
    {
        $this->_sales_channel = $sales_channel;
    }

    /**
     * @return null| DateTime
     */
    public function getServiceCreated()
    {
        return $this->_service_created;
    }

    /**
     * @param null| DateTime $service_created
     */
    public function setServiceCreated($service_created)
    {
        $this->_service_created = $service_created;
    }

    /**
     * @return null|string
     */
    public function getServiceValue()
    {
        return $this->_service_value;
    }

    /**
     * @param null|string $service_value
     */
    public function setServiceValue($service_value)
    {
        $this->_service_value = $service_value;
    }

    /**
     * @return null|string
     */
    public function getServiceOrderId()
    {
        return $this->_service_order_id;
    }

    /**
     * @param null|string $service_order_id
     */
    public function setServiceOrderId($service_order_id)
    {
        $this->_service_order_id = $service_order_id;
    }

    /**
     * @return null | string
     */
    public function getProducts()
    {
        return $this->_products;
    }

    /**
     * @param null | string $products
     */
    public function setProducts($products)
    {
        $this->_products = $products;
    }

    /**
     * @return null | DopigoCustomer
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * null izin verdim ama bunu sakın null girme hacı bunu customer oluşturmak içinde kullanacan zira
     * @param null | DopigoCustomer $customer
     */
    public function setCustomer($customer)
    {
        $this->_customer = $customer;
    }

    /**
     * @return null | DopigoBillingAddress
     */
    public function getBillingAddress()
    {
        return $this->_billing_address;
    }

    /**
     * @param null | DopigoBillingAddress $billing_address
     */
    public function setBillingAddress($billing_address)
    {
        $this->_billing_address = $billing_address;
    }

    /**
     * @return null | DopigoShippingAddress
     */
    public function getShippingAddress()
    {
        return $this->_shipping_address;
    }

    /**
     * @param null | DopigoShippingAddress $shipping_address
     */
    public function setShippingAddress($shipping_address)
    {
        $this->_shipping_address = $shipping_address;
    }

    /**
     * @return null | DateTime
     */
    public function getShippedDate()
    {
        return $this->_shipped_date;
    }

    /**
     * @param null | DateTime $shipped_date
     */
    public function setShippedDate($shipped_date)
    {
        $this->_shipped_date = $shipped_date;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->_payment_type;
    }

    /**
     * @param string $payment_type
     */
    public function setPaymentType($payment_type)
    {
        $this->_payment_type = $payment_type;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * @return float | null
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * @param float | null $total
     */
    public function setTotal($total)
    {
        $this->_total = $total;
    }

    /**
     * @return null | float
     */
    public function getServiceFee()
    {
        return $this->_service_fee;
    }

    /**
     * @param null | float $service_fee
     */
    public function setServiceFee($service_fee)
    {
        $this->_service_fee = $service_fee;
    }

    /**
     * @return float | null
     */
    public function getDiscount()
    {
        return $this->_discount;
    }

    /**
     * @param float | null $discount
     */
    public function setDiscount($discount)
    {
        $this->_discount = $discount;
    }

    /**
     * @return boolean
     */
    public function getArchived()
    {
        return $this->_archived;
    }

    /**
     * @param boolean $archived
     */
    public function setArchived($archived)
    {
        $this->_archived = $archived;
    }

    /**
     * @return null | string
     */
    public function getNotes()
    {
        return $this->_notes;
    }

    /**
     * @param null | string $notes
     */
    public function setNotes($notes)
    {
        $this->_notes = $notes;
    }

    /**
     * @return null | array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param null | array $items
     */
    public function setItems($items)
    {
        $this->_items = $items;
    }

    /**
     * @return null | array
     */
    public function getTransactions()
    {
        return $this->_transactions;
    }

    /**
     * @param null | array $transactions
     */
    public function setTransactions($transactions)
    {
        $this->_transactions = $transactions;
    }

    /**
     * @return null | string
     */
    public function getInvoiceNumber()
    {
        return $this->_invoice_number;
    }

    /**
     * @param null | string $invoice_number
     */
    public function setInvoiceNumber($invoice_number)
    {
        $this->_invoice_number = $invoice_number;
    }

    /**
     * @return null | DateTime
     */
    public function getInvoiceCreated()
    {
        return $this->_invoice_created;
    }

    /**
     * @param null | DateTime $invoice_created
     */
    public function setInvoiceCreated($invoice_created)
    {
        $this->_invoice_created = $invoice_created;
    }

    /**
     * @return string | null
     */
    public function getInvoiceType()
    {
        return $this->_invoice_type;
    }

    /**
     * @param string | null $invoice_type
     */
    public function setInvoiceType($invoice_type)
    {
        $this->_invoice_type = $invoice_type;
    }

    /**
     * @return float | null
     */
    public function getInvoiceVatTotal()
    {
        return $this->_invoice_vat_total;
    }

    /**
     * @param float | null $invoice_vat_total
     */
    public function setInvoiceVatTotal($invoice_vat_total)
    {
        $this->_invoice_vat_total = $invoice_vat_total;
    }

    /**
     * @return float | null
     */
    public function getInvoiceGrandTotal()
    {
        return $this->_invoice_grand_total;
    }

    /**
     * @param float | null $invoice_grand_total
     */
    public function setInvoiceGrandTotal($invoice_grand_total)
    {
        $this->_invoice_grand_total = $invoice_grand_total;
    }

    /**
     * @return null | DateTime
     */
    public function getInvoiceDeleted()
    {
        return $this->_invoice_deleted;
    }

    /**
     * @param null | boolean $invoice_deleted
     */
    public function setInvoiceDeleted($invoice_deleted)
    {
        $this->_invoice_deleted = $invoice_deleted;
    }

    //</editor-fold>

    /**
     * @return array | null hata fırlatabilir id durumunu kontrol etmelisin
     * 2.olarak da dopigoda customer'ın önceden oluşup oluşmadığı kontrol edilmelidir aksi halde
     * 'İlgili Customer Henüz Oluşturulmamış Dopigo Tarafında Customer Olmaksızın Sipariş Oluşturulamaz' hatası alınır
     */
    public function push_order_to_dopigo()
    {
        if (isset($this->_id) && $this->getId() !== 0) {
            include_once "dopigo.php";

            //<editor-fold desc="order-items">
            $casted_items = array();// casted to array
            if (isset($this->_items) && count($this->getItems()) > 0) {

                foreach ($this->getItems() as $item) {
                    if (is_a($item, "DopigoOrderItem")) {
                        $casted_items[] = array(
                            'id' => $item->getId(),
                            'order' => $item->getOrder(),
                            'service_item_id' => $item->getServiceItemId(),
                            'service_product_id' => $item->getServiceProductId(),
                            'service_shipment_code' => $item->getServiceShipmentCode(),
                            'sku' => $item->getSku(),
                            'attributes' => $item->getAtributes(),
                            'name' => $item->getName(),
                            'amount' => $item->getAmount(),
                            'price' => $item->getPrice(),
                            'unit_price' => $item->getUnitPrice(),
                            'shipment' => $item->getShipment(),
                            'shipment_campaign_code' => $item->getShipmentCampaignCode(),
                            'buyer_pays_shipment' => $item->isBuyerPaysShipment(),
                            'status' => $item->getStatus(),
                            'shipment_provider' => $item->getShipmnetProvider(),
                            'tax_ratio' => $item->getTaxRatio(),
                            'product' => array(
                                'id' => $item->getProduct()->getId(),
                                'sku' => $item->getProduct()->getSku(),
                                'foreign_sku' => $item->getProduct()->getForeignSku()
                            ),
                            'linked_product' => array(
                                'id' => $item->getLinkedProduct()->getId(),
                                'sku' => $item->getLinkedProduct()->getSku(),
                                'foreign_sku' => $item->getLinkedProduct()->getForeignSku()
                            )
                        );
                    }
                }
            }
            //</editor-fold>

            if (!$this->getCustomer()->dopigo_check_user_exists())
                throw new Exception("İlgili Customer Henüz Oluşturulmamış Dopigo Tarafında Customer Olmaksızın Sipariş Oluşturulamaz");

            return (new dopigo())->createOrder(
                $this->getId(),
                $this->getService(),
                $this->getServiceValue(),
                array(
                    'id' => $this->getCustomer()->getId(),
                    'account_type' => $this->getCustomer()->getAccountType(),
                    'full_name' => $this->getCustomer()->getFullName(),
                    'address' => array(
                        'id' => $this->getCustomer()->getAddress()->getId(),
                        'full_address' => $this->getCustomer()->getAddress()->getFullAddress(),
                        'contact_full_name' => $this->getCustomer()->getAddress()->getContactFullName(),
                        'contact_phone_number' => $this->getCustomer()->getAddress()->getContactPhoneNumber(),
                        'city' => $this->getCustomer()->getAddress()->getCity(),
                        'district' => $this->getCustomer()->getAddress()->getDistrict(),
                        'zip_code' => $this->getCustomer()->getAddress()->getZipCode()
                    ),
                    'email' => $this->getCustomer()->getEmail(),
                    'phone_number' => $this->getCustomer()->getPhoneNumber(),
                    'citizen_id' => $this->getCustomer()->getCitizenId(),
                    'tax_id' => $this->getCustomer()->getTaxId(),
                    'tax_office' => $this->getCustomer()->getTaxOffice(),
                    'company_name' => $this->getCustomer()->getTaxOffice()
                ),//customer şayet önceden dopigoda bulunmuyor ise önce oluşturulmalıdır
                $this->getServiceName(),
                $this->getSalesChannel(),
                $this->getServiceCreated(),
                $this->getServiceOrderId(),
                $this->getProducts(),//string
                array(
                    'id' => $this->getBillingAddress()->getId(),
                    'full_address' => $this->getBillingAddress()->getFullAddress(),
                    'contact_full_name' => $this->getBillingAddress()->getContactFullName(),
                    'contact_phone_number' => $this->getBillingAddress()->getContactPhoneNumber(),
                    'city' => $this->getBillingAddress()->getCity(),
                    'district' => $this->getBillingAddress()->getDistrict(),
                    'zip_code' => $this->getBillingAddress()->getZipCode()
                ),
                array(
                    'id' => $this->getShippingAddress()->getId(),
                    'full_address' => $this->getShippingAddress()->getFullAddress(),
                    'contact_full_name' => $this->getShippingAddress()->getContactFullName(),
                    'contact_phone_number' => $this->getShippingAddress()->getContactPhoneNumber(),
                    'city' => $this->getShippingAddress()->getCity(),
                    'district' => $this->getShippingAddress()->getDistrict(),
                    'zip_code' => $this->getShippingAddress()->getZipCode()
                ),
                $this->getShippedDate(),
                $this->getPaymentType(),
                $this->getStatus(),
                $this->getTotal(),
                $this->getServiceFee(),
                $this->getDiscount(),
                $this->getArchived(),
                $this->getNotes(),
                $casted_items,
                $this->getInvoiceNumber(),
                $this->getInvoiceCreated(),
                $this->getInvoiceType(),
                $this->getInvoiceVatTotal(),
                $this->getInvoiceGrandTotal(),
                $this->getInvoiceDeleted()
            );
        }
        throw new \http\Exception\InvalidArgumentException("Order id değeri önceden verilmelidir.");
    }


    /**
     * DopigoOrder constructor.
     * @param int $_id :dopigo order id
     * @param int $_service : siparişin alındığı kanal örneğin wordpress yada trendyol id no su
     * @param string $_service_name :siparişin alındığı kanal örneğin wordpress yada trendyol adı
     * @param string $_service_logo : url adresi ödeme kanalının logosu
     * @param string $_sales_channel : service_name ile aynı satış kanalı
     * @param null| DateTime $_service_created : order create time diyebiliriz
     * @param null|string $_service_value :ödeme servisi tarafından oluşturulmuş özel kod
     * @param null|string $_service_order_id :Sipariş numarası
     * @param null| string $_products :ürünler örnek products: "1 X (6953156257344) Gravity Araç Içi Telefon Tutucu
     * SUYL-01,
     * one size<br>", yani harbiden string
     * @param null|DopigoCustomer $_customer :müşteri class object
     * @param null | DopigoBillingAddress $_billing_address address class object
     * @param null | DopigoShippingAddress $_shipping_address address class object
     * @param null  | DateTime $_shipped_date :kargolanma zamanı
     * @param null | string $_payment_type :{'cc':'Kredi Kartı','bonus_pay':'Bonus Pay','bkm':'BKM','n11_points':'N11 Puanı',
     * 'cash':'nakit','undefined':'belirtilmemiş'}
     * @param string $_status : {'pending':'onay bekliyor','waiting_shipment':'Kargolanmayı Bekliyor',
     * 'shipped':'kargolandı','cancelled':'iptal edilmiş','undefined':'tanımlanmamış'}
     * @param float | null $_total :Toplam
     * @param float | null $_service_fee :Kargo Hizmet Bedeli
     * @param float | null $_discount :indirim
     * @param bool $_archived :arşivlendimi ?
     * @param string | null $_notes : notlar kullanıcı veya işyeri tarafından girilebilir sipariş notları
     * @param null| array $_items : class object array
     * @param null | array $_transactions : class object array
     * @param null | string $_invoice_number : fatura no
     * @param null | DateTime $_invoice_created : fatura oluşma zamanı
     * @param null | string $_invoice_type :fatura tipi string
     * @param float | null $_invoice_vat_total :fatura tutarı
     * @param float | null $_invoice_grand_total : fatura toplam tutarı
     * @param null | DateTime $_invoice_deleted : fatura silinme tarih
     */
    public function __construct($_id = null, $_service = 1, $_service_name = "store", $_service_logo = "logo_url",
                                $_sales_channel = "domain.com",
                                $_service_created = null,
                                $_service_value = null,
                                $_service_order_id = null, $_products = null, DopigoCustomer $_customer = null,
                                DopigoBillingAddress $_billing_address = null,//buradasın
                                DopigoShippingAddress $_shipping_address = null, $_shipped_date = null, $_payment_type = "cc", $_status = "undefined",
                                $_total = 0.00, $_service_fee = 0.00, $_discount = 0.00,
                                $_archived = false, $_notes = "", array $_items = null,
                                $_transactions = null, $_invoice_number = null, $_invoice_created = null, $_invoice_type = null, $_invoice_vat_total = 0.00, $_invoice_grand_total = 0.00, $_invoice_deleted = null)
    {

    }

    private $_id, $_service, $_service_name, $_service_logo, $_sales_channel, $_service_created, $_service_value,
        $_service_order_id, $_products, $_customer, $_billing_address, $_shipping_address, $_shipped_date, $_payment_type,
        $_status, $_total, $_service_fee, $_discount, $_archived, $_notes, $_items, $_transactions, $_invoice_number,
        $_invoice_created, $_invoice_type, $_invoice_vat_total, $_invoice_grand_total, $_invoice_deleted;


}

class DopigoTransactions
{
    //<editor-fold desc="getters setters">
    /**
     * @return string
     */
    public function getTransactionType()
    {
        return $this->_transaction_type;
    }

    /**
     * @param string $transaction_type
     */
    public function setTransactionType($transaction_type)
    {
        $this->_transaction_type = $transaction_type;
    }

    /**
     * @return string
     */
    public function getPaymentType()
    {
        return $this->_payment_type;
    }

    /**
     * @param string $payment_type
     */
    public function setPaymentType($payment_type)
    {
        $this->_payment_type = $payment_type;
    }

    /**
     * @return string|null
     */
    public function getAdditionalDescription()
    {
        return $this->_additional_description;
    }

    /**
     * @param string|null $additional_description
     */
    public function setAdditionalDescription($additional_description)
    {
        $this->_additional_description = $additional_description;
    }

    /**
     * @return DateTime|null
     */
    public function getPaidDate()
    {
        return $this->_paid_date;
    }

    /**
     * @param DateTime|null $paid_date
     */
    public function setPaidDate($paid_date)
    {
        $this->_paid_date = $paid_date;
    }

    /**
     * @return DateTime|null
     */
    public function getPaymentDueDate()
    {
        return $this->_payment_due_date;
    }

    /**
     * @param DateTime|null $payment_due_date
     */
    public function setPaymentDueDate($payment_due_date)
    {
        $this->_payment_due_date = $payment_due_date;
    }

    /**
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return $this->_invoice_number;
    }

    /**
     * @param string|null $invoice_number
     */
    public function setInvoiceNumber($invoice_number)
    {
        $this->_invoice_number = $invoice_number;
    }

    /**
     * @return null
     */
    public function getInvoiceFile()
    {
        return $this->_invoice_file;
    }

    /**
     * @param null $invoice_file
     */
    public function setInvoiceFile($invoice_file)
    {
        $this->_invoice_file = $invoice_file;
    }

    /**
     * @return string|null
     */
    public function getServiceDocumentUrl()
    {
        return $this->_service_document_url;
    }

    /**
     * @param string|null $service_document_url
     */
    public function setServiceDocumentUrl($service_document_url)
    {
        $this->_service_document_url = $service_document_url;
    }

    /**
     * @return float|null
     */
    public function getTotal()
    {
        return $this->_total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal($total)
    {
        $this->_total = $total;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->_archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived($archived)
    {
        $this->_archived = $archived;
    }

    /**
     * @return int
     */
    public function getInstallmentCount()
    {
        return $this->_installment_count;
    }

    /**
     * @param int $installment_count
     */
    public function setInstallmentCount($installment_count)
    {
        $this->_installment_count = $installment_count;
    }

    /**
     * @return string
     */
    public function getPosRefId()
    {
        return $this->_pos_ref_id;
    }

    /**
     * @param string $pos_ref_id
     */
    public function setPosRefId($pos_ref_id)
    {
        $this->_pos_ref_id = $pos_ref_id;
    }

    /**
     * @return string|null
     */
    public function getBankId()
    {
        return $this->_bank_id;
    }

    /**
     * @param string|null $bank_id
     */
    public function setBankId($bank_id)
    {
        $this->_bank_id = $bank_id;
    }

    /**
     * @return string|null
     */
    public function getAccountId()
    {
        return $this->_account_id;
    }

    /**
     * @param string|null $account_id
     */
    public function setAccountId($account_id)
    {
        $this->_account_id = $account_id;
    }

    //</editor-fold>


    private $_transaction_type, $_payment_type, $_additional_description, $_paid_date, $_payment_due_date,
        $_invoice_number, $_invoice_file, $_service_document_url, $_total, $_archived, $_installment_count, $_pos_ref_id,
        $_bank_id, $_account_id;


    /**
     * DopigoTransactions constructor.
     * @param string $_transaction_type :{'income':'Gelir','expense':'Gider'}
     * @param string $_payment_type :{'cc':'Kredi Kartı','cash':'nakit','wire_transfer':'Havale/EFT','check','çek',
     * 'undefined':'tanımsız}
     * @param string | null $_additional_description :fazladan açıklama
     * @param DateTime|*zorunlu $_paid_date:ödeme zamanı
     * @param DateTime|null $_payment_due_date :son ödeme tarihi
     * @param string | null $_invoice_number :fatura no
     * @param null | mixed olması gerekiyopr $_invoice_file
     * @param string | null $_service_document_url :servis dosyası url adresi
     * @param float | null $_total :toplam fiyat . dan sonra 2 karakter
     * @param bool $_archived :arişivlendi true/false
     * @param int $_installment_count :taksit sayısı def:0
     * @param string $_pos_ref_id :pos referans id
     * @param string | null $_bank_id :banka no
     * @param string | null $_account_id :hesap no
     */
    public function __construct($_transaction_type = "income", $_payment_type = "undefined", $_additional_description = "",
                                DateTime $_paid_date = null,
                                DateTime $_payment_due_date = null,
                                $_invoice_number = "", $_invoice_file = null, $_service_document_url = "", $_total = 0.00,
                                $_archived = false, $_installment_count = 0, $_pos_ref_id = "", $_bank_id = "", $_account_id = "")
    {
        $this->_transaction_type = $_transaction_type;
        $this->_payment_type = $_payment_type;
        $this->_additional_description = $_additional_description;
        $this->_paid_date = $_paid_date;
        $this->_payment_due_date = $_payment_due_date;
        $this->_invoice_number = $_invoice_number;
        $this->_invoice_file = $_invoice_file;
        $this->_service_document_url = $_service_document_url;
        $this->_total = $_total;
        $this->_archived = $_archived;
        $this->_installment_count = $_installment_count;
        $this->_pos_ref_id = $_pos_ref_id;
        $this->_bank_id = $_bank_id;
        $this->_account_id = $_account_id;
    }
}


class DopigoOrderItem
{
    //<editor-fold desc="getters setters">
    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->_order = $order;
    }

    /**
     * @return int
     */
    public function getServiceItemId()
    {
        return $this->_service_item_id;
    }

    /**
     * @param int $service_item_id
     */
    public function setServiceItemId($service_item_id)
    {
        $this->_service_item_id = $service_item_id;
    }

    /**
     * @return int
     */
    public function getServiceProductId()
    {
        return $this->_service_product_id;
    }

    /**
     * @param int $service_product_id
     */
    public function setServiceProductId($service_product_id)
    {
        $this->_service_product_id = $service_product_id;
    }

    /**
     * @return string
     */
    public function getServiceShipmentCode()
    {
        return $this->_service_shipment_code;
    }

    /**
     * @param string $service_shipment_code
     */
    public function setServiceShipmentCode($service_shipment_code)
    {
        $this->_service_shipment_code = $service_shipment_code;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->_sku;
    }

    /**
     * @param string $sku
     */
    public function setSku($sku)
    {
        $this->_sku = $sku;
    }

    /**
     * @return null | string
     */
    public function getAtributes()
    {
        return $this->_attributes;
    }

    /**
     * @param null | string $atributes
     */
    public function setAtributes($attributes)
    {
        $this->_atributes = $attributes;
    }

    /**
     * @return string | null
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string | null $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->_amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->_amount = $amount;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->_price = $price;
    }

    /**
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->_unit_price;
    }

    /**
     * @param float $unit_price
     */
    public function setUnitPrice($unit_price)
    {
        $this->_unit_price = $unit_price;
    }

    /**
     * @return null | string
     */
    public function getShipment()
    {
        return $this->_shipment;
    }

    /**
     * @param null | string $shipment
     */
    public function setShipment($shipment)
    {
        $this->_shipment = $shipment;
    }

    /**
     * @return string
     */
    public function getShipmentCampaignCode()
    {
        return $this->_shipment_campaign_code;
    }

    /**
     * @param string $shipment_campaign_code
     */
    public function setShipmentCampaignCode($shipment_campaign_code)
    {
        $this->_shipment_campaign_code = $shipment_campaign_code;
    }

    /**
     * @return bool
     */
    public function isBuyerPaysShipment()
    {
        return $this->_buyer_pays_shipment;
    }

    /**
     * @param bool $buyer_pays_shipment
     */
    public function setBuyerPaysShipment($buyer_pays_shipment)
    {
        $this->_buyer_pays_shipment = $buyer_pays_shipment;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * @return null | string
     */
    public function getShipmnetProvider()
    {
        return $this->_shipmnet_provider;
    }

    /**
     * @param null | string $shipmnet_provider
     */
    public function setShipmnetProvider($shipmnet_provider)
    {
        $this->_shipmnet_provider = $shipmnet_provider;
    }

    /**
     * @return int
     */
    public function getTaxRatio()
    {
        return $this->_tax_ratio;
    }

    /**
     * @param int $tax_ratio
     */
    public function setTaxRatio($tax_ratio)
    {
        $this->_tax_ratio = $tax_ratio;
    }

    /**
     * @return null | DopigoOrderItemProduct
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param null|DopigoOrderItemProduct $product
     */
    public function setProduct($product)
    {
        $this->_product = $product;
    }

    /**
     * @return null
     */
    public function getLinkedProduct()
    {
        return $this->_linked_product;
    }

    /**
     * @param null $linked_product
     */
    public function setLinkedProduct($linked_product)
    {
        $this->_linked_product = $linked_product;
    }

    //</editor-fold>


    private $_id, $_order, $_service_item_id, $_service_product_id, $_service_shipment_code, $_sku, $_attributes,
        $_name,
        $_amount = 0, $_price = 0.00, $_unit_price = 0.00, $_shipment = null, $_shipment_campaign_code = "none",
        $_buyer_pays_shipment = true, $_status = "undefined", $_shipmnet_provider = null, $_tax_ratio = 18, $_product = null,
        $_linked_product = null;

    /**
     * DopigoOrderItem constructor.
     * @param int $_id : identity pk sipariş item için
     * @param int $_order : siparişin kendi id si boş geçilebilir(o takdirde dopigo kendi atar)
     * @param null | string $_service_item_id :Entegrasyon item id
     * @param null | string $_service_product_id :Entegrasyon ürün id
     * @param null | string $_service_shipment_code :Service kargo kodu
     * @param null | string $_sku :stok kodu
     * @param null | string $_atributes :özellikler
     * @param null | string $_name :İsim
     * @param int $_amount :miktar
     * @param float $_price :Satış Fiyatı
     * @param float $_unit_price : birim fiyatı
     * @param null | string $_shipment : kargo
     * @param null | string $_shipment_campaign_code :Kargo kampanya kodu
     * @param bool $_buyer_pays_shipment :true/false alıcı ödeme
     * @param string $_status :sipariş durumu string verilecek buradaki keylerden biri olma zorunluluğu
     * var{'pending':'onay bekliyor',approved:'onaylanmış','rejected':'reddedildi','cancelled':'iptal edildi',
     * 'picking':'hazırlanıyor','shipped':'kargolandı','delivered':'teslim edildi','undelivered':'teslim edilemedi',
     * 'waiting_shipment':'undefined':'belirtilmemiş'}
     * @param string $_shipmnet_provider :kargo
     * @param int $_tax_ratio :vergi oranı ama hiç bir siparişte girilmemiş o yüzden int diye tahmin ediyorum boş
     * geçilebilinir
     * @param null|DopigoOrderItemProduct $_product
     * @param null $_linked_product : boş geçilebilir ben API de istendiği için yinede yazmandan geçmek istemedim
     */
    public function __construct($_id = 0, $_order = 0, $_service_item_id = null, $_service_product_id = null,
                                $_service_shipment_code = null, $_sku = null, $_attributes = null, $_name = null,
                                $_amount = 0,
                                $_price = 0.00, $_unit_price = 0.00, $_shipment = null, $_shipment_campaign_code = "none",
                                $_buyer_pays_shipment = true, $_status = "undefined", $_shipment_provider = "aras kargo",
                                $_tax_ratio = 8, DopigoOrderItemProduct $_product = null, $_linked_product = null)
    {
        $this->_id = $_id;
        $this->_order = $_order;
        $this->_service_item_id = $_service_item_id;
        $this->_service_product_id = $_service_product_id;
        $this->_service_shipment_code = $_service_shipment_code;
        $this->_sku = $_sku;
        $this->_attributes = $_attributes;
        $this->_name = $_name;
        $this->_amount = $_amount;
        $this->_price = $_price;
        $this->_unit_price = $_unit_price;
        $this->_shipment = $_shipment;
        $this->_shipment_campaign_code = $_shipment_campaign_code;
        $this->_buyer_pays_shipment = $_buyer_pays_shipment;
        $this->_status = $_status;
        $this->_shipmnet_provider = $_shipment_provider;
        $this->_tax_ratio = $_tax_ratio;
        $this->_product = $_product;
        $this->_linked_product = $_linked_product;
    }
}

//alt + num(1,2,4)=pipe(|)
class DopigoOrderItemProduct
{
    private $_id, $_sku, $_foreign_sku;

    /**
     * @return null|int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param null $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return null|string
     */
    public function getSku()
    {
        return $this->_sku;
    }

    /**
     * @param null $sku
     */
    public function setSku($sku)
    {
        $this->_sku = $sku;
    }

    /**
     * @return null|string
     */
    public function getForeignSku()
    {
        return $this->_foreign_sku;
    }

    /**
     * @param null $foreign_sku
     */
    public function setForeignSku($foreign_sku)
    {
        $this->_foreign_sku = $foreign_sku;
    }

    /**
     * order içindeki itemler için
     * DopigoOrderItemProduct constructor.
     * @param null|int $_id
     * @param null|string $_sku
     * @param null|string $_foreign_sku
     */
    public function __construct($_id = null, $_sku = null, $_foreign_sku = null)
    {
        $this->_id = $_id;
        $this->_sku = $_sku;
        $this->_foreign_sku = $_foreign_sku;
    }
}

class DopigoOrderItemLinkedProduct extends DopigoOrderItemProduct
{

}//tamamiyle aynısı