<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="<?=base_url()?>fgb_logo.png" alt="FGB Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ideaPMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
          <li class="nav-item <?=is_active_menu('dashboard')?>">
            <a href="<?=site_url('dashboard')?>" class="nav-link <?=is_active('dashboard')?>"><i class="nav-icon fa fa-home"></i><p>Dashboard</p>
            </a>
          </li>
        <?php if($this->session->userdata('pms_usertype')<5):?>    
          <li class="nav-item">
            <a href="<?=site_url('projects')?>" class="nav-link <?=is_active('projects')?>"><i class="nav-icon fa fa-road"></i><p>Projects</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('purchase')?>" class="nav-link <?=is_active('purchase')?>"><i class="nav-icon fa fa-file-alt"></i><p>Purchase Orders</p>
            </a>
          </li>
          
		  <li class="nav-item <?=is_active_menu('stocksin,inventory,transfers,stocksout,detailinfo,itemsumreport')?>">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-check-double"></i><p>Inventory<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?=site_url('stocksin')?>" class="nav-link <?=is_active('stocksin')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Stocks In</p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="<?=site_url('stocksout')?>" class="nav-link <?=is_active('stocksout')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Stocks Out</p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="<?=site_url('transfers')?>" class="nav-link <?=is_active('transfers')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Stocks Transfer</p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="<?=site_url('inventory')?>" class="nav-link <?=is_active('inventory,detailinfo')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Stocks Inventory</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('itemsumreport')?>" class="nav-link <?=is_active('itemsumreport')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Summary Report</p>
            </a>
          </li>
		   </ul>
		  </li>
		  
		  <li class="nav-item <?=is_active_menu('payments,payables,opex,directp,directpurchase,print_payment_cheque')?>">
            <a href="#" class="nav-link"><i class="nav-icon fa fa-cube"></i><p>AP<i class="right fas fa-angle-left"></i></p></a>
            <ul class="nav nav-treeview">
            <li class="nav-item"><a href="<?=site_url('payables')?>" class="nav-link <?=is_active('payables')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>Payables (Invoices)</a></li>
            <!--<li class="nav-item"><a href="<?=site_url('opex')?>" class="nav-link <?=is_active('opex')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>Bills/OPEX</a></li>-->
            <li class="nav-item"><a href="<?=site_url('directp')?>" class="nav-link <?=is_active('directp')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>Direct Purchases</a></li>
            <!--<li class="nav-item"><a href="<?=site_url('directpurchase')?>" class="nav-link <?=is_active('directpurchase')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>Direct Purchases (v.2)</a></li>
            -->
            <li class="nav-item"><a href="<?=site_url('payments')?>" class="nav-link <?=is_active('payments,print_payment_cheque')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>Payments</a></li>
            </ul>
            </li>
            
            <li class="nav-item <?=is_active_menu('receivables,arpayments')?>">
            <a href="#" class="nav-link"><i class="nav-icon fa fa-building"></i><p>AR<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview"> <li class="nav-item">
            <a href="<?=site_url('receivables')?>" class="nav-link <?=is_active('receivables')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Receivables</p>
            </a></li><li class="nav-item">
            <a href="<?=site_url('arpayments')?>" class="nav-link <?=is_active('arpayments')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Collections</p>
            </a>
            </li></ul></li>
              
            <li class="nav-item <?=is_active_menu('accounting,liquidations,payroll,addnewform,titles')?>">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-book"></i><p>Accounting<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item"><a href="<?=site_url('payroll')?>" class="nav-link <?=is_active('payroll')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Payroll/Labor</p></a></li>
            <li class="nav-item">
            <a href="<?=site_url('liquidations')?>" class="nav-link <?=is_active('liquidations,addnewform')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Liquidations</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('accounting')?>" class="nav-link <?=is_active('accounting')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Accounting Entries</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('titles')?>" class="nav-link <?=is_active('titles')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Chart of Accounts</p>
            </a>
          </li>
                
         
            </ul>
          </li><li class="nav-item <?=is_active_menu('summaryproject,arprojectsummary,summaryexpenses,summaryexpenses_project,overall,overallsum,rcollections,disbursement,supplierledger')?>">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-calendar"></i><p>Reports<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview"> 
            <li class="nav-item">
            <a href="<?=site_url('reports/summaryproject')?>" class="nav-link <?=is_active('summaryproject')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Income/Summary Project</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/arprojectsummary')?>" class="nav-link <?=is_active('arprojectsummary')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>AR Ledger</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/supplierledger')?>" class="nav-link <?=is_active('supplierledger')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Supplier Ledger</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/summaryexpenses')?>" class="nav-link <?=is_active('summaryexpenses,summaryexpenses_project')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Summary Expenses</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/overall')?>" class="nav-link <?=is_active('overall,overallsum')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Overall Summary</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/rcollections')?>" class="nav-link <?=is_active('rcollections')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Collections</p>
            </a>
          </li><li class="nav-item">
            <a href="<?=site_url('reports/disbursement')?>" class="nav-link <?=is_active('disbursement')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Disbursement</p>
            </a>
          </li>
            </ul>
          </li>
		  <?php endif; 
            if($this->session->userdata('pms_usertype')==5 or $this->session->userdata('pms_usertype')==3 or $this->session->userdata('pms_usertype')<2): ?>
		  <li class="nav-item <?=is_active_menu('employees,deductions,genpayroll,dtreditinfo,employees_sum,dailytimerecord,holidays')?>">
            <a href="#" class="nav-link"><i class="nav-icon fas fa-users"></i><p>Payroll (HR)<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview"> <li class="nav-item">
				<a href="<?=site_url('employees')?>" class="nav-link <?=is_active('employees')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Employees</p>
				</a>
				</li><li class="nav-item">
				<a href="<?=site_url('employees_sum')?>" class="nav-link <?=is_active('employees_sum')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Summary List</p>
				</a>
				</li><li class="nav-item">
				<a href="<?=site_url('dailytimerecord')?>" class="nav-link <?=is_active('dailytimerecord')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Daily Time Record</p>
                </a></li><li class="nav-item" <?=($this->session->userdata('pms_usertype')==6?'style="display:none;"':'')?>>
				<a href="<?=site_url('genpayroll')?>" class="nav-link <?=is_active('genpayroll')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Generate Payroll</p>
				</a>
				</li><!--<li class="nav-item">
				<a href="<?=site_url('cashadvances')?>" class="nav-link <?=is_active('cashadvances')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Other Deductions</p>
                </a></li>--><li class="nav-item">
				<a href="<?=site_url('deductions')?>" class="nav-link <?=is_active('deductions')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Deductions Summary</p>
                </a></li><li class="nav-item" <?=($this->session->userdata('pms_usertype')==6?'style="display:none;"':'')?>>
				<a href="<?=site_url('holidays')?>" class="nav-link <?=is_active('holidays')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Holidays</p>
                </a></li>
            </ul>
          </li>
          
           <?php 
            endif;
            if($this->session->userdata('pms_usertype')<5):?> 
		  <li class="nav-item <?=is_active_menu('settings,items,itemscat,locations,companies')?>">
            <a href="#" class="nav-link"><i class="nav-icon fa fa-cog"></i><p>Maintenance<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview"> <li class="nav-item">
            <a href="<?=site_url('items')?>" class="nav-link <?=is_active('items')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Items</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=site_url('itemscat')?>" class="nav-link <?=is_active('itemscat')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Item Categories</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?=site_url('locations')?>" class="nav-link <?=is_active('locations')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Locations</p>
            </a>
          </li>
		  <li class="nav-item">
            <a href="<?=site_url('companies')?>" class="nav-link <?=is_active('companies')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Companies</p>
            </a>
          </li>
            <?php if($this->session->userdata('pms_usertype')<2): ?>
            <li class="nav-item">
            <a href="<?=site_url('settings')?>" class="nav-link <?=is_active('settings')?>"><i class="nav-icon fas fa-long-arrow-alt-right"></i><p>Settings</p>
            </a>
          </li><?php endif; ?>
            </ul>
          </li>
		  <?php endif; ?>
		  
		  <li class="nav-item <?=is_active_menu('sms_create,outbox')?>" <?=($this->session->userdata('pms_usertype')==6?'style="display:none;"':'')?>>
            <a href="#" class="nav-link"><i class="nav-icon fa fa-comment"></i><p>SMS<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview"> <li class="nav-item">
				<a href="<?=site_url('sms/sms_create')?>" class="nav-link <?=is_active('sms_create')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Create SMS</p>
				</a>
			  </li>
			  <li class="nav-item">
				<a href="<?=site_url('sms/outbox')?>" class="nav-link <?=is_active('outbox')?>">
				  <i class="nav-icon fas fa-long-arrow-alt-right"></i>
				  <p>Outbox</p>
				</a>
			  </li>
            </ul>
          </li>
		  <?php if($this->session->userdata('pms_usertype')<2): ?>
		  <li class="nav-item <?=is_active_menu('sysusers')?>">
            <a href="<?=site_url('sysusers')?>" class="nav-link <?=is_active('sysusers')?>"><i class="nav-icon fas fa-users-cog"></i><p>System Users</p>
            </a>
          </li>
		  <?php endif; ?>
		  <li class="nav-item <?=is_active_menu('logs')?>" <?=($this->session->userdata('pms_usertype')==6?'style="display:none;"':'')?>>
            <a href="<?=site_url('logs')?>" class="nav-link <?=is_active('logs')?>"><i class="nav-icon fa fa-history"></i><p>System Logs</p>
            </a>
          </li>
		  
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


