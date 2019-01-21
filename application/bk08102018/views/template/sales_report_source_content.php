<!DOCTYPE html>
<html>
<head>
	<title>Sales Report</title>
	<style>
		body {
			font-family: 'Segoe UI',sans-serif;
			font-size: 12px;
		}
		table, th, td { border-color: white; text-align: left;}
		tr { border-bottom: none !important; }
		th { border-bottom: 1px solid gray; }

		.report-header {
			font-size: 22px;
		}
		.right-align{

			text-align:right;
		}
		@media print {
      @page { margin: 0; }
      body { margin: 1.0cm; }
}

	
	</style>
	<script>
		(function(){
			window.print();
		})();
	</script>
</head>
<body>
	<table width="100%">
        <tr>
            <td width="10%"><img src="<?php echo base_url($company_info->logo_path); ?>" style="height: 90px; width: 120px; text-align: left;"></td>
            <td width="90%">
                <span class="report-header"><strong><?php echo $company_info->company_name; ?></strong></span><br>
                <span><?php echo $company_info->company_address; ?></span><br>
                <span><?php echo $company_info->landline.'/'.$company_info->mobile_no; ?></span>
            </td>
        </tr>
    </table><hr>
    <div>
        <h3><strong><center>Sales Report by Source</center> </strong></h3>
    </div>
<?php $rtotal = 0; $gtotal=0; ?>

<table cellspacing="0" cellpadding="5">
	<tr>
		<td><strong>Period: </strong> <?php echo $_GET['start']  ?> - <?php echo $_GET['end']  ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><strong>Source: </strong><?php echo $source_name?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td><strong>Invoice Source: </strong><?php $si = $_GET['si']; if($si == 0){ echo 'All Invoices'; }else if ($si == 1){echo 'Sales Invoices Only'; }else if($si== 2){ echo 'Cash Invoice Only' ;} ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
<h2>Sales</h2>
<?php 
foreach ($sources as $source) { ?>
<h3 style="font-weight: normal;"><?php echo $source->order_source_name ?></h3>
<table style="width: 100%;border: 1px solid gray;" cellspacing="0" cellpadding="3">
	<thead>
		<th>Invoice No.</th>
        <th>Invoice Date</th>
        <th>Customer Name</th>
        <th>Product Desription</th>
        <th class="right-align">Quantity</th>
        <th class="right-align">Unit Cost</th>
        <th class="right-align">Gross</th>
        <th class="right-align">Discount(%)</th>
        <th class="right-align">Net</th>
	</thead>
	<tbody>
		<?php  
		foreach ($data as $raw) {
			if($source->order_source_id == $raw->order_source_id){ ?>
				<tr>
				<td><?php echo $raw->inv_no ?></td>
		        <td><?php echo $raw->date ?></td>
		        <td><?php echo $raw->customer_name ?></td>
		        <td><?php echo $raw->product_desc ?></td>
		        <td class="right-align"><?php echo number_format($raw->inv_qty,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->inv_price,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->inv_gross,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->inv_discount,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->inv_line_total_price,2) ?></td>
		        </tr>
	<?php 	} // END OF IF 
		} ?>
		 <!-- END OF FOREACH DATA  -->


<?php 
$gtotal = 0;
foreach ($totals as $total) { 
	$gtotal += $total->sub_total;
	?>
	<?php if($source->order_source_id == $total->order_source_id){ ?>
	<tr>
	<td colspan="8" class="right-align">
		<strong>Sub Total:</strong>
	</td>
	<td class="right-align"><strong><?php echo number_format($total->sub_total,2) ?></strong></td>
	</tr>

<?php } } ?>
	</tbody>



</table>

<?php } ?>
<br>
<table style="width: 100%;font-size: 12px;font-weight: bold;" cellpadding="5" cellspacing="0">
	<tr style="text-align: right;">
	<td style="width: 75%;text-align: right;"></td>
	<td style="width: 15%;text-align: left;border-bottom: 1px solid black!important;">Grand Total:</td>
	<td style="text-align: right;border-bottom: 1px solid black!important;"><?php echo number_format($gtotal,2) ?></td>
</tr>
</table>




<h2>Returns</h2>



<?php 
foreach ($return_sources as $source) { ?>
<h3 style="font-weight: normal;"><?php echo $source->order_source_name ?></h3>
<table style="width: 100%;border: 1px solid gray;" cellspacing="0" cellpadding="3">
	<thead>
		<th>Invoice No.</th>
		<th>Reference</th>
        <th>Retrn Date</th>
        <th>Customer Name</th>
        <th>Product Desription</th>
        <th class="right-align">Quantity</th>
        <th class="right-align">Unit Cost</th>
        <th class="right-align">Total</th>
	</thead>
	<tbody>
		<?php  
		foreach ($return_data as $raw) {
			if($source->order_source_id == $raw->order_source_id){ ?>
				<tr>
				<td><?php echo $raw->inv_no ?></td>
		        <td><?php echo $raw->adjustment_code ?></td>
		        <td><?php echo $raw->date_adjusted ?></td>
		        <td><?php echo $raw->customer_name ?></td>
		        <td><?php echo $raw->product_desc ?></td>
		        <td class="right-align"><?php echo number_format($raw->adjust_qty,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->adjust_price,2) ?></td>
		        <td class="right-align"><?php echo number_format($raw->adjust_line_total_price,2) ?></td>
		        </tr>
	<?php 	} // END OF IF 
		} ?>
		 <!-- END OF FOREACH DATA  -->


<?php 
$rtotal = 0;
foreach ($return_totals as $total) { 
	$rtotal += $total->sub_total;
	?>
	<?php if($source->order_source_id == $total->order_source_id){ ?>
	<tr>
	<td colspan="7" class="right-align">
		<strong>Sub Total:</strong>
	</td>
	<td class="right-align"><strong><?php echo number_format($total->sub_total,2) ?></strong></td>
	</tr>

<?php } } ?>
	</tbody>



</table>

<?php } ?>
<br>
<table style="width: 100%;font-size: 12px;font-weight: bold;" cellpadding="5" cellspacing="0">
	<tr style="text-align: right;">
	<td style="width: 75%;text-align: right;"></td>
	<td style="width: 15%;text-align: left;border-bottom: 1px solid black!important;">Total Returns:</td>
	<td style="text-align: right;border-bottom: 1px solid black!important;"><?php echo number_format($rtotal,2) ?></td>
</tr>
</table>



<table style="width: 100%;font-size: 12px;font-weight: bold;" cellpadding="5" cellspacing="0">
	<tr style="text-align: right;">
	<td style="width: 75%;text-align: right;"></td>
	<td style="width: 15%;text-align: left;border-bottom: 1px solid black!important;">Net Sales:</td>
	<td style="text-align: right;border-bottom: 1px solid black!important;"><?php echo number_format($gtotal-$rtotal,2) ?></td>
</tr>
</table>












</body>
</html>
