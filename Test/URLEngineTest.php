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
                array('/blog/{name:string}/show', '/blog/somename/show', array('name' => 'somename')),
                array('/blog/{name:int}/show', '/blog/somename/show', false),
                array('/blog/{name:int}/show', '/blog/5/show', array('name' => 5)),
                array('/blog/{name:digit}/show', '/blog/5/show', array('name' => 5)),
                array('/blog/{name:digit}/show', '/blog/51/show', false),
                array('/blog/{name:digital}/show', '/blog/5/show', false),
                array('/blog/{name:digit}/show/{item:string}', '/blog/5/show/abcdef', array('name' => 5, 'item' => 'abcdef')),
            );
        }

    }

?>
