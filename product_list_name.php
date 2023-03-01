<?php
class Product_List_Name extends Module {

    /**
     * @var true
     */
    private static $hasRunProductUpdate = false;

    public function __construct()
    {
        $this->name = 'product_list_name';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Adilis';

        parent::__construct();
        $this->displayName = $this->trans('Nom alternatif dans les listes produits', array(), 'Modules.ADC_extrafields.Admin');
        $this->description = $this->trans('Permet de définir un titre alternatif aux produits dans les listings', array(), 'Modules.ADC_extrafields.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return
            parent::install()
            && $this->installSQL()
            && $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
            && $this->registerHook('actionProductUpdate')
        ;
    }

    public function installSQL() {
        $extra_fields = [
            'product_lang' => [
                [
                    'field' => 'product_list_name',
                    'query' => "ADD product_list_name varchar(128) NOT NULL DEFAULT ''"
                ],
            ],
        ];

        foreach($extra_fields as $table => $fields) {
            $definitions = Db::getInstance()->executeS('DESCRIBE '._DB_PREFIX_.$table);
            foreach($fields as $field) {
                $field_exists = false;
                foreach($definitions as $definition) {
                    if ($field['field'] == $definition['Field']) {
                        $field_exists = true;
                        break;
                    }
                }
                if (!$field_exists) {
                    Db::getInstance()->execute('ALTER TABLE '._DB_PREFIX_.$table.' '.$field['query']);
                }
            }
        }
        return true;

    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle(array $params)
    {
        $product = new Product($params['id_product']);
        $this->context->smarty->assign([
            'product_list_name' => $product->product_list_name,
            'languages' => Language::getLanguages(),
            'default_language' => $this->context->employee->id_lang
        ]);

        return $this->display(__FILE__, 'views/templates/hook/product.mainstepleftcolumnmiddle.tpl');
    }

    public function hookActionProductUpdate(array $params)
    {
        if (self::$hasRunProductUpdate) {
            return;
        }
        self::$hasRunProductUpdate = true;
        if (Tools::getIsset('product_list_name_update')) {
            $product_list_name = [];
            foreach (LanguageCore::getLanguages(false) as $lang) {
                $product_list_name[$lang['id_lang']] = Tools::getValue('product_list_name_lang_'.$lang['id_lang'], '');
            }
            $product = new Product((int)$params['id_product']);
            if (Validate::isLoadedObject($product)) {
                $product->product_list_name = $product_list_name;
                $product->update();
            }
        }
    }
}