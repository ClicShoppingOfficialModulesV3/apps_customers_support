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


  namespace ClicShopping\Apps\Customers\CustomersSupport\Sites\ClicShoppingAdmin\Pages\Home\Actions\CustomersSupport;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Customers\CustomersSupport\Classes\ClicShoppingAdmin\Status;

  class SetFlag extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {
      $CLICSHOPPING_CustomersSupport = Registry::get('CustomersSupport');

      $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

      Status::getArchiveStatus((int)$_GET['id'], (int)$_GET['flag']);

      $CLICSHOPPING_CustomersSupport->redirect('CustomersSupport&' . $page . 'rID=' . (int)$_GET['id']);
    }
  }

