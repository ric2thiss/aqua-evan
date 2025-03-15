<?php

class Sidebar {
    private $activePage;

    public function __construct($activePage) {
        $this->activePage = $activePage;
    }

    public function render() {
        ob_start();
        ?>
        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">

                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('dashboard') ?>" href="dashboard.php">
                        <i class="bi bi-grid"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-heading">Inventory Management</li>

                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('stocks') ?>" href="stocks-tracking.php">
                        <i class="bi bi-card-checklist"></i>
                        <span>Stocks Tracking</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('return') ?>" href="return-deposit.php">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Return & Deposit</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('delivery') ?>" href="delivery-status.php">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Delivery & Orders </span>
                    </a>
                </li>

                <li class="nav-heading">Order Management</li>

                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('payment') ?>" href="payment.php">
                        <i class="bi bi-bag-check-fill"></i>
                        <span>Payment</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('sales') ?>" href="sales-report.php">
                        <i class="bi bi-cash-stack"></i>
                        <span>Sales & Reports</span>
                    </a>
                </li>

                <li class="nav-heading">Account Management</li>

                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('customer') ?>" href="customers.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Customers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('staff') ?>" href="staff.php">
                        <i class="bi bi-person-circle"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <li class="nav-heading">System Management</li>

                <li class="nav-item">
                    <a class="nav-link <?= $this->isActive('settings') ?>" href="settings.php">
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

    private function isActive($page) {
        return ($this->activePage === $page) ? '' : 'collapsed';
    }
}
