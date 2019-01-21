<!DOCTYPE html>
<html>
<head>
	<title>Profit Report</title>
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

	h4{
		font-size: 14px;margin-bottom: :5px;
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
        <h3><strong><center>Profit Report By Product</center> </strong></h3>
    </div>
<?php $rtotal = 0; $gtotal=0; ?>

<table cellspacing="0" cellpadding="5">
	<tr>
		<td><strong>Period: </strong> <?php echo $_GET['start']  ?> - <?php echo $_GET['end']  ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
                    <h4>Profit Report by Invoice (Detailed)</h4>
<?php $detailed_grand_qty=0;
$detailed_grand_gross=0;
$detailed_grand_profit=0; ?>
<?php foreach ($distinct as $inv) { ?>
                    <h4><?php echo $inv->inv_no ?></h4>
                    <table style="width:100%" class="table table-striped" id="">
                    <thead>
                    <th>Item Code</th>
                    <th>Item Description</th>
                    <th>UM</th>
                    <th class="right-align">QTY Sold</th>
                    <th class="right-align">SRP</th>
                    <th class="right-align">Gross</th>
                    <th class="right-align">Unit Cost</th>
                    <th class="right-align">Net Profit</th>
                   
                    </thead>
                        <tbody >
                     
                        <?php  foreach ($items as $item) { ?>
                           <?php if($item->identifier == $inv->identifier && $item->invoice_id == $inv->invoice_id ){?>
                        <tr>
                        <td><?php echo $item->product_code; ?></td>
                        <td><?php echo $item->product_desc; ?></td>
                        <td><?php echo $item->unit_name; ?></td>
                        <td class="right-align"><?php echo $item->inv_qty; ?></td>
                        <td class="right-align"><?php echo number_format($item->srp,2); ?></td>
                        <td class="right-align"><?php echo number_format($item->inv_gross,2); ?></td>
                        <td class="right-align"><?php echo number_format($item->purchase_cost,2); ?></td>
                        <td class="right-align"><?php echo number_format($item->net_profit,2); ?></td>
                        </tr>
                        <?php } ?>
                        <?php  } ?>


                        <?php  foreach ($subtotal as $sub) { ?>
                           <?php if($sub->identifier == $inv->identifier && $sub->invoice_id == $inv->invoice_id ){?>
                        <tr>
                        <td><strong>Total (<?php  echo $sub->inv_no?>)</strong></td>
                        <td></td>
                        <td></td>
                        <td class="right-align"><strong><?php echo $sub->qty_total; ?></strong></td>
                        <td class="right-align"></td>
                        <td class="right-align"><strong><?php echo number_format($sub->gross_total,2); ?></strong></td>
                        <td class="right-align"></td>
                        <td class="right-align"><strong><?php echo number_format($sub->profit_total,2); ?></strong></td>
                        </tr>
                        <?php                         
                        $detailed_grand_qty +=$sub->qty_total;
						$detailed_grand_gross +=$sub->gross_total;
						$detailed_grand_profit +=$sub->profit_total;
						} ?>
                        <?php } ?>
                        </tbody>
                    </table>



<?php } ?>

<br><br>
<table style="width:100%;border:none!important;font-size:12px!important;font-weight:bold;">
<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>
    <td style="width:70%;"> </td><td style="width:15%;">Total Quantity Sold: </td><td  style="width:15%;text-align:right;">&nbsp;<?php echo number_format($detailed_grand_qty,2);?></td>
</tr>
<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>
    <td style="width:70%;"> </td><td style="width:15%;">Total Gross: </td><td  style="width:15%;text-align:right;">&nbsp;<?php echo number_format($detailed_grand_gross,2);?></td>
</tr>
<tr style="font-size:18px;font-weight:bold;float:right;padding-right:10px;"><tr>
    <td style="width:70%;"> </td><td style="width:15%;">Total Profit: </td><td  style="width:15%;text-align:right;">&nbsp;<?php echo number_format($detailed_grand_profit,2);?></td>
</tr>
</table><br><br>

</body>
</html>
