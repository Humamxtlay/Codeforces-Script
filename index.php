<?php 
// include simple dom
include('simple_html_dom.php');
// set timeout as unlimit
set_time_limit(0);

// this function to calculate the results and print the data
function GetAndPrintData($handle){
    // this array to store this problem how many times is stored and calculated
    $occorence = array();

    // get the first page of the submissions
    $html = file_get_html('https://codeforces.com/submissions/' . $handle . '/page/1');

    // get the number of pages to get the data from
    $NumOfPages = $html->find('.pagination li+li+li+li+li+li span a')[0]->innertext;

    // check all pages
    for($i=1;$i<=$NumOfPages;$i++){

        // get html page
        $html = file_get_html('https://codeforces.com/submissions/' . $handle . '/page/' . $i);

        // pass all table rows 
        foreach($html->find('tr') as $el){
        
            // check if this table row from the submissions
            if($el->getAttribute('data-submission-id') != "" && sizeof($el->find('td+td+td+td+td span span')) > 0){

                // check if it's an accepted submission
                if($el->find('td+td+td+td+td span span')[0]->innertext == "Accepted"){
                
                    // get problem name
                    $name = $el->find('td+td+td+td a')[0]->innertext;
                    
                    // check if this problem was calculated before
                    if(isset($occorence[$name]) != true){

                        // get link of the statement and solution
                        $link = $el->find('td+td+td+td a')[0]->getAttribute('href');
                        $solution = $el->find('td a')[0]->getAttribute('href');
                        // call a method to store the problem tags
                        $tags = GetTagsByUrl($link);
                        
                        // print the result
                        
                        echo $name
                            .' <a href="https://codeforces.com' . $link . '">Statement</a>';

                        // check if it's valied solution
                        if(strpos($solution, 'submission') != false)
                            echo ' <a href="https://codeforces.com' . $solution . '">Solution</a>';
             
                        //check if there are tags for this problem
                        if(sizeof($tags) > 0)
                            echo ' Tages: ';

                        $j = 0;
                        // get array length
                        $len = count($tags);

                        foreach($tags as $tag){
                            echo $tag;

                            // if it's last elemet don't print comma
                            if ($j != $len - 1)
                                echo ', ';

                            $j++;
                        }

                        echo '</br>';

                        // mark this problem as calculated
                        $occorence[$name] = 1;
                    }
                }
            }
        }
    }
}

// this function to get the tags of specific problem
function GetTagsByUrl($url){
    // the array to store tags
    $ret = array();

    $html = file_get_html('https://codeforces.com' . $url);

    // pass all the tags
    foreach($html->find('.roundbox .roundbox') as $el){
        
        $tag = $el->find('span')[0]->innertext;
        
        // check if it's valied tag
        if($tag != '') 
            array_push($ret,$tag);
    }

    return $ret;
}

// call the function for my Handle on codeforces
GetAndPrintData('H.U.M.A.M');

?>