<?php
    /**
     * @param   $string
     * @return  string
     */
    function format_telephone_number($string){
        return filter_var(str_replace('(0)', '', $string), FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param   $value
     * @param   $unused
     * @param   $prefix_for_start_of_sentence
     * @return  string
     */
    function add_prefix(&$value, $unused, $prefix_for_start_of_sentence){
        $value = $prefix_for_start_of_sentence . $value;
    }

    /**
     * @param   $haystack
     * @param   $replace_with
     * @param   $length
     * @return  string
     */
    function replace_rsquote($haystack, $replace_with, $length = 3){
        $pos = strpos($haystack, chr('226'));

        if($pos > -1){
            return substr_replace($haystack, $replace_with, $pos, $length);
        } else {
            return $haystack;
        }
    }

    /**
     * @param   $haystack
     * @param   $replace_with
     * @param   $length
     * @return  string
     */
    function replace_squote($haystack, $replace_with, $length = 1){
        $pos = strpos($haystack, chr('39'));

        if($pos > -1){
            return substr_replace($haystack, $replace_with, $pos, $length);
        } else {
            return $haystack;
        }
    }

    /**
     * @param   $html_format
     * @param   $string
     * @param   $columns
     * @param   $separator
     * @return  string
     */
    function simple_wordwrap($html_format, $string, $columns = 3, $separator = ', '){
        $line_break  = "<br />\n";
        $output      = array();
        $string      = str_replace('/', '<span class="light-grey vertical-align-top">/</span>', $string);
        $sub_strings = explode($separator, $string);
        $rows        = ceil(sizeof($sub_strings) / $columns); // Round up

        for($i = 1; $i <= $columns; $i++){
            $temp        = array_slice($sub_strings, 0, $rows); // 0..$rows
            $sub_strings = array_slice($sub_strings, $rows); // $rows+1..<end>
            array_push($output, sprintf($html_format, implode($line_break, $temp)));
        }

        return implode("\n", $output) . "\n";
    }

    /**
     * @param   $width
     * @param   $prefix_for_wrapped_lines
     * @param   $prefix_for_start_of_sentence
     * @param   $string
     * @param   $do_split_sentences
     * @return  string
     */
    function complex_wordwrap($width, $prefix_for_wrapped_lines, $prefix_for_start_of_sentence, $string, $do_split_sentences = true){
        $line_break  = "<br />\n";
        $output      = array();
        $placeholder = '[break]';

        $string = replace_rsquote($string, "'");

        if($do_split_sentences){
            $sentences = preg_split('/(?<=[.?!])\s+/', $string, -1, PREG_SPLIT_NO_EMPTY); // Create sentence array()
        } else {
            $sentences = array($string);
        }

        foreach($sentences as $sentence){
            array_push($output, wordwrap($sentence, $width, $placeholder));
        }

        array_walk_recursive($output, 'add_prefix', $prefix_for_start_of_sentence);

        $output = implode($line_break, $output);
        $output = replace_squote($output, '&#8217;');
        $output = str_replace($placeholder, $line_break . $prefix_for_wrapped_lines, $output);

        return $output;
    }

    /**
     * @param   $number
     * @param   $precision
     * @return  int
     */
    function round_down($number, $precision = 2){
        $fig = (int) str_pad('1', $precision, '0');
        return (floor($number * $fig) / $fig);
    }

    /**
     * @param   $timestamp
     * @param   $is_unix
     * @param   $add_ending
     * @param   $force_positive
     * @return  string
     */
    function get_relative_time($timestamp, $is_unix = false, $add_ending = false, $force_positive = false){
        $difference = strtotime(date('Y-m-d H:i:0')) - (($is_unix) ? $timestamp : strtotime($timestamp));
        $periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lengths = array(60, 60, 24, 7, 4.35, 12, 10);

        if($difference >= 0){ // This was in the past
            $ending = 'ago';
        } else if($difference < 0){ // This is in the future
            if($force_positive)
                $difference = -$difference;

            $ending = 'to go';
        }

        $j = 0;
        while($difference >= $lengths[$j] && $j < count($lengths)){
            $difference /= $lengths[$j];
            $j++;
        }

        $difference = round_down($difference);

        $is_exact = false;
        if(fmod($difference, 1) == 0){
            $is_exact = true;
        }

        if(!$is_exact){
            $difference = round($difference);
        }

        if($difference != 1){
            $periods[$j] .= 's';
        }

        if(!$is_exact){
            $difference .= '+';
        }

        $text = ($add_ending) ? implode(' ', array($difference, $periods[$j], $ending)) : implode(' ', array($difference, $periods[$j]));

        return $text;
    }

    /**
     * @param   $a
     * @param   $b
     * @return  int
     */
    function date_compare_courses($a, $b){
        $t1 = strtotime($a->date);
        $t2 = strtotime($b->date);

        if($t1 === $t2){
            $t1 = $a->title;
            $t2 = $b->title;

            return strcmp($t1, $t2);
        }

        return ($t2 - $t1);
    }