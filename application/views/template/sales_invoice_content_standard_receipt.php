<head>
    <title>Sales Invoice</title>
    </head>
    <body><style type="text/css" media="print">
  @page { size: portrait; }
</style>

<style>

    body {
            font-family: 'Calibri',sans-serif;
            font-size: 12px;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .data {
      /*      border-bottom: 1px solid #404040;*/
        }

        .align-center {
            text-align: center;
        }

        .report-header {
            font-weight: bolder;
        }

        hr {
     /*       border-top: 3px solid #404040;*/
        }

        #header-table tr td {
            text-transform: uppercase;
            font-size: 10px;
        }
        .left {border-left: 1px solid black;}
        .right{border-right: 1px solid black;}
        .bottom{border-bottom: 1px solid black;}
        .top{border-top: 1px solid black;}

        .twentyfive{ width: 25%; }
        .fifteen{ width: 15%; }
        .text-center{text-align: center;}
        .text-right{text-align: right;}
        @page {
          margin-top: 0.5cm;
        }
</style>

<div>
    <table width="100%" cellspacing="5" cellspacing="0">
        <tr>
            <td width="100%"  class="" >
            <center>
                <span style="text-transform: uppercase;font-weight: bold;font-size: 10px;"><?php echo $company_info->company_name; ?></span><br>
                <span style="text-transform: uppercase;font-weight: bold;font-size: 10px;"><?php echo $company_info->company_address; ?></span><br>
            </center>
            </td>
        </tr>
    </table>
    <br><br>
    <table cellspacing="0" cellpadding="5" width="100%" id="header-table">
    <tr>
        <td class="fifteen">INVOICE NO:</td>
        <td class="bottom twentyfive"><strong><?php echo $sales_info->sales_inv_no; ?></strong></td>
        <td></td>
        <td class="fifteen">Date:</td>
        <td class="bottom"><strong><?php echo  date_format(new DateTime($sales_info->date_invoice),"m/d/Y"); ?></strong></td>
    </tr>
    <tr>
        <td class=" fifteen">CUSTOMER:</td>
        <td class="bottom twentyfive"><strong><?php echo $sales_info->customer_name; ?></strong></td>
        <td></td>
        <td class="fifteen">SALES TYPE:</td>
        <td class="bottom "><strong>CHARGE</strong></td>
    </tr>
    <tr>
        <td class="fifteen">TYPE:</td>
        <td class="bottom twentyfive"><strong><?php echo $sales_info->order_source_name; ?></strong></td>
        <td></td>
        <td class="fifteen">CLERK:</td>
        <td class="fifteen bottom"><strong><?php echo $sales_info->salesperson_name; ?></strong></td>
    </tr>
    <tr>
        <td class="fifteen">Remarks:</td>
        <td colspan="4" class="bottom"><strong><?php echo $sales_info->remarks; ?></strong></td>

    </tr>
    </table>
    <br>
    <center>
        <table width="100%" style="border-collapse: collapse;border-spacing: 0;font-family: tahoma;font-size: 11">
            <thead style="margin-bottom: 10px;">
            <tr >
                <th width="10%" class="bottom top" style="text-align: left;height: 30px;padding: 6px;">CODE</th>
                <th width="40%" class="bottom top" style="text-align: left;height: 30px;padding: 6px;">DESCRIPTION</th>
                <th width="12%" class="bottom top" style="text-align: center;height: 30px;padding: 6px;">QTY</th>
                <th width="12%" class="bottom top" style="text-align: right;height: 30px;padding: 6px;">S.PRICE</th>
                <th width="20%" class="bottom top" style="text-align: right;height: 30px;padding: 6px;">TOTAL</th>
            </tr>
            </thead>
            <tbody>
            <?php 
            $total_total = 0;
            $total_discount = 0;
            $total_after_global = 0;
            foreach($sales_invoice_items as $item){ ?>
                <tr>
                    <td width="10%" class="" style="text-align: left;height: 15px;padding: 3px;"><?php echo $item->product_code; ?></td>
                    <td width="50%" class="" style="text-align: left;height: 15px;padding: 3px;"><?php echo $item->product_desc; ?></td>
                    <td width="12%" class="" style="text-align: center;height: 15px;padding: 3px;"><?php echo number_format($item->inv_qty,0); ?></td>
                    <td width="12%" class="" style="text-align: right;height: 15px;padding: 3px;"><?php echo number_format($item->inv_price,2); ?></td>
                    <td width="20%" class="" style="text-align: right;height: 15px;padding: 3px;"><?php echo number_format($item->inv_gross,2); ?></td>
                </tr>
            <?php

            $total_total += $item->inv_gross;
            $total_discount += $item->inv_line_total_discount;
            $total_after_global += $item->inv_line_total_after_global;


             } ?>
<!--             <tr>
            <td colspan="6" style="text-align: left;font-weight: bolder; ;height: 30px;padding: 6px;border-right: 1px solid gray!important;">Remarks:</td>
            </tr>
            <tr>
            <td colspan="6" style="text-align: left;font-weight: bolder; ;height: 30px;padding: 6px;border-right: 1px solid gray!important;"><?php echo $sales_info->remarks; ?></td>
            </tr> -->
            </tbody>
            <tfoot>
            <tr><td colspan="5" class="" >&nbsp;</td></tr>
            <tr>
                <td colspan="2" class="" ></td>
                <td colspan="2" class="" style="text-align: right;height: 10px;padding: 2px;">SUB TOTAL: </td>
                <td class="bottom" style="text-align: right;height: 10px;padding: 2px;"><strong><?php echo number_format($total_total,2); ?></strong></td>
            </tr>
            <tr>
                <td colspan="2" class="" ></td>
                <td colspan="2" class="" style="text-align: right;height: 10px;padding: 2px;">DISCOUNT: </td>
                <td class="top bottom" style="text-align: right;height: 10px;padding: 2px;"><strong>0.00</strong></td>
            </tr>total_total
            <tr>
                <td colspan="2" class="" ></td>
                <td colspan="2" class="" style="text-align: right;height: 10px;padding: 2px;">TOTAL:</td>
                <td colspan="" class="bottom" style="text-align: right;height: 10px;padding: 2px;"><strong><?php echo number_format($total_total,2); ?></strong></td>
            </tr>
            </tfoot>
        </table><br /><br />

        <table style="width: 100%">
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 25%;">___________________________________</td>
                <td style="width: 20%;"></td>
                <td style="width: 25%;">___________________________________</td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td style="width: 25%;text-align: center;"><strong style="font-size: 10px;">CUSTOMER'S SIGNATURE</strong></td>
                <td style="width: 20%;"></td>
                <td style="width: 25%;text-align: center;"><strong style="font-size: 10px;">SALES CLERK'S SIGNATURE</strong></td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </center>
</div>





















