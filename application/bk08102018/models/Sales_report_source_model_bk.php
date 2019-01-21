<?php

class Sales_report_source_model extends CORE_Model
{
    protected $table = "sales_invoice";
    protected $pk_id = "sales_invoice_id";

    function __construct()
    {
        parent::__construct();
    }
    function get_sales_source($get_source=null,$get_totals=null,$order_source_id=null,$start,$end,$all=0){
        $sql="
		".($get_source==TRUE?"SELECT distinct main.order_source_id,main.order_source_name FROM(":"")."
		".($get_totals==TRUE?"SELECT n.order_source_id,SUM(n.inv_line_total_price) as sub_total FROM(":"")."

        SELECT * FROM (

        SELECT
        '1' as source_invoice,
		os.order_source_id,
		os.order_source_name,
		si.sales_inv_no as inv_no,
        si.date_invoice as date,
        c.customer_name,
		p.product_desc,
		sii.inv_qty,
        sii.inv_price,
        sii.inv_gross,
        sii.inv_discount,
        sii.inv_line_total_price

		FROM sales_invoice_items sii
		LEFT JOIN sales_invoice si ON si.sales_invoice_id = sii.sales_invoice_id 
		LEFT JOIN products p ON p.product_id = sii.product_id 
		LEFT JOIN customers c ON c.customer_id = si.customer_id
		LEFT JOIN order_source os ON os.order_source_id = si.order_source_id
		WHERE si.is_active = TRUE AND si.is_deleted = FALSE
		AND (si.date_invoice BETWEEN '$start' AND '$end')
        ".($order_source_id!=0?" AND si.order_source_id = $order_source_id":"")."

        UNION ALL
        
        SELECT 
        '2' as source_invoice,
        os.order_source_id,
        os.order_source_name,
        ci.cash_inv_no as inv_no,
        ci.date_invoice as date,
        c.customer_name,
        p.product_desc,
		cii.inv_qty,
        cii.inv_price,
        cii.inv_gross,
        cii.inv_discount,
        cii.inv_line_total_price
        
        FROM cash_invoice_items cii
        LEFT JOIN cash_invoice ci ON ci.cash_invoice_id = cii.cash_invoice_id 
        LEFT JOIN products p ON p.product_id = cii.product_id
        LEFT JOIN customers c ON c.customer_id = ci.customer_id
        LEFT JOIN order_source os ON os.order_source_id = ci.order_source_id
        
        WHERE ci.is_active = TRUE AND ci.is_deleted = FALSE
        AND (ci.date_invoice BETWEEN '$start' AND '$end')
		".($order_source_id!=0?" AND ci.order_source_id = $order_source_id":"")."


		) as main 


		".($all==1?" WHERE source_invoice = 1":"")."
		".($all==2?" WHERE source_invoice = 2":"")."
		


		".($get_source==TRUE?") as main":"")."
		".($get_totals==TRUE?") as n GROUP BY n.order_source_id":"")."

		";

        return $this->db->query($sql)->result();
    }

    function get_sales_return($get_source=null,$get_totals=null,$order_source_id=null,$start,$end,$all=0){
        $sql="
        ".($get_source==TRUE?"SELECT distinct main.order_source_id,main.order_source_name FROM(":"")."
        ".($get_totals==TRUE?"SELECT n.order_source_id,SUM(n.adjust_line_total_price) as sub_total FROM (":"")."

            SELECT * FROM 

            (SELECT 
            invoices.source_invoice,
            ai.inv_no,
            ai.date_adjusted,
            ai.adjustment_code,
            c.customer_name,
            p.product_desc,
            aii.adjust_qty,
            aii.adjust_price,
            aii.adjust_line_total_price,
            invoices.date_invoice,
            invoices.order_source_id,
            invoices.order_source_name
            FROM adjustment_items aii
            LEFT JOIN adjustment_info ai On ai.adjustment_id = aii.adjustment_id
            INNER JOIN customers c ON  c.customer_id=ai.customer_id
            INNER JOIN products p ON p.product_id = aii.product_id



            LEFT JOIN

            (SELECT 
            1 as source_invoice,
            si.sales_inv_no as inv_no,
            si.date_invoice,
            si.order_source_id,
            os.order_source_name

            FROM sales_invoice si
            LEFT JOIN order_source os ON os.order_source_id = si.order_source_id
            WHERE si.is_active = TRUE
            AND si.is_deleted = FALSE
            AND (si.date_invoice BETWEEN '$start' AND '$end')
            UNION ALL

            SELECT 
            2 as source_invoice,
            ci.cash_inv_no as inv_no,
            ci.date_invoice,
            ci.order_source_id,
            os.order_source_name
            FROM cash_invoice ci

            LEFT JOIN order_source os ON os.order_source_id = ci.order_source_id
            WHERE ci.is_active = TRUE
            AND ci.is_deleted = FALSE
            AND (ci.date_invoice BETWEEN '$start' AND '$end')
            
            ) as invoices ON invoices.inv_no = ai.inv_no


            WHERE ai.is_active = TRUE
            AND ai.is_deleted = FALSE
            AND ai.adjustment_type = 'IN'
            ".($order_source_id!=0?" AND invoices.order_source_id = $order_source_id":"")."
            ) as main


            ".($all==1?" WHERE source_invoice = 1":"")."
            ".($all==2?" WHERE source_invoice = 2":"")."



        ".($get_source==TRUE?") as main":"")."
        ".($get_totals==TRUE?") as n GROUP BY n.order_source_id":"")."

        ";

        return $this->db->query($sql)->result();
    }
 }

 ?>