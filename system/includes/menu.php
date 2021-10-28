 <?php if (!defined("PHB")) die();  ?>
 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/index">
         <div class="sidebar-brand-icon rotate-n-15">
             <i class="fas fa-hand-holding-usd"></i>
         </div>
         <div class="sidebar-brand-text mx-3">Financeiro <sup>by PHB</sup></div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard -->
     <li class="nav-item <?= $selectedPage == "dashboard" ? "active" : ""; ?>">
         <a class=" nav-link" href="/index">
             <i class="fas fa-fw fa-tachometer-alt"></i>
             <span>Dashboard</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">
     <div class="sidebar-heading">
         Registro
     </div>
     <!-- Nav Item - Charts -->
     <li class="nav-item">
         <a class="nav-link" href="/add-entrada">
             <i class="fas fa-fw fa-chart-area"></i>
             <span>Cadastrar entrada</span></a>
     </li>
     <!-- Nav Item - Charts -->
     <li class="nav-item">
         <a class="nav-link" href="/add-saida">
             <i class="fas fa-fw fa-chart-area"></i>
             <span>Cadastrar saída</span></a>
     </li>

     <!-- Divider -->
     <hr class="sidebar-divider">
     <!-- Heading -->
     <div class="sidebar-heading">
         Relatórios
     </div>
     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
             <i class="fas fa-fw fa-cog"></i>
             <span>Entrada</span>
         </a>
         <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Custom Components:</h6>
                 <a class="collapse-item" href="buttons.html">Buttons</a>
                 <a class="collapse-item" href="cards.html">Cards</a>
             </div>
         </div>
     </li>

     <li class="nav-item">
         <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
             <i class="fas fa-fw fa-cog"></i>
             <span>Saída</span>
         </a>
         <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Custom Components:</h6>
                 <a class="collapse-item" href="buttons.html">Buttons</a>
                 <a class="collapse-item" href="cards.html">Cards</a>
             </div>
         </div>
     </li>

     <hr class="sidebar-divider">
     <!-- Heading -->
     <div class="sidebar-heading">
         Configurações
     </div>
     <?php $categoriasConfig = array("bancos", "cartoes", "categorias_ganhos", "categorias_gastos"); ?>
     <!-- Nav Item - Pages Collapse Menu -->
     <li class="nav-item <?= in_array($selectedPage, $categoriasConfig) ? "active" : ""; ?>">
         <a class="nav-link <?= in_array($selectedPage, $categoriasConfig) ? "collapsed" : ""; ?>" href=" #" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
             <i class="fas fa-fw fa-folder"></i>
             <span>Configurações</span>
         </a>
         <div id="collapsePages" class="collapse <?= in_array($selectedPage, $categoriasConfig) ? "show" : ""; ?>" aria-labelledby="headingPages" data-parent="#accordionSidebar">
             <div class="bg-white py-2 collapse-inner rounded">
                 <h6 class="collapse-header">Bancos e cartões</h6>
                 <a class="collapse-item <?= ($selectedPage == "bancos") ? "active" : ""; ?>" href="/bancos">Bancos</a>
                 <a class="collapse-item <?= ($selectedPage == "cartoes") ? "active" : ""; ?>" href="/cartoes">Cartões</a>
                 <div class="collapse-divider"></div>
                 <h6 class="collapse-header">Categorias</h6>
                 <a class="collapse-item <?= ($selectedPage == "categorias_ganhos") ? "active" : ""; ?>" href="/categorias/ganhos">Ganhos</a>
                 <a class="collapse-item <?= ($selectedPage == "categorias_gastos") ? "active" : ""; ?>" href="/categorias/gastos">Gastos</a>
                 <div class="collapse-divider"></div>
             </div>
         </div>

     </li>

     <!-- Divider -->
     <hr class="sidebar-divider d-none d-md-block">

     <!-- Sidebar Toggler (Sidebar) -->
     <div class="text-center d-none d-md-inline">
         <button class="rounded-circle border-0" id="sidebarToggle"></button>
     </div>

     <!-- Sidebar Message -->
     <div class="sidebar-card d-none d-lg-flex">
         <i class="fas fa-history fa-3x"></i>
         <p class="text-center mb-2">Seu <strong>Histórico</strong> está disponível!</p>
         <a class="btn btn-success btn-sm" href="/history">Verificar</a>
     </div>

 </ul>
 <!-- End of Sidebar -->

 <!-- Content Wrapper -->
 <div id="content-wrapper" class="d-flex flex-column">

     <!-- Main Content -->
     <div id="content">

         <!-- Topbar -->
         <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

             <!-- Sidebar Toggle (Topbar) -->
             <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                 <i class="fa fa-bars"></i>
             </button>

             <!-- Topbar Navbar -->
             <ul class="navbar-nav ml-auto">

                 <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                 <li class="nav-item dropdown no-arrow d-sm-none">
                     <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fas fa-search fa-fw"></i>
                     </a>
                     <!-- Dropdown - Messages -->
                     <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                         <form class="form-inline mr-auto w-100 navbar-search">
                             <div class="input-group">
                                 <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                 <div class="input-group-append">
                                     <button class="btn btn-primary" type="button">
                                         <i class="fas fa-search fa-sm"></i>
                                     </button>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </li>

                 <!-- Nav Item - Alerts -->
                 <li class="nav-item dropdown no-arrow mx-1">
                     <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fas fa-bell fa-fw"></i>
                         <!-- Counter - Alerts -->
                         <span class="badge badge-danger badge-counter">3+</span>
                     </a>
                     <!-- Dropdown - Alerts -->
                     <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                         <h6 class="dropdown-header">
                             Central de Notificações
                         </h6>
                         <a class="dropdown-item d-flex align-items-center" href="#">
                             <div class="mr-3">
                                 <div class="icon-circle bg-primary">
                                     <i class="fas fa-file-alt text-white"></i>
                                 </div>
                             </div>
                             <div>
                                 <div class="small text-gray-500">December 12, 2019</div>
                                 <span class="font-weight-bold">A new monthly report is ready to download!</span>
                             </div>
                         </a>
                         <a class="dropdown-item d-flex align-items-center" href="#">
                             <div class="mr-3">
                                 <div class="icon-circle bg-success">
                                     <i class="fas fa-donate text-white"></i>
                                 </div>
                             </div>
                             <div>
                                 <div class="small text-gray-500">December 7, 2019</div>
                                 $290.29 has been deposited into your account!
                             </div>
                         </a>
                         <a class="dropdown-item d-flex align-items-center" href="#">
                             <div class="mr-3">
                                 <div class="icon-circle bg-warning">
                                     <i class="fas fa-exclamation-triangle text-white"></i>
                                 </div>
                             </div>
                             <div>
                                 <div class="small text-gray-500">December 2, 2019</div>
                                 Spending Alert: We've noticed unusually high spending for your account.
                             </div>
                         </a>
                         <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                     </div>
                 </li>

                 <div class="topbar-divider d-none d-sm-block"></div>

                 <!-- Nav Item - User Information -->
                 <li class="nav-item dropdown no-arrow">
                     <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $accountData["first_name"]; ?></span>
                         <img class="img-profile rounded-circle" src="/img/undraw_profile.svg">
                     </a>
                     <!-- Dropdown - User Information -->
                     <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                         <a class="dropdown-item" href="/settings">
                             <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                             Configurações
                         </a>
                         <a class="dropdown-item" href="/activity">
                             <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                             Registro de Atividades
                         </a>
                         <div class="dropdown-divider"></div>
                         <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                             <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                             Logout
                         </a>
                     </div>
                 </li>

             </ul>

         </nav>