<?php extract(require('fetch-shipped-orders.php')); ?>
<!--/**
 * Created by PhpStorm.
 * User: philr
 * Date: 13/7/2017
 * Time: 8:14 AM
 */-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="Veeqo - API Dashboard">
    <meta name="author" content="Phil Reynolds">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Veeqo-Tracking-Report</title>

    <!-- Bootstrap Core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

    <!-- Theme CSS -->
    <link href="main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .slider {
          width: 300px;
          height: 300px;
          display: flex;
          overflow-x: auto;
        }
        .slide {
          width: 300px;
          flex-shrink: 0;
          height: 100%;
        }
    </style>
</head>


<body>
<!--
    ==========
    Navigation
    ==========
              -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Veeqo Tracking Report</a>
        </div>

    </div>
    <!-- /.container -->
</nav>
<!--
    ==========
     Content
    ==========
              -->

<div class="container">
    <hr>

    <!-- Title -->
    <div class="row">
        <div class="col-lg-12">
            <h3></h3>
        </div>
    </div>

    <?php
    $api_key = htmlentities($_POST['api-key']);
    $channel_id = htmlentities($_POST['channel_id']);
//    $since_id = htmlentities($_POST['since_id']);
    $page_size = htmlentities($_POST['page_size']);
    $page = htmlentities($_POST['page']);
    ?>

        <form id="fetch_shipped_orders" action="index.php" method="post">

            <?php if(!isset($_POST['api-key']) || ($error)): ?>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                            <h2>Enter your API key:</h2>
                            <input class="form-control input-sm"
                                   type="text"
                                   name="api-key"
                                   value="">
                        <div class="blue-line"></div>
                    </div>
                </div><!-- /.row -->
            <?php endif; ?>

            <?php if(isset($_POST['api-key']) && (!$error)): ?>

                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <h2>Your API Key: </h2>
                        <h3><?php echo $api_key ?></h3>
                        <div class="blue-line"></div>
                    </div>

                </div><!-- /.row -->
            <?php endif; ?>

            <?php if(!isset($_POST['channel_id']) || ($error)): ?>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                            <h2>Enter Channel ID:</h2>
                            <input class="form-control input-sm"
                                   type="text"
                                   name="channel_id"
                                   value="">
                        <div class="blue-line"></div>
                    </div>
                </div><!-- /.row -->
            <?php endif; ?>

            <?php if(isset($_POST['channel_id']) && (!$error)): ?>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <h2>Channel Name: </h2>
                        <h3><?php echo $channel['name'] ?></h3>
                        <div class="blue-line"></div>
                    </div>

                </div><!-- /.row -->
            <?php endif; ?>

            <?php if(!isset($_POST['channel_id']) || !isset($_POST['api-key']) || ($error)): ?>
                <div class="text-center">
                    <label for="page_size">Orders per Page: </label>
                    <select id="page_size" name="page_size">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100" selected>100</option>
                    </select>
                </div>
                <div class="text-center">
                    <input class="btn btn-primary veeqo-background" type="submit" value="Get Orders" />
                </div>
            <?php endif; ?>

            <?php if(isset($_POST['channel_id']) && isset($_POST['api-key']) && (!$error)): ?>
                <div class="text-center form-group">
                    <div>
                        <label for="page_size">Orders per Page: </label>
                        <select id="page_size" name="page_size" type="submit">
                            <option value="10" <?php if (isset($page_size) && $page_size==10) echo("selected");?>>10</option>
                            <option value="25" <?php if (isset($page_size) && $page_size==25) echo("selected");?>>25</option>
                            <option value="50" <?php if (isset($page_size) && $page_size==50) echo("selected");?>>50</option>
                            <option value="100" <?php if (isset($page_size) && $page_size==100) echo("selected");?>>100</option>
                        </select>
                    </div>
                    <input type="hidden"  name="api-key" value="<?php echo $api_key ?>">
                    <input type="hidden"  name="channel_id" value="<?php echo $channel_id ?>">
<!--                    <input class="btn btn-danger" type="submit" value="Clear Values" />-->
                    <input class="btn btn-success" type="submit" value="Refresh" />
                    <input class="btn btn-danger" type="button" value="Clear Values" onclick="window.location.href='https://veeqo-tracking-report.herokuapp.com/index.php'" />

                <?php if($headers_arr['X-Total-Count']>$page_size): ?>
                    <?php $page_count = ceil($headers_arr['X-Total-Count']/$page_size) ?>
                        <nav>
                            <div id="inner" style="overflow:auto;">
                                <ul class="pagination">
                                    <?php for ($i=1; $i<=$page_count; $i++): ?>
                                        <li class="page-item <?php if ($i==$page) echo("active"); ?>">
                                            <input type="submit" name="page" class="page-link" value="<?php echo($i) ?>" />
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </div>
                        </nav>
                <?php endif; ?>
                </div>
            <?php endif; ?>



        </form>



    <?php if ($error): ?>
        <div class="alert alert-danger text-center" role="alert"><?= $error; ?></div>



    <?php else: ?>

        <table class="table table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col"><strong>Number</strong><br /> </th>
                    <th scope="col">Created At Date</th>
                    <th scope="col"><strong>Deliver to:</strong><br />First Name</th>
                    <th scope="col">Surname</th>
                    <th scope="col">Post Code</th>
                    <th scope="col">Country</th>
                    <th scope="col"><strong>Allocation ID</strong><br /> </th>
                    <th scope="col"><strong>First Product: </strong><br/> Title</th>
                    <th scope="col">SKU</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Item Price</th>
                    <th scope="col"><strong>Shipment Details:</strong><br />Delivery Method</th>
                    <th scope="col">Carrier</th>
                    <th scope="col">Tracking Number</th>
                    <th scope="col">Shipped At Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <?php foreach ($order['allocations'] as $allocation): ?>
                    <tr>
                        <th><?= $order['number'] ?></th>
                        <th><?= $date = date('d-m-Y', strtotime($order['created_at'])) ?></th>
                        <th><?= $order['deliver_to']['first_name'] ?></th>
                        <th><?= $order['deliver_to']['last_name'] ?></th>
                        <th><?= $order['deliver_to']['zip'] ?></th>
                        <th><?= $order['deliver_to']['country'] ?></th>
                        <th><?= $allocation['id'] ?></th>
                        <th><?= $allocation['line_items'][0]['sellable']['full_title'] ?></th>
                        <th><?= $allocation['line_items'][0]['sellable']['sku_code'] ?></th>
                        <th><?= $allocation['line_items'][0]['quantity'] ?></th>
                        <th><?= $allocation['line_items'][0]['sellable']['price'] ?></th>
                        <th><?= $order['delivery_method']['name'] ?></th>
                        <th><?= $allocation['shipment']['carrier']['name'] ?></th>
                        <th><?php if (isset($allocation['shipment']['tracking_number']['tracking_number']))
                            {
                                echo ($allocation['shipment']['tracking_number']['tracking_number']);
                            }
                            elseif (isset($allocation['shipment']['tracking_number']['delivery_confirmation_number']))
                            {
                                echo ($allocation['shipment']['tracking_number']['delivery_confirmation_number']);
                            }
                            else
                            {
                                echo ("No Value");
                            }?></th>
                        <th><?= $date = date('d-m-Y', strtotime($order['shipped_at'])) ?></th>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="blue-line"></div>
    <?php endif; ?>

    <hr>
    <!-- Footer -->
    <footer class="text-center">
        <div class="row">
            <div class="col-lg-12">
                <p>Stats for Geeks</p>
                <p>API request took <?= $time ?>s, response
                    size <?= $responseSize ?> bytes</p>
                <p>API Reponse Code: <?= $responseCode ?></p>
                <p>Total Shipped Channel Orders: <?= $headers_arr['X-Total-Count'] ?></p>
                <p> &copy; Veeqo 2018 Ltd</p>
            </div>
        </div>
    </footer>
</div>
<!-- /.container -->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
</body>
<!--</html>-->
</html>