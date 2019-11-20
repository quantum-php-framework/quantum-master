<?php

class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $default_ipp = 10;
	var $querystring;
	
	var $c_next_page;
	var $c_prev_page;
	
	var $system_url;

	function __construct()
	{
		//$this->current_page = 1;
		$this->mid_range = 7;
		
	}

	function paginate($passed_page, $passed_ipp)
	{
		if($passed_ipp== 'All')
		{
			$this->num_pages = ceil($this->items_total/$this->default_ipp);

			$this->items_per_page = $this->default_ipp;
		}
		else
		{
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0)
			    $this->items_per_page = $this->default_ipp;

			$this->num_pages = ceil($this->items_total/$this->items_per_page);
		}

		$this->current_page = (int) $passed_page; // must be numeric > 0

        if($this->current_page < 1 Or !is_numeric($this->current_page))
		    $this->current_page = 1;

		if($this->current_page > $this->num_pages)
		    $this->current_page = $this->num_pages;

		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

		$this->c_next_page = $next_page;
		$this->c_prev_page = $prev_page;
		

		if($this->num_pages > 10)
		{
            $this->return .= " <ul class=\"pagination\">";

			$this->return .= ($this->current_page != 1 And $this->items_total >= 10) ? "<li><a class=\"paginate\" href=\"$this->system_url?page=$prev_page&ipp=$this->items_per_page\">&laquo; Previous</a></li> ":"<li><span class=\"inactive\" href=\"#\">&laquo; Previous</span></li> ";

			$this->start_range = $this->current_page - floor($this->mid_range/2);

			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}

			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0])
				    $this->return .= " ... ";

				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					$this->return .= ($i == $this->current_page And $passed_page != 'All') ? "<li><a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a></li> ":"<li><a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$this->system_url?page=$i&ipp=$this->items_per_page\">$i</a></li> ";
				}

				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1])
				    $this->return .= " ... ";
			}

			$this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($passed_page != 'All')) ? "<li><a class=\"paginate\" href=\"$this->system_url?page=$next_page&ipp=$this->items_per_page\">Next &raquo;</a></li>\n":"<li><span class=\"inactive\" href=\"#\">&raquo; Next</span></li>\n";

			$this->return .= ($passed_page == 'All') ? "<li class='active'><a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a></li> \n":"<li><a class=\"paginate\" style=\"margin-left:10px\" href=\"$this->system_url?page=1&ipp=All\">All</a></li> \n";

            $this->return .= "</ul>";
		}
		else
		{
            $this->return .= "<ul class=\"pagination\">";

			for($i=1;$i<=$this->num_pages;$i++)
			{
				$this->return .= ($i == $this->current_page) ? "<li class=\"active\"><a class=\"current\" href=\"#\">$i</a></li> ":"<li><a class=\"paginate\" href=\"$this->system_url?page=$i&ipp=$this->items_per_page\">$i</a></li>";
			}

			$this->return .= "<li><a class=\"paginate\" href=\"$this->system_url?page=1&ipp=All\">All</a></li> \n";

            $this->return .= "</ul>";
		}

		$this->low = ($this->current_page-1) * $this->items_per_page;

		$this->high = ($passed_ipp == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;

		$this->limit = ($passed_ipp == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
	}

	function display_items_per_page()
	{
		$items = '';
		$ipp_array = array(10,25,50,100,'All');
		foreach($ipp_array as $ipp_opt)	$items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<span class=\"paginate\">Items per page: </span><select class=\"paginate\" onchange=\"window.location='$this->system_url?page=1&ipp='+this[this.selectedIndex].value+'';return false\">$items</select>\n";
	}

	function display_jump_menu()
	{
		for($i=1;$i<=$this->num_pages;$i++)
		{
			if (!isset($option)) {
				$option = '';
			}
			
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
		}
		if (isset($option))
		{
		    return "<div class=\"col-sm-1\"><span class=\"paginate\">Page: </span><select class=\"form-control\" onchange=\"window.location='$this->system_url?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page';return false\">$option</select></div>\n";
        }
	}

	function display_pages()
	{
		return $this->return;
	}
	

}