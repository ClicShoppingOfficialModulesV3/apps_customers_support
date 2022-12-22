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

  namespace ClicShopping\Apps\Customers\CustomersSupport\Module\Hooks\ClicShoppingAdmin\StatsDashboard;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Customers\CustomersSupport\CustomersSupport as CustomersSupportApp;

  class PageTabContent implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected mixed $app;

    public function __construct()
    {
      if (!Registry::exists('CustomersSupport')) {
        Registry::set('CustomersSupport', new CustomersSupportApp());
      }

      $this->app = Registry::get('CustomersSupport');

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/StatsDashboard/page_tab_content');
    }

    private function statsCountContactCustomer()
    {
      $QcontactCustomers = $this->app->db->prepare('select count(contact_customers_id) as count
                                                    from :table_contact_customers
                                                    where contact_customers_archive = 0
                                                    and spam = 0
                                                  ');
      $QcontactCustomers->execute();

      $contact_customers_total = $QcontactCustomers->valueInt('count');
      return $contact_customers_total;
    }


    public function display()
    {
      if (!\defined('CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS') || CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS == 'False') {
        return false;
      }

      if ($this->statsCountContactCustomer() != 0) {

        $content = '
        <div class="row">
          <div class="col-md-11 mainTable">
            <div class="form-group row">
              <label for="' . $this->app->getDef('box_entry_contact_customer') . '" class="col-9 col-form-label"><a href="' . $this->app->link('CustomersSupport') . '">' . $this->app->getDef('box_entry_contact_customer') . '</a></label>
              <div class="col-md-3">
                ' . $this->statsCountContactCustomer() . '
              </div>
            </div>
          </div>
        </div>
       ';

        $output = <<<EOD
  <!-- ######################## -->
  <!--  Start Customer Support      -->
  <!-- ######################## -->
             {$content}
  <!-- ######################## -->
  <!--  Start Customer Support      -->
  <!-- ######################## -->
EOD;
        return $output;
      }
    }
  }