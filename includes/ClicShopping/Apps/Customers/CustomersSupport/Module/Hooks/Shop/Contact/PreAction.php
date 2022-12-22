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

use ClicShopping\Apps\Customers\CustomersSupport\CustomersSupport as CustomersSupportApp;

class PreAction implements \ClicShopping\OM\Modules\HooksInterface
{
  protected mixed $app;
  protected mixed $mail;

  public function __construct()
  {
    if (!Registry::exists('CustomersSupport')) {
      Registry::set('CustomersSupport', new CustomersSupportApp());
    }

    if (!Registry::exists('Mail')) {
      Registry::set('Mail', new Mail());
    }

    $this->mail = Registry::get('Mail');
    $this->app = Registry::get('CustomersSupport');
    $this->app->loadDefinitions('Module/Hooks/Shop/Contact/process');
  }

  public function execute()
  {
    $email_address = HTML::sanitize($_POST['email']);

    $Qcheck = $this->app->db->prepare('select count(contact_email_address) as count
                                          from :table_contact_customers
                                          where spam = 1
                                          and contact_email_address = :contact_email_address
                                        ');

    $Qcheck->bindValue('contact_email_address', $email_address);
    $Qcheck->execute();

    if ($Qcheck->valueInt('count') >= 1) {
      exit;
    }

    if ( $this->mail->validateDomainEmail($email_address) === false ||  $this->mail->excludeEmailDomain($email_address) === true) {
      exit;
    }
  }
}
