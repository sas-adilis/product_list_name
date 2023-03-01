<?php

class Product extends ProductCore
{

    public $product_list_name;

    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null) {
        self::$definition['fields']['product_list_name'] = ['type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'required' => false, 'size' => 128];
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
    }

}
