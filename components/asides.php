<?php

function aside_component($data = []){
    
    switch ($data['page']) {
        case "index":
            $index = '';
            break;
        case "stocks":
            $stocks = '';
            break;
        case "return":
            $return = '';
            break;
        case "delivery":
            $delivery = '';
            break;
        case "cashier":
            $cashier = '';
            break;
        case "sales":
            $sales = '';
            break;
        case "customer":
            $customer = '';
            break;
        case "staff":
            $staff = '';
            break;
        case "settings":
            $settings = '';
            break;
        default:
            $state = 'collapsed';
    }
    
                    
    ob_start();
    ?>

<!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link <?=$index, $state?>" href="index.php">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-heading">Inventory Management</li>

            <li class="nav-item">
                <a class="nav-link <?=$stocks , $state?>" href="stocks-tracking.php">
                <i class="bi bi-card-checklist"></i>
                <span>Stocks Tracking</span>
                </a> 
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$return , $state?>" href="return-deposit.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Return & Deposit</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$delivery , $state?>" href="delivery-status.php">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Delivery Status</span>
                </a>
            </li>

            <li class="nav-heading">Order Management</li>

            <li class="nav-item">
                <a class="nav-link <?=$cashier , $state?>" href="cashier-orders.php">
                <i class="bi bi-bag-check-fill"></i>
                <span>Cashier & Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$sales , $state?>" href="sales-report.php">
                <i class="bi bi-cash-stack"></i>
                <span>Sales & Reports</span>
                </a>
            </li>

            <li class="nav-heading">Account Management</li>

            <li class="nav-item">
                <a class="nav-link <?=$customer , $state?>" href="customers.php">
                <i class="bi bi-people-fill"></i>
                <span>Customers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$staff , $state?>" href="staff.php">
                <i class="bi bi-person-circle"></i>
                <span>Staff</span>
                </a>
            </li>

            <li class="nav-heading">System Management</li>

            <li class="nav-item">
                <a class="nav-link <?=$settings , $state?>" href="settings.php">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
                </a>
            </li>

        </ul>

    </aside>
<!-- End Sidebar-->
    <?php
    return ob_get_clean();
}