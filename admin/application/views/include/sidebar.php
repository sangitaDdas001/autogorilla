  <?php 
  $controller = (!empty($this->uri->segment(1))?$this->uri->segment(1):'');
  $method = (!empty($this->uri->segment(2))?$this->uri->segment(2):'');
  $parent_menuList = fetchParentMenu();
  
  ?>

  <aside class="main-sidebar">
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url(); ?>assets/images/auto-gorilla-logo1.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
     <?php if(!empty($parent_menuList)) { ?>
        <ul class="sidebar-menu" data-widget="tree" data-accordion="false">
          <li>
            <a href="<?php echo base_url('dashboard'); ?>">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
          </li>

          <li class="treeview">
            <ul class="treeview-menu">
              <li class="treeview">
              </li>
            </ul>
          </li>

          <?php foreach ($parent_menuList as $key => $p_value){ 
            $segment = $p_value['segment'];
            $submenuList  = fetchSubmenu($p_value['id']); ?>
              <li <?php echo empty($p_value['menu_url'])? 'class="treeview"':''  ?>>
                <a href="<?php echo !empty($p_value['menu_url']) ? base_url($p_value['menu_url']): 'javascript:void(0)'; ?>">
                  <i class="<?php echo $p_value['menu_icon']; ?>"></i>
                    <span><?php echo $p_value['menu_name']; ?></span>
                </a>
                <?php if(!empty($submenuList)) { ?>
                  <ul class="treeview-menu">
                    <?php foreach($submenuList as $sKey=> $sub_value){ 
                      $fetchMicroMenu = fetchMicromenu($sub_value['id']); ?>
                      <li class="<?php if($sub_value['menu_icon']=='fa fa-bars') {echo 'treeview' ;} ?>">
                        <a href="<?php echo !empty($sub_value['menu_url'])?  base_url().$sub_value['menu_url']  : "javascript:void(0)" ; ?> ">
                          <i class="<?php echo $sub_value['menu_icon']; ?>"></i>
                            <span><?php echo $sub_value['menu_name']; ?></span>
                        </a>
                        <?php if(!empty($fetchMicroMenu)){ ?>
                          <ul class="treeview-menu" <?php if(in_array($controller, array($segment))) { echo 'style="display:block"'; } ?> >
                            <?php foreach ($fetchMicroMenu as $m_key => $m_value){ $methodname = $m_value['method_name']; ?>
                              <li <?php if(in_array($method, array($methodname))) { echo 'class="active"'; } ?>>
                                <a href="<?php echo base_url($m_value['menu_url']); ?>"><i class="<?php echo $m_value['menu_icon']; ?> text-red"></i><?php echo $m_value['menu_name']; ?></a>
                              </li> 
                            <?php } ?>
                          </ul>
                        <?php } ?>
                      </li>
                    <?php } ?>
                  </ul>
                <?php } ?>
              </li> 
          <?php } ?>
        </ul>
      <?php } ?>  
    </section>
      <!-- /.sidebar -->
  </aside>