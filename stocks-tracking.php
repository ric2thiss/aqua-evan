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
  }
}


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
  $sidebar = new Sidebar('stocks');
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
      <h1>Stocks Tracking</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Stocks Tracking</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Borrowed <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-journal-arrow-down"></i>
                    </div>
                    <div class="ps-3">
                      <h6>145</h6>
                      <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-3 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Bottle <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-droplet-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>$3,264</h6>
                      <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-3 col-md-6">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Out of Stock <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-dash-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6>1244</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Customers Card -->
            <div class="col-xxl-3 col-md-6">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Damage Stock <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-trash-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>1244</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->
            <!-- Summary -->
            <div class="row mt-3">
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Summary</h5>
                    <table class="table datatable">
                      <thead>
                        <tr>
                          <th scope="col">ID #</th>
                          <th scope="col">Product</th>
                          <th scope="col">Listed</th>
                          <th scope="col">Unit Price</th>
                          <th scope="col">Quantity</th>
                          <th scope="col">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Gallon</td>
                          <td>3</td>
                          <td>20.00</td>
                          <td>10</td>
                          <td>200.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Out of Stock</h5>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#re-stock">
                      Re-Stock
                    </button>
                    <table class="table datatable">
                      <thead>
                        <tr>
                          <th scope="col">ID #</th>
                          <th scope="col">Product</th>
                          <th scope="col">Unit Price</th>
                          <th scope="col">Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Cap</td>
                          <td>10.00</td>
                          <td>4</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Add Product Modal -->
            <section class="section mb-4">
              <!-- Form -->
              <!-- Insert Product -->
              <div class="modal fade" id="insert-product" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Insert Product</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <!-- Floating Labels Form -->
                      <form class="row g-3" method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                        <div class="col-md-8">
                          <div class="form-floating">
                            <input type="text" name="product_name" class="form-control" id="floatingProductName" placeholder="Product Name">
                            <label for="floatingProductName">Product Name</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-floating">
                            <input type="number" name="product_cost" class="form-control" id="floatingCost" placeholder="Product Cost">
                            <label for="floatingCost">Cost</label>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-floating">
                            <input type="number" name="product_quantity" class="form-control" id="floatingQuantity" placeholder="Quantity">
                            <label for="floatingQuantity">Quantity</label>
                          </div>
                        </div>
                        <div class="col-md-8">
                          <div class="form-floating">
                            <!-- <input type="text" name="supplier_name" class="form-control" id="floatingSupplier" placeholder="Supplier"> -->
                            <select class="form-select" name="supplier_id" aria-label="Default select example">
                              <?php if (empty($suppliers)): ?>
                                <option value="">No supplier found. (Please insert a supplier name first)</option>
                              <?php endif ?>
                              <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier["supplier_id"] ?>"><?= $supplier["supplier_name"] ?></option>
                              <?php endforeach ?>
                            </select>
                            <label for="floatingSupplier">Supplier</label>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="col-md-12">
                            <div class="form-floating">
                              <input type="date" name="date" class="form-control" id="floatingDate" placeholder="Date">
                              <label for="floatingDate">Date</label>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" disabled value="<?= htmlspecialchars(trim($user["firstname"] . " " . $user["lastname"])) ?>" name="employee" class="form-control" id="floatingEmployee" placeholder="Employee">
                            <label for="floatingEmployee">Employee</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="add-product">Submit</button>
                        </div>
                      </form><!-- End floating Labels Form -->
                    </div>

                  </div>
                </div>
              </div><!-- End Insert Product Modal-->

              <!-- Form -->
              <!-- Insert Supplier -->
              <div class="modal fade" id="insert-supplier" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Create Supplier</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <!-- Floating Labels Form -->
                      <form class="row g-3" method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" name="supplier_name" class="form-control" id="floatingSupplierName" placeholder="Supplier Name">
                            <label for="floatingSupplierName">Supplier Name</label>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-floating">
                            <!-- <input type="text" name="supplier_name" class="form-control" id="floatingSupplier" placeholder="Supplier"> -->
                            <select class="form-select" aria-label="Default select example">
                              <?php if (empty($suppliers)): ?>
                                <option>No supplier created.</option>
                              <?php endif ?>
                              <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier["supplier_id"] ?>"><?= $supplier["supplier_name"] ?></option>
                              <?php endforeach ?>
                            </select>
                            <label for="floatingSupplier">Suppliers List</label>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" name="add-supplier">Submit</button>
                        </div>
                      </form><!-- End floating Labels Form -->
                    </div>

                  </div>
                </div>
              </div>
              <!-- End Insert Supplier -->

            </section>


            <!-- Stock Products Table -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <!-- Table -->
                <div class="card-body pb-0">
                  <h5 class="card-title">Stocked Products <span>| Today</span></h5>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insert-product">
                    Insert Product
                  </button>
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insert-supplier">
                    Insert Supplier
                  </button>

                  <table class="table datatable">
                    <thead>
                      <tr>
                        <th scope="col">ID #</th>
                        <th scope="col">Preview</th>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Qty</th>
                        <th scope="col">Total</th>
                        <th scope="col">Supplier</th>
                        <th scope="col">Date</th>
                        <th scope="col">Uploaded</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($products as $product): ?>
                        <tr>
                          <td><?= $product["product_id"] ?></td>
                          <td></td>
                          <td><?= $product["product_name"] ?></td>
                          <td><?= $product["product_cost"] ?></td>
                          <td><?= $product["product_quantity"] ?></td>
                          <td><?= $product["product_total_cost"] ?></td>
                          <td><?= $product["supplier_name"] ?></td>
                          <td><?= date("d, F Y", strtotime($product["date"])); ?></td>
                          <td><?= date("d, F Y", strtotime($product["created_at"])); ?></td>
                          <td><?= $product["firstname"] . " " . $product["lastname"] ?></td>
                          <td><?= $product["product_id"] ?></td>
                        </tr>
                      <?php endforeach ?>

                    </tbody>
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>



          <!-- Reports -->
          <div class="col-12">
            <div class="card">

              <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <li class="dropdown-header text-start">
                    <h6>Filter</h6>
                  </li>

                  <li><a class="dropdown-item" href="#">Today</a></li>
                  <li><a class="dropdown-item" href="#">This Month</a></li>
                  <li><a class="dropdown-item" href="#">This Year</a></li>
                </ul>
              </div>

              <div class="card-body">
                <h5 class="card-title">Reports <span>/Today</span></h5>

                <!-- Line Chart -->
                <div id="reportsChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new ApexCharts(document.querySelector("#reportsChart"), {
                      series: [{
                        name: 'Sales',
                        data: [31, 40, 28, 51, 42, 82, 56],
                      }, {
                        name: 'Revenue',
                        data: [11, 32, 45, 32, 34, 52, 41]
                      }, {
                        name: 'Customers',
                        data: [15, 11, 32, 18, 9, 24, 11]
                      }],
                      chart: {
                        height: 350,
                        type: 'area',
                        toolbar: {
                          show: false
                        },
                      },
                      markers: {
                        size: 4
                      },
                      colors: ['#4154f1', '#2eca6a', '#ff771d'],
                      fill: {
                        type: "gradient",
                        gradient: {
                          shadeIntensity: 1,
                          opacityFrom: 0.3,
                          opacityTo: 0.4,
                          stops: [0, 90, 100]
                        }
                      },
                      dataLabels: {
                        enabled: false
                      },
                      stroke: {
                        curve: 'smooth',
                        width: 2
                      },
                      xaxis: {
                        type: 'datetime',
                        categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                      },
                      tooltip: {
                        x: {
                          format: 'dd/MM/yy HH:mm'
                        },
                      }
                    }).render();
                  });
                </script>
                <!-- End Line Chart -->

              </div>

            </div>
          </div>
          <!-- End Reports -->
        </div>


      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php include 'components/footer.php' ?>
  <!-- End Footer -->
</body>

</html>