<?php

namespace App\Controllers;

use andreskrey\Readability\Readability;
use andreskrey\Readability\Configuration;

class ReadabilityController extends Controller
{
    public function getExtractedContent($request, $response, array $args)
    {
        //$this->logger->addInfo("Read request of $args[type]");

        $data = $request->getParsedBody();
        $response = $this->getReadableContent($data['source'], $args['type'], true);
        return json_encode($response);
    }

    private function getReadableContent($source, $type = 'string', $stripTags = true)
    {
        switch ($type) {
            case 'link':
                $source = file_get_contents($source);
                break;
            default:
                $source = $source;
        }
        return $this->getContent($source, $this->getConfiguration($source), $stripTags);
    }

    private function getContent($content, $config, $strip)
    {
        $status = false;
        $message = "error";
        $readability = new Readability($config);
        try {
            $readability->parse($content);
            $status = true;
            $content = $this->sanitizeOutput($readability->getContent(), $strip);
            $title = $this->sanitizeOutput($readability->getTitle(), $strip);
            $author = $this->sanitizeOutput($readability->getAuthor(), $strip);
            $excerpt = $this->sanitizeOutput($readability->getExcerpt(), $strip);
            $images = $readability->getImages();
            $message = "success";
        } catch (ParseException $e) {
            $content = sprintf('Error processing text: %s', $e->getMessage());
        } finally {
            return compact("status", "message", "content", "title", "author", "excerpt", "images");
        }
    }


    private function getConfiguration($source)
    {
        return new Configuration([
                'fixRelativeURLs' => true,
                'originalURL' => $source
            ]);
    }

    private function sanitizeOutput($content, $strip)
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
}
