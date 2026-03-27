<?php

namespace App\Services\Blog;

class RichTextRenderer
{
    /**
     * Render structured JSON doc to HTML.
     * Text nodes run through __() for Laravel localization / AI translation.
     */
    public static function render(string|array $json, bool $translate = true): string
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

    private static function renderBlock(array $block, bool $translate): string
    {
        $type = $block['type'] ?? '';

        switch ($type) {

            case 'paragraph':
                $inner = self::renderContent($block['content'] ?? [], $translate);
                // Blank paragraph = intentional spacing
                if ($inner === '' || $inner === '<br>') {
                    return '<div style="height:1.5em"></div>';
                }
                return '<div class="mb-2">' . $inner . '</div>';

            case 'bulletList':
                $items = '';
                foreach ($block['content'] ?? [] as $item) {
                    $items .= '<li>' . self::renderContent($item['content'] ?? [], $translate) . '</li>';
                }
                return '<ul style="margin:0.75rem 0;padding-left:1.5rem;list-style-type:disc">' . $items . '</ul>';

            case 'orderedList':
                $items = '';
                foreach ($block['content'] ?? [] as $item) {
                    $items .= '<li>' . self::renderContent($item['content'] ?? [], $translate) . '</li>';
                }
                return '<ol style="margin:0.75rem 0;padding-left:1.5rem;list-style-type:decimal">' . $items . '</ol>';

            case 'listItem':
                return self::renderContent($block['content'] ?? [], $translate);

            case 'image':
                return self::renderImage($block);

            case 'spacer':
                return '<div style="height:1.5em"></div>';

            case 'blockquote':
                $inner = self::renderContent($block['content'] ?? [], $translate);
                return '<div class="border-l-4 border-zinc-400 dark:border-zinc-500 pl-4 italic text-zinc-600 dark:text-zinc-400 my-4">' . $inner . '</div>';

            case 'codeBlock':
                $code = self::getPlainText($block['content'] ?? []);
                return '<pre class="bg-zinc-100 dark:bg-zinc-900 rounded-xl p-4 overflow-x-auto my-4 text-sm font-mono"><code>'
                    . htmlspecialchars($code) . '</code></pre>';

            case 'horizontalRule':
                return '<hr class="border-zinc-200 dark:border-zinc-700 my-6">';

            case 'hardBreak':
                return '<br>';

            default:
                return '';
        }
    }

    private static function renderImage(array $block): string
    {
        $src   = htmlspecialchars($block['attrs']['src']   ?? '', ENT_QUOTES);
        $alt   = htmlspecialchars($block['attrs']['alt']   ?? '', ENT_QUOTES);
        $align = $block['attrs']['align'] ?? 'center';
        $width = (int) ($block['attrs']['width'] ?? 100);
        $width = max(10, min(100, $width));

        $justify = match ($align) {
            'left'  => 'flex-start',
            'right' => 'flex-end',
            default => 'center',
        };

        return "<div style=\"display:flex;justify-content:{$justify};margin:1.5rem 0\">"
            . "<img src=\"{$src}\" alt=\"{$alt}\" loading=\"lazy\" "
            . "style=\"width:{$width}%;max-width:100%;border-radius:0.75rem;box-shadow:0 4px 6px -1px rgba(0,0,0,.1)\">"
            . "</div>";
    }

    private static function renderContent(array $content, bool $translate): string
    {
        $html = '';
        foreach ($content as $item) {
            $nodeType = $item['type'] ?? 'text';
            if ($nodeType !== 'text') {
                $html .= self::renderBlock($item, $translate);
            } else {
                $html .= self::renderText($item, $translate);
            }
        }
        return $html;
    }

    private static function renderText(array $item, bool $translate): string
    {
        $text = $item['text'] ?? '';

        $text = str_replace('  ', '&nbsp; ', $text);

        if ($translate && $text !== '' && !is_numeric(trim($text))) {
            $text = __($text);
        } else {
            $text = htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        foreach ($item['marks'] ?? [] as $mark) {
            $text = self::applyMark($text, $mark);
        }

        return $text;
    }

    private static function applyMark(string $text, array $mark): string
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
                return '<code style="background:rgba(0,0,0,.08);border-radius:3px;padding:0.1em 0.3em;font-size:0.875em">'
                    . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</code>';
            case 'highlight':
                $color = htmlspecialchars($mark['attrs']['color'] ?? '#fef08a', ENT_QUOTES);
                return '<mark style="background:' . $color . ';padding:0 2px;border-radius:2px;color:inherit">' . $text . '</mark>';
            case 'textColor':
                $color = htmlspecialchars($mark['attrs']['color'] ?? '#000000', ENT_QUOTES);
                return '<span style="color:' . $color . '">' . $text . '</span>';
            case 'fontSize':
                $raw = $mark['attrs']['size'] ?? 'md';
                if ($raw === 'md') return $text;
                $sizes = [ 'sm' => '0.75em', 'lg' => '1.375em', 'xl' => '2em' ];
                $size = htmlspecialchars($sizes[$raw] ?? $raw, ENT_QUOTES);
                return '<span style="font-size:' . $size . '">' . $text . '</span>';
            case 'link':
                $href = htmlspecialchars($mark['attrs']['href'] ?? '#', ENT_QUOTES);
                return '<a href="' . $href . '" target="_blank" rel="noopener" style="color:#dc2626;text-decoration:underline">'
                    . $text . '</a>';
            default:
                return $text;
        }
    }

    private static function getPlainText(array $content): string
    {
        $text = '';
        foreach ($content as $item) $text .= $item['text'] ?? '';
        return $text;
    }
}