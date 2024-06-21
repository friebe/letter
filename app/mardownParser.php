<?php

function parseFrontMatter($filePath) {
    $fileContent = file_get_contents($filePath);
    
    // Trenne Front Matter vom restlichen Inhalt
    if (preg_match('/^---\s*(.*?)\s*---\s*(.*)$/s', $fileContent, $matches)) {
        $frontMatter = $matches[1];
        $content = $matches[2];

        // Parsen der Front Matter (YAML)
        $parsedFrontMatter = yaml_parse($frontMatter);
        $htmlContent = markdownToHtml($content);

        return [
            'frontMatter' => $parsedFrontMatter,
            'content' => $htmlContent
        ];
    }
    
    return [
        'frontMatter' => [],
        'content' => $markdownToHtml($fileContent)
    ];
}

function markdownToHtml($content) {
    $content = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $content);
    $content = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $content);
    $content = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $content);
    $content = preg_replace('/\*\*(.*)\*\*/U', '<strong>$1</strong>', $content);
    $content = preg_replace('/\*(.*)\*/U', '<em>$1</em>', $content);
    $content = nl2br($content);

    return $content;
}