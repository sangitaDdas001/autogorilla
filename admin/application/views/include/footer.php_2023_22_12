<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url(); ?>assets/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url(); ?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js"></script>
<script src="<?php echo base_url(); ?>assets/tinymce/jquery.tinymce.min.js"></script>


<style>
    .d-flex{display:flex;}
    .mt-0 {
    margin-top: 0!important
    }

    .mt-1 {
        margin-top: .25rem!important
    }

    .mt-2 {
        margin-top: .5rem!important
    }

    .mt-3 {
        margin-top: 1rem!important
    }

    .mt-4 {
        margin-top: 1.5rem!important
    }

    .mt-5 {
        margin-top: 3rem!important
    }

    .mb-1 {
    margin-bottom: .25rem!important
    }

    .mb-2 {
        margin-bottom: .5rem!important
    }

    .mb-3 {
        margin-bottom: 1rem!important
    }

    .mb-4 {
        margin-bottom: 1.5rem!important
    }

    .mb-5 {
        margin-bottom: 3rem!important
    }

</style>

<!-- Company csv Modal -->
    <div class="modal fade" id="company_csv_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action ="<?php echo base_url('supplier/uploadCsvFile'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Upload company csv</h4>
                    </div>
                    <div class="col-md-12" style="margin-top: 4%;">
                        <div class="form-group">
                            <input type="file" class="form-control" name="userfile" placeholder="Area Name" autocomplete="off">
                        </div>
                    </div> 
                    <div class ="col-md-12" style="margin-top: 6%;">
                        <span>Note: </span>
                        <ul>
                            <li>Use uniq email id and phone no</li>
                            <li>Email id is required</li>
                            <li>Company name is required</li>
                            <li>Company category url is must be uniq</li>
                            <li>Gst, Tan, Pan and website url is must be uniq</li>
                        </ul> 
                    </div>
                    <div class="modal-footer" style="margin-top: 14%;">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>    
        </div>
    </div>


<!-- Product csv Modal -->
    <div class="modal fade" id="product_csv_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Upload product information csv</h4>
                    </div>
                    <form action ="<?php echo base_url('product/uploadProductCsv'); ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5><b>Upload Product CSV (Step :1)</b></h5>
                                </div>
                                <div class="col-md-12 d-flex align-items-center">
                                    <input type="file" class="form-control" name="userfile" autocomplete="off">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div> 
                    </form>
                    <div class="col-md-12 mt-3 mb-3">
                        <div class="row">
                            <div class="col-md-12">
                                <h5><b>Upload Product Specification CSV (Step :2)</b></h5>
                            </div>
                            <form action ="<?php echo base_url('product/uploadProductSpecificationCsv'); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="col-md-12 d-flex align-items-center">
                                    <input type="file" class="form-control" name="sp_userfile" autocomplete="off">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>    
                        </div>
                           
                    </div> 

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h5><b>Upload Product Mapping CSV (Step :3)</b></h5>
                            </div>
                            <form action ="<?php echo base_url('product/uploadMicroCategoryCsv'); ?>" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="col-md-12 d-flex align-items-center">
                                    <input type="file" class="form-control" name="mapp_userfile"  autocomplete="off">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div> 
                    
                    <div class="col-md-12 mt-5">
                        <span><b><u>Product Csv Upload Note: </u></b></span>
                            <ul>
                              <li>Product name is required</li>
                              <li>Company id is required</li>
                              <li>Product code cannot be duplicate</li>
                            </ul>
                        <span><b><u>Product Specification Csv Upload Note:  </u></b></span>
                            <ul>
                              <li>Product id is required</li>
                              <li>Product title is required</li>
                              <li>Product specification description is required</li>
                            </ul>
                        <span><b><u>Product Mapping Csv Upload Note: </u></b></span>
                            <ul>
                              <li>Product id is required</li>
                              <li>Company id is required</li>
                              <li>Parent Category id is required</li>
                              <li>Sub Category id is required</li>
                              <li>Micro Category id is required</li>
                            </ul>
                    </div>
                    <div class="modal-footer" >
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
              
        </div>
    </div>
<!-- Assignec company List Modal -->
    <div class="modal fade bd-example-modal-lg " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="assignedCompany_modal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Company Assigned List</h4>
            </div>
            <div class="card-body px-0 pb-2">
                <div class="table-responsive" style="margin-right: 2%;margin-left: 2%;">
                   <table class="table align-items-center mb-0 table-padding-15">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Sr.No</div></th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Company Id</div></th>
                                
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Company Name</div></th>
                            </tr>
                        </thead>
                        <tbody class="dynamicCompanyData">
                            
                        </tbody>
                    </table> 
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
      </div>
    </div>
 <!-- End -->


<!-- Product Modal -->
    <div class="modal fade" id="product_csv_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action ="<?php echo base_url('product/uploadCsvFile'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Upload product csv</h4>
                    </div>
                    <div class="col-md-12" style="margin-top: 4%;">
                        <div class="form-group">
                            <input type="file" class="form-control" name="userfile" placeholder="Product" autocomplete="off">
                        </div>
                    </div> 
                    <!-- <div class ="col-md-12" style="margin-top: 6%;">
                        <span>Note: </span>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p> 
                    </div> -->
                    <div class="modal-footer" style="margin-top: 14%;">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>    
        </div>
    </div>


<!-- Product sample Modal -->
    <div class="modal fade" id="download_csv_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Download Sample</h4>
                    </div>
                    <div class="col-md-12" style="margin-top: 4%;">
                        <div class="form-group">
                            <form action="<?php echo base_url('product/productCsvDownload') ;?>" method="post">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                               <button type="submit" name="Download Product Csv" class="btn btn-success" id ="product_spec_csv">Product Csv <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                            </form>
                        </div>   
                        <form action="<?php echo base_url('product/productSpecificationCsvDownload') ;?>" method="post"> 
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="form-group">
                                <button type="submit" name="Download Product Spcification Csv" class="btn btn-warning" id ="product_spec_csv">Product Spcification Csv <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                            </div>
                        </form>
                        <form action="<?php echo base_url('product/productMappingCsvDownload') ;?>" method="post"> 
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="form-group">    
                                <button type="submit" name="Download Product Mapping Csv" class="btn btn-default" id="product_mapping_csv">Product Mapping Csv <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                            </div>
                        </form>   
                    </div> 
                     
                    <div class="modal-footer" >
                        <!-- <button type="submit" name="submit" class="btn btn-primary">Submit</button> -->
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
        </div>
    </div>

<!-- Company Assign list csv Modal -->
    <div class="modal fade" id="companyAssign_csv_modal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <form action ="<?php echo base_url('userManagement/importAssignCompanyList'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Upload company csv</h4>
                    </div>
                    <div class="col-md-12" style="margin-top: 4%;">
                        <div class="form-group">
                            <input type="file" class="form-control" name="userfile" placeholder="Area Name" autocomplete="off" required>
                            <input type="hidden" name="admin_user_id" value="<?php echo $this->uri->segment(3); ?>" id="admin_userId">
                        </div>
                    </div> 
                    <div class ="col-md-12" style="margin-top: 6%;">
                        <span>Note: </span>
                        <ul>
                            <li>Company Id is required</li>
                        </ul> 
                    </div>
                    <div class="modal-footer" style="margin-top: 14%;">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>    
        </div>
    </div>
    
    
<!-- LEAD REJECTED REASON MODAL -->
<div class="modal fade" id="leadRejectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reject Lead</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Lead rejection form -->
                <form>
                    <input type="hidden" name="lead_id" value="" id="lead_id">
                    <div class="form-group">
                        <label for="reason">Reason for Rejection:</label>
                        <textarea class="form-control" id="reason" rows="3" required name="reason"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="reject_lead">Reject Lead</button>
            </div>
        </div>
    </div>
</div>
<!-- End -->


<!-- LEAD REJECTED REASON MODAL -->
<div class="modal fade" id="activeleadRejectionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reject Lead</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Lead rejection form -->
                <form>
                    <input type="hidden" name="lead_id" value="" id="lead_id">
                    <div class="form-group">
                        <label for="reason">Reason for Rejection:</label>
                        <textarea class="form-control" id="reject_reason" rows="3" required name="reason"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="active_to_reject_lead">Reject Lead</button>
            </div>
        </div>
    </div>
</div>
<!-- End -->


<script type="text/javascript">
   var localurl = "<?php echo base_url(); ?>";
</script>
<script type="text/javascript">
   $(document).ready(function(){
       var maxField = 4; //Input fields increment limitation
       var addButton = $('.add_button'); //Add button selector
       var wrapper = $('.field_wrapper'); //Input field wrapper
       var fieldHTML = '<div class="d-flex margin-gap" style="display: flex; align-items: center;"><input type="text" placeholder="State Name" class="form-control" name="state_name[]" value="" style="width: 80%"/><a href="javascript:void(0);" class="remove_button"><img src="<?php echo VIEW_IMAGE_URL;?>remove-icon.png" style="width:25px; margin-left:19px"/></a></div>'; //New input field html 
       var x = 1; //Initial field counter is 1
       
       //Once add button is clicked
       $(addButton).click(function(){
           //Check maximum number of input fields
           if(x < maxField){ 
               x++; //Increment field counter
               $(wrapper).append(fieldHTML); //Add field html
           }
       });
       
       //Once remove button is clicked
       $(wrapper).on('click', '.remove_button', function(e){
           e.preventDefault();
           $(this).parent('div').remove(); //Remove field html
           x--; //Decrement field counter
       });

        $('.country').on('change', function() {
            let countryId = this.value;
        if(countryId !=''){
          $.ajax({
            url : localurl+"city/getStateNameById",
            type:'post',
            dataType : "json",
            data:{'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>','country_id':countryId},
            beforeSend: function () {
              jQuery('select#state-name').find("option:eq(0)").html("Please wait..");
            },
            complete: function () {
                // code
            },
            success: function (json) {
            var options = '';
            options +='<option value="">Select State</option>';
            for (var i = 0; i < json.length; i++) {
                options += '<option value="' + json[i].state_id + '">' + json[i].state_name + '</option>';
            }
            jQuery("select#state-name").html(options);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
         });
        }
      });

   });

</script>