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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\HTTP;
  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Configuration\TemplateEmail\Classes\ClicShoppingAdmin\TemplateEmailAdmin;
  use ClicShopping\Apps\Configuration\Administrators\Classes\ClicShoppingAdmin\AdministratorAdmin;

  class Update extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function __construct()
    {
      $this->app = Registry::get('CustomersSupport');
    }

    public function execute()
    {

      $CLICSHOPPING_Mail = Registry::get('Mail');
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

      if (isset($_GET['Update'])) {
        $contact_customers_id = HTML::sanitize($_GET['rID']);
        $customers_response = $_POST['customers_response'];
        $administrator_user_name = AdministratorAdmin::getUserAdmin();
        $contact_customers_archive = HTML::sanitize($_POST['contact_customers_archive']);
        $contact_customers_status = HTML::sanitize($_POST['contact_customers_status']);
        $contact_name = HTML::sanitize($_POST['contact_name']);
        $contact_email_address = HTML::sanitize($_POST['contact_email_address']);

        if (!empty($customers_response)) {
          $sql_data_array = [
            'contact_customers_id' => (int)$contact_customers_id,
            'administrator_user_name' => $administrator_user_name,
            'customers_response' => $customers_response,
            'contact_date_sending' => 'now()'
          ];
          $this->app->db->save('contact_customers_follow', $sql_data_array);
        }

        $Qupdate = $this->app->db->prepare('update :table_contact_customers
                                            set contact_customers_archive = :contact_customers_archive,
                                                 contact_customers_status = :contact_customers_status
                                            where contact_customers_id = :contact_customers_id
                                          ');
        $Qupdate->bindInt(':contact_customers_archive', $contact_customers_archive);
        $Qupdate->bindInt(':contact_customers_status', $contact_customers_status);
        $Qupdate->bindInt(':contact_customers_id', $contact_customers_id);
        $Qupdate->execute();

        $from = $this->app->getDef('email_from');
        $subject = $this->app->getDef('subject_email', ['store_name' => STORE_NAME]);
        $message = $_POST['customers_response'];

// email template
        $template_email_signature = TemplateEmailAdmin::getTemplateEmailSignature();
        $template_email_footer = TemplateEmailAdmin::getTemplateEmailTextFooter();
        $consult_message = $this->app->getDef('text_info_message');

        $message .= '<br />' . $consult_message . '<br />' . $template_email_signature . '<br />' . $template_email_footer;
        $message = $message;

// Envoie du mail avec gestion des images pour Fckeditor
        $message = html_entity_decode($message);
        $message = str_replace('src="/', 'src="' . HTTP::getShopUrlDomain(), $message);

        $CLICSHOPPING_Mail->addHtmlCkeditor($message);
        ;
        $CLICSHOPPING_Mail->send($contact_name, $contact_email_address, null, $from, $subject);

        $CLICSHOPPING_Hooks->call('CustomersSupport', 'Update');

        $this->app->redirect('CustomersSupport&page=' . $page . '&rID=' . $contact_customers_id);
      }
    }
  }