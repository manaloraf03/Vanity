<?php

class Profit_model extends CORE_Model
{

    function __construct()
    {
        parent::__construct();
    }
    function get_profit_by_product($start,$end){
        $sql="SELECT

                main.product_id,
                p.product_code,
                p.product_desc,
                u.unit_name,
                SUM(main.inv_qty) as qty_sold,
                p.sale_price as srp,
                SUM(main.inv_gross) as gross,
                p.purchase_cost,
                (SUM(main.inv_qty)  * IFNULL(p.purchase_cost,0)) as net_cost,
                (SUM(main.inv_gross) - (SUM(main.inv_qty)  * IFNULL(p.purchase_cost,0))) as net_profit

                FROM(
                SELECT 
                sii.product_id,
                SUM(sii.inv_qty) as inv_qty,
                SUM(sii.inv_gross) as inv_gross

                FROM 
                sales_invoice_items sii
                LEFT JOIN sales_invoice si ON si.sales_invoice_id = sii.sales_invoice_id
                WHERE (si.date_invoice BETWEEN '$start' AND '$end') AND si.is_active = TRUE AND si.is_deleted = FALSE
                GROUP BY sii.product_id

                UNION ALL

                SELECT 
                cii.product_id,
                SUM(cii.inv_qty) as inv_qty,
                SUM(cii.inv_gross) as inv_gross

                FROM 
                cash_invoice_items cii
                LEFT JOIN cash_invoice ci ON ci.cash_invoice_id = cii.cash_invoice_id
                WHERE (ci.date_invoice BETWEEN '$start' AND '$end') AND ci.is_active = TRUE AND ci.is_deleted = FALSE
                GROUP BY cii.product_id) as main

                LEFT JOIN products p ON p.product_id = main.product_id
                LEFT JOIN units u ON u.unit_id = p.parent_unit_id
                GROUP BY 
                main.product_id

                ORDER BY p.product_desc ASC
	

		";

        return $this->db->query($sql)->result();
    }

    function get_profit_by_invoice_detailed($start,$end,$distinct=false,$subtotal=false){


        $sql="
             ".($distinct==TRUE?" SELECT DISTINCT n.identifier,n.invoice_id,n.inv_no FROM (":" ")."
             ".($subtotal==TRUE?" SELECT n.identifier,n.invoice_id,n.inv_no,c.customer_name,n.date_invoice,SUM(n.inv_qty) as qty_total,SUM(n.inv_gross) as gross_total,SUM(n.net_profit) as profit_total FROM( ":" ")."

        SELECT
            main.identifier,
            main.invoice_id,
            main.inv_no,
            main.customer_id,
            main.date_invoice,
            p.product_code,
            p.product_desc,
            u.unit_name,
            main.inv_qty,
            main.inv_gross,
            p.sale_price as srp,
            p.purchase_cost,
            (main.inv_qty*p.purchase_cost) as net_cost,
            (main.inv_gross - (main.inv_qty*p.purchase_cost)) as net_profit
             FROM
             
            (SELECT 
            '1' as identifier,
            si.sales_invoice_id as invoice_id,
            si.sales_inv_no as inv_no,
            si.customer_id,
            si.date_invoice,
            sii.product_id,
            sii.unit_id,
            sii.inv_qty,
            sii.inv_gross

            FROM sales_invoice_items sii
            LEFT JOIN sales_invoice si ON si.sales_invoice_id = sii.sales_invoice_id
            WHERE (si.date_invoice BETWEEN '$start' AND '$end') AND si.is_active = TRUE AND si.is_deleted = FALSE
            


            UNION ALL

            SELECT 
            '2' as identifier,
            ci.cash_invoice_id as invoice_id,
            ci.cash_inv_no as inv_no,
            ci.customer_id,
            ci.date_invoice,
            cii.product_id,
            cii.unit_id,
            cii.inv_qty,
            cii.inv_gross

            FROM cash_invoice_items cii
            LEFT JOIN cash_invoice ci ON ci.cash_invoice_id = cii.cash_invoice_id
            WHERE (ci.date_invoice BETWEEN '$start' AND '$end') AND ci.is_active = TRUE AND ci.is_deleted = FALSE) as main

            LEFT JOIN products p ON p.product_id = main.product_id
            LEFT JOIN units u ON u.unit_id = main.unit_id

            ORDER BY main.identifier ASC, main.invoice_id ASC

             ".($distinct==TRUE?") as n ORDER BY n.identifier ASC, n.invoice_id ASC":" ")."
             ".($subtotal==TRUE?" ) as n

            LEFT JOIN customers c ON c.customer_id = n.customer_id
            GROUP BY n.invoice_id,n.identifier 
            ORDER BY n.identifier ASC, n.invoice_id ASC
            ":" ")."


             ";

        return $this->db->query($sql)->result();
    }

 }

 ?>