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
     * @return null | array
     * @param $array_of_results array | null ilgili fonksiyondan dönen json verilerinin result'ı verilmelidir
     * varsayılan olarak 30 order değeri gelmektedir !!
     */
    public static function dopigo_order_parser($array_of_results)
    {
        if (!isset($array_of_results) || empty($array_of_results))
            return null;


        $dopigo_orders=array();
        foreach ($array_of_results as $item) {
            $dopigo_order=new DopigoOrder();
            $dopigo_order->setId(!empty($item->id) ? $item->id:0);
            $dopigo_order->setService(!empty($item->service) ? $item->service:0);
            $dopigo_order->setServiceName(!empty($item->service_name) ? $item->service_name:"");
            $dopigo_order->setServiceLogo(!empty($item->service_logo) ? $item->service_logo:null);
            $dopigo_order->setSalesChannel(!empty($item->sales_channel) ? $item->sales_channel:null);
            $dopigo_order->setServiceCreated(!empty($item->service_created) ? $item->service_created:null);
            $dopigo_order->setServiceValue(!empty($item->service_value) ? $item->service_value:null);
            $dopigo_order->setServiceOrderId(!empty($item->service_order_id) ? $item->service_order_id:null);
            $dopigo_order->setProducts(!empty($item->products) ? $item->products:null);

            //dopigo customer
            $dCustomer=new DopigoCustomer();
            $dCustomer->setId(!empty($item->customer->id) ? $item->customer->id: 0);
            $dCustomer->setAccountType(!empty($item->customer->account_type) ? $item->customer->account_type: "person");
            $dCustomer->setFullName(!empty($item->customer->full_name) ? $item->customer->full_name: "anonymous person");

            //dopigo customer address
            $dCAddress=new DopigoAddress();
            $dCAddress->setId(!empty($item->customer->address->id) ? $item->customer->address->id : 0);
            $dCAddress->setFullAddress(!empty($item->customer->address->full_address) ? $item->customer->address->full_address : "");
            $dCAddress->setContactFullName(!empty($item->customer->address->contact_full_name) ? $item->customer->address->contact_full_name : "");
            $dCAddress->setContactPhoneNumber(!empty($item->customer->address->contact_phone_number) ? $item->customer->address->contact_phone_number : 0);
            $dCAddress->setCity(!empty($item->customer->address->city) ? $item->customer->address->city : "");
            $dCAddress->setDistrict(!empty($item->customer->address->district) ? $item->customer->address->district : "");
            $dCAddress->setZipCode(!empty($item->customer->address->zip_code) ? $item->customer->address->zip_code : "");
            $dCustomer->setAddress($dCAddress);

            //dopigo customer devam
            $dCustomer->setEmail(!empty($item->customer->email) ? $item->customer->email: "anonymous@domain.com");
            $dCustomer->setPhoneNumber(!empty($item->customer->phone_number) ? $item->customer->phone_number: "+905555555555");
            $dCustomer->setCitizenId(!empty($item->customer->citizen_id) ? $item->customer->citizen_id: null);
            $dCustomer->setTaxId(!empty($item->customer->tax_id) ? $item->customer->tax_id: null);
            $dCustomer->setTaxOffice(!empty($item->customer->tax_office) ? $item->customer->tax_office: null);
            $dCustomer->setCompanyName(!empty($item->customer->company_name) ? $item->customer->company_name: "");
            $dopigo_order->setCustomer($dCustomer);

            //dopigo billing address
            $dBillingAddress=new DopigoBillingAddress();
            $dBillingAddress->setId(!empty($item->billing_address->id) ?  $item->billing_address->id : 0);
            $dBillingAddress->setId(!empty($item->billing_address->id) ?  $item->billing_address->id : 0);

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
        return json_decode($data)->results;
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
     * @param $arrayOfOrders array bir dizi halinde order->results parametre olarak verilmelidir ilgili veriyi veritabanına işler
     * burada veri tabanında var olan veriyi tekrar eklemeyecek bir yapı kurulmalıdır
     */
    public function syncOrdersToDatabase(array $arrayOfOrders)
    {
        try {
            foreach ($arrayOfOrders as $order) {
                $prep = self::$con->prepare("insert into siparisler(user_id, teslimat_adresi, fatura_adresi, odeme_tipi, siparis_durumu, kargo_firmasi,
                       teslimat_yetkili_id, genel_toplam, siparis_no, kart_no, eklenme_tarihi, 
                       durumu, banka_json, ekleyen_id, guncelleyen_id, silindi,dekont) 
values (:user_id,:teslimat_adresi,:fatura_adresi,:odeme_tipi,:siparis_durumu,:kargo_firmasi,:teslimat_yetkili_id,:genel_toplam,:siparis_no,:kart_no,:ekleme_tarihi,
        :durumu,:banka_json,:ekleyen_id,:guncelleyen_id,:silindi,:dekont)");
                $siparisDurumu = $order['status'];
                if ($siparisDurumu == 'pending') {
                    $siparisDurumu = 1;//yarım kalan
                }
                if ($siparisDurumu == 'waiting_shipment') {
                    $siparisDurumu = 2;//kargo bekliyor
                }
                if ($siparisDurumu == 'shipped') {
                    $siparisDurumu = 3;//kargoya verildi
                }
                if ($siparisDurumu == 'cancelled') {
                    $siparisDurumu = 4;//iptal
                }
                if ($siparisDurumu == 'undefined') {
                    $siparisDurumu = 4;//abi bunu sen istediğin gibi ayarla
                }
                if (isset($order['items']->child->children->shipment_provider)) {
                    $shipment_provider = $order['items']->child->children->shipment_provider;
                } else {
                    $shipment_provider = null;
                }
                if (isset($order['items']->child->children->owner)) {
                    $shipment_owner = $order['items']->child->children->owner;
                } else {
                    $shipment_owner = null;
                }//sipariş sahibi
                $prep->execute(array(
                    ":user_id" => $order['customer']->id,
                    ":teslimat_adresi" => $order['shipping_address']->id,
                    ":fatura_adresi" => $order['billing_address']->id,
                    ":odeme_tipi" => 1,
                    ":siparis_durumu" => $siparisDurumu,
                    ":kargo_firmasi" => $shipment_provider,//bu değer set edilmemiş olabilir
                    ":teslimat_yetkili_id" => $shipment_owner,//bu değer set edilmemiş olabilir
                    ":genel_toplam" => $order['total'],//bu değer set edilmemiş olabilir bu alanı float parse etmek gerekebilir
                    ":siparis_no" => $order['id'],//abi bunu pk yapaydın ya
                    ":kart_no" => null,//bununla ilgili dönen hicbir veri yok
                    ":ekleme_tarih" => $order['shipped_date'],
                    ":durumu" => 1,//herhangi bir aktif/pasif durumu yok bu nedenle 1 aktif yapıldı
                    ":banka_json" => null,//herhangi bir banka verisi yok bu nedenle null
                    ":ekleyen_id" => 1,//herhangi bir ekleyen verisi yok bu nedenle 1
                    ":guncelleyen_id" => 1,//herhangi bir guncelleyen verisi yok bu nedenle 1
                    ":silindi" => $order['invoice_deleted'],//fatura silindi bilgisi
                    ":dekont" => null//fatura silindi bilgisi
                ));
            }
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }

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

    private static function checkCategory($kategori_id)
    {
        $prep = self::$con->prepare("select count(id) as 'var' from product_category where id=:id");
        $prep->execute(array(':id' => $kategori_id));
        $sonuc = $prep->fetch(PDO::FETCH_ASSOC);
        if ($sonuc > 0)
            return true;
        return false;
    }

    /**
     * transaction:işlem tam anlamıyla doğrulanmadan yani tüm ürünler eklenebilir olmadan hiçbir ürünü eklemez
     * @param mixed $product dopigoProduct array
     */
    public static function addDatabaseFromProductArray(array $product)
    {
        if (isset($product) && is_array($product) && is_a($product[0], "dopigoProduct")) {
            try {
                self::$con->beginTransaction();
                foreach ($product as $item) {
                    if (is_a($item, "dopigoProduct")) {//bu sayede object gibi davranmasını sağlıyorum
                        $prep = self::$con->prepare("insert into products(id, isim, aciklama, kategori_id, vat, meta_id, sku,
                     yabanci_sku, barcode, agirlik, stok, mevcut_stok, fiyat, fiyat_para_birimi, yerel_fiyat, liste_fiyati,
                     satin_alma_fiyati, alici_odeme, birincil) VALUES (:id,:isim,:aciklama,:kategori_id,:vat,:meta_id,:sku,:yabanci_sku,:barcode,:agirlik,
                                                                       :stok,:mevcut_stok,:fiyat,:fiyat_para_birimi,:yerel_fiyat,:liste_fiyati,:satin_alma_fiyati,
                                                                       :alici_odeme,:birincil)");
                        $prep->execute(array(':id' => $item->getId(), ':isim' => $item->getIsim(), ':aciklama' => $item->getAciklama(), ':kategori_id' => $item->getKategoriId(), ':vat' => $item->getVat(), ':meta_id' => $item->getMetaId(), ':sku' => $item->getSku(), ':yabanci_sku' => $item->getYabanciSku(), ':barcode' => $item->getBarcode(), ':agirlik' => $item->getAgirlik(), ':stok' => $item->getStok(), ':mevcut_stok' => $item->getStok(), ':fiyat' => $item->getFiyat(), ':fiyat_para_birimi' => $item->getFiyatParaBirim(), ':yerel_fiyat' => $item->getYerelFiyat(), ':liste_fiyati' => $item->getListeFiyat(), ':satin_alma_fiyati' => $item->getSatinAlmaFiyati(), ':alici_odeme' => $item->getAliciOdeme(), ':birincil' => $item->getBirincil()));
                        $strImagesConcat = "";
                        foreach ($item->getResimler() as $resim) {
                            if (is_a($resim, "dopigoImage")) {
                                $strImagesConcat .= "{kesinUrl:" . strval($resim->getKesinUrl()) . ",kaynakUrl:" . $resim->getKaynakUrl() . "},";
                            }
                        }
                        $prep2 = self::$con->prepare("update products set resimler=:resimler where id=:id");
                        $prep2->execute(array(':resimler' => $strImagesConcat, ':id' => $item->getId()));

                        if (dopigo::checkCategory($item->getKategoriId())) {
                            $prep3 = self::$con->prepare("insert into product_category(id, isim) VALUES(:id,:isim) ");
                            $katName = dopigo::getCategorFromId($item->getKategoriId());
                            $prep3->execute(array(':id' => $item->getKategoriId(), ":isim" => $katName));
                        }
                    }

                }
                self::$con->commit();
                self::saveToUserProducts($product);
            } catch (PDOException $exception) {
                echo $exception->getMessage();
            }

        }
    }

    /**
     * @param array $data array of products
     */
    private static function saveToUserProducts(array $data)
    {

        if (count($data) > 0 && is_array($data)) {
            $prep = self::$con->prepare("select * from scheme_product");
            $prep->execute();
            //buradan sonra tablo field sayıları eşleşmeme durumu için prep statement seçilen tablo şemasına göre oluşturulacak

            $schemeProduct = $prep->fetch(PDO::FETCH_ASSOC);
            $schemeTable = (new dBaseTemplate($schemeProduct['targetTableName']))->getScheme();
            $stringTableInsertScheme = null;
            $stringTableInsertValues = null;
            foreach ($schemeProduct as $productKey => $fieldProduct) {
                foreach ($schemeTable as $tableKey => $fieldTable) {
                    if (strtolower($fieldProduct) == strtolower($fieldTable['Field'])) {
                        $stringTableInsertScheme .= $fieldTable['Field'] . ',';
                        $stringTableInsertValues .= ":" . $productKey . ',';
                    }

                }
            }
            $stringTableInsertValues = rtrim($stringTableInsertValues, "\,");
            $stringTableInsertScheme = rtrim($stringTableInsertScheme, "\,");


            foreach ($data as $item) {
                $productArray = array();
                $strImagesConcat = "";
                foreach ($item->getResimler() as $resim) {
                    if (is_a($resim, "dopigoImage")) {
                        $strImagesConcat .= "{kesinUrl:" . strval($resim->getKesinUrl()) . ",kaynakUrl:" . $resim->getKaynakUrl() . "},";
                    }
                }
                if (is_a($item, "dopigoProduct")) {
                    $productArray['id'] = $item->getId();
                    $productArray['isim'] = $item->getIsim();
                    $productArray['aciklama'] = $item->getAciklama();
                    $productArray['kategori_id'] = $item->getKategoriId();
                    $productArray['vat'] = $item->getVat();
                    $productArray['meta_id'] = $item->getMetaId();
                    $productArray['sku'] = $item->getSku();
                    $productArray['yabanci_sku'] = $item->getYabanciSku();
                    $productArray['barcode'] = $item->getBarcode();
                    $productArray['agirlik'] = $item->getAgirlik();
                    $productArray['stok'] = $item->getStok();
                    $productArray['mevcut_stok'] = $item->getMevcutStok();
                    $productArray['fiyat'] = $item->getFiyat();
                    $productArray['fiyat_para_birimi'] = $item->getFiyatParaBirim();
                    $productArray['yerel_fiyat'] = $item->getYerelFiyat();
                    $productArray['liste_fiyati'] = $item->getListeFiyat();
                    $productArray['satin_alma_fiyati'] = $item->getSatinAlmaFiyati();
                    $productArray['alici_odeme'] = $item->getAliciOdeme();
                    $productArray['birincil'] = $item->getBirincil();
                    $productArray['resimler'] = $strImagesConcat;
                }
                $insertValuesArray = explode(',', $stringTableInsertValues);
                $tableName = $schemeProduct['targetTableName'];
                foreach ($insertValuesArray as $item2) {
                    $insertArray[$item2] = $productArray[str_replace(array(":"), array(""), $item2)];
                }
                $query = "insert into $tableName($stringTableInsertScheme) values ($stringTableInsertValues)";
                $prep = self::$con->prepare($query);
                if (!$prep->execute($insertArray)) {
                    die($prep->errorInfo()[2]);
                } else {
                    echo "Success";
                }
            }
        }
    }
}
