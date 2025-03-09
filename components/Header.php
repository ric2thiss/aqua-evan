<?php

class HeaderComponent {
    private array $data;

    public function __construct(array $data = []) {
        $this->data = $data;
    }

    public function render(): string {
        ob_start();
        ?>
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <a href="index.html" class="logo d-flex align-items-center">
                    <img src="assets/img/logo.png" alt="">
                    <span class="d-none d-lg-block">AQUA EVAN</span>
                </a>
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div>

            <div class="search-bar">
                <form class="search-form d-flex align-items-center" method="POST" action="#">
                    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                </form>
            </div>

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">

                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="badge bg-primary badge-number"><?= $this->data['notifications']['count'] ?? 0; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                                You have <?= $this->data['notifications']['count'] ?? 0; ?> new notifications
                                <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                            </li>
                            <?php if (!empty($this->data['notifications']['items'])): ?>
                                <?php foreach ($this->data['notifications']['items'] as $notification): ?>
                                    <li class="notification-item">
                                        <i class="bi <?= $notification['icon'] ?> text-<?= $notification['type'] ?>"></i>
                                        <div>
                                            <h4><?= $notification['title'] ?></h4>
                                            <p><?= $notification['message'] ?></p>
                                            <p><?= $notification['time'] ?></p>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown pe-3">
                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                            <span class="d-none d-md-block dropdown-toggle ps-2"><?= $this->data['user']['username'] ?? 'Guest'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6><?= ucfirst($this->data['user']['username'] ?? 'Guest'); ?></h6>
                                <span><?= ucfirst($this->data['user']['role'] ?? 'User'); ?></span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                                    <i class="bi bi-person"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </header>
        <?php
        return ob_get_clean();
    }
}

// Example usage
?>
