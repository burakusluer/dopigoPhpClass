<?php

/**
 * Class dopigoProduct
 * Unutulmamalıdır ki burada alıncak ürünlerin products array i count(get_products())==0 ise bu bir basit ürün aksi
 * takdirde varyantlı üründür product[0] base diğerleri ise varyasyonlarıdır products get_products() getter
 * kullanılarak çağırılır array'a ait her bir std class object bir sub product 0'ıncı base dir
 */
class Dopigo_product
{

    /**
     * Dopigo_product constructor.
     * @param null|string $name
     * @param null|string $description
     * @param null|string $category
     * @param int $vat
     * @param array|null $products
     */
    public function __construct($name=null,$description=null,$category=null,$vat=0,array $products=null)
    {
        $this->_name=$name;
        $this->_description=$description;
        $this->_category=$category;
        $this->_vat=$vat;
        $this->_products=$products;
    }

    private $_name, $_description, $_category, $_vat, $_products;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->_category = $category;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->_vat;
    }

    /**
     * @param int $vat
     */
    public function setVat($vat)
    {
        $this->_vat = $vat;
    }

    /**
     * @return array
     */
    public function getProducts()
    {
        return $this->_products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products)
    {
        $this->_products = $products;
    }


}

/**
 * Class Dopigo_sub_product
 * bu class main product'ın altında yer alan(_products array'i kast ederek) ürünleri sembolize eder birden fazla ürün
 * var ise 0 indisindeki array base product diğerleri ise variation product dır yani ürün varyasyonlarıdır
 */
class Dopigo_sub_product
{

    /**
     * para birimlerinde virgülden sonra 2 hane bırakın ayraç '.' örnek price_local: "2989.05"
     * Dopigo_sub_product constructor.
     * @param int $id ürün id
     * @param int $meta_id ürün meta_id
     * @param null|string $sku ürün stokkodu
     * @param null|string $foreign_sku tedarikçi ürün kodu
     * @param null|string $barcode barkod
     * @param float $weight ağarlık
     * @param int $stock stock
     * @param int $available_stock anlık stok
     * @param float $price ürün fiyatı
     * @param string $price_currency para birimi
     * @param float $price_local yerel fiyat
     * @param float $listing_price liste fiyatı
     * @param float $purchase_price satın alma fiyatı
     * @param bool $buyer_pays_shipping satın alan öder
     * @param array|null $images resimler dizisi  dopigoImage class ından object dizisi
     * @param array|null $custom_attributes ekstra özellikler dizisi['özellik'=>'değeri']
     * @param bool $is_primary ana ürün olma durumu çok önemli değil zaten 0'ıncı ürünü dikkate al tavsiye
     * @param metaData|null $meta ürün meta data
     */
    public function __construct($id = 0, $meta_id = 0, $sku = null, $foreign_sku = null, $barcode = null, $weight =
    0.0, $stock = 0,
                                $available_stock = 0, $price = 0.0, $price_currency = "EUR", $price_local = 0.0,
                                $listing_price = 0.0, $purchase_price = 0.0, $buyer_pays_shipping = true, array $images = null,
                                array $custom_attributes = null, $is_primary = false, metaData $meta = null)
    {
        $this->_id=$id;
        $this->_stock=$stock;
        $this->_meta_id=$meta_id;
        $this->_sku=$sku;
        $this->_foreign_sku=$foreign_sku;
        $this->_barcode=$barcode;
        $this->_weight=$weight;
        $this->_available_stock = $available_stock;
        $this->_price = $price;
        $this->_price_currency=$price_currency;
        $this->_price_local=$price_local;
        $this->_listing_price=$listing_price;
        $this->_purchase_price=$purchase_price;
        $this->_buyer_pays_shipping=$buyer_pays_shipping;
        $this->_images=$images;
        $this->_custom_attributes=$custom_attributes;
        $this->_is_primary=$is_primary;
        $this->_meta=$meta;
    }

    private
        $_id, $_meta_id, $_sku, $_foreign_sku, $_barcode, $_weight, $_stock, $_available_stock, $_price,
        $_price_currency, $_price_local, $_listing_price, $_purchase_price, $_buyer_pays_shipping = true,
        $_images, $_custom_attributes, $_is_primary = false, $_meta;

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
    public function getMetaId()
    {
        return $this->_meta_id;
    }

    /**
     * @param int $meta_id
     */
    public function setMetaId($meta_id)
    {
        $this->_meta_id = $meta_id;
    }

    /**
     * @return string|null
     */
    public function getSku()
    {
        return $this->_sku;
    }

    /**
     * @param string|null $sku
     */
    public function setSku($sku)
    {
        $this->_sku = $sku;
    }

    /**
     * @return string|null
     */
    public function getForeignSku()
    {
        return $this->_foreign_sku;
    }

    /**
     * @param string|null $foreign_sku
     */
    public function setForeignSku($foreign_sku)
    {
        $this->_foreign_sku = $foreign_sku;
    }

    /**
     * @return string|null
     */
    public function getBarcode()
    {
        return $this->_barcode;
    }

    /**
     * @param string|null $barcode
     */
    public function setBarcode($barcode)
    {
        $this->_barcode = $barcode;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->_weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->_weight = $weight;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->_stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->_stock = $stock;
    }

    /**
     * @return int
     */
    public function getAvailableStock()
    {
        return $this->_available_stock;
    }

    /**
     * @param int $available_stock
     */
    public function setAvailableStock($available_stock)
    {
        $this->_available_stock = $available_stock;
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
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->_price_currency;
    }

    /**
     * @param string $price_currency
     */
    public function setPriceCurrency($price_currency)
    {
        $this->_price_currency = $price_currency;
    }

    /**
     * @return float
     */
    public function getPriceLocal()
    {
        return $this->_price_local;
    }

    /**
     * @param float $price_local
     */
    public function setPriceLocal($price_local)
    {
        $this->_price_local = $price_local;
    }

    /**
     * @return float
     */
    public function getListingPrice()
    {
        return $this->_listing_price;
    }

    /**
     * @param float $listing_price
     */
    public function setListingPrice($listing_price)
    {
        $this->_listing_price = $listing_price;
    }

    /**
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->_purchase_price;
    }

    /**
     * @param float $purchase_price
     */
    public function setPurchasePrice($purchase_price)
    {
        $this->_purchase_price = $purchase_price;
    }

    /**
     * @return bool
     */
    public function isBuyerPaysShipping()
    {
        return $this->_buyer_pays_shipping;
    }

    /**
     * @param bool $buyer_pays_shipping
     */
    public function setBuyerPaysShipping($buyer_pays_shipping)
    {
        $this->_buyer_pays_shipping = $buyer_pays_shipping;
    }

    /**
     * @return array|null
     */
    public function getImages()
    {
        return $this->_images;
    }

    /**
     * @param array|null $images
     */
    public function setImages($images)
    {
        $this->_images = $images;
    }

    /**
     * @return array|null
     */
    public function getCustomAttributes()
    {
        return $this->_custom_attributes;
    }

    /**
     * @param array|null $custom_attributes
     */
    public function setCustomAttributes($custom_attributes)
    {
        $this->_custom_attributes = $custom_attributes;
    }

    /**
     * @return bool
     */
    public function isIsPrimary()
    {
        return $this->_is_primary;
    }

    /**
     * @param bool $is_primary
     */
    public function setIsPrimary($is_primary)
    {
        $this->_is_primary = $is_primary;
    }

    /**
     * @return metaData|null
     */
    public function getMeta()
    {
        return $this->_meta;
    }

    /**
     * @param metaData|null $meta
     */
    public function setMeta($meta)
    {
        $this->_meta = $meta;
    }
}

/**
 * Class metaData
 * bir sub_product a ait meta değerleri bunlar şu şekildedir vat(value added tax/türkçe mailiylen kdv oluyor),
 * name ürün adı(metada farklı verilmiş olabilir)
 * description ürün açıklaması
 * active bool ürünün satışa açıklık durumudur default true
 */
class metaData
{
    /**
     * metaData constructor.
     * @param null|string $name ürün adı
     * @param null|string $description ürün açıklaması
     * @param int $vat KDV
     * @param bool $active ürün satışa açık mı ?
     */
    public function __construct($name = null, $description = null, $vat = 18, $active = true)
    {
        $this->_vat = $vat;
        $this->_name = $name;
        $this->_description = $description;
        $this->active = $active;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->_vat;
    }

    /**
     * @param int $vat
     */
    public function setVat($vat)
    {
        $this->_vat = $vat;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    private $_vat, $_name, $_description, $active = true;
}

/**
 * Class dopigoImage
 * dopigo image url'leri için tanımlanmıştır
 */
class dopigoImage
{

    public function __construct($kesin_url=null,$kaynak_url=null)
    {
        $this->_kesin_url=$kesin_url;
        $this->_kaynak_url=$kaynak_url;
    }

    private $_kesin_url, $_kaynak_url;

    /**
     * @return string
     */
    public function getKesinUrl()
    {
        return $this->_kesin_url;
    }

    /**
     * @param string $kesin_url
     */
    public function setKesinUrl($kesin_url)
    {
        $this->_kesin_url = $kesin_url;
    }

    /**
     * @return string
     */
    public function getKaynakUrl()
    {
        return $this->_kaynak_url;
    }

    /**
     * @param string $kaynak_url
     */
    public function setKaynakUrl($kaynak_url)
    {
        $this->_kaynak_url = $kaynak_url;
    }
}