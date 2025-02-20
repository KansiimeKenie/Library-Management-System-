<?php
require('init/functions.php');
$page  = 'index';
if (!$global_var['loggedin']) {
    $page = 'login';
}

if (!empty($_GET['page']) && $global_var['loggedin']) {
    $page = secure_data($_GET['page']);
}
if (!$global_var['loggedin'] && isset($_GET['page'])) {
    if ($_GET['page'] == 'login' || $_GET['page'] == 'register' || $_GET['page'] == 'forgot_pwd') {
        $page = secure_data($_GET['page']);
    }
}


$page_loaded = load_page("$page");

?>

<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title><?= $global_var['config']['site_name'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $global_var['site_url'] . $global_var['config']['favicon']  ?>">

    <!-- jsvectormap css -->
    <link href="<?= $global_var['site_url'] ?>/layout/assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" />

    <!-- gridjs css -->
    <link rel="stylesheet" href="<?= $global_var['site_url'] ?>/layout/assets/libs/gridjs/theme/mermaid.min.css">
    <!-- jQuery (Required) -->


    <!-- Layout config Js -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="<?= $global_var['site_url'] ?>/layout/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= $global_var['site_url'] ?>/layout/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= $global_var['site_url'] ?>/layout/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= $global_var['site_url'] ?>/layout/assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <!-- SweetAlert JavaScript -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.min.js"></script> -->

    <link href="<?= $global_var['site_url'] ?>/layout/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->

    <!-- Bootstrap Select CSS (For Dropdown Styling) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">



    <script type="text/javascript">
        function request() {
            return "<?php echo $global_var['site_url'] . 'request.php'; ?>"
        }
    </script>

</head>

<body>

    <?php if ($page == 'login' || $page == 'forgot_pwd' || $page == 'register' || $page == 'reset'):
        echo $page_loaded;
        return;
    else:

    ?>
        <div id="layout-wrapper">
            <!-- Preloader -->
            <div class="vertical-overlay"></div>

            <?= load_page('partials/header') ?>

            <?= $page_loaded ?>

            <!--body content end-->

            <?= load_page('partials/footer') ?>


        </div>
        <!-- Add new Book Modal -->
        <div id="register" class="modal fade fadeInUp" tabindex="-1" aria-labelledby="fadeInUpModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fadeInUpModalLabel">Add New Book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add_book">
                            <div class="row gy-4">

                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">Book Category</label>
                                        <select name="category_id" class="form-control selectpicker" data-live-search="true">
                                            <option value="">--Select Category--</option>
                                            <?php foreach ($db->get("categories") as $value) { ?>
                                                <option value="<?= $value->id ?>"><?= $value->name ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                </div>


                                <!--end col-->
                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="labelInput" class="form-label">Book Title </label>
                                        <input type="text" name="title" class="form-control">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-12" id="selectAuthorDiv">
                                    <div>
                                        <label for="basiInput" class="form-label">Book Author</label>
                                        <select id="authorSelect" name="author_id" class="form-control selectpicker" data-live-search="true">
                                            <option value="">--Select Authors--</option>
                                            <?php foreach ($db->get("book_authors") as $value) { ?>
                                                <option value="<?= $value->id ?>"><?= $value->names ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="registerAuthor" class="form-label">Register New Book Author</label>
                                        <input type="checkbox" name="new_author" value="1" id="registerAuthor" />
                                    </div>
                                </div>

                                <!-- Book Author Name Input (Initially Hidden) -->
                                <div class="col-xxl-3 col-md-12 d-none" id="newAuthorDiv">
                                    <div>
                                        <label for="placeholderInput" class="form-label">Book Author Name</label>
                                        <input type="text" class="form-control" name="author_names" placeholder="Book Author">
                                    </div>
                                </div>


                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="placeholderInput" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" name="isbn" placeholder="ISBN">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="placeholderInput" class="form-label">Year of Publication</label>
                                        <input type="text" class="form-control" name="year_of_publication" placeholder="Year of Publication">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="placeholderInput" class="form-label">No. Of Copies Available</label>
                                        <input type="text" class="form-control" name="no_of_copies" placeholder="No. Of Book Copies Available">
                                    </div>
                                </div>
                                <!--end col-->


                            </div>
                            <hr>
                            <div class="modal-footer mt-4">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary ">Add New Book</button>
                            </div>
                    </div>
                    </form>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- Add new Borrow request Modal -->
        <div id="new_borrow_request" class="modal fade fadeInUp" tabindex="-1" aria-labelledby="fadeInUpModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="fadeInUpModalLabel">Add New Borrow Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="add_new_borrow_request">
                            <div class="row gy-4">

                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="basiInput" class="form-label">Book</label>
                                        <select id="book_id" name="book_id" class="form-control selectpicker " data-live-search="true">
                                            <option value="">--Select Book --</option>
                                            <?php foreach ($db->get("books") as $value) { ?>
                                                <option value="<?= $value->id ?>"><?= $value->title . ' : By ' . $db->where('id', $value->author_id)->getOne('book_authors')->names ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-xxl-3 col-md-12">
                                    <div>
                                        <label for="placeholderInput" class="form-label">Reason</label>
                                        <textarea placeholder="Enter Borrowing reason here.." class="form-control" name="reason"></textarea>

                                    </div>
                                </div>

                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="placeholderInput" class="form-label">No. Of Copies to be Borrowed</label>
                                        <input type="text" class="form-control" name="no_of_copies" placeholder="No. Of Book Copies Available">
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-6">
                                    <div>
                                        <label for="placeholderInput" class="form-label">Return date</label>
                                        <input type="date" class="form-control" name="return_date">
                                    </div>
                                </div>
                                <!--end col-->


                            </div>
                            <hr>
                            <div class="modal-footer mt-4">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button id="request_button" type="submit" class="btn btn-primary ">Submit request</button>
                            </div>
                    </div>
                    </form>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!--End pagewrapper-->
    <?php endif; ?>
    <!-- Add new Borrow request Modal -->
    <div id="filter_" class="modal fade fadeInUp" tabindex="-1" aria-labelledby="fadeInUpModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fadeInUpModalLabel">Filter Books </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filter_book">
                        <input type="hidden" value="<?= $page ?>" name="page" />
                        <div class="row gy-4">

                            <!-- Book Category Filter -->
                            <div class="col-xxl-3 col-md-12">
                                <div>
                                    <label for="categorySelect" class="form-label">Book Category</label>
                                    <select id="categorySelect" name="category_id" class="form-control selectpicker" data-live-search="true">
                                        <option value="">--Select Category--</option>
                                        <?php foreach ($db->get("categories") as $value) { ?>
                                            <option value="<?= $value->id ?>"><?= $value->name  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Book Author Filter -->
                            <div class="col-xxl-3 col-md-12">
                                <div>
                                    <label for="authorSelect" class="form-label">Book Author</label>
                                    <select id="authorSelection" name="author_id" class="form-control selectpicker" data-live-search="true">
                                        <option value="">--Select Book Author--</option>
                                        <?php foreach ($db->get("book_authors") as $value) { ?>
                                            <option value="<?= $value->id ?>"><?= $value->names  ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- Alert Message -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <small class="text-muted" id="filterAlert"></small>
                            </div>
                        </div>

                        <hr>

                        <!-- Filter Button -->
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Filter Books</button>
                        </div>
                    </form>
                </div>

                <script>
                    function updateFilterAlert() {
                        let categoryValue = document.getElementById("categorySelect").value;
                        let authorValue = document.getElementById("authorSelection").value;
                        let alertBox = document.getElementById("filterAlert");

                        if (categoryValue && !authorValue) {
                            alertBox.innerText = "You are filtering by category only.";
                        } else if (!categoryValue && authorValue) {
                            alertBox.innerText = "You are filtering by author only.";
                        } else if (categoryValue && authorValue) {
                            alertBox.innerText = "You are filtering by both category and author.";
                        } else {
                            alertBox.innerText = "";
                        }
                    }

                    document.getElementById("categorySelect").addEventListener("change", updateFilterAlert);
                    document.getElementById("authorSelection").addEventListener("change", updateFilterAlert);
                </script>

                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- Add new Borrow request Modal -->
    <div id="filter_by_date" class="modal fade fadeInUp" tabindex="-1" aria-labelledby="fadeInUpModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fadeInUpModalLabel">Filter By Date</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filter_by_dates">
                        <input type="text" value="<?= $page ?>" name="page" hidden />
                        <div class="row gy-4">

                            <div class="col-xxl-3 col-md-6">
                                <div>
                                    <label for="basiInput" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                            </div>
                            <div class="col-xxl-3 col-md-6">
                                <div>
                                    <label for="basiInput" class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary ">Filter</button>
                        </div>
                </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- JAVASCRIPT -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/node-waves/waves.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/feather-icons/feather.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/js/plugins.js"></script>

    <!-- apexcharts -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/apexcharts/apexcharts.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Vector map-->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/jsvectormap/jsvectormap.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/jsvectormap/maps/world-merc.js"></script>

    <!-- gridjs js -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/gridjs/gridjs.umd.js"></script>

    <!-- Dashboard init -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/js/pages/dashboard-job.init.js"></script>

    <!-- App js -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/js/app.js"></script>



    <!-- Sweet Alerts js -->
    <script src="<?= $global_var['site_url'] ?>/layout/assets/libs/sweetalert2/sweetalert2.min.js"></script>





    <!-- Dashboard init -->
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/js/pages/dashboard-job.init.js"></script>

    <!-- App js -->
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/js/app.js"></script>


    <!-- prismjs plugin -->
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/libs/prismjs/prism.js"></script>
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/libs/list.js/list.min.js"></script>
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/libs/list.pagination.js/list.pagination.min.js"></script>

    <!-- listjs init -->
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/js/pages/listjs.init.js"></script>

    <!-- Sweet Alerts js -->
    <script src="<?= $global_var['site_url'] ?>admin/layout/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <!-- Include Bootstrap JS -->

    <!-- FontAwesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- jQuery (Required for Bootstrap Select) -->


    <!-- Bootstrap Bundle JS (Required for Bootstrap Components) -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> -->

    <!-- Bootstrap Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <!-- Initialize Bootstrap Select -->
    <script>
        $(document).ready(function() {
            $('.selectpicker').selectpicker(); // Activate Bootstrap Select
        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
        });


        $('#add_new_borrow_request').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                url: request() + '?file=borrow&action=add_new_borrow_request',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: true,
                        });
                        setTimeout(function() {
                            window.location.reload()
                        }, 2e3);

                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: response.message,
                            icon: "error",
                            cancelButtonClass: "btn btn-danger ml-2 mt-2",
                            confirmButtonColor: "#6c757d"
                        });
                    }
                }
            });
        });
        $('#add_book').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                url: request() + '?file=books&action=new_book',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 2e3);

                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: response.message,
                            icon: "error",
                            cancelButtonClass: "btn btn-danger ml-2 mt-2",
                            confirmButtonColor: "#6c757d"
                        });
                    }
                }
            });
        });
        $('#filter_book').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                url: request() + '?file=books&action=filter_by_category',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 2e3);

                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: response.message,
                            icon: "error",
                            cancelButtonClass: "btn btn-danger ml-2 mt-2",
                            confirmButtonColor: "#6c757d"
                        });
                    }
                }
            });
        });
        $('#filter_by_dates').on('submit', function(e) {

            e.preventDefault();
            $.ajax({
                url: request() + '?file=books&action=filter_by_dates',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                cache: false,
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        Swal.fire({
                            title: "Good job!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: true,
                        });
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 2e3);

                    } else {
                        Swal.fire({
                            title: "Oops!",
                            text: response.message,
                            icon: "error",
                            cancelButtonClass: "btn btn-danger ml-2 mt-2",
                            confirmButtonColor: "#6c757d"
                        });
                    }
                }
            });
        });


        document.getElementById('registerAuthor').addEventListener('change', function() {
            let newAuthorDiv = document.getElementById('newAuthorDiv');
            let selectAuthorDiv = document.getElementById('selectAuthorDiv');
            if (this.checked) {
                newAuthorDiv.classList.remove('d-none'); // Show new author input field
                selectAuthorDiv.classList.add('d-none'); // Hide Select Author field
            } else {
                newAuthorDiv.classList.add('d-none'); // Hide input field
                selectAuthorDiv.classList.remove('d-none'); // Show Select Author field
            }
        });
        document.getElementById("book_id").addEventListener("change", function() {
            var selected_book_id = this.value;
            let request_button = document.getElementById('request_button');

            $.ajax({
                url: request() + '?file=books&action=get_book_copies',
                method: 'POST',
                data: {
                    id: selected_book_id
                },
                success: function(response) {
                    console.log(response);

                    if (response.status == 200) {
                        request_button.classList.remove('disabled'); // Enable button
                    } else {
                        request_button.classList.add('disabled'); // Disable button
                        Swal.fire({
                            title: "Oops!",
                            text: response.message,
                            icon: "error",
                            confirmButtonColor: "#6c757d"
                        });
                    }
                }
            });
        });
    </script>







</body>

</html>