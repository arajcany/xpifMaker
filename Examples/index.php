<?php
/*
 * The MIT License
 *
 * Copyright 2015 Administrator.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
//print_r($_SERVER);
$web_dir = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$directory = realpath(dirname(__FILE__));
$scanned_directory = array_diff(scandir($directory), array('..', '.'));
?>

<html>
    <head>
        <title>xpifMaker Class Sample Files</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #main{
                width: 600px;
                float: left;
                margin-top: 20px;
            }
            #heading{
                padding-left: 40px;
            }
            h1{
                margin: 0px;
            }
            #toc {
                list-style: none;
                margin-bottom: 20px;
                margin-top: 0px;
            }
            #toc li {
                background: url(dot.gif) repeat-x bottom left;
                overflow: hidden;
                padding-bottom: 2px;
            }
            #toc a,
            #toc span {
                display: inline-block;
                background: #fff;
                position: relative;
                bottom: -4px;
            }
            #toc a {
                float: right;
                padding: 0 0 3px 2px;
            }
            #toc span {
                float: left;
                padding: 0 2px 3px 0;
            }
        </style>
    </head>
    <body>
        <div id="main">
            <div id="heading">
                <h1>Table of Contents</h1>
            </div>
            <ul id="toc">
                <?php
                foreach ($scanned_directory as $file) {
                    if (substr($file, 0, 8) == 'Example_' && substr($file, -3) == 'php') {
                        $link = $web_dir . $file;
                        $file_nice = str_replace('.php', '',(str_replace('_', ' ', $file)));
                        echo "<li><span>{$file_nice}</span> <a href=\"{$link}\">View</a></li>";
                    }
                }
                ?>
            </ul>
        </div>
    </body>
</html>