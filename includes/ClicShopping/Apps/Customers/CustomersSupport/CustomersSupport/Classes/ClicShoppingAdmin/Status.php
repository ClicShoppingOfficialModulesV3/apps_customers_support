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

  namespace ClicShopping\Apps\Customers\CustomersSupport\Classes\ClicShoppingAdmin;

  use ClicShopping\OM\Registry;

  class Status
  {
    protected int $contact_customers_id;
    protected int $contact_customers_archive;

    /**
     * Status contact customers -  Sets the archive of a contact customers
     *
     * @param int $contact_customers_id , contact_customers_archive
     * @param int $contact_customers_archive
     * @return int $contact_customers_archive on or off
     */
    Public static function getArchiveStatus(int $contact_customers_id, int $contact_customers_archive)
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      if ($contact_customers_archive == 1) {
        return $CLICSHOPPING_Db->save('contact_customers', ['contact_customers_archive' => 1], ['contact_customers_id' => (int)$contact_customers_id]);
      } elseif ($contact_customers_archive == 0) {
        return $CLICSHOPPING_Db->save('contact_customers', ['contact_customers_archive' => 0], ['contact_customers_id' => (int)$contact_customers_id]);
      } else {
        return -1;
      }
    }

    /**
     * Spam status
     *
     * @param string $email , contact_customers_archive
     * @param int $spam
     * @return int $spam on or off
     */
    Public static function getSpamStatus(string $email, int $spam)
    {
      $CLICSHOPPING_Db = Registry::get('Db');

      if ($spam == 1) {
        return $CLICSHOPPING_Db->save('contact_customers', ['spam' => 1], ['contact_email_address' => $email]);
      } elseif ($spam == 0) {
        return $CLICSHOPPING_Db->save('contact_customers', ['spam' => 0], ['contact_email_address' => $email]);
      } else {
        return -1;
      }
    }
  }