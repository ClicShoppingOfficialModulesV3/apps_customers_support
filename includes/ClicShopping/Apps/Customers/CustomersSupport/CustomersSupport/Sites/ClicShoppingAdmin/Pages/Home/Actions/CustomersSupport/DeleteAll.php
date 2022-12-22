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
  use ClicShopping\OM\HTML;

  class DeleteAll extends \ClicShopping\OM\PagesActionsAbstract
  {
    protected mixed $app;

    public function __construct()
    {
      $this->app = Registry::get('CustomersSupport');
    }

    public function execute()
    {
      $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

       if (!empty($_POST['selected']) && isset($_POST['selected'])) {
         foreach ($_POST['selected'] as $id) {
          $Qdelete = $this->app->db->prepare('delete
                                              from :table_contact_customers
                                              where contact_customers_id = :contact_customers_id
                                            ');
          $Qdelete->bindInt(':contact_customers_id', $id);
          $Qdelete->execute();

          $Qdelete = $this->app->db->prepare('delete
                                              from :table_contact_customers_follow
                                              where contact_customers_id = :contact_customers_id
                                            ');
          $Qdelete->bindInt(':contact_customers_id', $id);
          $Qdelete->execute();
        }
      }

      $this->app->redirect('CustomersSupport&page=' . $page);
    }
  }
