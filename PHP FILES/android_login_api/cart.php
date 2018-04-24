<?php
include "../db_functions.php";


$appuser = $_GET['appuserID'];
$shopping_cart = get_shopping_cart($appuser);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="An online electronics store, for all your digital needs">
    <meta name="author" content="Dimitri Alikakos">
    <meta name="keywords" content="estore, electronics, xenorious, dimitri, alikakos, xenor, store, digital">
    <meta name="robots" content="all">

    <title>Xenorious</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">

    <!-- Customizable CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/blue.css">
    <link rel="stylesheet" href="../assets/css/owl.carousel.css">
    <link rel="stylesheet" href="../assets/css/owl.transitions.css">
    <!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
    <link href="../assets/css/lightbox.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
    <link rel="stylesheet" href="../assets/css/rateit.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-select.min.css">




    <!-- Icons/Glyphs -->
    <link rel="stylesheet" href="../assets/css/font-awesome.min.css">

    <!-- Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>

    <!-- Favicon -->
    <link rel="shortcut icon" href="../assets/images/favicon.ico">

    <!-- HTML5 elements and media queries Support for IE8 : HTML5 shim and Respond.js -->
    <!--[if lt IE 9]>
    <script src="../assets/js/html5shiv.js"></script>
    <script src="../assets/js/respond.min.js"></script>
    <![endif]-->

</head>
<?php
if ($shopping_cart == NULL) {
    echo "<h3 style='text-align: center'><b>SHOPPING CART IS EMPTY</b></h3>";
}else {
    ?>
    <div class="col-md-12 col-sm-12 shopping-cart-table " style="width:360px;">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="cart-description item">Image</th>
                    <th class="cart-product-name item">Product Name</th>
                    <th class="cart-qty item" style="width:20px;">Quantity</th>
                    <th class="cart-total last-item">Price</th>
                </tr>
                </thead>
                <!-- /thead -->
                <tfoot>

                </tfoot>
                <tbody>

                <?php
                foreach ($shopping_cart as $item) {
                    $product_id = intval($item['prodID']);
                    $product_array = get_product_by_ID($product_id);
                    $average = round(floatval(get_average_rating($product_id)));
                    foreach ($product_array as $productitem) {
                        ?>
                        <tr>
                            <td class="cart-image" style="padding: 5px;">
                                <a class="entry-thumbnail"
                                   href='index.php?clickedProduct=true&clickedProd=<?php echo $productitem['id'] ?>'>
                                    <img style="width: 70px; height: auto; " src="../assets/images/product_images/<?php echo $productitem['id'] ?>/small.jpg"
                                        alt="<?php echo $productitem['pname'] ?> ">
                                </a>
                            </td>
                            <td class="cart-product-name-info" style="padding: 5px;">
                                <h4 class='cart-product-description'><a style="font-size: 12px;"
                                                                        href='index.php?clickedProduct=true&clickedProd=<?php echo $productitem['id'] ?>'><?php echo $productitem['pname'] ?> </a>
                                </h4>
                            </td>
                            <td class="cart-product-quantity" style="padding: 5px; ">
                                <div class="cart-quantity">
                                    <div class="quant-input">

                                        <input type="text" style="padding: 0px; padding-left: 5px ; width: 35px"
                                               value="<?php echo $item['quantity'] ?>">
                                    </div>
                                </div>
                            </td>
                            </td>
                            <td class="cart-product-grand-total"><span
                                    class="cart-grand-total-price">&euro;<?php echo $productitem['price'] ?></span></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <!-- /tbody -->
            </table>
            <!-- /table -->
        </div>
    </div>
    <?php
}
?>
</html>

