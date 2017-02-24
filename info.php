<?php
include('header.php');
$blk_get = $_GET['blkc'];
if($blk_get == ""){
$blk_inft = $_POST['blkinf'];
if($blk_inft == 0){
$blk_inft++;
}
}else{
$blk_inft = $blk_get;
}
for ($blk_inf = 1; $blk_inf <= $blk_inft; $blk_inf++) {
}
$blk_inf--;
$block_index = $blk_inf;
$block_hash = $client->getblockhash($block_index);
if($block_hash == ""){
$blk_trans = "0";
$blk_mdiff = "0";
$blk_amount = "0";
$blk_size = "0";
$blk_ver = "0";
$blk_flgd = "unknown";
$blk_bits = "unknown";
$blk_nonce = "0";
$blk_confirm = "0";
}else{
$blk_info = $client->getblock($block_hash);
$blk_mdiff = $blk_info["difficulty"];
$blk_tx = $blk_info["tx"];
$blk_trans = count($blk_tx);
$blk_fee = $fee * ($blk_trans - 1);
$blk_amount = $blk_info["mint"];
$blk_flgd = $blk_info["flags"];
$blk_size = $blk_info["size"];
$blk_ver = $blk_info["version"];
$blk_bits = $blk_info["bits"];
$blk_nonce = $blk_info["nonce"];
$blk_confirm = $blk_info["confirmations"];
if (strpos($blk_flgd, 'proof-of-work') !== false)
{
$blk_flgd = "POW";
} else {
$blk_flgd = "POS";
}
$epoch = $blk_info["time"];
$dt = new DateTime("@$epoch");
$ntime = $dt->format('Y-m-d H:i:s');
}
?>
<!DOCTYPE html>
<html lang="en">
<div class="container">
<div class="alert alert-info">
  <strong><?php echo $langconst['blk'] ?> #<?php echo $blk_inf ?></strong> <?php echo $langconst['hsh'] ?> <?php echo $blk_info["hash"] ?>
</div>
<div class="row">
<div class="col-md-6">
<table class="table table-details mobile-wrap">
<tr>
<td><?php echo $langconst['tstamp'] ?></td>
<td><?php echo $ntime ?></td>
</tr>
<tr>
<td><?php echo $langconst['hght'] ?></td>
<td><?php echo $blk_inf ?></td>
</tr>
<tr>
<td><?php echo $langconst['diff'] ?></td>
<td><?php echo $blk_mdiff ?></td>
</tr>
<tr>
<td><?php echo $langconst['ver'] ?></td>
<td><?php echo $blk_ver ?></td>
</tr>
<tr>
<td><?php echo $langconst['bts'] ?></td>
<td><?php echo $blk_bits ?></td>
</tr>
<tr>
<td><?php echo $langconst['nnce'] ?></td>
<td><?php echo $blk_nonce ?></td>
</tr>
<tr>
<td><?php echo $langconst['sze'] ?></td>
<td><?php echo $blk_size ?> B</td>
</tr>
</table>
<br/>
</div>
<div class="col-md-6">
<table class="table table-details mobile-wrap">
<tr>
<td><?php echo $langconst['mnr'] ?></td>
<td>
<?php echo $blk_flgd ?> </td>
</tr>
<tr>
<td><?php echo $langconst['сtranz'] ?></td>
<td><?php echo $blk_trans ?></td>
</tr>
<tr>
<td><?php echo $langconst['fee'] ?></td>
<td><?php echo number_format( $blk_fee, 8, '.', '' ) ?><small><?php echo " $short" ?></small></td>
</tr>
<tr>
<td><?php echo $langconst['blkrwd'] ?></td>
<td><?php echo $blk_info["mint"] ?> <small><?php echo $short ?></small></td>
</tr>
<tr>
<td><?php echo $langconst['confirm'] ?></td>
<td><?php echo $blk_confirm ?></td>
</tr>
</table>
<?php
$prev_blk = $blk_inf - 1;
$next_blk = $blk_inf + 1;
?>
</div>
<div class="container">
  <ul class="pager">
    <li class="previous"><a href="info.php?blkc=<?php echo $prev_blk ?>"><?php echo $langconst['prev'] ?></a></li>
    <li class="next"><a href="info.php?blkc=<?php echo $next_blk ?>"><?php echo $langconst['next'] ?></a></li>
  </ul>
</div>
</div>
<br/>
<h2><?php echo $langconst['blktx'] ?></h2>
<?php
foreach($blk_tx as $tx_id) {
$raw_tx = $client->getrawtransaction($tx_id, $verbose=1);
foreach ($raw_tx["vout"] as $key => $txout)
{
$value_tx = $txout["value"];
if($txout["value"] == 0){
$value_tx = $blk_info["mint"];
$tx_fee = 0.00000000;
$tx_st = "mined";
}else{
$tx_fee = $fee;
$tx_st = "input";
}
if($txout["value"] == $blk_info["mint"]){
$tx_st = "mined";
}
foreach ($txout["scriptPubKey"]["addresses"] as $key => $address);
{
$addres = $address;
if($addres == ""){
$addres = $langconst['nit'];
$tx_st = "mined";
}
}
}
echo '<div id="sub-content"><div class="wrap">
<table id="table_tx" class="table mobile-wrap">
<thead></thead>
<tbody>
<tr style="background-color: #f8f8f8;font-weight: 400;border-bottom: 2px solid #008cba;">
<td colspan="3"><a href="#" title="',$fullname.' transaction ',$tx_id.'">',$tx_id.'</a></td>
</tr>
<tr>
<td class="mobile-hide"><table id="table_txin" class="table mobile-wrap">
<tbody> <tr>
<td style="border-top: 0px solid #dddddd;"><a href="#" title="',$fullname.' address ',$addres.'">',$addres.'</a></td>
</tr>
</tbody>
</table></td>
<td colspan="3"><table id="table_txout" class="table mobile-wrap">
<tbody> <tr>
<td style="text-align: right;border-top: 0px solid #dddddd;"><img src="/img/',$tx_st.'.png">   ',$value_tx.' <small>',$short.'</small></td>
</tr>
</tbody>
</table></td>
</tr>
</tbody>';
}  
?>
</div>
<div class="modal fade" id="RawBlock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog raw-class">
<div class="modal-content">
<div class="modal-header">
<div class="modal-body">
<pre><code style="background-color: #f5f5f5;"><div id="subcontent"><div id="subloading"><img src="/img/loader.gif" class="ajax-loader"></div></div></code></pre>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
</div>
</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.2/highlight.min.js"></script>
<script src="/js/language.js"></script>
</body>
</html>