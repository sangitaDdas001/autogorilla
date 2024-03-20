<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Upload Summary
      </h1>
      <ol class="breadcrumb">
         <li><button type="button" name="submit" class="btn btn-primary" onclick="window.location.href='<?php echo base_url('supplier'); ?>'">Back</button></center></li>
      </ol>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="row mt-25px">
         <div class="col-xs-12">
            <div class="row">
               <div class="col-xl-4 col-sm-4 mb-xl-0 mb-4">
                  <div class="card">
                     <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                           <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMMAAADDCAYAAAA/f6WqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyVpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDYuMC1jMDAyIDc5LjE2NDQ2MCwgMjAyMC8wNS8xMi0xNjowNDoxNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjIgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NEEzMDE2QUNDMDNGMTFFREFBMjA4QzY2RjdFNjlDMEMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NEEzMDE2QURDMDNGMTFFREFBMjA4QzY2RjdFNjlDMEMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0QTMwMTZBQUMwM0YxMUVEQUEyMDhDNjZGN0U2OUMwQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0QTMwMTZBQkMwM0YxMUVEQUEyMDhDNjZGN0U2OUMwQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PnPZlckAAAXySURBVHja7N3h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'/>
                        </div>
                        <div class="text-end pt-1">
                           <p class="text-sm mb-0 text-capitalize">Total</p>
                           <h4 class="mb-0"><?php echo ($failed_csvdata + $success_csvdata) ?></h4>
                        </div>
                     </div>
                     <!-- <hr class="dark horizontal my-0">
                     <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
                     </div> -->
                  </div>
               </div>
               <div class="col-xl-4 col-sm-4 mb-xl-0 mb-4">
                  <div class="card">
                     <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-success shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                           <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMMAAADDCAYAAAA/f6WqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyVpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDYuMC1jMDAyIDc5LjE2NDQ2MCwgMjAyMC8wNS8xMi0xNjowNDoxNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjIgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NEEzMDE2QjBDMDNGMTFFREFBMjA4QzY2RjdFNjlDMEMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NEEzMDE2QjFDMDNGMTFFREFBMjA4QzY2RjdFNjlDMEMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0QTMwMTZBRUMwM0YxMUVEQUEyMDhDNjZGN0U2OUMwQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo0QTMwMTZBRkMwM0YxMUVEQUEyMDhDNjZGN0U2OUMwQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PjYk7ggAAApWSURBVHja7J2/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'/>
                        </div>
                        <div class="text-end pt-1">
                           <p class="text-sm mb-0 text-capitalize">Success</p>
                           <h4 class="mb-0"><?php echo $success_csvdata; ?></h4>
                           <form action="<?php echo base_url('supplier/exportSuccessCsvData'); ?>" method="post">
                              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                              <input type="hidden" name="uniq_code" value="<?php echo $uniq_code; ?>">
                              <button type="submit" name="Download Success Company Csv" class="btn btn-success" id="success_csv">Success Csv <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                          </form>
                        </div>
                     </div>
                     <!-- <hr class="dark horizontal my-0">
                     <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p>
                     </div> -->
                  </div>
               </div>
               <div class="col-xl-4 col-sm-4 mb-xl-0 mb-4">
                  <div class="card">
                     <div class="card-header p-3 pt-2">
                        <div class="icon icon-lg icon-shape bg-gradient-primary shadow-success text-center border-radius-xl mt-n4 position-absolute">
                           <img src='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMMAAADDCAYAAAA/f6WqAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyVpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDYuMC1jMDAyIDc5LjE2NDQ2MCwgMjAyMC8wNS8xMi0xNjowNDoxNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIDIxLjIgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NjZCMjc5QTdDMDVEMTFFREFBMjA4QzY2RjdFNjlDMEMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NjZCMjc5QThDMDVEMTFFREFBMjA4QzY2RjdFNjlDMEMiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo0QTMwMTZCMkMwM0YxMUVEQUEyMDhDNjZGN0U2OUMwQyIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2NkIyNzlBNkMwNUQxMUVEQUEyMDhDNjZGN0U2OUMwQyIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PpZWtnEAAAqPSURBVHja7J1L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'/>
                        </div>
                        <div class="text-end pt-1">
                           <p class="text-sm mb-0 text-capitalize">Failed</p>
                           <h4 class="mb-0"><?php echo $failed_csvdata; ?></h4>
                           <form action="<?php echo base_url('supplier/exportFailedCsvData'); ?>" method="post">
                              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                              <input type="hidden" name="uniq_code" value="<?php echo $uniq_code; ?>">
                              <button type="submit" name="Download Failed Company Csv" class="btn btn-danger" id="failed_csv">Failed Csv <i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                          </form>
                        </div>
                     </div>
                     <!-- <hr class="dark horizontal my-0">
                     <div class="card-footer p-3">
                        <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</span> than yesterday</p>
                     </div> -->
                  </div>
               </div>
            </div>
            <div class="row mt-25px ddddddd">
              <div class="col-xs-12">
                <div class="card failed-details-block">
                 <div class="card-header pb-0">
                    <div class="row">
                       <div class="col-lg-6 col-7">
                          <h6>Failed Details</h6>
                          
                       </div>
                       
                    </div>
                 </div>
                 <div class="card-body px-0 pb-2">
                    <div class="table-responsive" style="margin-right: 2%;margin-left: 2%;">
                      <!--  <table class="table align-items-center mb-0 table-padding-15"  id="company_rejected_list">
                          <thead>
                             <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Sl. No.</div></th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"><div class="test-warp">Excel Row</div></th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Company Name</div></th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Email</div></th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><div class="test-warp">Phone</div></th>
                                <th class="text-left text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" width="24%"><div class="test-warp">Remark</div></th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                                <td>1</td>
                                <td>34</td>
                                <td class="align-middle">Sristri Private Ltd.</td>
                                <td class="align-middle">sristri@gmail.com</td>
                                <td class="align-middle">9876765456</td>
                                <td class="align-middle">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum luctus orci elit, sed cursus eros lacinia eu.</td>
                             </tr>
                             <tr>
                                <td>2</td>
                                <td>36</td>
                                <td class="align-middle">Kartik Private Ltd.</td>
                                <td class="align-middle">kartik@gmail.com</td>
                                <td class="align-middle">9876765456</td>
                                <td class="align-middle">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum luctus orci elit, sed cursus eros lacinia eu.</td>
                             </tr>
                             <tr>
                                <td>3</td>
                                <td>398</td>
                                <td class="align-middle">Samar Private Ltd.</td>
                                <td class="align-middle">samar@gmail.com</td>
                                <td class="align-middle">9876765456</td>
                                <td class="align-middle">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum luctus orci elit, sed cursus eros lacinia eu.</td>
                             </tr>
                          </tbody>
                       </table> -->
                       <table id="company_rejected_list" class="table table-bordered table-hover align-items-center mb-0 table-padding-15">
                           <thead>
                           <tr>
                           <?php if(!empty($columns)) {
                                  foreach($columns as $value)  { 
                                    echo '<th>'.$value.'</th>';
                                  }
                           } ?>
                           </tr>
                         </thead>
                       </table>
                    </div>
                 </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>