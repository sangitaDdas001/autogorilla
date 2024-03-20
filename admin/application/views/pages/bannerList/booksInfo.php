<?php 
  $BookId = $this->uri->segment(3);
  if(empty($BookId)) {
    redirect('BookList');
  }
?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Books Information
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="<?php echo base_url('BookList'); ?>">Books Info</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- SELECT2 EXAMPLE -->
      <div class="box box-default">
        <div class="box-body">
          <div class="row">
            
            <div class="col-md-6">
              <div class="form-group">
                <label>Book Name</label>
                <input type="text" class="form-control" name="bookName" id="bookName" value="<?php echo $fetch_data['bookName']?>" readonly>
              </div>

              <div class="form-group">
                <label>Books Category</label>
                  <input type="text" class="form-control" name="category_name" id="category_name" value="<?php echo $categoryList[0]['category_name'];?>" readonly>
              </div>
              <div class="form-group">
                <label for="">Book Publisher</label>
                <input type="text" class="form-control" name="publisher" placeholder="Books Publisher" autocomplete="off" value="<?php echo !empty($fetch_data['publisher'])?$fetch_data['publisher'] : ''; ?>" readonly> 
              </div>
              <div class="form-group">
                <label for="">Book Publication Year</label>
                <input type="text" class="form-control" name="publicationYear" placeholder="Book Publication Year" autocomplete="off" value="<?php echo !empty($fetch_data['publicationYear'])?$fetch_data['publicationYear']:''; ?>" readonly> 
              </div>
              <div class="form-group">
                <label for="">Book Generic Name</label>
                  <input type="text" class="form-control" name="genericName" placeholder="Book Generic Name" autocomplete="off" value="<?php echo !empty($fetch_data['genericName'])?$fetch_data['genericName'] : ''; ?>" readonly> 
              </div>
              <div class="form-group">
                <label for="">ISBN</label>
                  <input type="text" class="form-control" name="isbn" placeholder="ISBN" autocomplete="off" value="<?php echo !empty($fetch_data['isbn'])?$fetch_data['isbn'] : ''; ?>" readonly> 
              </div>
              
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Books Detals</label>
                  <textarea class="form-control" name="boksDetails" id="boksDetails" readonly><?php echo $fetch_data['bookDetails'];?></textarea>
                </div>
                <div class="form-group">
                  <label>Book Author Name</label>
                  <input type="text" class="form-control" name="authorName" id="authorName" value="<?php echo $fetch_data['authorName'];?>" readonly>

                </div>
                <div class="form-group">
                  <label for="">Book Binding</label>
                    <input type="text" class="form-control" name="binding" placeholder="Books Binding" autocomplete="off" value="<?php echo !empty($fetch_data['binding'])?$fetch_data['binding']:''; ?>" readonly> 
                </div>
                <div class="form-group">
                  <label for="">Edition</label>
                  <input type="text" class="form-control" name="Edition" placeholder="Book Edition" autocomplete="off" value="<?php echo !empty($fetch_data['edition'])?$fetch_data['edition'] : ''; ?>" readonly> 
                </div>
                <div class="form-group">
                <label for="">Pages</label>
                  <input type="text" class="form-control" name="pages" placeholder="Pages" autocomplete="off" value="<?php echo !empty($fetch_data['pages'])?$fetch_data['pages'] : ''; ?>" readonly> 
                </div>
                <div class="form-group">
                  <label>Books Image</label>
                  <div class="input-group date">
                    <img src="../../../uploads/bookImage/<?php echo $fetch_data['bookImage'];?>" style="width:20%">
                  </div>
                </div>
              </div>
              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <div class="box-footer">
          <div class="row">
            <div class="col-md-12">
              <center>
                <button type="button" class="btn btn-warning" onclick="history.back();">Back</button>
              </center>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->