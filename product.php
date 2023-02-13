<?php
require('header.php');
if (isset($_GET['id'])) {
	$product_id = mysqli_real_escape_string($con, $_GET['id']);
	if ($product_id > 0) {
		$get_product = get_product($con, '', '', $product_id);
	} else {
?>
		<script>
			window.location.href = 'index.php';
		</script>
	<?php
	}

	$resMultipleImages = mysqli_query($con, "select product_images from product_images where product_id='$product_id'");
	$multipleImages = [];
	if (mysqli_num_rows($resMultipleImages) > 0) {
		while ($rowMultipleImages = mysqli_fetch_assoc($resMultipleImages)) {
			$multipleImages[] = $rowMultipleImages['product_images'];
		}
	}
} else {
	?>
	<script>
		window.location.href = 'index.php';
	</script>
<?php
}
?>
<!-- Start Bradcaump area -->
<div class="ht__bradcaump__area" style="background: rgba(236, 236, 236);">
    <div class="ht__bradcaump__wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bradcaump__inner">
                        <nav class="bradcaump-inner">
                            <a class="breadcrumb-item" href="index.php">Home</a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <a class="breadcrumb-item" href="categories.php?id=<?php echo $get_product['0']['categories_id'] ?>"><?php echo $get_product['0']['categories'] ?></a>
                            <span class="brd-separetor"><i class="zmdi zmdi-chevron-right"></i></span>
                            <span class="breadcrumb-item active"><?php echo $get_product['0']['product_name'] ?></span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bradcaump area -->
<!-- Start Product Details Area -->
<section class="htc__product__details bg__white ptb--100">
    <!-- Start Product Details Top -->
    <div class="htc__product__details__top">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-lg-5 col-sm-12 col-xs-12">
                    <div class="htc__product__details__tab__content">
                       <!-- Start Product Big Images -->
						<div class="product__big__images">
							<div class="portfolio-full-image tab-content">
								<div role="tabpanel" class="tab-pane fade in active" id="img-tab-1">
									<img src="<?php echo PRODUCT_IMAGE_SITE_PATH . $get_product['0']['image'] ?>" >
								</div>

								<?php if (isset($multipleImages[0])) { ?>
									<div id="multiple_images">
										<?php
										foreach ($multipleImages as $list) {
											echo "<img src='" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list . "' onclick=showMultipleImage('" . PRODUCT_MULTIPLE_IMAGE_SITE_PATH . $list . "')>";
										}
										?>
									</div>
								<?php } ?>
							</div>
						</div>
						<!-- End Product Big Images -->
                    </div>
                </div>
                <div class="col-md-7 col-lg-7 col-sm-12 col-xs-12 smt-40 xmt-40">
                    <div class="ht__product__dtl">
                        <h2><?php echo $get_product['0']['product_name'] ?></h2>
                        <ul class="pro__prize">
                            <li class="old__prize"><?php echo $get_product['0']['mrp'] ?></li>
                            <li><?php echo $get_product['0']['price'] ?></li>
                        </ul>
                        <p class="pro__info"><?php echo $get_product['0']['short_description'] ?></p>
                        <div class="ht__pro__desc">
                            <div class="sin__desc">
                                <p><span>Quantity:</span>
                                    <select id="qty">
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                        <option>10</option>
                                    </select>
                                </p>
                            </div>
                            <div class="sin__desc">
                                <p><span>Av:</span> In Stock</p>
                            </div>
                            <div class="sin__desc align--left">
                                <p><span>Categories:</span></p>
                                <ul class="pro__cat__list">
                                    <li><a href="#"><?php echo $get_product['0']['categories'] ?></a></li>
                                </ul>
                            </div><br>
                            <a class="btn btn-danger" href="javascript:void(0)" data-toggle="modal" data-target="#exampleModalCenter" onclick="manage_cart('<?php echo $get_product['0']['id'] ?>',
                            'add')">Add to Cart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Product Details Top -->
</section>
<!-- Start Product Description -->
<section class="htc__produc__decription bg__white">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Start List And Grid View -->
                <ul class="pro__details__tab" role="tablist">
                    <li role="presentation" class="description active"><a href="#description" role="tab" data-toggle="tab">description</a></li>
                </ul>
                <!-- End List And Grid View -->
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="ht__pro__details__content">
                    <!-- Start Single Content -->
                    <div role="tabpanel" id="description" class="pro__single__content tab-pane fade in active">
                                <div class="pro__tab__content__inner">
                                    <?php echo $get_product['0']['description']?>
                                </div>
                            </div>
                        <!-- End Single Content -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h3>Product Added to Cart Successfully</h3>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</section>
<!-- End Product Description -->
<!-- End Product Details Area -->
<script>
    function showMultipleImage(im) {
        jQuery('#img-tab-1').html("<img src='" + im + "'/>");
    }
</script>
<?php require('footer.php') ?>