<?php

    /*
     * This file is part of the Ariadne Component Library.
     *
     * (c) Muze <info@muze.nl>
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

    require_once( __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php' );

    class TestHTTP extends PHPUnit_Framework_TestCase
    {
        function testCreateInstance()
        {
            $client = new \arc\http\ClientStream();
            $this->assertTrue( $client instanceof  \arc\http\ClientStream );

            $options = array (
                    'header' => 'X-Test-Header: frop',
                    'method' => 'HEAD'
                );

            $client = new \arc\http\ClientStream($options);

            // do request, any will do, just that requestHeaders will get set
            $client->get('http://www.ariadne-cms.org/');

            $this->assertTrue(strstr($client->requestHeaders,"X-Test-Header: frop\r\n") !== false);

        }

        function testGet()
        {
            $client = new \arc\http\ClientStream();
            $res = $client->get('http://www.ariadne-cms.org/');

            $this->assertTrue( $res != '');
            $this->assertTrue ($client->responseHeaders[0] == 'HTTP/1.1 200 OK');
        }

        function testHeader()
        {
            $client = new \arc\http\ClientStream();
            $client->headers(array('User-Agent: SimpleTestClient'));
            // set second set of headers as string
            $client->headers("X-Debug1: false\r\nX-Debug2: true\r\n");

           // do request, any will do
            $client->get('http://www.ariadne-cms.org/');

            $this->assertTrue(strstr($client->requestHeaders,"User-Agent: SimpleTestClient\r\n") !== false);
            // should not contain an empty line
            $this->assertFalse(strstr($client->requestHeaders,"\r\n\r\n") !== false);
        }

        function testBroken()
        {
            $client = new \arc\http\ClientStream();
            $page = $client->get('afeafawfafweaga');
            $this->assertFalse($page);
        }

        // second request should unset old data
        function testSecondRequest()
        {
            $client = new \arc\http\ClientStream();
            $res1 = $client->get('http://www.ariadne-cms.org/');
            $resHeader1 = $client->responseHeaders;

            $res2 = $client->get('invalid');
            $resHeader2 = $client->responseHeaders;
            $this->assertTrue($resHeader1 !== $resHeader2);
        }
    }
