<?php


include_once "dopigoProduct.php";
include "dopigoOrder.php";

class dopigo
{
    public $token = null;

    /**
     * @param $array_of_results array dopigodan dönen raw ->results object i parser yardımı ile alır ***önemli dopigo
     * sadece ilk 30 ürünü gönderir bir sonraki 30 ürün için ilgili objenin ->next parametresi kullanılarak tekrar
     * sorgu yapılması gerekmektedir * bir seferde 30 dan fazla ürün taleb etmemeni tavsiye ederim
     * @return array product array döndürür Dopigo_product
     * her bir array'in kendi içinde dopigoya ilet_guncelle metodu bulunuyor
     * intellisense desteği alabilmen için
     * foreach ($product_list as $item) {
     *if (is_a($item,"Dopigo_product")){->şu şekilde bir kullanım tavsiye ederim
     *$item->getName();//gibi gibi
     *}
     *}
     */
    public static function dopigo_product_parser($array_of_results)
    {
        $products_of_dopigo = array();
        foreach ($array_of_results as $product) {
            $dopigo_product = new Dopigo_product();
            $dopigo_product->setName(!empty($product->name) ? $product->name : null);
            $dopigo_product->setDescription(!empty($product->description) ? $product->description : null);
            $dopigo_product->setCategory(!empty($product->category) ? $product->category : null);
            $dopigo_product->setVat(!empty($product->vat) ? $product->vat : null);
            $dopigo_sub_products = array();
            foreach ($product->products as $sub_product) {
                $dopigo_sub_product = new Dopigo_sub_product();
                $dopigo_sub_product->setId(!(empty($sub_product->id)) ? intval($sub_product->id) : null);
                $dopigo_sub_product->setMetaId(!(empty($sub_product->meta_id)) ? $sub_product->meta_id : null);
                $dopigo_sub_product->setSku(!(empty($sub_product->sku)) ? $sub_product->sku : null);
                $dopigo_sub_product->setForeignSku(!(empty($sub_product->foreign_sku)) ? $sub_product->foreign_sku : null);
                $dopigo_sub_product->setBarcode(!(empty($sub_product->barcode)) ? $sub_product->barcode : null);
                $dopigo_sub_product->setWeight(!(empty($sub_product->weight)) ? $sub_product->weight : null);
                $dopigo_sub_product->setStock(!(empty($sub_product->stock)) ? $sub_product->stock : null);
                $dopigo_sub_product->setAvailableStock(!(empty($sub_product->available_stock)) ? $sub_product->available_stock : null);
                $dopigo_sub_product->setPrice(!(empty($sub_product->price)) ? $sub_product->price : null);
                $dopigo_sub_product->setPriceCurrency(!(empty($sub_product->price_currency)) ? $sub_product->price_currency : null);
                $dopigo_sub_product->setPriceLocal(!(empty($sub_product->price_local)) ? $sub_product->price_local : null);
                $dopigo_sub_product->setListingPrice(!(empty($sub_product->listing_price)) ? $sub_product->listing_price : null);
                $dopigo_sub_product->setPurchasePrice(!(empty($sub_product->purchase_price)) ? $sub_product->purchase_price : null);
                $dopigo_sub_product->setBuyerPaysShipping(!(empty($sub_product->buyer_pays_shipping)) ? $sub_product->buyer_pays_shipping : null);
                $dopigo_sub_product->setIsPrimary(!(empty($sub_product->is_primary)) ? $sub_product->is_primary : null);

                //metaData
                $meta = new metaData();
                $meta->setVat(!(empty($sub_product->meta->vat)) ? $sub_product->meta->vat : null);
                $meta->setName(!(empty($sub_product->meta->name)) ? $sub_product->meta->name : null);
                $meta->setDescription(!(empty($sub_product->meta->description)) ? $sub_product->meta->description : null);
                $meta->setActive(!(empty($sub_product->meta->active)) ? $sub_product->meta->active : null);
                $dopigo_sub_product->setMeta($meta);

                //images
                $sub_product_image_array = array();
                foreach ($sub_product->images as $raw_image) {
                    $dopigo_image = new dopigoImage();
                    $dopigo_image->setKesinUrl(!empty($raw_image->absolute_url) ? $raw_image->absolute_url : null);
                    $dopigo_image->setKaynakUrl(!empty($raw_image->source_url) ? $raw_image->source_url : null);
                    array_push($sub_product_image_array, $dopigo_image);
                }
                $dopigo_sub_product->setImages($sub_product_image_array);

                //attributes
                $attr_array = array();
                foreach ($sub_product->custom_attributes as $custom_attribute) {
                    $attr_array[$custom_attribute->attribute->name] = $custom_attribute->value->name;
                }
                $dopigo_sub_product->setCustomAttributes($attr_array);


                $dopigo_sub_products[] = $dopigo_sub_product;
            }
            $dopigo_product->setProducts($dopigo_sub_products);
            $products_of_dopigo[] = $dopigo_product;
        }
        return $products_of_dopigo;
    }

    /**
     * @param $array_of_results array | null ilgili fonksiyondan dönen json verilerinin result'ı verilmelidir
     * varsayılan olarak 30 order değeri gelmektedir !!
     * @return null | array
     */
    public static function dopigo_order_parser($array_of_results)
    {
        if (!isset($array_of_results) || empty($array_of_results)):
            echo "results boş olarak geldi";
            return null;
        endif;

        $dopigo_orders = array();
        foreach ($array_of_results as $item) {
            $dopigo_order = new DopigoOrder();
            $dopigo_order->setId(!empty($item->id) ? $item->id : 0);
            $dopigo_order->setService(!empty($item->service) ? $item->service : 0);
            $dopigo_order->setServiceName(!empty($item->service_name) ? $item->service_name : "");
            $dopigo_order->setServiceLogo(!empty($item->service_logo) ? $item->service_logo : null);
            $dopigo_order->setSalesChannel(!empty($item->sales_channel) ? $item->sales_channel : null);
            $dopigo_order->setServiceCreated(!empty($item->service_created) ? $item->service_created : null);
            $dopigo_order->setServiceValue(!empty($item->service_value) ? $item->service_value : null);
            $dopigo_order->setServiceOrderId(!empty($item->service_order_id) ? $item->service_order_id : null);
            $dopigo_order->setProducts(!empty($item->products) ? $item->products : null);

            //dopigo customer
            $dCustomer = new DopigoCustomer();
            $dCustomer->setId(!empty($item->customer->id) ? $item->customer->id : 0);
            $dCustomer->setAccountType(!empty($item->customer->account_type) ? $item->customer->account_type : "person");
            $dCustomer->setFullName(!empty($item->customer->full_name) ? $item->customer->full_name : "anonymous person");

            //dopigo customer address
            $dCAddress = new DopigoAddress();
            $dCAddress->setId(!empty($item->customer->address->id) ? $item->customer->address->id : 0);
            $dCAddress->setFullAddress(!empty($item->customer->address->full_address) ? $item->customer->address->full_address : "");
            $dCAddress->setContactFullName(!empty($item->customer->address->contact_full_name) ? $item->customer->address->contact_full_name : "");
            $dCAddress->setContactPhoneNumber(!empty($item->customer->address->contact_phone_number) ? $item->customer->address->contact_phone_number : 0);
            $dCAddress->setCity(!empty($item->customer->address->city) ? $item->customer->address->city : "");
            $dCAddress->setDistrict(!empty($item->customer->address->district) ? $item->customer->address->district : "");
            $dCAddress->setZipCode(!empty($item->customer->address->zip_code) ? $item->customer->address->zip_code : "");
            $dCustomer->setAddress($dCAddress);

            //dopigo customer devam
            $dCustomer->setEmail(!empty($item->customer->email) ? $item->customer->email : "anonymous@domain.com");
            $dCustomer->setPhoneNumber(!empty($item->customer->phone_number) ? $item->customer->phone_number : "+905555555555");
            $dCustomer->setCitizenId(!empty($item->customer->citizen_id) ? $item->customer->citizen_id : null);
            $dCustomer->setTaxId(!empty($item->customer->tax_id) ? $item->customer->tax_id : null);
            $dCustomer->setTaxOffice(!empty($item->customer->tax_office) ? $item->customer->tax_office : null);
            $dCustomer->setCompanyName(!empty($item->customer->company_name) ? $item->customer->company_name : "");
            $dopigo_order->setCustomer($dCustomer);

            //dopigo billing address
            $dBillingAddress = new DopigoBillingAddress();
            $dBillingAddress->setId(!empty($item->billing_address->id) ? $item->billing_address->id : 0);
            $dBillingAddress->setFullAddress(!empty($item->billing_address->full_address) ? $item->billing_address->full_address : 0);
            $dBillingAddress->setContactFullName(!empty($item->billing_address->contact_full_name) ? $item->billing_address->contact_full_name : "");
            $dBillingAddress->setContactPhoneNumber(!empty($item->billing_address->contact_phone_number) ? $item->billing_address->contact_phone_number : "+905555555555");
            $dBillingAddress->setCity(!empty($item->billing_address->city) ? $item->billing_address->city : "+905555555555");
            $dBillingAddress->setDistrict(!empty($item->billing_address->district) ? $item->billing_address->district : "none");
            $dBillingAddress->setZipCode(!empty($item->billing_address->zip_code) ? $item->billing_address->zip_code : "none");
            $dopigo_order->setBillingAddress($dBillingAddress);

            //dopigo shipping address
            $dShippingAddress = new DopigoShippingAddress();
            $dShippingAddress->setId(!empty($item->shipping_address->id) ? $item->shipping_address->id : 0);
            $dShippingAddress->setFullAddress(!empty($item->shipping_address->full_address) ? $item->shipping_address->full_address : "none");
            $dShippingAddress->setContactFullName(!empty($item->shipping_address->contact_full_name) ? $item->shipping_address->contact_full_name : "none");
            $dShippingAddress->setContactPhoneNumber(!empty($item->shipping_address->contact_phone_number) ? $item->shipping_address->contact_phone_number : "+905555555555");
            $dShippingAddress->setCity(!empty($item->shipping_address->city) ? $item->shipping_address->city : "none");
            $dShippingAddress->setDistrict(!empty($item->shipping_address->district) ? $item->shipping_address->district : "none");
            $dShippingAddress->setZipCode(!empty($item->shipping_address->zip_code) ? $item->shipping_address->zip_code : "none");
            $dopigo_order->setShippingAddress($dShippingAddress);

            //dopigo order devam
            $dopigo_order->setShippedDate(!empty($item->shipped_date) ? $item->shipped_date : null);
            $dopigo_order->setPaymentType(!empty($item->payment_type) ? $item->payment_type : "undefined");
            $dopigo_order->setStatus(!empty($item->status) ? $item->status : "undefined");
            $dopigo_order->setTotal(!empty($item->total) ? $item->total : 0.00);
            $dopigo_order->setServiceFee(!empty($item->service_fee) ? $item->service_fee : 0.00);
            $dopigo_order->setDiscount(!empty($item->discount) ? $item->discount : null);
            $dopigo_order->setArchived(!empty($item->archived) ? $item->archived : false);
            $dopigo_order->setNotes(!empty($item->notes) ? $item->notes : "");

            //dopigo items

            $array_of_items = array();
            if (isset($item->items) && !empty($item->items) && count($item->items) > 0) {
                foreach ($item->items as $dopigoOrderItem) {
                    $dopigoNewProductItem = new DopigoOrderItem();
                    $dopigoNewProductItem->setId(!empty($dopigoOrderItem->id) ? $dopigoOrderItem->id : 0);
                    $dopigoNewProductItem->setOrder(!empty($dopigoOrderItem->order) ? $dopigoOrderItem->order : 0);
                    $dopigoNewProductItem->setServiceItemId(!empty($dopigoOrderItem->service_item_id) ? $dopigoOrderItem->service_item_id : 0);
                    $dopigoNewProductItem->setServiceProductId(!empty($dopigoOrderItem->service_product_id) ? $dopigoOrderItem->service_product_id : 0);
                    $dopigoNewProductItem->setServiceShipmentCode(!empty($dopigoOrderItem->service_shipment_code) ? $dopigoOrderItem->service_shipment_code : 0);
                    $dopigoNewProductItem->setSku(!empty($dopigoOrderItem->sku) ? $dopigoOrderItem->sku : 0);
                    $dopigoNewProductItem->setAtributes(!empty($dopigoOrderItem->attributes) ? $dopigoOrderItem->attributes : null);//string
                    $dopigoNewProductItem->setName(!empty($dopigoOrderItem->name) ? $dopigoOrderItem->name : null);
                    $dopigoNewProductItem->setAmount(!empty($dopigoOrderItem->amount) ? $dopigoOrderItem->amount : 0);
                    $dopigoNewProductItem->setPrice(!empty($dopigoOrderItem->price) ? $dopigoOrderItem->price : 0.00);
                    $dopigoNewProductItem->setUnitPrice(!empty($dopigoOrderItem->unit_price) ? $dopigoOrderItem->unit_price : 0.00);
                    $dopigoNewProductItem->setShipment(!empty($dopigoOrderItem->shipment) ? $dopigoOrderItem->shipment : null);
                    $dopigoNewProductItem->setShipmentCampaignCode(!empty($dopigoOrderItem->shipment_campaign_code) ? $dopigoOrderItem->shipment_campaign_code : "none");
                    $dopigoNewProductItem->setBuyerPaysShipment(!empty($dopigoOrderItem->buyer_pays_shipment) ? $dopigoOrderItem->buyer_pays_shipment : false);
                    $dopigoNewProductItem->setStatus(!empty($dopigoOrderItem->status) ? $dopigoOrderItem->status : "undefined");
                    $dopigoNewProductItem->setShipmnetProvider(!empty($dopigoOrderItem->shipment_provider) ? $dopigoOrderItem->shipment_provider : null);
                    $dopigoNewProductItem->setTaxRatio(!empty($dopigoOrderItem->tax_ratio) ? $dopigoOrderItem->tax_ratio : null);

                    //Dopigo Order Item Product
                    $dopigoNewProductItem->setProduct((isset($dopigoOrderItem->product) && !empty($dopigoOrderItem->product)) ? new DopigoOrderItemProduct(
                        (isset($dopigoOrderItem->product->id) && !empty($dopigoOrderItem->product->id)) ? $dopigoOrderItem->product : 0,
                        (isset($dopigoOrderItem->product->sku) && !empty($dopigoOrderItem->product->sku)) ? $dopigoOrderItem->product->sku : null,
                        (isset($dopigoOrderItem->product->foreign_sku) && !empty($dopigoOrderItem->product->foreign_sku)) ? $dopigoOrderItem->product->foreign_sku : null) :
                        null);
                    //Dopigo Order Item Linked Product
                    $dopigoNewProductItem->setLinkedProduct((isset($dopigoOrderItem->linked_product) && !empty($dopigoOrderItem->linked_product)) ? new DopigoOrderItemLinkedProduct(
                        (isset($dopigoOrderItem->linked_product->id) && !empty($dopigoOrderItem->linked_product->id)) ? $dopigoOrderItem->linked_product : 0,
                        (isset($dopigoOrderItem->linked_product->sku) && !empty($dopigoOrderItem->linked_product->sku)) ? $dopigoOrderItem->linked_product->sku : null,
                        (isset($dopigoOrderItem->linked_product->foreign_sku) && !empty($dopigoOrderItem->linked_product->foreign_sku)) ? $dopigoOrderItem->linked_product->foreign_sku : null) :
                        null);

                    $array_of_items[] = $dopigoNewProductItem;
                }
                $dopigo_order->setItems($array_of_items);

                //transactions
                $array_of_transactions = array();
                if (isset($item->transactions) && !empty($item->transactions) && count($item->transactions) > 0) {
                    foreach ($item->transactions as $transaction) {
                        $dopigoTransaction = new DopigoTransactions();
                        $dopigoTransaction->setTransactionType(!empty($transaction->transaction_type) ? $transaction->transaction_type : "income");
                        $dopigoTransaction->setPaymentType(!empty($transaction->payment_type) ? $transaction->payment_type : "undefined");
                        $dopigoTransaction->setAdditionalDescription(!empty($transaction->additional_description) ? $transaction->additional_description : null);
                        $dopigoTransaction->setPaidDate(!empty($transaction->paid_date) ? $transaction->paid_date : null);
                        $dopigoTransaction->setPaymentDueDate(!empty($transaction->payment_due_date) ? $transaction->payment_due_date : null);
                        $dopigoTransaction->setInvoiceNumber(!empty($transaction->invoice_number) ? $transaction->invoice_number : null);
                        $dopigoTransaction->setInvoiceFile(!empty($transaction->invoice_file) ? $transaction->invoice_file : null);//bu tahminen byte array blob dönecek var ise
                        $dopigoTransaction->setServiceDocumentUrl(!empty($transaction->service_document_url) ? $transaction->service_document_url : null);
                        $dopigoTransaction->setTotal(!empty($transaction->total) ? $transaction->total : 0.00);
                        $dopigoTransaction->setArchived(!empty($transaction->archived) ? $transaction->archived : false);
                        $dopigoTransaction->setInstallmentCount(!empty($transaction->installment_count) ? $transaction->installment_count : null);
                        $dopigoTransaction->setPosRefId(!empty($transaction->pos_ref_id) ? $transaction->pos_ref_id : null);
                        $dopigoTransaction->setBankId(!empty($transaction->bank_id) ? $transaction->bank_id : null);
                        $dopigoTransaction->setAccountId(!empty($transaction->account_id) ? $transaction->account_id : null);
                        $array_of_transactions[] = $dopigoTransaction;
                    }

                }
                $dopigo_order->setTransactions($array_of_transactions);

            }
            $dopigo_order->setInvoiceNumber(!empty($item->invoice_number) ? $item->invoice_number : null);
            $dopigo_order->setInvoiceCreated(!empty($item->invoice_created) ? $item->invoice_created : null);
            $dopigo_order->setInvoiceType(!empty($item->invoice_type) ? $item->invoice_type : "");
            $dopigo_order->setInvoiceVatTotal(!empty($item->invoice_vat_total) ? $item->invoice_vat_total : 0.00);
            $dopigo_order->setInvoiceGrandTotal(!empty($item->invoice_grand_total) ? $item->invoice_grand_total : 0.00);
            $dopigo_order->setInvoiceDeleted(!empty($item->invoice_deleted) ? $item->invoice_deleted : null);//fatura silinme tarihi


            $dopigo_orders[] = $dopigo_order;
        }


        return $dopigo_orders;
    }

    /**
     * @return null|string token verisini döndürür şayet token ilgili class object içinde önceden sorgulanmış ise
     * zatenet edilmiş demektir 2 sefer sorgulamaz
     *
     */
    public function getToken($username = "wptest@gmail.com", $password = "wptest1234")
    {
        if (isset($this->token) && !is_null($this->token))
            return $this->token;

        $ch = curl_init("https://panel.dopigo.com/users/get_auth_token/");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array('username' => $username, 'password' => $password));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $this->token = json_decode($data)->token;
        return $this->token;
    }

    /**
     * @param int $limit elle sorgulanacak ürün sayısını değiştirmek için
     * @param int $offset hangi sıradaki üründen başlayarak sorgulamak istiyorsun
     * @return mixed|array array object karışık olarak döner next,previous,count ve products döner
     * next: bir sonraki 30 luk ürün listesi yok ise boş döner
     * previous: bir önceki 30 luk ürün listesi yok ise boş döner
     * count: TOPLAM PRODUCT sayısı fakat hatalı dönüyor denedim sorun dopigo kaynaklı yada active olmayan ürünleride
     * sayıyor
     * products:ürünlerin listesini dönmektedir
     */
    public function getProducts($limit = 30, $offset = 0)
    {
        $ch = curl_init("https://panel.dopigo.com/api/v1/products/all/?limit=$limit&offset=$offset");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken()));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    /**
     * @return mixed|array
     */
    public function getOrders()
    {
        $ch = curl_init("https://panel.dopigo.com/api/v1/orders/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    /**
     * @param $id integer kategori id
     * @param $Name string kategori ismi
     * @param null|integer $Parent
     * @return array başarılı sonuç dönerse
     */
    public function createCategory($id, $Name, $Parent = null)
    {
        $options = array(
            'Id' => $id, 'Name' => $Name
        );
        if (!is_null($Parent)) {
            $options['Parent'] = $Parent;
        }
        $ch = curl_init("https://panel.dopigo.com/api/v1/categories/create_category/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data)->results;
    }

    /**
     * @param $id integer kategori id
     * @param $Name string kategori ismi
     * @param null|integer $Parent
     * @return array başarılı sonuç dönerse
     */
    public function updateCategory($id, $Name, $Parent = null)
    {
        $options = array(
            'Name' => $Name
        );
        if (!is_null($Parent)) {
            $options['Parent'] = $Parent;
        }
        $ch = curl_init("https://panel.dopigo.com/api/v1/categories/update_category/$id/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data)->results;
    }

    /**
     * NOT:customer önceden tanımlı olmalı customer id si primary key dir dolaysıyla önce customer bilgisi gerekmektedir ben denemelerimde Melih Yılmaz'ı baz aldım
     * @param $id integer Zorunlu
     * @param $service integer entegrasyon
     * @param string $service_value "204565647292"
     * @param array $customer customer: {
     *id: 14806245,->customerId
     *account_type: "person",
     *full_name: "Melih Yılmaz",
     *address: {
     *id: 45772062,
     *full_address: "Yeniköy Mh. Köybaşıarkası Sk. No:23, Daire:1",
     *contact_full_name: null,
     *contact_phone_number: null,
     *city: "İstanbul",
     *district: "Sarıyer",
     *zip_code: null
     *}
     * @param null|string $service_name servis adı örneğin n11
     * @param null|string $sales_channel
     * @param null|string $service_created string datetime "2021-01-20T06:33:26.051503Z",
     * @param null|string $service_order_id "136598049"
     * @param null|string $products "1 X Monitör"
     * @param array $billing_address
     * @param array|null $shipping_address
     * @param null|string $shipping_date
     * @param null|string $payment_type Choice:{cc/bonus_pay/bkm/n11_points/undefined}
     * @param null|string $status Choice:{pending/waiting_shipment/shipped/cancelled/undefined(default)}
     * @param null|float $total decimal(15,2)
     * @param null|float $service_fee decimal(15,2) kargo hizmet bedeli
     * @param null|float $discount decimal(15,2) indirim
     * @param null|boolean $archived arşivlendi
     * @param null|string $notes string
     * @param array|null $items
     * @param null|string $invoice_number fatura no
     * @param null|string $invoice_created fatura tarihi
     * @param null|string $invoice_type fatura tipi
     * @param null|float $invoice_vat_total decimal(15,2) fatura toplamı
     * @param null|float $invoice_grand_total decimal(15,2) fatura genel toplam
     * @param null|boolean $invoice_deleted fatura silindi
     * @return array
     */
    public function updateOrder($id, $service, $service_value, array $customer, $service_name = null, $sales_channel = null, $service_created = null, $service_order_id = null
        , $products = null, array $billing_address = null, array $shipping_address = null, $shipping_date = null, $payment_type = null, $status = null, $total = null, $service_fee = null
        , $discount = null, $archived = null, $notes = null, array $items = null, $invoice_number = null, $invoice_created = null, $invoice_type = null, $invoice_vat_total = null
        , $invoice_grand_total = null, $invoice_deleted = null)
    {
        $options = array('id' => $id, 'service' => $service, 'service_value' => $service_value, 'customer' => $customer);
        if (!is_null($service_name)) {
            $options['service_name'] = $service_name;
        }
        if (!is_null($sales_channel)) {
            $options['sales_channel'] = $sales_channel;
        }
        if (!is_null($service_created)) {
            $options['service_created'] = $service_created;
        }
        if (!is_null($service_order_id)) {
            $options['service_order_id'] = $service_order_id;
        }
        if (!is_null($products)) {
            $options['products'] = $products;
        }
        if (!is_null($billing_address)) {
            $options['billing_address'] = $billing_address;
        }
        if (!is_null($shipping_address)) {
            $options['shipping_address'] = $shipping_address;
        }
        if (!is_null($shipping_date)) {
            $options['shipping_date'] = $shipping_date;
        }
        if (!is_null($payment_type)) {
            $options['payment_type'] = $payment_type;
        }
        if (!is_null($status)) {
            $options['status'] = $status;
        }
        if (!is_null($total)) {
            $options['total'] = $total;
        }
        if (!is_null($service_fee)) {
            $options['service_fee'] = $service_fee;
        }
        if (!is_null($archived)) {
            $options['archived'] = $archived;
        }
        if (!is_null($notes)) {
            $options['notes'] = $notes;
        }
        if (!is_null($items)) {
            $options['items'] = $items;
        }
        if (!is_null($invoice_number)) {
            $options['invoice_number'] = $invoice_number;
        }
        if (!is_null($invoice_created)) {
            $options['invoice_created'] = $invoice_created;
        }
        if (!is_null($invoice_type)) {
            $options['invoice_type'] = $invoice_type;
        }
        if (!is_null($invoice_vat_total)) {
            $options['invoice_vat_total'] = $invoice_vat_total;
        }
        if (!is_null($invoice_grand_total)) {
            $options['invoice_grand_total'] = $invoice_grand_total;
        }
        if (!is_null($invoice_deleted)) {
            $options['invoice_deleted'] = $invoice_deleted;
        }

        $ch = curl_init("https://panel.dopigo.com/api/v1/orders/$id/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    /**
     * NOT:customer önceden tanımlı olmalı customer id si primary key dir dolaysıyla önce customer bilgisi gerekmektedir ben denemelerimde Melih Yılmaz'ı baz aldım
     * @param $id integer Zorunlu
     * @param $service integer entegrasyon
     * @param string $service_value "204565647292"
     * @param array $customer customer: {
     *id: 14806245,->customerId
     *account_type: "person",
     *full_name: "Melih Yılmaz",
     *address: {
     *id: 45772062,
     *full_address: "Yeniköy Mh. Köybaşıarkası Sk. No:23, Daire:1",
     *contact_full_name: null,
     *contact_phone_number: null,
     *city: "İstanbul",
     *district: "Sarıyer",
     *zip_code: null
     *}
     * @param null|string $service_name servis adı örneğin n11
     * @param null|string $sales_channel
     * @param null|string $service_created string datetime "2021-01-20T06:33:26.051503Z",
     * @param null|string $service_order_id "136598049"
     * @param null|string $products "1 X Monitör"
     * @param array $billing_address
     * @param array|null $shipping_address
     * @param null|string $shipping_date
     * @param null|string $payment_type Choice:{cc/bonus_pay/bkm/n11_points/undefined}
     * @param null|string $status Choice:{pending/waiting_shipment/shipped/cancelled/undefined(default)}
     * @param null|float $total decimal(15,2)
     * @param null|float $service_fee decimal(15,2) kargo hizmet bedeli
     * @param null|float $discount decimal(15,2) indirim
     * @param null|boolean $archived arşivlendi
     * @param null|string $notes string
     * @param array|null $items
     * @param null|string $invoice_number fatura no
     * @param null|string $invoice_created fatura tarihi
     * @param null|string $invoice_type fatura tipi
     * @param null|float $invoice_vat_total decimal(15,2) fatura toplamı
     * @param null|float $invoice_grand_total decimal(15,2) fatura genel toplam
     * @param null|boolean $invoice_deleted fatura silindi
     * @return array
     */
    public function createOrder($id, $service, $service_value, array $customer, $service_name = null, $sales_channel = null, $service_created = null, $service_order_id = null
        , $products = null, array $billing_address = null, array $shipping_address = null, $shipping_date = null, $payment_type = null, $status = null, $total = null, $service_fee = null
        , $discount = null, $archived = null, $notes = null, array $items = null, $invoice_number = null, $invoice_created = null, $invoice_type = null, $invoice_vat_total = null
        , $invoice_grand_total = null, $invoice_deleted = null)
    {
        $options = array('id' => $id, 'service' => $service, 'service_value' => $service_value, 'customer' => $customer);
        if (!is_null($service_name)) {
            $options['service_name'] = $service_name;
        }
        if (!is_null($sales_channel)) {
            $options['sales_channel'] = $sales_channel;
        }
        if (!is_null($service_created)) {
            $options['service_created'] = $service_created;
        }
        if (!is_null($service_order_id)) {
            $options['service_order_id'] = $service_order_id;
        }
        if (!is_null($products)) {
            $options['products'] = $products;
        }
        if (!is_null($billing_address)) {
            $options['billing_address'] = $billing_address;
        }
        if (!is_null($shipping_address)) {
            $options['shipping_address'] = $shipping_address;
        }
        if (!is_null($shipping_date)) {
            $options['shipping_date'] = $shipping_date;
        }
        if (!is_null($payment_type)) {
            $options['payment_type'] = $payment_type;
        }
        if (!is_null($status)) {
            $options['status'] = $status;
        }
        if (!is_null($total)) {
            $options['total'] = $total;
        }
        if (!is_null($service_fee)) {
            $options['service_fee'] = $service_fee;
        }
        if (!is_null($archived)) {
            $options['archived'] = $archived;
        }
        if (!is_null($notes)) {
            $options['notes'] = $notes;
        }
        if (!is_null($items)) {
            $options['items'] = $items;
        }
        if (!is_null($invoice_number)) {
            $options['invoice_number'] = $invoice_number;
        }
        if (!is_null($invoice_created)) {
            $options['invoice_created'] = $invoice_created;
        }
        if (!is_null($invoice_type)) {
            $options['invoice_type'] = $invoice_type;
        }
        if (!is_null($invoice_vat_total)) {
            $options['invoice_vat_total'] = $invoice_vat_total;
        }
        if (!is_null($invoice_grand_total)) {
            $options['invoice_grand_total'] = $invoice_grand_total;
        }
        if (!is_null($invoice_deleted)) {
            $options['invoice_deleted'] = $invoice_deleted;
        }

        $ch = curl_init("https://panel.dopigo.com/api/v1/orders/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data);
    }

    /**
     * @param string $name Ürün ismi
     * @param null|string $description ürün açıklaması
     * @param array $products ürünün alt ürünleri ve diğer bilgiler burada tutulur örneğin:products: [
     * {
     * id: 12106572,
     * meta_id: 8630150,
     * sku: "MAMA-SANDALYESI",
     * foreign_sku: "",
     * barcode: "",
     * weight: null,
     * stock: 89,
     * available_stock: 89,
     * price: "79.90",
     * price_currency: "TRY",
     * price_local: "79.90",
     * listing_price: "80.00",
     * purchase_price: "70.00",
     * buyer_pays_shipping: true,
     * images: [
     * {
     * absolute_url: "https://dopigo.s3.amazonaws.com/images/18562/1edde411-158f-432a-83d1-6933500efc6f/292435151_tn50_0.jpg",
     * source_url: null
     * }
     * ],
     * @param array|null $category
     * @param null $vat
     * @return array ürün eklenir ise
     */
    public function createProduct($name, array $products, array $category = null, $description = null, $vat = null)
    {
        $options = array(
            'name' => $name,
            'products' => $products
        );
        if (!is_null($category)) {
            $options['category'] = $category;
        }
        if (!is_null($description)) {
            $options['description'] = $description;
        }
        if (!is_null($vat)) {
            $options['vat'] = $vat;
        }
        $ch = curl_init("https://panel.dopigo.com/api/v1/products/create_product/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data)->results;
    }

    /**
     * @param $id integer burası hangi ürünün güncelleneceğini belirtir
     * @param string $name Ürün ismi
     * @param array $products ürünün alt ürünleri ve diğer bilgiler burada tutulur örneğin:products: [
     * {
     * id: 12106572,
     * meta_id: 8630150,
     * sku: "MAMA-SANDALYESI",
     * foreign_sku: "",
     * barcode: "",
     * weight: null,
     * stock: 89,
     * available_stock: 89,
     * price: "79.90",
     * price_currency: "TRY",
     * price_local: "79.90",
     * listing_price: "80.00",
     * purchase_price: "70.00",
     * buyer_pays_shipping: true,
     * images: [
     * {
     * absolute_url: "https://dopigo.s3.amazonaws.com/images/18562/1edde411-158f-432a-83d1-6933500efc6f/292435151_tn50_0.jpg",
     * source_url: null
     * }
     * ],
     * @param array|null $category
     * @param null|string $description ürün açıklaması
     * @param null $vat
     * @return array ürün eklenir ise
     */
    public function updateProduct($id, $name, array $products, array $category = null, $description = null, $vat = null)
    {
        $options = array(
            'name' => $name,
            'products' => $products
        );
        if (!is_null($category)) {
            $options['category'] = $category;
        }
        if (!is_null($description)) {
            $options['description'] = $description;
        }
        if (!is_null($vat)) {
            $options['vat'] = $vat;
        }

        $ch = curl_init("https://panel.dopigo.com/api/v1/products/update_product/" . $id . "/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken(), "Content-Type:application/json"));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($options));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        return json_decode($data)->results;
    }

    /**
     * @return mixed
     * Array örneği bu şekilde döndürür
     *(
     *[0] => stdClass Object
     *(
     *[id] => 24859
     *[name] => Mama Sandalyesi
     *[parent] =>
     *)
     *)
     *
     *
     *
     */
    public function getAllCategories()
    {
        $ch = curl_init("https://panel.dopigo.com/api/v1/categories/all/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . $this->getToken()));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);

        return json_decode($data)->results;
    }


    /**
     * @param $kategoriId
     * @return string
     */
    public static function getCategorFromId($kategoriId)
    {
        $ch = curl_init("https://panel.dopigo.com/api/v1/categories/all/");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('authorization:Token ' . (new dopigo)->getToken()));
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);
        $newData = json_decode($data)->results;
        foreach ($newData as $newDatum) {

            if ($newDatum->id == $kategoriId)
                return $newDatum->name;
        }
        return "";
    }


}
