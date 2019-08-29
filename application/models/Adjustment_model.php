<?php

class Adjustment_model extends CORE_Model
{
protected $table = "adjustment_info";
protected $pk_id = "adjustment_id";

function __construct()
{
parent::__construct();
}
// OUT ADJUSTMENT
 function get_journal_entries_2($adjustment_id){
        $sql="SELECT 
        main.* 
        FROM(

        SELECT
		(SELECT pi.adj_debit_id FROM purchasing_integration pi) as account_id,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as dr_amount,
		0 as cr_amount,
		'' as memo

		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.expense_account_id > 0
		GROUP BY adj.adjustment_id

		UNION ALL

		SELECT 
		p.expense_account_id as account_id,
		0 as dr_amount,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as cr_amount,
		'' as memo

		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.expense_account_id > 0
		GROUP BY p.expense_account_id) as main 
		WHERE main.dr_amount > 0 OR main.cr_amount > 0";
        return $this->db->query($sql)->result();

    }

 function list_per_customer($customer_id = null){
        $sql="SELECT 
			si.sales_inv_no as inv_no,
			p.product_code,
			p.product_desc,
			sii.inv_qty,
			u.unit_name,
			p.is_bulk,
			p.child_unit_id,
			p.parent_unit_id,
			p.child_unit_desc,
			p.sale_price,
			IF(si.is_journal_posted = TRUE, 'Note: Invoice is posted in Accounting', 'Note: Invoice is not yet posted in Accounting') as note,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.parent_unit_id) as parent_unit_name,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.child_unit_id) as child_unit_name,
			sii.* FROM sales_invoice_items sii

			LEFT JOIN sales_invoice si ON si.sales_invoice_id = sii.sales_invoice_id
			LEFT JOIN products p ON p.product_id = sii.product_id
			LEFT JOIN units u ON u.unit_id = sii.unit_id
			WHERE si.is_active = TRUE 
			AND si.is_deleted = FALSE
			AND si.customer_id= '$customer_id'

			UNION ALL

			SELECT
			ci.cash_inv_no as inv_no,
			p.product_code,
			p.product_desc,
			cii.inv_qty,
			u.unit_name,
			p.is_bulk,
			p.child_unit_id,
			p.parent_unit_id,
			p.child_unit_desc,
			p.sale_price,
			IF(ci.is_journal_posted = TRUE, 'Invoice is posted in Accounting', 'Invoice is not yet posted in Accounting') as note,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.parent_unit_id) as parent_unit_name,
			(SELECT units.unit_name  FROM units WHERE  units.unit_id = p.child_unit_id) as child_unit_name,
			cii.* FROM cash_invoice_items cii
			LEFT JOIN cash_invoice ci ON ci.cash_invoice_id = cii.cash_invoice_id
			LEFT JOIN products p ON p.product_id = cii.product_id
			LEFT JOIN units u ON u.unit_id = cii.unit_id
			WHERE ci.is_active = TRUE 
			AND ci.is_deleted = FALSE
			AND ci.customer_id= '$customer_id'
			";

        return $this->db->query($sql)->result();

    }


// IN ADJUSTMENT
     function get_journal_entries_2_in($adjustment_id){
        $sql="SELECT 
        main.* 
        FROM(
		SELECT
		p.expense_account_id as account_id,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as dr_amount,
		0 as cr_amount,
		
		'' as memo
		FROM adjustment_items adj
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id= $adjustment_id AND p.expense_account_id > 0
		GROUP BY p.expense_account_id



		UNION ALL

        SELECT
		(SELECT pi.adj_credit_id FROM purchasing_integration pi) as account_id,
		0 as dr_amount,
		SUM(IFNULL(adj.adjust_non_tax_amount,0)) as cr_amount,
		
		'' as memo

		FROM 
		adjustment_items adj 
		INNER JOIN products p ON p.product_id = adj.product_id
		WHERE adj.adjustment_id = $adjustment_id AND p.expense_account_id > 0
		GROUP BY adj.adjustment_id

		) as main 
		WHERE main.dr_amount > 0 OR main.cr_amount > 0";

        return $this->db->query($sql)->result();



    }


     function get_adjustments_for_review(){
        $sql='SELECT main.*

			FROM(SELECT 
			ai.adjustment_id,
			ai.adjustment_code,
			ai.remarks,
			ai.adjustment_type,
			ai.date_created,
			DATE_FORMAT(ai.date_adjusted,"%m/%d/%Y") as date_adjusted,
			d.department_id,
			d.department_name

			FROM adjustment_info ai

			LEFT JOIN departments d ON d.department_id = ai.department_id

			LEFT JOIN (SELECT 
			aii.adjustment_id, SUM(IFNULL(p.expense_account_id,0)) as identifier
			FROM adjustment_items aii 
			LEFT JOIN products p ON p.product_id = aii.product_id
			GROUP BY aii.adjustment_id) as aii ON aii.adjustment_id = ai.adjustment_id


			WHERE
			ai.is_active=TRUE AND
			ai.is_deleted=FALSE AND 
			is_journal_posted=FALSE
			AND ai.adjustment_type = "IN"
			AND aii.identifier > 0

			UNION ALL


			SELECT 

			ai.adjustment_id,
			ai.adjustment_code,
			ai.remarks,
			ai.adjustment_type,
			ai.date_created,
			DATE_FORMAT(ai.date_adjusted,"%m/%d/%Y") as date_adjusted,
			d.department_id,
			d.department_name

			FROM adjustment_info ai

			LEFT JOIN departments d ON d.department_id = ai.department_id

			LEFT JOIN (SELECT 
			aii.adjustment_id, SUM(IFNULL(p.expense_account_id,0)) as identifier
			FROM adjustment_items aii 
			LEFT JOIN products p ON p.product_id = aii.product_id
			GROUP BY aii.adjustment_id) as aii ON aii.adjustment_id = ai.adjustment_id

			WHERE
			ai.is_active=TRUE AND
			ai.is_deleted=FALSE AND 
			is_journal_posted=FALSE
			AND ai.adjustment_type = "OUT"
			AND aii.identifier > 0) as main

			ORDER BY main.adjustment_id';
        return $this->db->query($sql)->result();



    }

 function spoilage_by_product_summary($start_Date,$end_Date,$tid){
        $sql="SELECT main.*,
		p.product_code, 
		p.product_desc
		FROM
		(SELECT 
		aii.product_id,
		SUM(aii.adjust_qty) as adjust_qty,
		SUM(aii.adjust_line_total_price) as adjust_line_total_price

		FROM adjustment_items aii 
		LEFT JOIN adjustment_info ai ON aii.adjustment_id = ai.adjustment_id
		".($tid==1?"
			WHERE ai.is_active = TRUE AND ai.is_deleted = FALSE  AND ai.is_spoilage = TRUE AND ai.adjustment_type = 'OUT'  ":"
			WHERE ai.is_active = TRUE AND ai.is_deleted = FALSE  AND ai.is_spoilage = TRUE AND ai.adjustment_type = 'IN' AND ai.is_returns = TRUE")."

		 AND ai.date_adjusted BETWEEN '$start_Date' AND '$end_Date'
		GROUP BY aii.product_id) as main
		LEFT JOIN products p ON p.product_id= main.product_id";
        return $this->db->query($sql)->result();

    }

 function spoilage_by_product_detailed($start_Date,$end_Date,$tid,$distinct_code=0){
        $sql="
        ".($distinct_code==1?" SELECT DISTINCT main.adjustment_code,main.customer_name,main.date_adjusted,main.adjustment_id FROM (":"")."
        SELECT 
			ai.adjustment_code,
			ai.adjustment_id,
			ai.date_adjusted,
			IFNULL(c.customer_name,'') as customer_name,
			aii.product_id,
			p.product_code, 
			p.product_desc,
			aii.adjust_qty,
			aii.adjust_price,
			aii.adjust_line_total_price


			FROM adjustment_items aii 
			LEFT JOIN adjustment_info ai ON aii.adjustment_id = ai.adjustment_id
			LEFT JOIN products p ON p.product_id= aii.product_id
			LEFT JOIN customers c on c.customer_id = ai.customer_id
		".($tid==1?"
			WHERE ai.is_active = TRUE AND ai.is_deleted = FALSE  AND ai.is_spoilage = TRUE AND ai.adjustment_type = 'OUT'  ":"
			WHERE ai.is_active = TRUE AND ai.is_deleted = FALSE  AND ai.is_spoilage = TRUE AND ai.adjustment_type = 'IN' AND ai.is_returns = TRUE")."

		 AND ai.date_adjusted BETWEEN '$start_Date' AND '$end_Date'

			ORDER BY ai.adjustment_id DESC

			".($distinct_code==1?") as main":"")."";
        return $this->db->query($sql)->result();

    }
}


?>