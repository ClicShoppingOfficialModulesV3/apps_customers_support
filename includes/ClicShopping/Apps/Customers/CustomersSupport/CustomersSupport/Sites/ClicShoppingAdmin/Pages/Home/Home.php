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

  namespace ClicShopping\Apps\Customers\CustomersSupport\Sites\ClicShoppingAdmin\Pages\Home;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Customers\CustomersSupport\CustomersSupport;

  class Home extends \ClicShopping\OM\PagesAbstract
  {
    public mixed $app;

    protected function init()
    {
      $CLICSHOPPING_CustomersSupport = new CustomersSupport();
      Registry::set('CustomersSupport', $CLICSHOPPING_CustomersSupport);

      $this->app = Registry::get('CustomersSupport');

      $this->app->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }
