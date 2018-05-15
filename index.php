<?php

require __DIR__ . '/vendor/autoload.php';

use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;

extract($_POST);
echo json_encode(getReadableContent($source, $type, $stripTags));
exit;


function getReadableContent($source, $type = 'string', $stripTags = true)
{
    switch ($type) {
        case 'link':
            $source = file_get_contents($source);
            break;
        default:
            $source = $source;
    }
    return getContent($source, getConfiguration($source), $stripTags);
}

function getContent($content, $config, $strip)
{
    $status = false;
    $message = "error";
    $readability = new Readability($config);
    try {
        $readability->parse($content);
        $status = true;
        $content = sanitizeOutput($readability->getContent(), $strip);
        $title = sanitizeOutput($readability->getTitle(), $strip);
        $author = sanitizeOutput($readability->getAuthor(), $strip);
        $excerpt = sanitizeOutput($readability->getExcerpt(), $strip);
        $images = $readability->getImages();
        $message = "success";
    } catch (ParseException $e) {
        $content = sprintf('Error processing text: %s', $e->getMessage());
    } finally {
        return compact("status", "message", "content", "title", "author", "excerpt", "images");
    }
}


function getConfiguration($source)
{
    return new Configuration([
            'fixRelativeURLs' => true,
            'originalURL' => $source
        ]);
}

function sanitizeOutput($content, $strip)
{
    $search = array(
        '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
        '/[^\S ]+\</s',     // strip whitespaces before tags, except space
        '/(\s)+/s',         // shorten multiple whitespace sequences
        '/<!--(.|\s)*?-->/',// Remove HTML comments
        '/[\r\n]*/' 		// strip spaces and new lines
    );

    $replace = array(
        '>',
        '<',
        '\\1',
        '',
        ''
    );
    $content = ($strip) ? strip_tags($content) :  $content;
    $content = preg_replace($search, $replace, $content);
    return $content;
}
