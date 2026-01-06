<?php include 'db.php';
include 'check_login.php';

$query = "SELECT * FROM `admin` WHERE `id`=" . $_SESSION['A_id'];
$data = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($data);
$role = $row['role'];
$sales_person = $row['fname'] . " " . $row['lname'];
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <img src="dist/img/logo-k.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
      style="opacity: .8">
    <span class="brand-text font-weight-light">Ku Networks</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= $sales_person ?></a>
      </div>
    </div>



    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <li class="nav-item menu-open">
          <a href="index.php" class="nav-link active">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>

        </li>
        <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Charts
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ChartJS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/flot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Flot</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/inline.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inline</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/uplot.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>uPlot</p>
                </a>
              </li>
            </ul>
          </li> -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Admin Wallet
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="admin_wallet.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>


<li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Announcements
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_announcment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="announcment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>



        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Users
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_users.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="users.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              User Wallets
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="user_wallets.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="wallet_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>History</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              User Balance Transfer
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <!-- <li class="nav-item">
              <a href="add_balance_transfer.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Balance Transfer</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="accept_transfer.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>User Balance Accept</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="balance_transfer_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Balance Transfer History</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="transfer_approval.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Transfer Admin Approval</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="transfer_fee_management.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Balance transfer Fee</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Countries
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_country.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="countries.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Points
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_point.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="points_settings.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Levels
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_level.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="levels.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Level Upgrade Requirements
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_level_require.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="level_require.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-item has-treeview">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-layer-group"></i>
        <p>
            Staking System
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="add_staking_package.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Package</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="manage_staking_packages.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show Packages</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="staking_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Staking History</p>
            </a>
        </li>
    </ul>
</li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Level Upgrade Bonus
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_level_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="level_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Referral Codes
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="referral_codes.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show Referral Codes</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="referral_teams.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show Referral Teams</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Team Earning Commission
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_team.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="team_earning.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="team_commission_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>History</p>
              </a>
            </li>
          </ul>
        </li>


        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Monthly Salary Bonus
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_monthly_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="monthly_salary_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Rank Achieve Bonus
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_rank_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="rank_bonuses.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Points Earning
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_points_earning.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="points_earning.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Monthly Tournament Competition
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_tournament_competition.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="monthly_tournament_competition.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Stages
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <li class="nav-item">
              <a href="add_stage.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="stages.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Membership
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="add_membership.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="membership.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              User's Capital Payment
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <!-- <li class="nav-item">
              <a href="add_payment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="payment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Payment Show</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="view_products.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Product Show</p>
              </a>
            </li>

          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Payment Withdrawal
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <!-- <li class="nav-item">
              <a href="add_withdraw.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="withdraw_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="approve_withdrawals.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Approve</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Claim
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">

            <!-- <li class="nav-item">
              <a href="claim_bonus.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add Claim Bonus</p>
              </a>
            </li> -->
            <li class="nav-item">
              <a href="bonus_history.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Bonus History</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-th"></i>
            <p>
              Rank Achieve Bonus
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="rank_assignment.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Show</p>
              </a>
            </li>
          </ul>
        </li>




        <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Sidebar Categories
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_menu_cat.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="show_menu_cat.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Show</p>
                </a>
              </li>
            </ul>
          </li> -->
        <!-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Sidebar Products
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="add_sidebar_pro.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="show_sidebar_pro.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Show</p>
                </a>
              </li>
            </ul>
          </li>  -->

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>