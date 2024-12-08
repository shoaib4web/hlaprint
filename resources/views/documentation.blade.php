<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-json.min.js"></script>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --background-color: #f8fafc;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
            --code-background: #f1f5f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .header {
            background-color: white;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .content-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: var(--primary-color);
        }

        h2 {
            font-size: 1.8rem;
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            color: var(--secondary-color);
        }

        h3 {
            font-size: 1.3rem;
            margin: 1.5rem 0 1rem;
            color: var(--text-color);
        }

        .endpoint {
            background-color: var(--code-background);
            padding: 1rem;
            border-radius: 6px;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .method {
            font-weight: bold;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            color: white;
        }

        .method.post { background-color: #22c55e; }
        .method.get { background-color: #3b82f6; }

        .url-path {
            font-family: monospace;
            color: var(--text-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: var(--code-background);
            font-weight: 600;
        }

        .code-block {
            position: relative;
            margin: 1rem 0;
        }

        .code-header {
            background-color: var(--code-background);
            padding: 0.5rem 1rem;
            border-radius: 6px 6px 0 0;
            font-weight: 500;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copy-button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.25rem 0.75rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: background-color 0.2s;
        }

        .copy-button:hover {
            background-color: var(--secondary-color);
        }

        pre {
            margin: 0 !important;
            border-radius: 0 0 6px 6px;
            background-color: var(--code-background);
            padding: 1rem;
            overflow-x: auto;
        }

        .response-block pre {
            border-radius: 6px;
        }

        .note {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 0 6px 6px 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }

            .content-section {
                padding: 1rem;
            }

            table {
                display: block;
                overflow-x: auto;
            }

            .endpoint {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav-container">
            <div class="logo">Print API</div>
        </nav>
    </header>

    <div class="container">
        <div class="content-section">
            <h1>API Documentation</h1>

            <h2>Headers</h2>
            <div class="code-block">
                <div class="code-header">
                    <span>Required Headers</span>
                    <button class="copy-button" onclick="copyCode(this)">Copy</button>
                </div>
                <pre><code class="language-json">{
    "Accept": "application/json",
    "Content-Type": "application/json"
}</code></pre>
            </div>

            <h2>1. Login</h2>
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url-path">/login</span>
            </div>
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

            <h3>Sample Request</h3>
            <div class="code-block">
                <div class="code-header">
                    <span>Request</span>
                    <button class="copy-button" onclick="copyCode(this)">Copy</button>
                </div>
                <pre><code class="language-json">{
    "email": "user@example.com",
    "password": "your_password"
}</code></pre>
            </div>

            <h3>Response</h3>
            <p><strong>Status Code:</strong> 200 OK</p>
            <div class="response-block">
                <pre><code class="language-json">{
    "message": "Logged in successfully",
    "token": "string"
}</code></pre>
            </div>

            <h3>Error Responses</h3>
            <p><strong>Status Code:</strong> 422 Unprocessable Entity</p>
            <div class="response-block">
                <pre><code class="language-json">{
    "message": "The provided credentials are incorrect."
}</code></pre>
            </div>

            <h2>2. Logout</h2>
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url-path">/logout</span>
            </div>
            <p><strong>Description:</strong> Logs out the authenticated user by revoking their API tokens and invalidating the session.</p>

            <h3>Response</h3>
            <p><strong>Status Code:</strong> 200 OK</p>
            <div class="response-block">
                <pre><code class="language-json">{
    "message": "Logged out successfully"
}</code></pre>
            </div>

            <h2>3. Create Print Jobs</h2>
            <div class="endpoint">
                <span class="method post">POST</span>
                <span class="url-path">/printjobs</span>
            </div>
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

            <h3>Sample Request</h3>
            <div class="code-block">
                <div class="code-header">
                    <span>Request</span>
                    <button class="copy-button" onclick="copyCode(this)">Copy</button>
                </div>
                <pre><code class="language-json">{
    "phone": "+1234567890",
    "totalPrice": 25.50,
    "print_files": [
        {
            "filename": "https://example.com/document.pdf",
            "color": true,
            "double_sided": false,
            "pages_start": 1,
            "page_end": 10,
            "page_size": "A4",
            "copies": 2,
            "page_orientation": "portrait"
        }
    ]
}</code></pre>
            </div>

            <h3>Response</h3>
            <p><strong>Status Code:</strong> 200 OK</p>
            <div class="response-block">
                <pre><code class="language-json">{
    "message": "Print jobs created successfully",
    "transaction_id": 12345,
    "code": 6789
}</code></pre>
            </div>

            <h2>4. Retrieve Print Jobs by Transaction ID</h2>
            <div class="endpoint">
                <span class="method get">GET</span>
                <span class="url-path">/printjobs/{transactionId}</span>
            </div>
            <p><strong>Description:</strong> Retrieves all print jobs associated with a specified transaction ID.</p>

            <h3>Path Parameters</h3>
            <table>
                <tr><th>Parameter</th><th>Type</th><th>Required</th><th>Description</th></tr>
                <tr><td>transactionId</td><td>integer</td><td>Yes</td><td>The transaction ID to retrieve associated print jobs.</td></tr>
            </table>

            <h3>Response</h3>
            <p><strong>Status Code:</strong> 200 OK</p>
            <div class="response-block">
                <pre><code class="language-json">[
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
]</code></pre>
            </div>

            <h3>Error Responses</h3>
            <p><strong>Status Code:</strong> 404 Not Found</p>
            <div class="response-block">
                <pre><code class="language-json">{
    "message": "No print jobs found"
}</code></pre>
            </div>
        </div>
    </div>
    <script>
        function copyCode(button) {
            const pre = button.parentElement.nextElementSibling;
            const code = pre.textContent;
            
            navigator.clipboard.writeText(code).then(() => {
                const originalText = button.textContent;
                button.textContent = 'Copied!';
                setTimeout(() => {
                    button.textContent = originalText;
                }, 2000);
            });
        }
    </script>
</body>
</html>