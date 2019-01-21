<?php
	class Bir_2307_model extends CORE_Model {
	    protected  $table="form_2307";
	    protected  $pk_id="form_2307_id";

	    function __construct() {
	        parent::__construct();
	    }

        function get_2307($startDate,$endDate,$supplier_id) {
        $sql="SELECT 
				((IFNULL(s.tax_output,0) /100)* ji.amount) as tax_deducted,
				IFNULL(s.tax_output,0) as tax_output,
				ji.*
				 FROM journal_info ji
				LEFT JOIN  suppliers s ON s.supplier_id = ji.supplier_id
				WHERE 

				ji.supplier_id = $supplier_id
				AND  ji.is_active = TRUE AND ji.is_deleted = FALSE AND ji.book_type = 'CDJ'
				AND ji.date_txn BETWEEN '$startDate' AND '$endDate'
          ";
            return $this->db->query($sql)->result();
    	}

        function get_2307_files($month,$year,$supplier_id) {
        $sql="
        SELECT 
				((IFNULL(s.tax_output,0) /100)* ji.amount) as tax_deducted,
				IFNULL(s.tax_output,0) as tax_output,
				ji.*
				 FROM journal_info ji
				LEFT JOIN  suppliers s ON s.supplier_id = ji.supplier_id
				WHERE 

				ji.supplier_id = $supplier_id
				AND  ji.is_active = TRUE AND ji.is_deleted = FALSE AND ji.book_type = 'CDJ'
				AND MONTH(ji.date_txn) = '$month' AND YEAR (ji.date_txn) = '$year' 
          ";
            return $this->db->query($sql)->result();
    	}

        function get_2307_validate($month,$year,$supplier_id) {
        $sql="
       SELECT f.* FROM form_2307 f
		WHERE MONTH(f.date)  = $month AND year(f.date) = $year
		AND supplier_id = $supplier_id AND f.is_active = TRUE AND f.is_deleted = FALSE
          ";
            return $this->db->query($sql)->result();
    	}
	}
?>