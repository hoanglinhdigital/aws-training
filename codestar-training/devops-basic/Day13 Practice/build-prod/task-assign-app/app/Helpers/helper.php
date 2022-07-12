<?php 

	/*
	* showErrors()
	* Hiển thị lỗi
	* @param: $errors, $name
	* return: error string
	*/

	function showErrors($errors, $name) {
		if($errors->has($name)) {
			$html  = '<span class="alert alert-danger ml-3 p-1">';
			$html .= $errors->first($name);
			$html .= '</span>';
	    	return $html;
		}
	}

?>