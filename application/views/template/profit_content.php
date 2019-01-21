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
                    <h4>Profit Report by Products</h4>
                    <table style="width:100%" class="table table-striped" id="tbl_products">
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
                        <tbody>
                        <?php $p_qty = 0;$p_gross= 0;$p_net=0; ?>
                        <?php foreach ($items as $value) { ?>
                        <tr>
	                        <td><?php echo $value->product_code;?></td>
	                        <td><?php echo $value->product_desc;?></td>
	                        <td><?php echo $value->unit_name;?></td>
	                        <td class="right-align"><?php echo number_format($value->qty_sold,2);?></td>
	                        <td class="right-align"><?php echo number_format($value->srp,2);?></td>
	                        <td class="right-align"><?php echo number_format($value->gross,2);?></td>
	                        <td class="right-align"><?php echo number_format($value->purchase_cost,2);?></td>
	                        <td class="right-align"><?php echo number_format($value->net_profit,2);?></td>
                        </tr>
                        <?php 
							$p_qty+=$value->qty_sold;
							$p_gross+=$value->gross;
							$p_net+=$value->net_profit;
                        } ?>
                        <tr>
                        <td><strong>TOTAL</strong></td>
                        <td></td>
                        <td></td>
                        <td class="right-align"><strong><?php echo number_format($p_qty,2);?></strong></td>
                        <td class="right-align"></td>
                        <td class="right-align"><strong><?php echo number_format($p_gross,2);?></strong></td>
                        <td class="right-align"></td>
                        <td class="right-align"><strong><?php echo number_format($p_net,2);?></strong></td>
                        </tr>
                        </tbody>
                        </table>










</body>
</html>
