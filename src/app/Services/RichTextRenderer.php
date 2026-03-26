<?php

namespace App\Services;

class RichTextRenderer
{
    public static function render($json, $translate = true)
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }

        if (!$json || !isset($json['content'])) {
            return '';
        }

        $html = '';
        foreach ($json['content'] as $block) {
            $html .= self::renderBlock($block, $translate);
        }

        return $html;
    }

    private static function renderBlock($block, $translate = true)
    {
        $type = $block['type'] ?? 'text';

        switch ($type) {
            case 'paragraph':
                return '<p>' . self::renderContent($block['content'] ?? [], $translate) . '</p>';

            case 'heading':
                $level = $block['attrs']['level'] ?? 2;
                return '<h' . $level . '>' . self::renderContent($block['content'] ?? [], $translate) . '</h' . $level . '>';

            case 'bulletList':
                return '<ul>' . self::renderContent($block['content'] ?? [], $translate) . '</ul>';

            case 'orderedList':
                return '<ol>' . self::renderContent($block['content'] ?? [], $translate) . '</ol>';

            case 'listItem':
                return '<li>' . self::renderContent($block['content'] ?? [], $translate) . '</li>';

            case 'codeBlock':
                $code = self::getPlainText($block['content'] ?? []);
                return '<pre><code>' . htmlspecialchars($code) . '</code></pre>';

            case 'blockquote':
                return '<blockquote>' . self::renderContent($block['content'] ?? [], $translate) . '</blockquote>';

            case 'horizontalRule':
                return '<hr>';

            case 'hardBreak':
                return '<br>';

            default:
                return '';
        }
    }

    private static function renderContent($content, $translate = true)
    {
        $html = '';
        foreach ($content as $item) {
            if (isset($item['type']) && $item['type'] !== 'text') {
                $html .= self::renderBlock($item, $translate);
            } else {
                $html .= self::renderText($item, $translate);
            }
        }
        return $html;
    }

    private static function renderText($item, $translate = true)
    {
        $text = $item['text'] ?? '';
        
        if ($translate && $text) {
            $text = __($text);
        }

        $marks = $item['marks'] ?? [];
        
        foreach ($marks as $mark) {
            $text = self::applyMark($text, $mark);
        }

        return $text;
    }

    private static function applyMark($text, $mark)
    {
        $type = $mark['type'] ?? '';

        switch ($type) {
            case 'bold':
                return '<strong>' . $text . '</strong>';

            case 'italic':
                return '<em>' . $text . '</em>';

            case 'underline':
                return '<u>' . $text . '</u>';

            case 'strike':
                return '<s>' . $text . '</s>';

            case 'code':
                return '<code>' . htmlspecialchars($text) . '</code>';

            case 'highlight':
                $color = $mark['attrs']['color'] ?? '#fef08a';
                return '<mark style="background-color: ' . $color . ';">' . $text . '</mark>';

            case 'textColor':
                $color = $mark['attrs']['color'] ?? '#000000';
                return '<span style="color: ' . $color . ';">' . $text . '</span>';

            case 'fontSize':
                $size = $mark['attrs']['size'] ?? '1rem';
                return '<span style="font-size: ' . $size . ';">' . $text . '</span>';

            case 'link':
                $href = $mark['attrs']['href'] ?? '#';
                return '<a href="' . htmlspecialchars($href) . '" target="_blank" rel="noopener">' . $text . '</a>';

            default:
                return $text;
        }
    }

    private static function getPlainText($content)
    {
        $text = '';
        foreach ($content as $item) {
            if (isset($item['text'])) {
                $text .= $item['text'];
            }
        }
        return $text;
    }
}
