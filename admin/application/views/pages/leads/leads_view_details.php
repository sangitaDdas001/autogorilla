<?php $url = "http://dev.autogorilla.com/" ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Leads Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('leads/all_leads'); ?>">Leads</a></li>
      </ol>
    </section>

   
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <?php if(!empty($lead_details['user_name'])){?>
                <h3 class="box-title"><u><?php echo ucwords($lead_details['user_name']); ?>'s Lead Details</u>
                </h3>
                <button type="button" class="btn btn-success" onclick ="history.back();" style="float: right;">Back</button>
              <?php } else { ?>
                <h3 class="box-title"><u>General Lead Details</u><span></h3>
              <?php } ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table class="table table-bordered table-hover" style="margin-bottom: 38px;">
                <thead style="background:#3c8dbc">
                  <td style="text-transform: capitalize;color: #fff;" colspan="2">LEAD GENERAL INFO <span><img src = "<?php echo base_url('assets/images/mail-sends.png'); ?>" style="width: 31px;height: 33px;margin-left: 8px;margin-bottom: 9px;"></span></td>
                </thead>
                <tbody>
                  <tr>
                    <th style="width:40%">Receive enquery about</th>
                    <td><?php echo !empty($lead_details['request_submit_form_name'])?$lead_details['request_submit_form_name']:'-';?></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Generate lead date&time</th>
                    <td><?php echo !empty($lead_details['created_at'])?$lead_details['created_at']:'-'; ?></td>
                  </tr>
                </tbody>
              </table>

              <!-- START HERE -->
              <table class="table table-bordered table-hover" style="margin-bottom: 38px;">
                <thead style="background:#3c8dbc">
                  <td style="text-transform: capitalize;color: #fff;" colspan="2">LEAD DETAILS</td>
                </thead>
                <tbody>
                  <tr>
                    <th style="width:40%"><?php echo !empty($lead_details['leads_for'])?ucwords($lead_details['leads_for']):'-'; ?></th>
                    <td><?php echo !empty($lead_details['product_name'])?$lead_details['product_name']:'-';?></td>
                  </tr>
                  <?php if(!empty($lead_details['required_product_name'])) { ?>
                    <tr>
                      <th style="width:40%">Submitted Product Name</th>
                      <td><?php echo !empty($lead_details['required_product_name'])?$lead_details['required_product_name']:'-';?></td>
                    </tr>
                  <?php } ?>
                  <tr>
                    <th style="width:40%">Source page details </th>
                    <td><?php echo !empty($lead_details['fromsource_name'])?$lead_details['fromsource_name']:'-';?></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Source page url details</th>
                    <td><a target="_blank" href="<?php echo !empty($lead_details['formsource_path'])?$lead_details['formsource_path']:'javascript:void(0);';?> "><?php echo !empty($lead_details['formsource_path'])?$lead_details['formsource_path']:'-';?></a></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Quantity</th>
                    <td><?php echo !empty($lead_details['qty'])?$lead_details['qty']:'0';?></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Requirement details</th>
                    <td><?php echo !empty($lead_details['requirement_details'])?$lead_details['requirement_details']:'-';?></td>
                  </tr>
                  <?php if(!empty($lead_details['looking_suppliers'])) { ?>
                    <tr>
                      <th style="width:40%">Looking For Suppliers </th>
                      <td><?php echo !empty($lead_details['looking_suppliers'])?$lead_details['looking_suppliers']:'-';?></td>
                    </tr>
                  <?php } ?>

                  <?php if(!empty($lead_details['file'])) { ?>
                    <tr>
                      <th style="width:40%">Attachment</th>
                      <td><img src="<?php echo $url.'uploads/lead/'.$lead_details['file'] ;?>" style="height: 20%; width: 20%;"></td>
                    </tr>
                  <?php } ?>

                  <tr>
                    <th style="width:40%">Email status </th>
                    <?php if($lead_details['email_status'] == 'successful') { ?>
                      <td style="color:green;">Email send successfully</td>
                    <?php } else { ?>
                      <td style="color:red;">Failed to send email</td>
                    <?php } ?> 
                  </tr>
                </tbody>
              </table>

              <table class="table table-bordered table-hover" style="margin-bottom: 38px;">
                <thead style="background:#3c8dbc">
                  <td style="text-transform: capitalize;color: #fff;" colspan="2">BUYER DETAILS</td>
                </thead>
                <tbody>
                  <tr>
                    <th style="width:40%">Buyer Status</th>
                    <?php if($lead_details['status'] == 'Inactive') { ?>
                      <td style="color:red;">Rejected</td>
                    <?php } else { ?>
                      <td style="color:green;">Active</td>
                    <?php } ?> 
                  </tr>

                  <?php if($lead_details['status'] == 'Inactive'){ ?>
                    <tr>
                      <th style="width:40%">Rejected Reason</th>
                        <td><?php echo !empty($lead_details['rejected_reason'])?$lead_details['rejected_reason']:'-';?></td>
                    </tr>
                  <?php } ?>  
                  <tr>
                    <th style="width:40%">Buyer user name</th>
                    <td><?php echo !empty($lead_details['user_name'])?ucwords($lead_details['user_name']):'-'; ?></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Buyer email</th>
                    <td><?php echo !empty($lead_details['email'])?$lead_details['email']:'-'; ?></td>
                  </tr>
                  <tr>
                    <th style="width:40%">Buyer contact</th>
                    <td><?php echo !empty($lead_details['phone'])?$lead_details['phone']:'-'; ?></td>
                  </tr>
                  
                  <tr>
                    <th style="width:40%">Buyer submitted company name</th>
                    <td><?php echo !empty($lead_details['leads_company_name'])?$lead_details['leads_company_name']:'-';?></td> 
                  </tr>
                  <tr>
                    <th style="width:40%">Is member of AutoGorilla</th>
                    <?php if($lead_details['led_reg_company']['type'] == 1){ ?>
                      <td style="color:#8a2b01">No (Buyer)</td>
                    <?php } else { ?>
                      <td style="color:#ffa20e">Yes (Supplier)<br><span style="color:black;"><a href="<?php echo $url.$lead_details['led_reg_company']['vendor_catalog_url_slug'];?>"><?php echo $lead_details['led_reg_company']['led_reg_company_as_supplier'];?></a></span>
                      </td>
                    <?php } ?>  
                  </tr>
                </tbody>
              </table>

              <table class="table table-bordered table-hover" style="margin-bottom: 38px;">
                <thead style="background:#3c8dbc">
                  <td style="text-transform: capitalize;color: #fff;" colspan="2">SELLER DETAILS <span><img src = "<?php echo base_url('assets/images/mail-receive.png'); ?>" style="width: 31px;height: 33px;margin-left: 8px;margin-bottom: 9px;"></span></td>
                </thead>
                <tbody>
                  <tr>
                    <th style="width:40%">Send enquiry to Company </th>
                    <?php if(!empty($lead_details['vendor_email'])){ ?>
                      <td><?php echo !empty($lead_details['company_name'])?$lead_details['company_name'].'('.$lead_details['vendor_email'].')':'-'; ?></td>
                    <?php } else { ?>
                      <td><?php echo !empty($lead_details['company_name'])?$lead_details['company_name']:'-'?></td>
                    <?php } ?>  
                  </tr>
                  <tr>
                    <th style="width:40%">Seller Name</th>
                    <td><?php echo !empty($lead_details['name'])?ucfirst($lead_details['name']):'-';?></td>
                  </tr>

                  <tr>
                    <th style="width:40%">Seller Contact No</th>
                    <td><?php echo !empty($lead_details['vendor_phone'])?ucfirst($lead_details['vendor_phone']):'-';?></td>
                  </tr>

                  <tr>
                    <th style="width:40%">Request is general or not</th>
                    <td><?php echo !empty($lead_details['genral_request'])?ucfirst($lead_details['genral_request']):'-';?></td>
                  </tr>
                </tbody>
              </table>

              <div style="margin-top: 15px;">
                <button type="button" class="btn btn-success" onclick ="history.back();" style="float: left;" >Back</button>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
</div>