<?php
/*
 * Memory Tracks contact and newsletter form handler.
 * Sends each submission by email to info@m-tracks.com,
 * then sends the visitor to the thank-you page.
 */

$to   = 'info@m-tracks.com';
$from = 'info@m-tracks.com';   // must be an address on this domain

// Only accept form posts
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact');
    exit;
}

// Spam trap: real people never fill the hidden "website" field
if (!empty($_POST['website'])) {
    header('Location: /thank-you');
    exit;
}

function clean_line($value) {
    // One line of plain text, no line breaks (stops email header tricks)
    $value = trim((string)$value);
    $value = str_replace(["\r", "\n", "%0a", "%0d"], ' ', $value);
    return mb_substr(strip_tags($value), 0, 200);
}

function clean_text($value) {
    return mb_substr(trim(strip_tags((string)$value)), 0, 5000);
}

$type  = ($_POST['form_type'] ?? '') === 'newsletter' ? 'newsletter' : 'contact';
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);


if (!$email) {
    header('Location: /contact?error=1');
    exit;
}

$topics = [
    'support'       => 'MedTracks user needing help',
    'organisation'  => 'Care or health organisation',
    'funder'        => 'Funder or grant body',
    'research'      => 'Researcher',
    'collaboration' => 'Company interested in collaboration',
    'memory-tracks' => 'Interested in Memory Tracks',
    'other'         => 'Something else',
];

if ($type === 'newsletter') {
    $subject = 'Website: newsletter signup';
    $body    = "New newsletter signup from the website.\n\nEmail: $email\n";
} else {
    $name         = clean_line($_POST['name'] ?? '');
    $organisation = clean_line($_POST['organisation'] ?? '');
    $topic_key    = $_POST['topic'] ?? '';
    $topic        = $topics[$topic_key] ?? '';
    $message      = clean_text($_POST['message'] ?? '');
    $updates      = !empty($_POST['updates']) ? 'Yes' : 'No';

    if ($name === '' || $topic === '' || $message === '') {
        header('Location: /contact?error=1');
        exit;
    }

    $subject = "Website enquiry: $topic";
    $body  = "New message from the website contact form.\n\n";
    $body .= "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Organisation: " . ($organisation !== '' ? $organisation : '-') . "\n";
    $body .= "I am: $topic\n";
    $body .= "Wants updates: $updates\n\n";
    $body .= "Message:\n$message\n";
}

$headers  = "From: Memory Tracks website <$from>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = mail($to, $subject, $body, $headers, "-f$from");

if ($sent) {
    header('Location: /thank-you');
} else {
    header('Location: /contact?error=1');
}
exit;
