<?php
require_once 'session.php';
require_once('components/head.includes.php');
require_once('components/Header.php');

require_once('controller/User.php');
require_once('database/Database.php');

require_once('controller/Product.php');

require_once('controller/Supplier.php');

$database = new Database();
$db = $database->connect();

$userData = new User($db);

$Supplier_object = new Supplier($db);
$suppliers = $Supplier_object->all();


$product_object = new Product($db);
$products = $product_object->all();
$outofstocks = $product_object->get_outofstock(["min" => 5]);
$summary_list = $product_object->get_summary();


// Getter : Get userbyid
$user = $userData->getUserById($_SESSION["user_id"]);

// Initial Load of Data fetch from database
$userData = [
    'user' => [
        'username' => $user["firstname"],
        'role' => $user["role_name"]
    ],
    'notifications' => [
        'count' => 3,
        'items' => [
            ['icon' => 'bi-exclamation-circle', 'type' => 'warning', 'title' => 'System Alert', 'message' => 'Update required', 'time' => '10 min ago'],
            ['icon' => 'bi-x-circle', 'type' => 'danger', 'title' => 'Error', 'message' => 'Failed login attempt', 'time' => '1 hr ago'],
            ['icon' => 'bi-check-circle', 'type' => 'success', 'title' => 'Success', 'message' => 'Payment received', 'time' => '2 hrs ago']
        ]
    ]
];

// Process : Send data to database
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["add-product"])) {
        $inputs = [
            "product_name" => $_POST["product_name"] ?? null,
            "product_cost" => $_POST["product_cost"] ?? null,
            "product_quantity" => $_POST["product_quantity"] ?? null,
            "supplier_id" => $_POST["supplier_id"] ?? null,
            "date" => $_POST["date"] ?? null,
            "user_id" => $_SESSION["user_id"] ?? null,
            "activity" => ($user["firstname"] ?? '') . " " . ($user["lastname"] ?? '') . " inserted new product"
        ];

        if ($product_object->insert_product($inputs)) {
            $_SESSION["status"] = "alert-success";
            $_SESSION["alert"] = "Product successfully inserted";
        } else {
            $_SESSION["status"] = "alert-danger";
            $_SESSION["alert"] = "Faild to add a product";
        }
    } else if (isset($_POST["add-supplier"])) {
        if ($Supplier_object->insert()) {
            $_SESSION["status"] = "alert-success";
            $_SESSION["alert"] = "Supplier successfully inserted";
        } else {
            $_SESSION["status"] = "alert-danger";
            $_SESSION["alert"] = "Faild to add supplier ";
        }
    } else if (isset($_POST["re-stock"])) {
        $inputs = [
            "product_id" => $_POST["product_id"] ?? null,
            "product_quantity" => $_POST["quantity"] ?? null,
        ];

        if ($product_object->insert_re_stock($inputs)) {
            $_SESSION["status"] = "alert-success";
            $_SESSION["alert"] = "Product successfully re-stocked";
        } else {
            $_SESSION["status"] = "alert-danger";
            $_SESSION["alert"] = "Failed to re-stock product";
        }
    }
}

// Message from v2
// End of proccessing data

?>

<?= head(['title' => 'Stocks Tracking']) ?>

<body>

    <!-- ======= Header ======= -->

    <?php
    $header = new HeaderComponent($userData);
    echo $header->render();
    ?>
    <!-- End Header -->
    <!-- ======= Sidebar ======= -->
    <?php
    require_once('components/SideBar.php');
    $sidebar = new Sidebar('payment');
    echo $sidebar->render();
    ?>
    <!-- End Sidebar-->

    <main id="main" class="main">
        <style>
            .insert-message {
                position: fixed;
                top: 10%;
                right: 2%;
                z-index: 9999999;
                transition: transform 0.5s ease-out, opacity 0.5s ease-out;
            }

            .remove {
                transform: translateX(300px);
                /* Smaller value for a smoother transition */
                opacity: 0
                    /* Fade out for a better effect */
            }
        </style>

        <!-- Alert Messages -->
        <?php if (!empty($_SESSION["alert"])): ?>
            <div id="alertBox" class="alert <?= $_SESSION["status"] ?> sticky-top insert-message col-md-3" role="alert">
                <?= $_SESSION["alert"] ?>
            </div>
            <?php
            unset($_SESSION["status"]);
            unset($_SESSION["alert"]);
            ?>
        <?php endif; ?>

        <script>
            const alertBox = document.getElementById("alertBox");
            if (alertBox) {
                setTimeout(() => {
                    alertBox.classList.add("remove");

                    // Wait for animation before removing the element
                    setTimeout(() => {
                        alertBox.remove();
                    }, 500); // Matches CSS transition time (0.5s)

                }, 3000); // Alert disappears after 3 seconds
            }
        </script>

        <!-- End of alert message -->
        <div class="pagetitle">
            <h1>Payment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    <li class="breadcrumb-item active">Payment</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-md-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Cashier Form</h5>

                            <!-- Vertical Form -->
                            <form class="row g-3" method="POST" action="order.php">
                                <div class="col-md-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="floatingName" disabled="" value="<?=$user["firstname"] . " " . $user["lastname"]?>" fdprocessedid="gvwi0d">
                                        <label for="floatingName">Employee Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="type" id="floatingCategory" aria-label="Category" fdprocessedid="9bi7c">
                                            <option value="refill">Refill</option>
                                            <option value="new-bottle">New Bottle</option>
                                        </select>
                                        <label for="floatingCategory">Type</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="type-qty" class="form-control" id="floatingProductQuantity" placeholder="Quantity" fdprocessedid="inmpdr">
                                        <label for="floatingProductQuantity">Quantity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="category_id" id="floatingCategory" aria-label="Category" fdprocessedid="lexjom">
                                            <option value="" selected="">Select</option>
                                            <option value="11">Cap</option>
                                            <option value="7">Filter</option>
                                            <option value="8">Gallon</option>
                                            <option value="10">Sealed</option>
                                        </select>
                                        <label for="floatingCategory">Additional</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="additional-qty" class="form-control" id="floatingStockQuantity" placeholder="Quantity" fdprocessedid="1v5b0k">
                                        <label for="floatingStockQuantity">Quantity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="unit_price" value="20" class="form-control" id="floatingUnitPrice" placeholder="Quantity" fdprocessedid="71g92p">
                                        <label for="floatingStockQuantity">Unit Price</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" name="total_price" class="form-control" id="floatingTotalPrice" placeholder="Quantity" fdprocessedid="i0jrzm">
                                        <label for="floatingStockQuantity">Total Price</label>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', () => {
                                        const total_price = document.querySelector('#floatingTotalPrice');
                                        const unit_price = document.querySelector('#floatingUnitPrice');
                                        const quantity = document.getElementById('floatingProductQuantity');

                                        quantity.addEventListener('input', (e) => {
                                            const unit_price_value = parseFloat(unit_price.value) || 0; // Get numeric value, default to 0
                                            const quantity_value = parseInt(e.target.value) || 0; // Get numeric value, default to 0
                                            total_price.value = unit_price_value * quantity_value; // Update total price
                                        });
                                    });
                                </script>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" name="set-order" fdprocessedid="tlwky">Pay Now</button>
                                    <button type="reset" class="btn btn-secondary" fdprocessedid="1dgd0l">Reset</button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>

                </div>

            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include 'components/footer.php' ?>
    <!-- End Footer -->
</body>

</html>