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
<?php $summary_grand_qty = 0;
$summary_grand_gross = 0;
$summary_grand_profit = 0; ?>

<table cellspacing="0" cellpadding="5">
	<tr>
		<td><strong>Period: </strong> <?php echo $_GET['start']  ?> - <?php echo $_GET['end']  ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</table>
    <h4>Profit Report by Invoice (Summary)</h4><br>
    <table style="width:100%" class="table table-striped" id="tbl_summary">
    <thead>
    <th>Invoice No</th>
    <th>Customer Name</th>
    <th>Date</th>
    <th class="right-align">QTY Sold</th>
    <th class="right-align">Gross</th>
    <th class="right-align">Net Profit</th>
 
    </thead>
        <tbody>
        <?php foreach ($summary as $value) { ?>
        <tr>
        <td><?php echo $value->inv_no ?></td>
        <td><?php echo $value->customer_name ?></td>
        <td><?php echo $value->date_invoice ?></td>
        <td class="right-align"><?php echo $value->qty_total ?></td>
        <td class="right-align"><?php echo number_format($value->gross_total,2) ?></td>
        <td class="right-align"><?php echo number_format($value->profit_total,2) ?></td>
        </tr>
        <?php 
            $summary_grand_qty+=$value->qty_total;
            $summary_grand_gross+=$value->gross_total;
            $summary_grand_profit+=$value->profit_total;
        } ?>

        <tr>
        <td><strong>Total : </strong></td>
        <td></td>
        <td></td>
        <td class="right-align"><strong><?php echo number_format($summary_grand_qty,2); ?></strong></td>
        <td class="right-align"><strong><?php echo number_format($summary_grand_gross,2); ?></strong></td>
        <td class="right-align"><strong><?php echo number_format($summary_grand_profit,2); ?></strong></td>
        </tr>
        </tbody>
    </table>

</body>
</html>
