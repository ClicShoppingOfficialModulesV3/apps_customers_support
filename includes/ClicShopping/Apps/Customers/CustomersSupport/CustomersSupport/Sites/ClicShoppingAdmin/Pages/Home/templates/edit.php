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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\DateTime;
  use ClicShopping\OM\Registry;

  use ClicShopping\Sites\ClicShoppingAdmin\HTMLOverrideAdmin;

  $CLICSHOPPING_CustomersSupport = Registry::get('CustomersSupport');
  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
  $CLICSHOPPING_Language = Registry::get('Language');

  $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;

  if (isset($_GET['rID'])) {
    $rID = HTML::sanitize($_GET['rID']);
  } else {
    return false;
  }

  $Qcontact = $CLICSHOPPING_CustomersSupport->db->prepare('select *
                                                           from :table_contact_customers
                                                           where contact_customers_id = :contact_customers_id
                                                          ');
  $Qcontact->bindInt(':contact_customers_id', $rID);
  $Qcontact->execute();

  //creation du tableau pour le  dropdown des status des messages
  $contact_customers_status_array = [
    array('id' => '0', 'text' => $CLICSHOPPING_CustomersSupport->getDef('entry_status_message_realised')),
    array('id' => '1', 'text' => $CLICSHOPPING_CustomersSupport->getDef('entry_status_message_not_realised'))
  ];

  //creation du tableau pour le  dropdown pour les archives
  $contact_customers_archive_array = [
    array('id' => '1', 'text' => $CLICSHOPPING_CustomersSupport->getDef('entry_archive_yes')),
    array('id' => '0', 'text' => $CLICSHOPPING_CustomersSupport->getDef('entry_archive_no'))
  ];
?>
<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span
            class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/customers_services.png', $CLICSHOPPING_CustomersSupport->getDef('heading_title'), '40', '40'); ?></span>
          <span
            class="col-md-3 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_CustomersSupport->getDef('heading_title'); ?></span>
          <span class="col-md-8 float-end text-end">
<?php
  echo HTML::form('contact', $CLICSHOPPING_CustomersSupport->link('CustomersSupport&Update&page=' . $page . '&rID=' . $rID));
  echo HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_back'), null, $CLICSHOPPING_CustomersSupport->link('CustomersSupport&page=' . $page . '&rID=' . $rID), 'primary') . '&nbsp;';
  echo HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_update'), null, null, 'success') . '&nbsp;';
  echo HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_delete'), null, $CLICSHOPPING_CustomersSupport->link('CustomersSupport&Delete&page=' . $page . '&rID=' . $rID), 'danger');
  echo HTMLOverrideAdmin::getCkeditor();
?>
            </span>
        </div>
      </div>
    </div>
  </div>

  <div class="separator"></div>
  <div>
    <ul class="nav nav-tabs flex-column flex-sm-row" role="tablist" id="myTab">
      <li
        class="nav-item"><?php echo '<a href="#tab1" role="tab" data-bs-toggle="tab" class="nav-link active">' . $CLICSHOPPING_CustomersSupport->getDef('tab_general') . '</a>'; ?></li>
      <li
        class="nav-item"><?php echo '<a href="#tab2" role="tab" data-bs-toggle="tab" class="nav-link">' . $CLICSHOPPING_CustomersSupport->getDef('tab_history'); ?></a></li>
    </ul>
    <div class="tabsClicShopping">
      <div class="tab-content">
        <?php
          // -- ------------------------------------------------------------ //
          // --          ONGLET Information General contact client          //
          // -- ------------------------------------------------------------ //
        ?>

        <div class="tab-pane active" id="tab1">
          <div class="col-md-12 mainTitle">
            <div
              class="float-start"><?php echo $CLICSHOPPING_CustomersSupport->getDef('title_review_general'); ?></div>
          </div>
          <div class="adminformTitle">

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_contact_id'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_contact_id'); ?></label>
                  <div class="col-md-5">
                    <?php
                      if (empty($Qcontact->valueInt('contact_customers_id'))) {
                        $customers_id = '';
                      } else {
                        echo $Qcontact->valueInt('contact_customers_id');
                      }
                    ?>
                  </div>
                </div>
              </div>

              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customer_id'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customer_id'); ?></label>
                  <div class="col-md-5">
                    <?php echo $Qcontact->valueInt('customer_id'); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_department'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_department'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::hiddenField('contact_department', $Qcontact->value('contact_department')) . $Qcontact->value('contact_department'); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_name'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_name'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::hiddenField('contact_name', $Qcontact->value('contact_name')) . $Qcontact->value('contact_name'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_telephone'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_telephone'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::hiddenField('contact_telephone', $Qcontact->value('contact_telephone')) . $Qcontact->value('contact_telephone'); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_email_address'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_email_address'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::hiddenField('contact_email_address', $Qcontact->value('contact_email_address')) . $Qcontact->value('contact_email_address'); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_email_subject'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_email_subject'); ?></label>
                  <div class="col-md-5">
                    <?php echo $Qcontact->value('contact_email_subject'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_date_added'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_date_added'); ?></label>
                  <div class="col-md-5">
                    <?php echo DateTime::toShort($Qcontact->value('contact_date_added')); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_archive'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_archive'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::selectMenu('contact_customers_archive', $contact_customers_archive_array, (($Qcontact->valueInt('contact_customers_archive') == '0') ? '0' : '1')); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_status_message'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_status_message'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::selectMenu('contact_customers_status', $contact_customers_status_array, (($Qcontact->value('contact_customers_status') == '0') ? '0' : '1')); ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_message'); ?>"
                         class="col-2 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_customers_message'); ?></label>
                  <div class="col-md-10">
                    <?php echo $Qcontact->value('contact_enquiry'); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="separator">&nbsp;</div>
          <div class="col-md-12 mainTitle">
            <div
              class="float-start"><?php echo $CLICSHOPPING_CustomersSupport->getDef('title_reviews_entry'); ?></div>
          </div>
          <div class="adminformTitle">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_reviews'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_reviews'); ?></label>
                  <div class="col-md-11">
                    <?php echo HTMLOverrideAdmin::textAreaCkeditor('customers_response', 'soft', '750', '300', $Qcontact->value('customers_response') ?? null); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php
          // -- ------------------------------------------------------------ //
          // --          ONGLET comentaires contact client          //
          // -- ------------------------------------------------------------ //
        ?>
        <div class="tab-pane" id="tab2">
          <table class="table table-sm table-hover">
            <thead>
            <tr class="dataTableHeadingRow">
              <th class="text-center"
                  width="100"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_ref'); ?></th>
              <th class="text-center"
                  width="150"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_date_sending'); ?></th>
              <th class="text-center"
                  width="150"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_user_name'); ?></th>
              <th
                class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_customers_response'); ?></th>
            </tr>
            <thead>
            <tbody>
            <?php
              $QfollowContact = $CLICSHOPPING_CustomersSupport->db->prepare('select c.contact_customers_id,
                                                                                    ccf.id_contact_customers_follow,
                                                                                    ccf.administrator_user_name,
                                                                                    ccf.customers_response,
                                                                                    ccf.contact_date_sending
                                                                             from :table_contact_customers c,
                                                                                  :table_contact_customers_follow ccf
                                                                             where  c.contact_customers_id = ccf.contact_customers_id
                                                                             and c.contact_customers_id = :contact_customers_id
                                                                           ');
              $QfollowContact->bindInt(':contact_customers_id', $rID);
              $QfollowContact->execute();

              while ($QfollowContact->fetch()) {
                ?>
                <tr>
                  <td><?php echo $QfollowContact->valueInt('contact_customers_id'); ?></td>
                  <td
                    class="text-center"><?php echo DateTime::toShort($QfollowContact->value('contact_date_sending')); ?></td>
                  <td><?php echo $QfollowContact->value('administrator_user_name'); ?></td>
                  <td><?php echo $QfollowContact->value('customers_response'); ?></td>
                </tr>
                <?php
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  </form>
</div>
