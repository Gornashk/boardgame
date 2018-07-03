<?php
function ks_priceRepeater($row) {
  
  if($row['stock'] == 'true') {
    $stock = 'Available';
  }
  elseif($row['stock'] == 'false') {
    $stock = 'Not Available';
  }
  else {
    $stock = $row['stock'];
  }
?>

<div class="priceRow" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
  <?php /*<meta itemprop="name" content="<?php the_title(); ?>" />*/ ?>
  <div class="rowName">
    <a href="<?php echo $row['link']; ?>"><span itemprop="seller"><?php echo $row['retailer']; ?></span></a>
  </div>
  <div class="rowPrice">
    <meta itemprop="priceCurrency" content="USD" />
    <span itemprop="price"><?php echo $row['price']; ?></span>
  </div>
  <div class="rowStock">
    <span><?php echo $stock; ?></span>
  </div>
  <div class="rowLink">
    <a href="<?php echo $row['link']; ?>" target="_blank" itemprop="url" class="storeBtn">Visit Store</a>
  </div>
</div>

<?php
} ?>