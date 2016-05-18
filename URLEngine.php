<?php namespace Landscape\URL;

    class URLEngine
    {
        private $template;
        public function __construct($tmp)
        {
            $this->template = $tmp;
        }

        public function parse($url)
        {
            //Split given URL on every '/'
            $split = explode("/", $url);
            //Split template on every '/'
            $template = explode("/", $this->template);
            //Create empty array
            $tmpSplit = [];
            foreach($template as $t)
            {
                //Check if part of template contains '{'
                if(strpos($t, "{") !== false)
                {
                    //Add entrys, with '{' to an array
                    $tmpSplit[] = $t;
                }
            }
            foreach($tmpSplit as $key => $t)
            {
                //Find position of variable parts
                $t = array_search($t, $template);
                //Set the positions in an array
                $tmpSplit[$key] = $t;
            }
            //Create empty array
            $res_raw = [];
            foreach($tmpSplit as $k => $t)
            {
                //Create array with the following pattern: ['template'] => 'url'
                $res_raw[] = Array(trim($template[$tmpSplit[$k]], "{, }") => $split[$tmpSplit[$k]]);
            }
            //Flatten multidimensional Array
            $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($res_raw));
            //Convert Iterator to an array
            $res = iterator_to_array($it, true);
            //Return the result
            return $res;
        }
    }

?>

