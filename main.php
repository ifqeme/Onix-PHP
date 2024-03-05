<?php

// Function to validate user input
function validateInput($input) {
    return !empty($input) ? true : false;
}

// Function to generate unique filename
function generateFilename($title, $author) {
    $timestamp = date("YmdHis");
    return strtolower(str_replace(' ', '-', $title)) . "-" . strtolower(str_replace(' ', '-', $author)) . "-" . $timestamp . ".md";
}

// Function to generate markdown file
function generateMarkdownFile($title, $author, $categories, $outputDir = '.', $dateFormat = "Y-m-d") {
    // Validate input
    if (!validateInput($title) || !validateInput($author) || empty($categories)) {
        echo "Error: Please provide a non-empty title, author, and at least one category.\n";
        return;
    }

    if (!validateDateFormat($dateFormat)) {
        echo "Error: Invalid date format. Please provide a valid PHP date format (e.g., 'Y-m-d').\n";
        return;
    }

    // Generate filename
    $filename = generateFilename($title, $author);

    // Create metadata
    $date = date($dateFormat);
    $metadata = "---\n";
    $metadata .= "title: \"$title\"\n";
    $metadata .= "author: \"$author\"\n";
    if (strpos($categories, ',') !== false) {
        $categoriesArray = explode(',', $categories);
        $categoriesArray = array_map('trim', $categoriesArray);
        $metadata .= "categories: [\"" . implode('", "', $categoriesArray) . "\"]\n";
    } else {
        $metadata .= "category: \"$categories\"\n";
    }
    $metadata .= "date: \"$date\"\n";
    $metadata .= "---\n\n";

    $content = "Write your blog post content here...\n";

    // Construct file path
    $outputDir = rtrim($outputDir, '/');
    $filePath = $outputDir . '/' . $filename;

    // Write to file
    file_put_contents($filePath, $metadata . $content);

    return $filePath;
}

function validateDateFormat($format) {
    return date($format) !== false;
}

$title = readline("Enter the blog post title: ");
$author = readline("Enter the author's name: ");
$categories = readline("Enter the categories separated by commas: ");
$outputDir = readline("Enter the output directory (optional): ");
$dateFormat = readline("Enter the custom date format (optional, default is 'Y-m-d'): ");

if (empty($outputDir)) {
    $outputDir = '.';
}

if (empty($dateFormat)) {
    $dateFormat = "Y-m-d";
}

$filePath = generateMarkdownFile($title, $author, $categories, $outputDir, $dateFormat);
echo "Blog post template generated at: " . $filePath . "\n";

?>
