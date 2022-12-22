<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Apps\Customers\CustomersSupport\Sites\ClicShoppingAdmin\Pages\Home\Actions\Configure;

  use ClicShopping\OM\Registry;

  use ClicShopping\OM\Cache;

  class Install extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {

      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_CustomersSupport = Registry::get('CustomersSupport');

      $current_module = $this->page->data['current_module'];

      $CLICSHOPPING_CustomersSupport->loadDefinitions('Sites/ClicShoppingAdmin/install');

      $m = Registry::get('CustomersSupportAdminConfig' . $current_module);
      $m->install();

      static::installDbMenuAdministration();
      static::installDb();

      $CLICSHOPPING_MessageStack->add($CLICSHOPPING_CustomersSupport->getDef('alert_module_install_success'), 'success', 'customers_support');

      $CLICSHOPPING_CustomersSupport->redirect('Configure&module=' . $current_module);
    }

    private static function installDbMenuAdministration()
    {
      $CLICSHOPPING_CustomersSupport = Registry::get('CustomersSupport');
      $CLICSHOPPING_Language = Registry::get('Language');
      $Qcheck = $CLICSHOPPING_CustomersSupport->db->get('administrator_menu', 'app_code', ['app_code' => 'app_customers_customers_support']);

      if ($Qcheck->fetch() === false) {

        $sql_data_array = ['sort_order' => 6,
          'link' => 'index.php?A&Customers\CustomersSupport&CustomersSupport',
          'image' => 'customers_services.png',
          'b2b_menu' => 1,
          'access' => 0,
          'app_code' => 'app_customers_customers_support'
        ];

        $insert_sql_data = ['parent_id' => 4];

        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

        $CLICSHOPPING_CustomersSupport->db->save('administrator_menu', $sql_data_array);

        $id = $CLICSHOPPING_CustomersSupport->db->lastInsertId();

        $languages = $CLICSHOPPING_Language->getLanguages();

        for ($i = 0, $n = \count($languages); $i < $n; $i++) {

          $language_id = $languages[$i]['id'];

          $sql_data_array = ['label' => $CLICSHOPPING_CustomersSupport->getDef('title_menu')];

          $insert_sql_data = ['id' => (int)$id,
            'language_id' => (int)$language_id
          ];

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          $CLICSHOPPING_CustomersSupport->db->save('administrator_menu_description', $sql_data_array);
        }

        Cache::clear('menu-administrator');
      }
    }

    private static function installDb()
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      $Qcheck = $CLICSHOPPING_Db->query('show tables like ":table_contact_customers"');

      if ($Qcheck->fetch() === false) {
        $sql = <<<EOD
CREATE TABLE :table_contact_customers (
  contact_customers_id int(11) NOT NULL,
  contact_department varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_email_address varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_email_subject varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_enquiry longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_date_added datetime NOT NULL,
  languages_id int(11) NOT NULL,
  contact_customers_archive tinyint(1) NOT NULL DEFAULT '0',
  contact_customers_status tinyint(1) NOT NULL,
  customer_id int(11) NOT NULL DEFAULT '0',
  contact_telephone varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  spam tinyint(1) NOT NULL DEFAULT '0'  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE :table_contact_customers ADD PRIMARY KEY (contact_customers_id), ADD KEY idx_contact_name (contact_name);
  
ALTER TABLE :table_contact_customers MODIFY contact_customers_id int(11) NOT NULL AUTO_INCREMENT;
EOD;
        $CLICSHOPPING_Db->exec($sql);
      }

      $Qcheck = $CLICSHOPPING_Db->query('show tables like ":table_contact_customers_follow"');

      if ($Qcheck->fetch() === false) {
        $sql = <<<EOD
CREATE TABLE :table_contact_customers_follow (
  id_contact_customers_follow int(11) NOT NULL,
  contact_customers_id int(11) NOT NULL,
  administrator_user_name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  customers_response longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  contact_date_sending datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE :table_contact_customers_follow ADD PRIMARY KEY (id_contact_customers_follow);

ALTER TABLE :table_contact_customers_follow MODIFY id_contact_customers_follow int(11) NOT NULL AUTO_INCREMENT;
EOD;
        $CLICSHOPPING_Db->exec($sql);
      }
    }
  }
