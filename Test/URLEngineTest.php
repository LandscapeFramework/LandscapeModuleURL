<?php

    namespace Test;


    require_once('URLEngine.php');
    use Landscape\URLEngine;

    class URLEngineTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider providerTestParser
         */
        public function testParser($template, $url, $expected)
        {
            $parser = new URLEngine($template);
            $result = $parser->parse($url);
            $this->assertEquals($result, $expected);
        }

        public function providerTestParser()
        {
            return array(
                array('/blog/{name}/show','/blog/mine/show', array('name' => 'mine')),
                array('/blg12/show-some/{file}', '/blg12/show-some/page', array('file' => 'page')),
                array('/blog/{name}/show', '/index/', false),
                array('/http/{abc}/x/{def}', '/http/a/x/d', array('abc' => 'a', 'def' => 'd')),
                array('/http/x/{[opt]}', '/http/x/', array()),
                array('/http/x/{[opt]}', '/http/x/show', array('opt' => 'show')),
            );
        }

    }

?>
