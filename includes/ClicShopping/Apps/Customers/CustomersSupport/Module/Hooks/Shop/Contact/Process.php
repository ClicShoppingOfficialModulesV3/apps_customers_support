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

  namespace ClicShopping\Apps\Customers\CustomersSupport\Module\Hooks\Shop\Contact;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Mail;


  use ClicShopping\Apps\Customers\CustomersSupport\CustomersSupport as CustomersSupportApp;

  class Process implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected mixed $app;

    public function __construct()
    {
      if (!Registry::exists('CustomersSupport')) {
        Registry::set('CustomersSupport', new CustomersSupportApp());
      }

      $this->app = Registry::get('CustomersSupport');
      $this->app->loadDefinitions('Module/Hooks/Shop/Contact/process');
    }

    public function execute()
    {
      $CLICSHOPPING_Customer = Registry::get('Customer');
      $CLICSHOPPING_Language = Registry::get('Language');
      $CLICSHOPPING_Mail = Registry::get('Mail');

      $name = HTML::sanitize($_POST['name']);
      $email_address = HTML::sanitize($_POST['email']);
      $enquiry = HTML::sanitize($_POST['enquiry']);
      $email_subject = HTML::sanitize($_POST['email_subject']);

      if (isset($_POST['order_id'])) {
        $order_id = HTML::sanitize($_POST['order_id']);
      } else {
        $order_id = 0;
      }

      $customers_telephone = HTML::sanitize($_POST['customers_telephone']);

      if (isset($_POST['send_to'])) {
        $send_to = HTML::sanitize($_POST['send_to']);
      } else {
        $send_to = null;
      }

      if (!\defined('CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS') || CLICSHOPPING_APP_CUSTOMERS_CUSTOMERS_SUPPORT_CS_STATUS == 'False') {
        return false;
      }

      $contact_department = '';

      if (!empty(CONTACT_DEPARTMENT_LIST)) {
        $send_to_array = explode(',', CONTACT_DEPARTMENT_LIST);
        preg_match('/\<[^>]+\>/', $send_to_array[$send_to], $send_email_array);
        $contact_department = $send_to_array[$send_to];
      }

      if ($CLICSHOPPING_Customer->isLoggedOn()) {
        if ($order_id != 0) {
          $sql_data_array = [
            'contact_department' => $contact_department,
            'contact_name' => $name,
            'contact_email_address' => $email_address,
            'contact_email_subject' => $email_subject,
            'contact_enquiry' => $enquiry,
            'languages_id' => (int)$CLICSHOPPING_Language->getId(),
            'contact_date_added' => 'now()',
            'customer_id' => (int)$CLICSHOPPING_Customer->getID(),
            'contact_telephone' => $customers_telephone,
            'contact_customers_status' => 0
          ];

          $this->app->db->save('contact_customers', $sql_data_array);
        }
      } elseif ($order_id == 0) {
          if ($CLICSHOPPING_Mail->excludeEmailDomain($email_address) === true) {
            return false;
          }

        $Qspam = $this->app->db->prepare('select count(contact_email_address) as count
                                          from :table_contact_customers
                                          where spam = 1
                                          and contact_email_address = :contact_email_address
                                        ');

        $Qspam->bindValue('contact_email_address', $email_address);
        $Qspam->execute();

        if ($Qspam->valueInt('count') > 0) {
          $this->app->db->save('contact_customers', ['spam' => 1], ['contact_email_address' => $email_address]);
          return false;
        } else {
            $sql_data_array = [
              'contact_department' => $contact_department,
              'contact_name' => $name,
              'contact_email_address' => $email_address,
              'contact_email_subject' => $email_subject,
              'contact_enquiry' => $enquiry,
              'contact_date_added' => 'now()',
              'languages_id' => (int)$CLICSHOPPING_Language->getId(),
              'contact_customers_archive' => 0,
              'contact_customers_status' => 0,
              'customer_id' => 0,
              'contact_telephone' => $customers_telephone
            ];

            $this->app->db->save('contact_customers', $sql_data_array);
        }
      }
    }
  }