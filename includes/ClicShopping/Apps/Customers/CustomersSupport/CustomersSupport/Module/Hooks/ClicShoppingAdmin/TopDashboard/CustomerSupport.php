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

  namespace ClicShopping\Apps\Customers\CustomersSupport\Module\Hooks\ClicShoppingAdmin\TopDashboard;

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Customers\CustomersSupport\CustomersSupport as CustomersSupportApp;

  class CustomerSupport implements \ClicShopping\OM\Modules\HooksInterface
  {
    /**
     * @var bool|null
     */
    protected mixed $app;

    public function __construct()
    {
      if (!Registry::exists('CustomersSupport')) {
        Registry::set('CustomersSupport', new CustomersSupportApp());
      }

      $this->app = Registry::get('CustomersSupport');

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/TopDashboard/dashboard_top_customer_support');
    }

    public function Display(): string
    {
      if (!\defined('CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS') || CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS == 'False') {
        return false;
      }

      $QstatOrders = $this->app->db->prepare('select count(contact_customers_id) as count
                                              from :table_contact_customers
                                              where contact_customers_archive = 0
                                              and spam = 0
                                             ');
      $QstatOrders->execute();

      $number_support = $QstatOrders->valueInt('count');

      $QstatOrders = $this->app->db->prepare('select count(contact_customers_id) as countSpam
                                              from :table_contact_customers
                                              where contact_customers_archive = 0
                                              and spam = 1
                                             ');
      $QstatOrders->execute();

      $count_spam = $QstatOrders->valueInt('countSpam');

      If ($count_spam) {
        $text_spam = '<span class="text-danger"><i class="bi bi-dash-circle"></i></span>';
      } else {
        $text_spam = '';
      }

      $text = $this->app->getDef('text_number_support');
      $text_view = $this->app->getDef('text_view');
      $output = '';

      if ($number_support > 0) {
        $output = '
<div class="col-md-2 col-12 m-1">
    <div class="card bg-warning">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
            <span class="col-sm-10"><h6 class="card-title text-white"><i class="bi bi-headset"></i> ' . $text . '</h6></span>
            <span class="col-sm-2 text-end">' . $text_spam . '</span>
            </div>
          </div> 
        </div>
        <div class="col-md-12">
          <span class="text-white"><strong>' . $number_support . '</strong></span>
          <span><small class="text-white">' . HTML::link(CLICSHOPPING::link(null, 'A&Customers\CustomersSupport&CustomersSupport'), $text_view, 'class="text-white"') . '</small></span>
        </div>
      </div>
    </div>
</div>
';
      }

      return $output;
    }
  }