<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and clean form values
    // Handle JSON input as well as form-data
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
        
        $name    = trim($decoded['name'] ?? '');
        $email   = trim($decoded['email'] ?? '');
        $phone   = trim($decoded['phone'] ?? '');
        $message = trim($decoded['message'] ?? '');
    } else {
        // Fallback for standard POST
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $message = trim($_POST['message'] ?? '');
    }

    // Basic validation (server-side)
    if ($name === '' || $email === '' || $phone === '' || $message === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
        exit;
    }

    // Send to specific address
    $to = "info@easternrice.lk";




    $subject = "New contact form message from $name";

    $body  = "You have received a new message from the contact form on easternrice.lk.\n\n";
    $body .= "Name:  $name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone\n\n";
    $body .= "Message:\n$message\n";

    // Email headers
    $headers  = "From: Eastern Rice Website <info@easternrice.lk>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    // For testing/demo purposes, we'll assume success mostly.
    // In production, check return of mail() function.
    // Attempt to send email
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you for your message! We have received your enquiry and will get back to you soon.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Sorry, we could not send your message. Please try again later.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}
?>
