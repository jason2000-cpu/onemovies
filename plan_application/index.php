<?php
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_','',$page)) ?> | <?php echo $_SESSION['system_info']['company_name'] ?></title>
    <link rel="icon" href="<?php echo isset($_SESSION['system_info']['logo_path']) && is_file('.'.(explode('?',$_SESSION['system_info']['logo_path'])[0])) ? '.'.$_SESSION['system_info']['logo_path'] : "./images/no-image-available.png" ?>" />

    <link rel="stylesheet" href="Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="./select2/css/select2.min.css">
    <link rel="stylesheet" href="./css/custom.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="./select2/js/select2.min.js"></script>
    <link rel="stylesheet" href="DataTables/datatables.min.css">
    <script src="DataTables/datatables.min.js"></script>
    <script src="Font-Awesome-master/js/all.min.js"></script>
    <script src="js/script.js"></script>
    
    <style>
      
        #main-header{
            position:relative;
            background: transparent;
            /* background: radial-gradient(circle, rgba(0,0,0,0.48503151260504207) 22%, rgba(0,0,0,0.39539565826330536) 49%, rgba(0,212,255,0) 100%)!important; */
            height:70vh;
        }
        #main-header:before{
            content:"";
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background-image:url(<?php echo isset($_SESSION['system_info']['website_cover']) ? '.'.$_SESSION['system_info']['website_cover'] : './images/dark-bg.jpg' ?>);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            z-index:0;
        }
    </style>
</head>
<body>
    <main>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary bg-gradient sticky-top" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="#">
            <?php echo $_SESSION['system_info']['company_name'] ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == "home" ? "active" :"" ?>" href="./?page=home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == "plans" ? "active" :"" ?>" href="./?page=plans">Plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $page == "about_us" ? "active" :"" ?>" href="./?page=about_us">About Us</a>
                    </li>
                </ul>
            </div>
            <div>
            </div>
        </div>
    </nav>
    <header class="bg-dark py-5 shadow-lg" id="main-header">
        <div class="container h-100 d-flex align-items-end justify-content-center w-100">
            <div class="text-center text-white w-100">
                <h1 class="display-4 fw-bolder"></h1>
                <p class="lead fw-normal text-white-50 mb-0"></p>
                <div class="col-auto mt-2">
                </div>
            </div>
        </div>
    </header>
    <div class="container py-3" id="page-container">
        <?php 
            if(isset($_SESSION['flashdata'])):
        ?>
        <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?>">
        <div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a></div>
            <?php echo $_SESSION['flashdata']['msg'] ?>
        </div>
        <?php unset($_SESSION['flashdata']) ?>
        <?php endif; ?>
        <?php
            include $page.'.php';
        ?>
    </div>
    </main>
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
            <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal_secondary" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal_secondary form').submit()">Save</button>
            <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content rounded-0">
            <div class="modal-header py-2">
            <h5 class="modal-title">Confirmation</h5>
        </div>
        <div class="modal-body">
            <div id="delete_content"></div>
        </div>
        <div class="modal-footer py-1">
            <button type="button" class="btn btn-primary btn-sm rounded-0" id='confirm' onclick="">Continue</button>
            <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
        </div>
    </div>

    <script>
        $(function(){
            $('#register-btn').click(function(){
                uni_modal("Register New Account","register.php","mid-large");
            })
            $('#login-btn').click(function(){
                uni_modal('Please Enter your Login Credentials',"login.php")
            })
        })
    </script>
</body>
</html>