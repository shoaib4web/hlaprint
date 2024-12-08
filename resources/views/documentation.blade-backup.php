<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #444;
        }
        h2 {
            color: #555;
            border-bottom: 2px solid #ddd;
            padding-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        code {
            background-color: #f1f1f1;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .json-block {
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 0.95em;
            overflow-x: auto;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>API Documentation</h1>

    <h2>1. Login</h2>
    <p><strong>URL:</strong> <code>/login</code></p>
    <p><strong>Method:</strong> POST</p>
    <p><strong>Description:</strong> Authenticates a user and provides an API access token for subsequent requests.</p>
    
    <h3>Request Parameters</h3>
    <table>
        <tr>
            <th>Parameter</th>
            <th>Type</th>
            <th>Required</th>
            <th>Description</th>
        </tr>
        <tr>
            <td>email</td>
            <td>string</td>
            <td>Yes</td>
            <td>The user's email address.</td>
        </tr>
        <tr>
            <td>password</td>
            <td>string</td>
            <td>Yes</td>
            <td>The user's password.</td>
        </tr>
    </table>

    <h3>Response</h3>
    <p><strong>Status Code:</strong> 200 OK</p>
    <div class="json-block">
        <pre>{
    "message": "Logged in successfully",
    "token": "string"
}</pre>
    </div>

    <h3>Error Responses</h3>
    <p><strong>Status Code:</strong> 422 Unprocessable Entity</p>
    <div class="json-block">
        <pre>{
    "message": "The provided credentials are incorrect."
}</pre>
    </div>

    <h2>2. Logout</h2>
    <p><strong>URL:</strong> <code>/logout</code></p>
    <p><strong>Method:</strong> POST</p>
    <p><strong>Description:</strong> Logs out the authenticated user by revoking their API tokens and invalidating the session.</p>

    <h3>Response</h3>
    <p><strong>Status Code:</strong> 200 OK</p>
    <div class="json-block">
        <pre>{
    "message": "Logged out successfully"
}</pre>
    </div>

    <h2>3. Create Print Jobs</h2>
    <p><strong>URL:</strong> <code>/printjobs</code></p>
    <p><strong>Method:</strong> POST</p>
    <p><strong>Description:</strong> Creates a new print job associated with a transaction. Each print job can include multiple print files with specified parameters.</p>

    <h3>Request Parameters</h3>
    <table>
        <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
        <tr><td>phone</td><td>string</td><td>No</td><td>User's phone number (optional).</td></tr>
        <tr><td>totalPrice</td><td>numeric</td><td>Yes</td><td>Total price for the print jobs.</td></tr>
        <tr><td>print_files</td><td>array</td><td>Yes</td><td>Array of print files to be processed.</td></tr>
        <tr><td>print_files.*.filename</td><td>string</td><td>Yes</td><td>URL to the print file (must be a PDF, JPG, or PNG).</td></tr>
        <tr><td>print_files.*.color</td><td>boolean</td><td>Yes</td><td>Indicates if the print is color (`true`) or black-and-white (`false`).</td></tr>
        <tr><td>print_files.*.double_sided</td><td>boolean</td><td>Yes</td><td>Indicates if the print is double-sided.</td></tr>
        <tr><td>print_files.*.pages_start</td><td>integer</td><td>Yes</td><td>Starting page number.</td></tr>
        <tr><td>print_files.*.page_end</td><td>integer</td><td>Yes</td><td>Ending page number (must be greater than or equal to pages_start).</td></tr>
        <tr><td>print_files.*.page_size</td><td>string</td><td>Yes</td><td>Page size, allowed values: `A4`, `A3`, `Letter`.</td></tr>
        <tr><td>print_files.*.copies</td><td>integer</td><td>Yes</td><td>Number of copies (1–100).</td></tr>
        <tr><td>print_files.*.page_orientation</td><td>string</td><td>Yes</td><td>Orientation of the page, allowed values: `auto`, `portrait`, `landscape`.</td></tr>
    </table>

    <h3>Response</h3>
    <p><strong>Status Code:</strong> 200 OK</p>
    <div class="json-block">
        <pre>{
    "message": "Print jobs created successfully",
    "transaction_id": 12345,
    "code": 6789
}</pre>
    </div>

    <h2>4. Retrieve Print Jobs by Transaction ID</h2>
    <p><strong>URL:</strong> <code>/printjobs/{transactionId}</code></p>
    <p><strong>Method:</strong> GET</p>
    <p><strong>Description:</strong> Retrieves all print jobs associated with a specified transaction ID.</p>

    <h3>Path Parameters</h3>
    <table>
        <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
        <tr><td>transactionId</td><td>integer</td><td>Yes</td><td>The transaction ID to retrieve associated print jobs.</td></tr>
    </table>

    <h3>Response</h3>
    <p><strong>Status Code:</strong> 200 OK</p>
    <div class="json-block">
        <pre>[
    {
        "id": 101,
        "transaction_id": 12345,
        "filename": "https://example.com/printfile.pdf",
        "color": true,
        "double_sided": false,
        "pages_start": 1,
        "page_end": 5,
        "page_size": "A4",
        "copies": 2,
        "page_orientation": "portrait",
        "status": "Received",
        "phone": "+123456789",
        "total_pages": 5,
        "code": 6789
    }
]</pre>
    </div>

    <h3>Error Responses</h3>
    <p><strong>Status Code:</strong> 404 Not Found</p>
    <div class="json-block">
        <pre>{
    "message": "No print jobs found"
}</pre>
    </div>

</div>

</body>
</html>
