<?php
    global $wp;
    // Set Post content
    $postContent = json_encode($data);
    
    // Prepare new cURL resource
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
    
    // Set HTTP Header for POST request 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postContent))
    );
    
    // Submit the POST request
    $result = curl_exec($ch);
    
    // Close cURL session handle
    curl_close($ch);
?>