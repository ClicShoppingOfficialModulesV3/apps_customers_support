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
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\ObjectInfo;

  $CLICSHOPPING_CustomersSupport = Registry::get('CustomersSupport');
  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
  $CLICSHOPPING_Language = Registry::get('Language');
  $CLICSHOPPING_Hooks = Registry::get('Hooks');

  $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
?>
<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span
            class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/customers_services.png', $CLICSHOPPING_CustomersSupport->getDef('heading_title'), '40', '40'); ?></span>
          <span
            class="col-md-6 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_CustomersSupport->getDef('heading_title'); ?></span>

          <span class="col-md-5 text-end">
<?php
  echo HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_archive'), null, $CLICSHOPPING_CustomersSupport->link('CustomersSupport&archive'), 'info') .  ' ';
  echo HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_spam'), null, $CLICSHOPPING_CustomersSupport->link('CustomersSupport&spam'), 'warning');

  if (isset($_GET['archive']) || isset($_GET['spam'])) {
    echo ' ' . HTML::button($CLICSHOPPING_CustomersSupport->getDef('button_reset'), null, $CLICSHOPPING_CustomersSupport->link('CustomersSupport'), 'success');
  }
?>
          </span>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>

  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <?php
          $QstatOrders = $CLICSHOPPING_CustomersSupport->db->prepare('select count(contact_customers_id) as count
                                                                      from :table_contact_customers
                                                                      where contact_customers_archive = 1
                                                                     ');
          $QstatOrders->execute();

          $number_support = $QstatOrders->valueInt('count');

          $text = $CLICSHOPPING_CustomersSupport->getDef('text_number_archive');
          ?>
          <div class="col-md-2 col-12 m-1">
            <div class="card bg-info">
              <div class="card-body">
                <div class="row">
                  <h6 class="card-title text-white"><i class="bi bi-archive"></i> <?php echo $text; ?></h6>
                </div>
                <div class="col-md-12">
                  <span class="text-white"><strong> <?php echo $number_support; ?></strong></span>
                </div>
              </div>
            </div>
          </div>

          <?php
          $QstatOrders = $CLICSHOPPING_CustomersSupport->db->prepare('select count(spam) as count
                                                                      from :table_contact_customers
                                                                      where spam = 1
                                                                     ');
          $QstatOrders->execute();

          $number_support = $QstatOrders->valueInt('count');

          $text = $CLICSHOPPING_CustomersSupport->getDef('text_number_spam');
          ?>
          <div class="col-md-2 col-12 m-1">
            <div class="card bg-warning">
              <div class="card-body">
                <div class="row">
                  <h6 class="card-title text-white"><i class="bi bi-dash-circle"></i> <?php echo $text; ?></h6>
                </div>
                <div class="col-md-12">
                  <span class="text-white"><strong> <?php echo $number_support; ?></strong></span>
                </div>
              </div>
            </div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Stats', 'StatsTopCustomerSupport', null, 'display'); ?>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>
  <?php echo HTML::form('delete_all', $CLICSHOPPING_CustomersSupport->link('CustomersSupport&DeleteAll&page=' . $page)); ?>
  <div id="toolbar" class="float-end">
    <button id="button" class="btn btn-danger"><?php echo $CLICSHOPPING_CustomersSupport->getDef('button_delete'); ?></button>
  </div>
  <table
    id="table"
    data-toggle="table"
    data-icons-prefix="bi"
    data-icons="icons"
    data-id-field="selected"
    data-select-item-name="selected[]"
    data-click-to-select="true"
    data-sort-order="asc"
    data-sort-name="sort_order"
    data-toolbar="#toolbar"
    data-buttons-class="primary"
    data-show-toggle="true"
    data-show-columns="true"
    data-mobile-responsive="true">

    <thead class="dataTableHeadingRow">
    <tr>
      <th data-checkbox="true" data-field="state"></th>
      <th data-field="selected" data-sortable="true" data-switchable="false"><?php echo $CLICSHOPPING_CustomersSupport->getDef('id'); ?></th>
      <th data-field="ref" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_ref'); ?></th>
      <th data-field="customer_id" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_customers_id'); ?></th>
      <th data-field="date" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_date_added'); ?></th>
      <th data-field="department" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_department'); ?></th>
      <th data-field="name" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_customers_name'); ?></th>
      <th data-field="email" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_customers_email'); ?>&nbsp;</th>
      <th data-field="language" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_customers_language'); ?>&nbsp;</th>
      <th data-field="user_name" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_user_name'); ?>&nbsp;</th>
      <th data-field="spam" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_spam'); ?>&nbsp;</th>
      <th data-field="archive" data-sortable="true" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_archive'); ?>&nbsp;</th>
      <th data-field="action" data-switchable="false" class="text-center"><?php echo $CLICSHOPPING_CustomersSupport->getDef('table_heading_action'); ?>&nbsp;</th>
    </tr>
    </thead>
      <tr>
      <?php
        if (isset($_GET['archive'])) {
          $Qcontact = $CLICSHOPPING_CustomersSupport->db->prepare('select distinct SQL_CALC_FOUND_ROWS contact_customers_id,
                                                                                                         contact_department,
                                                                                                         contact_name,
                                                                                                         contact_email_address,
                                                                                                         contact_email_subject,
                                                                                                         contact_date_added,
                                                                                                         languages_id ,
                                                                                                         contact_customers_archive,
                                                                                                         contact_customers_status,
                                                                                                         customer_id,
                                                                                                         contact_telephone,
                                                                                                         spam
                                                                                from :table_contact_customers
                                                                                where contact_customers_archive = 1
                                                                                and spam = 0
                                                                                order by contact_customers_id desc
                                                                                limit :page_set_offset,
                                                                                      :page_set_max_results
                                                                                ');

          $Qcontact->setPageSet((int)MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
          $Qcontact->execute();
        } elseif (isset($_GET['spam'])) {
          $Qcontact = $CLICSHOPPING_CustomersSupport->db->prepare('select distinct SQL_CALC_FOUND_ROWS contact_customers_id,
                                                                                                         contact_department,
                                                                                                         contact_name,
                                                                                                         contact_email_address,
                                                                                                         contact_email_subject,
                                                                                                         contact_date_added,
                                                                                                         languages_id ,
                                                                                                         contact_customers_archive,
                                                                                                         contact_customers_status,
                                                                                                         customer_id,
                                                                                                         contact_telephone,
                                                                                                         spam
                                                                                from :table_contact_customers
                                                                                where spam = 1
                                                                                order by contact_customers_id desc
                                                                                limit :page_set_offset,
                                                                                      :page_set_max_results
                                                                                ');

          $Qcontact->setPageSet((int)MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
          $Qcontact->execute();
        } else {
          $Qcontact = $CLICSHOPPING_CustomersSupport->db->prepare('select distinct SQL_CALC_FOUND_ROWS contact_customers_id,
                                                                                                     contact_department,
                                                                                                     contact_name,
                                                                                                     contact_email_address,
                                                                                                     contact_email_subject,
                                                                                                     contact_date_added,
                                                                                                     languages_id ,
                                                                                                     contact_customers_archive,
                                                                                                     contact_customers_status,
                                                                                                     customer_id,
                                                                                                     contact_telephone,
                                                                                                     spam
                                                                            from :table_contact_customers
                                                                            where contact_customers_archive = 0
                                                                            and spam = 0
                                                                            order by contact_customers_id desc
                                                                            limit :page_set_offset,
                                                                                  :page_set_max_results
                                                                            ');

          $Qcontact->setPageSet((int)MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
          $Qcontact->execute();
        }

        $listingTotalRow = $Qcontact->getPageSetTotalRows();

        if ($listingTotalRow > 0) {
          while ($Qcontact->fetch()) {
            if ((!isset($_GET['rID']) || (isset($_GET['rID']) && ((int)$_GET['rID'] === $Qcontact->valueInt('contact_customers_id')))) && !isset($rInfo)) {
              $rInfo = new ObjectInfo($Qcontact->toArray());
            } // end !isset($_GET['rID']


            $QfollowContact = $CLICSHOPPING_CustomersSupport->db->prepare('select c.contact_customers_id,
                                                                                 ccf.administrator_user_name
                                                                           from :table_contact_customers c,
                                                                                :table_contact_customers_follow ccf
                                                                           where c.contact_customers_id = :contact_customers_id
                                                                           and c.contact_customers_id = ccf.contact_customers_id
                                                                         ');
            $QfollowContact->bindInt(':contact_customers_id', $Qcontact->valueInt('contact_customers_id'));
            $QfollowContact->execute();

            $QlanguageContact = $CLICSHOPPING_CustomersSupport->db->prepare('select name
                                                                             from :table_languages
                                                                             where languages_id = :languages_id
                                                                           ');
            $QlanguageContact->bindInt(':languages_id', $Qcontact->valueInt('languages_id'));
            $QlanguageContact->execute();
            ?>
            <td></td>
            <td><?php echo $Qcontact->valueInt('contact_customers_id'); ?></td>
            <td>
            <?php
            if ($Qcontact->valueInt('customer_id') > 0) {
              ?>
             <strong><?php echo $Qcontact->valueInt('customer_id'); ?></strong>
              <?php
            }
            ?>
            </td>
            <td class="text-center"><?php echo DateTime::toShort($Qcontact->value('contact_date_added')); ?></td>
            <td>
              <?php
              if (!empty($Qcontact->value('contact_department'))) {
                echo $Qcontact->value('contact_department');
              }
              ?>
            </td>
            <td>
              <span><?php echo $Qcontact->value('contact_name'); ?></span><br />
              <span class="text-start small"><?php echo $Qcontact->value('contact_email_subject'); ?></span>
            </td>
            <td><?php echo $Qcontact->value('contact_email_address'); ?></td>
            <td><?php echo $QlanguageContact->value('name'); ?></td>
             <td>
            <?php
            if ($Qcontact->valueInt('contact_customers_status') == 0) {
              ?>
              <?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_status_message_realised'); ?>
              <?php
            } else {
              ?>
              <?php echo $CLICSHOPPING_CustomersSupport->getDef('entry_status_message_not_realised'); ?>
              <?php
            }
            ?>
            </td>
            <td class="text-center"><?php echo $QfollowContact->value('administrator_user_name') ?></td>
            <td class="text-center">
              <?php
              if ($Qcontact->valueInt('spam') === 1) {
                echo '<a href="' . $CLICSHOPPING_CustomersSupport->link('CustomersSupport&SetFlagSpam&flagSpam=0&id=' . $Qcontact->valueInt('contact_customers_id') . '&email=' . $Qcontact->value('contact_email_address')) . '"><i class="bi-check text-success"></i></a>';
              } else {
                echo '<a href="' . $CLICSHOPPING_CustomersSupport->link('CustomersSupport&SetFlagSpam&flagSpam=1&id=' . $Qcontact->valueInt('contact_customers_id') . '&email=' . $Qcontact->value('contact_email_address')) . '"><i class="bi bi-x text-danger"></i></a>';
              }
              ?>
            </td>
            <td class="text-center">
              <?php
                if ($Qcontact->valueInt('contact_customers_archive') === 1) {
                  echo '<a href="' . $CLICSHOPPING_CustomersSupport->link('CustomersSupport&SetFlag&flag=0&id=' . $Qcontact->valueInt('contact_customers_id')) . '"><i class="bi-check text-success"></i></a>';
                } else {
                  echo '<a href="' . $CLICSHOPPING_CustomersSupport->link('CustomersSupport&SetFlag&flag=1&id=' . $Qcontact->valueInt('contact_customers_id')) . '"><i class="bi bi-x text-danger"></i></a>';
                }
              ?>
            </td>
            <td class="text-end">
              <?php
                echo '<a href="' . $CLICSHOPPING_CustomersSupport->link('Edit&page=' . $page . '&rID=' . $Qcontact->valueInt('contact_customers_id')) . '">' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/edit.gif', $CLICSHOPPING_CustomersSupport->getDef('icon_edit')) . '</a>';
              ?>
            </td>
          </tr>
            <?php
          } // end while
        }
      ?>
      </tbody>
  </table>
  </div>
  <?php
    if ($listingTotalRow > 0) {
      ?>
      <div class="row">
        <div class="col-md-12">
          <div
            class="col-md-6 float-start pagenumber hidden-xs TextDisplayNumberOfLink"><?php echo $Qcontact->getPageSetLabel($CLICSHOPPING_CustomersSupport->getDef('text_display_number_of_link')); ?></div>
          <div
            class="float-end text-end"> <?php echo $Qcontact->getPageSetLinks(CLICSHOPPING::getAllGET(array('page', 'info', 'x', 'y'))); ?></div>
        </div>
      </div>
      <?php
    } // end $listingTotalRow
  ?>
  <!-- body_eof //-->
</div>
<script>
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>