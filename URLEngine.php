<?php namespace Landscape;

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

            foreach($template as $key => $t)
            {
                if(strpos($t, "{") === false && strpos($t, "}") === false)
                {
                    try
                    {
                        if($t != $split[$key])
                        {
                            return false;
                        }
                    } catch (Exception $e) {
                        return false; //Wrong array indexes
                    }
                }
            }

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
                $type = "string"; // default type is string
                $temp = trim($template[$tmpSplit[$k]], "{, }"); // Key
                $tempVal = $split[$tmpSplit[$k]];               // Value

                if(strpos($temp, ":") !== false)
                { // we have a type qualifier
                    $pos = strpos($temp, ":");
                    $type = substr($temp, $pos+1);
                    $temp = substr($temp, 0, $pos);
                }

                // now, perform tests which apply to the desired type
                switch ($type)
                {
                    case 'string':
                        //TODO: Implement some type checks on strings (length)
                        break;

                    case 'int':
                        if(!ctype_digit($tempVal))
                            return false; // value contains other items than numbers
                        $tempVal = intval($tempVal);
                        break;

                    case 'digit':
                        if(!ctype_digit($tempVal) || strlen($tempVal) != 1)
                            return false; // value contains other items than numbers or is not just a digit
                        $tempVal = intval($tempVal);
                        break;

                    case 'regex':
                        //TODO: implement ;)
                        break;

                    default:
                        print("Unknown type ".$type."!\n");
                        return false;
                        break;
                }



                if(strpos($temp, "[") !== false && strpos($temp, "]") !== false && $tempVal == "") // contains [] -> optional + value is empty
                    continue;                                                                      // do not add to result array
                else
                    $temp = trim($temp, "[]");                                                     // remove optional markers from the key

                $res_raw[] = Array($temp => $tempVal);                                             // Add to results
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
