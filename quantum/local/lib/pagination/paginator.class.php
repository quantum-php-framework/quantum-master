<?php

class Paginator
{
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

    var $system_url;

    function Paginator()
    {
        //$this->current_page = 1;
        $this->mid_range = 7;

    }

    function paginate($passed_page, $passed_ipp)
    {
        if ($passed_ipp == 'All')
        {
            $this->num_pages = ceil($this->items_total / $this->default_ipp);
            $this->items_per_page = $this->default_ipp;
        }
        else
        {
            if (!is_numeric($this->items_per_page) OR $this->items_per_page <= 0)
                $this->items_per_page = $this->default_ipp;

            $this->num_pages = ceil($this->items_total / $this->items_per_page);
        }

        $this->current_page = (int)$passed_page; // must be numeric > 0

        if ($this->current_page < 1 OR !is_numeric($this->current_page))
            $this->current_page = 1;

        if ($this->current_page > $this->num_pages)
            $this->current_page = $this->num_pages;

        $prev_page = $this->current_page - 1;

        $next_page = $this->current_page + 1;


        if ($this->num_pages > 10)
        {
            $this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$this->system_url/$prev_page/$this->items_per_page\">&laquo; Previous</a> " : "<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";

            $this->start_range = $this->current_page - floor($this->mid_range / 2);

            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0)
            {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }

            if ($this->end_range > $this->num_pages)
            {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }

            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++)
            {
                if ($this->range[0] > 2 And $i == $this->range[0])
                    $this->return .= " ... ";

                // loop through all pages. if first, last, or in range, display
                if ($i == 1 || $i == $this->num_pages || in_array($i, $this->range))
                {
                    $this->return .= ($i == $this->current_page And $passed_page != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> " : "<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$this->system_url/$i/$this->items_per_page\">$i</a> ";
                }

                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 And $i == $this->range[$this->mid_range - 1])
                    $this->return .= " ... ";
            }

            $this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($passed_page != 'All')) ? "<a class=\"paginate\" href=\"$this->system_url/$next_page/$this->items_per_page\">Next &raquo;</a>\n" : "<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";

            $this->return .= ($passed_page == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n" : "<a class=\"paginate\" style=\"margin-left:10px\" href=\"$this->system_url/1/All\">All</a> \n";
        }
        else
        {
            $this->return .= " <ul class=\"pagination\">";

            for ($i = 1; $i <= $this->num_pages; $i++)
            {

                $this->return .= ($i == $this->current_page) ? "<li><a class=\"current\" href=\"#\">$i</a></li> " : "<li><a class=\"paginate\" href=\"$this->system_url/$i/$this->items_per_page\">$i</a></li>";
            }

            $this->return .= "<li><a class=\"paginate\" href=\"$this->system_url/1/All\">All</a></li> \n";

            $this->return .= "</ul>";
        }

        $this->low = ($this->current_page - 1) * $this->items_per_page;

        $this->high = ($passed_ipp == 'All') ? $this->items_total : ($this->current_page * $this->items_per_page) - 1;

        $this->limit = ($passed_ipp == 'All') ? "" : " LIMIT $this->low,$this->items_per_page";
    }

    function display_items_per_page()
    {
        $items = '';
        $ipp_array = array(10, 25, 50, 100, 'All');
        foreach ($ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n" : "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$this->system_url/1/'+this[this.selectedIndex].value+'';return false\">$items</select>\n";
    }

    function display_jump_menu()
    {
        for ($i = 1; $i <= $this->num_pages; $i++) {
            $option .= ($i == $this->current_page) ? "<option value=\"$i\" selected>$i</option>\n" : "<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$this->system_url/'+this[this.selectedIndex].value+'/$this->items_per_page';return false\">$option</select>\n";
    }

    function display_pages()
    {
        return $this->return;
    }
}