     <head>
         <title>Sales Order </title>
     </head>

     <body>
         <style type="text/css">
             body {
                 font-family: 'Calibri', sans-serif;
                 font-size: 12px;
             }

             .align-right {
                 text-align: right;
             }

             .align-left {
                 text-align: left;
             }

             .align-center {
                 text-align: center;
             }

             .report-header {
                 font-weight: bolder;
             }

             table {
                 border: none !important;
             }

             table-td.left {
                 border-left: 1px solid gray !important;
             }

             table-td.right {
                 border-left: 1px solid gray !important;
             }
         </style>
         <div>
             <table width="100%">
                 <tr>
                     <td width="10%"><img src="<?php echo $company_info->logo_path; ?>" style="height: 90px; width: 120px; text-align: left;"></td>
                     <td width="90%" class=''>
                         <h1 class="report-header"><strong><?php echo $company_info->company_name; ?></strong></h1>
                         <p><?php echo $company_info->company_address; ?></p>
                         <p><?php echo $company_info->landline . '/' . $company_info->mobile_no; ?></p>
                         <span><?php echo $company_info->email_address; ?></span>
                     </td>
                 </tr>
             </table>
             <hr>

             <center>
                 <table width="95%" cellpadding="5" style="font-family: tahoma;font-size: 11;">
                     <tr class="row_child_tbl_so_list">
                         <td width="45%" valign="top" style="border: 0px !important;"><br />
                             <span>Department :</span><br />
                             <address>
                                 <strong><?php echo $sales_order->department_name; ?></strong><br /><br />

                             </address>
                             <p>
                                 <span>Order date : <br /> <b><?php echo  date_format(new DateTime($sales_order->date_order), "m/d/Y"); ?></b></span><br /><br />
                             </p>
                             <br />
                             <span>Customer :</span><br />
                             <strong><?php echo $sales_order->customer_name; ?></strong><br>
                         </td>

                         <td width="50%" align="right" style="border: 0px !important;">
                             <p>Sales Order No.</p><br />
                             <h4 class="text-navy"><?php echo $sales_order->so_no; ?></h4><br />
                         </td>
                     </tr>
                 </table>
             </center>

             <br /><br />

             <center>
                 <table width="95%" style="border-collapse: collapse;border-spacing: 0;font-family: tahoma;font-size: 11">
                     <thead>
                         <tr>
                             <th width="75%" style="border-bottom: 2px solid gray;text-align: left;height: 30px;padding: 6px;">Item</th>
                             <th width="25%" style="border-bottom: 2px solid gray;text-align: right;height: 30px;padding: 6px;">Total Weight</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php
                            $total_weight = 0;
                            foreach ($sales_order_items as $item) { ?>
                             <tr>
                                 <td style="border-bottom: 1px solid gray;text-align: left;height: 30px;padding: 6px;"><?php echo $item->product_desc; ?></td>
                                 <td style="border-bottom: 1px solid gray;text-align: right;height: 30px;padding: 6px;"><?php echo number_format($item->weight * $item->so_qty, 2); ?></td>
                             </tr>
                         <?php $total_weight += $item->weight * $item->so_qty;
                            } ?>
                            <tr>
                                <td align="right" style="height: 30px;padding: 6px;">Total : </td>
                                <td align="right" style="height: 30px;padding: 6px;">
                                    <strong><?php echo number_format($total_weight,2); ?></strong>
                                </td>
                            </tr>
                     </tbody>
                 </table><br /><br />
             </center>
         </div>