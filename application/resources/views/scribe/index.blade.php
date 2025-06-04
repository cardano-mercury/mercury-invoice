<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Mercury: Invoice API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
                    body .content .php-example code { display: none; }
                    body .content .python-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8100";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.2.1.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.2.1.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;,&quot;javascript&quot;,&quot;php&quot;,&quot;python&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="python">python</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-customers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customers">
                    <a href="#customers">Customers</a>
                </li>
                                    <ul id="tocify-subheader-customers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customers-GETapi-v1-customers">
                                <a href="#customers-GETapi-v1-customers">List Customers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-POSTapi-v1-customers">
                                <a href="#customers-POSTapi-v1-customers">Create Customer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-GETapi-v1-customers--id-">
                                <a href="#customers-GETapi-v1-customers--id-">Get Customer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-PUTapi-v1-customers--id-">
                                <a href="#customers-PUTapi-v1-customers--id-">Update Customer</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-emails" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-emails">
                    <a href="#customer-emails">Customer Emails</a>
                </li>
                                    <ul id="tocify-subheader-customer-emails" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-emails-GETapi-v1-customers--customer_id--emails">
                                <a href="#customer-emails-GETapi-v1-customers--customer_id--emails">List Customer Emails</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-emails-POSTapi-v1-customers--customer_id--emails">
                                <a href="#customer-emails-POSTapi-v1-customers--customer_id--emails">Create Customer Email</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-emails-GETapi-v1-customers--customer_id--emails--id-">
                                <a href="#customer-emails-GETapi-v1-customers--customer_id--emails--id-">Get Customer Email</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-emails-PUTapi-v1-customers--customer_id--emails--id-">
                                <a href="#customer-emails-PUTapi-v1-customers--customer_id--emails--id-">Update Customer Email</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-phones" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-phones">
                    <a href="#customer-phones">Customer Phones</a>
                </li>
                                    <ul id="tocify-subheader-customer-phones" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-phones-GETapi-v1-customers--customer_id--phones">
                                <a href="#customer-phones-GETapi-v1-customers--customer_id--phones">List Customer Phones</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-phones-POSTapi-v1-customers--customer_id--phones">
                                <a href="#customer-phones-POSTapi-v1-customers--customer_id--phones">Create Customer Phone</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-phones-GETapi-v1-customers--customer_id--phones--id-">
                                <a href="#customer-phones-GETapi-v1-customers--customer_id--phones--id-">Get Customer Phone</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-phones-PUTapi-v1-customers--customer_id--phones--id-">
                                <a href="#customer-phones-PUTapi-v1-customers--customer_id--phones--id-">Update Customer Phone</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-addresses" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-addresses">
                    <a href="#customer-addresses">Customer Addresses</a>
                </li>
                                    <ul id="tocify-subheader-customer-addresses" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-addresses-GETapi-v1-customers--customer_id--addresses">
                                <a href="#customer-addresses-GETapi-v1-customers--customer_id--addresses">List Customer Addresses</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-addresses-POSTapi-v1-customers--customer_id--addresses">
                                <a href="#customer-addresses-POSTapi-v1-customers--customer_id--addresses">Create Customer Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-addresses-GETapi-v1-customers--customer_id--addresses--id-">
                                <a href="#customer-addresses-GETapi-v1-customers--customer_id--addresses--id-">Get Customer Address</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-addresses-PUTapi-v1-customers--customer_id--addresses--id-">
                                <a href="#customer-addresses-PUTapi-v1-customers--customer_id--addresses--id-">Update Customer Address</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-categories" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-categories">
                    <a href="#customer-categories">Customer Categories</a>
                </li>
                                    <ul id="tocify-subheader-customer-categories" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-categories-GETapi-v1-customer-categories">
                                <a href="#customer-categories-GETapi-v1-customer-categories">List Customer Categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-categories-POSTapi-v1-customer-categories">
                                <a href="#customer-categories-POSTapi-v1-customer-categories">Create Customer Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-categories-GETapi-v1-customer-categories--id-">
                                <a href="#customer-categories-GETapi-v1-customer-categories--id-">Get Customer Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-categories-PUTapi-v1-customer-categories--id-">
                                <a href="#customer-categories-PUTapi-v1-customer-categories--id-">Update Customer Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-categories-PUTapi-v1-customer-categories-sync">
                                <a href="#customer-categories-PUTapi-v1-customer-categories-sync">Sync Customer Categories</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-products" class="tocify-header">
                <li class="tocify-item level-1" data-unique="products">
                    <a href="#products">Products</a>
                </li>
                                    <ul id="tocify-subheader-products" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="products-GETapi-v1-products">
                                <a href="#products-GETapi-v1-products">List Products</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="products-POSTapi-v1-products">
                                <a href="#products-POSTapi-v1-products">Create Product</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="products-GETapi-v1-products--id-">
                                <a href="#products-GETapi-v1-products--id-">Get Product</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="products-PUTapi-v1-products--id-">
                                <a href="#products-PUTapi-v1-products--id-">Update Product</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-product-categories" class="tocify-header">
                <li class="tocify-item level-1" data-unique="product-categories">
                    <a href="#product-categories">Product Categories</a>
                </li>
                                    <ul id="tocify-subheader-product-categories" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="product-categories-GETapi-v1-product-categories">
                                <a href="#product-categories-GETapi-v1-product-categories">List Product Categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="product-categories-POSTapi-v1-product-categories">
                                <a href="#product-categories-POSTapi-v1-product-categories">Create Product Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="product-categories-GETapi-v1-product-categories--id-">
                                <a href="#product-categories-GETapi-v1-product-categories--id-">Get Product Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="product-categories-PUTapi-v1-product-categories--id-">
                                <a href="#product-categories-PUTapi-v1-product-categories--id-">Update Product Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="product-categories-PUTapi-v1-product-categories-sync">
                                <a href="#product-categories-PUTapi-v1-product-categories-sync">Sync Customer Categories</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-services" class="tocify-header">
                <li class="tocify-item level-1" data-unique="services">
                    <a href="#services">Services</a>
                </li>
                                    <ul id="tocify-subheader-services" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="services-GETapi-v1-services">
                                <a href="#services-GETapi-v1-services">List Services</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="services-POSTapi-v1-services">
                                <a href="#services-POSTapi-v1-services">Create Service</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="services-GETapi-v1-services--id-">
                                <a href="#services-GETapi-v1-services--id-">Get Service</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="services-PUTapi-v1-services--id-">
                                <a href="#services-PUTapi-v1-services--id-">Update Service</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-service-categories" class="tocify-header">
                <li class="tocify-item level-1" data-unique="service-categories">
                    <a href="#service-categories">Service Categories</a>
                </li>
                                    <ul id="tocify-subheader-service-categories" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="service-categories-GETapi-v1-service-categories">
                                <a href="#service-categories-GETapi-v1-service-categories">List Service Categories</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="service-categories-POSTapi-v1-service-categories">
                                <a href="#service-categories-POSTapi-v1-service-categories">Create Service Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="service-categories-GETapi-v1-service-categories--id-">
                                <a href="#service-categories-GETapi-v1-service-categories--id-">Get Service Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="service-categories-PUTapi-v1-service-categories--id-">
                                <a href="#service-categories-PUTapi-v1-service-categories--id-">Update Service Category</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="service-categories-PUTapi-v1-service-categories-sync">
                                <a href="#service-categories-PUTapi-v1-service-categories-sync">Sync Service Categories</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-invoices" class="tocify-header">
                <li class="tocify-item level-1" data-unique="invoices">
                    <a href="#invoices">Invoices</a>
                </li>
                                    <ul id="tocify-subheader-invoices" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="invoices-GETapi-v1-invoices">
                                <a href="#invoices-GETapi-v1-invoices">List Invoices</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-POSTapi-v1-invoices">
                                <a href="#invoices-POSTapi-v1-invoices">Create Invoice</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-GETapi-v1-invoices--id-">
                                <a href="#invoices-GETapi-v1-invoices--id-">Get Invoice</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-PUTapi-v1-invoices--id-">
                                <a href="#invoices-PUTapi-v1-invoices--id-">Update Invoice</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-PUTapi-v1-invoice-void--invoice_id-">
                                <a href="#invoices-PUTapi-v1-invoice-void--invoice_id-">Void Invoice</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-PUTapi-v1-invoice-restore--invoice_id-">
                                <a href="#invoices-PUTapi-v1-invoice-restore--invoice_id-">Restore Invoice</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">
                                <a href="#invoices-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">Send Invoice Reminder Notifications</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="invoices-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">
                                <a href="#invoices-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">Manually Mark Invoice As Paid</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: June 4, 2025</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8100</code>
</aside>
<p>This documentation aims to provide all the information you need to work with our API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_ACCESS_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>You can generate your token by logging in and clicking your name, and then <a href="/user/api-tokens"><b>API Tokens</b></a>.</p>

        <h1 id="customers">Customers</h1>

    <p>Customer Management API</p>

                                <h2 id="customers-GETapi-v1-customers">List Customers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers?search=John+Doe&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers"
);

const params = {
    "search": "John Doe",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'John Doe',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers'
params = {
  'search': 'John Doe',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Murphy Walsh&quot;,
            &quot;tax_number&quot;: &quot;01JWW81PWH5DG40N09GTR948RX&quot;,
            &quot;tax_rate&quot;: 17.5,
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Non&quot;,
                    &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                    &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
                }
            ],
            &quot;default_email&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Mrs. Marcelle Streich&quot;,
                &quot;address&quot;: &quot;stephon.yost@example.net&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_phone&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Office&quot;,
                &quot;name&quot;: &quot;Melissa Lynch&quot;,
                &quot;number&quot;: &quot;+1 (954) 829-9382&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_address&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Billing&quot;,
                &quot;name&quot;: &quot;Dach Inc&quot;,
                &quot;line1&quot;: &quot;1337 Aurore Shoal&quot;,
                &quot;line2&quot;: null,
                &quot;city&quot;: &quot;North Erica&quot;,
                &quot;state&quot;: &quot;Virginia&quot;,
                &quot;postal_code&quot;: &quot;53035&quot;,
                &quot;country&quot;: &quot;United States&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Garrison Carroll&quot;,
            &quot;tax_number&quot;: &quot;01JWW81PWJSBF3N22GFGZGQ7VK&quot;,
            &quot;tax_rate&quot;: 17.5,
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Nobis&quot;,
                    &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                    &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
                }
            ],
            &quot;default_email&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Dan Kertzmann IV&quot;,
                &quot;address&quot;: &quot;hromaguera@example.com&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_phone&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Home&quot;,
                &quot;name&quot;: &quot;Prof. Elyse Hettinger DDS&quot;,
                &quot;number&quot;: &quot;+12833831393&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_address&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Shipping&quot;,
                &quot;name&quot;: &quot;Aufderhar-Medhurst&quot;,
                &quot;line1&quot;: &quot;66338 Madilyn Knoll Apt. 811&quot;,
                &quot;line2&quot;: null,
                &quot;city&quot;: &quot;Lake Isai&quot;,
                &quot;state&quot;: &quot;Massachusetts&quot;,
                &quot;postal_code&quot;: &quot;70294-5640&quot;,
                &quot;country&quot;: &quot;United States&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers" data-method="GET"
      data-path="api/v1/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers"
                    onclick="tryItOut('GETapi-v1-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers"
                    onclick="cancelTryOut('GETapi-v1-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-customers"
               value="John Doe"
               data-component="query">
    <br>
<p>Search for customers by name or tax number Example: <code>John Doe</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customers"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customers"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="customers-POSTapi-v1-customers">Create Customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/customers" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"tax_number\": \"ABCD-1234\",
    \"tax_rate\": 12.5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "tax_number": "ABCD-1234",
    "tax_rate": 12.5
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'John Doe',
            'tax_number' =&gt; 'ABCD-1234',
            'tax_rate' =&gt; 12.5,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers'
payload = {
    "name": "John Doe",
    "tax_number": "ABCD-1234",
    "tax_rate": 12.5
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Prof. Jedidiah Auer III&quot;,
        &quot;tax_number&quot;: &quot;01JWW81PWSM9GAWP47NFYW0MGG&quot;,
        &quot;tax_rate&quot;: 17.5,
        &quot;categories&quot;: [],
        &quot;default_email&quot;: null,
        &quot;default_phone&quot;: null,
        &quot;default_address&quot;: null,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers" data-method="POST"
      data-path="api/v1/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers"
                    onclick="tryItOut('POSTapi-v1-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers"
                    onclick="cancelTryOut('POSTapi-v1-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customers"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tax_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="tax_number"                data-endpoint="POSTapi-v1-customers"
               value="ABCD-1234"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>ABCD-1234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tax_rate</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tax_rate"                data-endpoint="POSTapi-v1-customers"
               value="12.5"
               data-component="body">
    <br>
<p>Must be between 0 and 100. Example: <code>12.5</code></p>
        </div>
        </form>

                    <h2 id="customers-GETapi-v1-customers--id-">Get Customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Murphy Walsh&quot;,
        &quot;tax_number&quot;: &quot;01JWW81PWZZMEV7E82A5XY19V2&quot;,
        &quot;tax_rate&quot;: 17.5,
        &quot;categories&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Debitis&quot;,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            }
        ],
        &quot;default_email&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Conor Corkery&quot;,
            &quot;address&quot;: &quot;ofeeney@example.com&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;default_phone&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Mobile&quot;,
            &quot;name&quot;: &quot;Stephon Yost&quot;,
            &quot;number&quot;: &quot;1-930-995-2285&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;default_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Shipping&quot;,
            &quot;name&quot;: &quot;Ondricka, Kulas and Russel&quot;,
            &quot;line1&quot;: &quot;71972 Jacinto Roads&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Auroreside&quot;,
            &quot;state&quot;: &quot;Texas&quot;,
            &quot;postal_code&quot;: &quot;00397&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--id-" data-method="GET"
      data-path="api/v1/customers/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--id-"
                    onclick="tryItOut('GETapi-v1-customers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--id-"
                    onclick="cancelTryOut('GETapi-v1-customers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-customers--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="customers-PUTapi-v1-customers--id-">Update Customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customers--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customers/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"tax_number\": \"ABCD-1234\",
    \"tax_rate\": 12.5
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "tax_number": "ABCD-1234",
    "tax_rate": 12.5
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'John Doe',
            'tax_number' =&gt; 'ABCD-1234',
            'tax_rate' =&gt; 12.5,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1'
payload = {
    "name": "John Doe",
    "tax_number": "ABCD-1234",
    "tax_rate": 12.5
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customers--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Prof. Jedidiah Auer III&quot;,
        &quot;tax_number&quot;: &quot;01JWW81PX2S75QM65BXJMZYA4J&quot;,
        &quot;tax_rate&quot;: 17.5,
        &quot;categories&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Ea&quot;,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            }
        ],
        &quot;default_email&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Prof. Dillon Huels&quot;,
            &quot;address&quot;: &quot;manuel.krajcik@example.com&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;default_phone&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Mobile&quot;,
            &quot;name&quot;: &quot;Antwon Runte DDS&quot;,
            &quot;number&quot;: &quot;984-547-7031&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;default_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Billing&quot;,
            &quot;name&quot;: &quot;Streich-Stroman&quot;,
            &quot;line1&quot;: &quot;992 Erdman Plains&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Koelpinland&quot;,
            &quot;state&quot;: &quot;Arizona&quot;,
            &quot;postal_code&quot;: &quot;08133-2285&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customers--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customers--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customers--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customers--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customers--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customers--id-" data-method="PUT"
      data-path="api/v1/customers/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customers--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customers--id-"
                    onclick="tryItOut('PUTapi-v1-customers--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customers--id-"
                    onclick="cancelTryOut('PUTapi-v1-customers--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customers--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customers/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customers/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customers--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customers--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-customers--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-customers--id-"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tax_number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="tax_number"                data-endpoint="PUTapi-v1-customers--id-"
               value="ABCD-1234"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>ABCD-1234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>tax_rate</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="tax_rate"                data-endpoint="PUTapi-v1-customers--id-"
               value="12.5"
               data-component="body">
    <br>
<p>Must be between 0 and 100. Example: <code>12.5</code></p>
        </div>
        </form>

                <h1 id="customer-emails">Customer Emails</h1>

    <p>Customer Email Management API</p>

                                <h2 id="customer-emails-GETapi-v1-customers--customer_id--emails">List Customer Emails</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--emails">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/emails?search=john.doe%40example.com&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/emails"
);

const params = {
    "search": "john.doe@example.com",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/emails';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'john.doe@example.com',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/emails'
params = {
  'search': 'john.doe@example.com',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--emails">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Murphy Walsh&quot;,
            &quot;address&quot;: &quot;kdaugherty@example.com&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Mrs. Marcelle Streich&quot;,
            &quot;address&quot;: &quot;serdman@example.com&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--emails" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--emails"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--emails"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--emails" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--emails">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--emails" data-method="GET"
      data-path="api/v1/customers/{customer_id}/emails"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--emails', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--emails"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--emails');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--emails"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--emails');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--emails"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/emails</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="john.doe@example.com"
               data-component="query">
    <br>
<p>Search for customer emails by name or address Example: <code>john.doe@example.com</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customers--customer_id--emails"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="customer-emails-POSTapi-v1-customers--customer_id--emails">Create Customer Email</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-customers--customer_id--emails">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/customers/1/emails" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"address\": \"john.doe@example.com\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/emails"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "address": "john.doe@example.com",
    "is_default": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/emails';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'John Doe',
            'address' =&gt; 'john.doe@example.com',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/emails'
payload = {
    "name": "John Doe",
    "address": "john.doe@example.com",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers--customer_id--emails">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Prof. Jedidiah Auer III&quot;,
        &quot;address&quot;: &quot;lubowitz.nona@example.com&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers--customer_id--emails" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers--customer_id--emails"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers--customer_id--emails"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers--customer_id--emails" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers--customer_id--emails">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers--customer_id--emails" data-method="POST"
      data-path="api/v1/customers/{customer_id}/emails"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers--customer_id--emails', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers--customer_id--emails"
                    onclick="tryItOut('POSTapi-v1-customers--customer_id--emails');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers--customer_id--emails"
                    onclick="cancelTryOut('POSTapi-v1-customers--customer_id--emails');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers--customer_id--emails"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers/{customer_id}/emails</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="POSTapi-v1-customers--customer_id--emails"
               value="john.doe@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Must be at least 3 characters. Must not be greater than 256 characters. Example: <code>john.doe@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-v1-customers--customer_id--emails" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="POSTapi-v1-customers--customer_id--emails"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-customers--customer_id--emails" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="POSTapi-v1-customers--customer_id--emails"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="customer-emails-GETapi-v1-customers--customer_id--emails--id-">Get Customer Email</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--emails--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/emails/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/emails/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/emails/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/emails/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--emails--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Murphy Walsh&quot;,
        &quot;address&quot;: &quot;lola85@example.com&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--emails--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--emails--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--emails--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--emails--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--emails--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--emails--id-" data-method="GET"
      data-path="api/v1/customers/{customer_id}/emails/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--emails--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--emails--id-"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--emails--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--emails--id-"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--emails--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--emails--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/emails/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--emails--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--emails--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--emails--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--emails--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-customers--customer_id--emails--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the email. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="customer-emails-PUTapi-v1-customers--customer_id--emails--id-">Update Customer Email</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customers--customer_id--emails--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customers/1/emails/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"John Doe\",
    \"address\": \"john.doe@example.com\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/emails/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "John Doe",
    "address": "john.doe@example.com",
    "is_default": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/emails/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'John Doe',
            'address' =&gt; 'john.doe@example.com',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/emails/1'
payload = {
    "name": "John Doe",
    "address": "john.doe@example.com",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customers--customer_id--emails--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Prof. Jedidiah Auer III&quot;,
        &quot;address&quot;: &quot;kovacek.bennett@example.org&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customers--customer_id--emails--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customers--customer_id--emails--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customers--customer_id--emails--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customers--customer_id--emails--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customers--customer_id--emails--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customers--customer_id--emails--id-" data-method="PUT"
      data-path="api/v1/customers/{customer_id}/emails/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customers--customer_id--emails--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customers--customer_id--emails--id-"
                    onclick="tryItOut('PUTapi-v1-customers--customer_id--emails--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customers--customer_id--emails--id-"
                    onclick="cancelTryOut('PUTapi-v1-customers--customer_id--emails--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customers--customer_id--emails--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customers/{customer_id}/emails/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customers/{customer_id}/emails/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the email. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>address</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="address"                data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
               value="john.doe@example.com"
               data-component="body">
    <br>
<p>Must be a valid email address. Must be at least 3 characters. Must not be greater than 256 characters. Example: <code>john.doe@example.com</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="PUTapi-v1-customers--customer_id--emails--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-v1-customers--customer_id--emails--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="PUTapi-v1-customers--customer_id--emails--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                <h1 id="customer-phones">Customer Phones</h1>

    <p>Customer Phone Management API</p>

                                <h2 id="customer-phones-GETapi-v1-customers--customer_id--phones">List Customer Phones</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--phones">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/phones?type=Office&amp;search=0123456789&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/phones"
);

const params = {
    "type": "Office",
    "search": "0123456789",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/phones';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'type' =&gt; 'Office',
            'search' =&gt; '0123456789',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/phones'
params = {
  'type': 'Office',
  'search': '0123456789',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--phones">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Home&quot;,
            &quot;name&quot;: &quot;Alisa Schroeder&quot;,
            &quot;number&quot;: &quot;(351) 476-0276&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Home&quot;,
            &quot;name&quot;: &quot;Cristopher Cronin Sr.&quot;,
            &quot;number&quot;: &quot;+1-702-716-4632&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--phones" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--phones"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--phones"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--phones" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--phones">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--phones" data-method="GET"
      data-path="api/v1/customers/{customer_id}/phones"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--phones', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--phones"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--phones');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--phones"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--phones');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--phones"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/phones</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="Office"
               data-component="query">
    <br>
<p>Example: <code>Office</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Home</code></li> <li><code>Office</code></li> <li><code>Mobile</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="0123456789"
               data-component="query">
    <br>
<p>Search for customer phones by name or number Example: <code>0123456789</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customers--customer_id--phones"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="customer-phones-POSTapi-v1-customers--customer_id--phones">Create Customer Phone</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-customers--customer_id--phones">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/customers/1/phones" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"Home\",
    \"name\": \"John Doe\",
    \"number\": \"+1-956-745-2290\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/phones"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "Home",
    "name": "John Doe",
    "number": "+1-956-745-2290",
    "is_default": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/phones';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'Home',
            'name' =&gt; 'John Doe',
            'number' =&gt; '+1-956-745-2290',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/phones'
payload = {
    "type": "Home",
    "name": "John Doe",
    "number": "+1-956-745-2290",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers--customer_id--phones">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Mobile&quot;,
        &quot;name&quot;: &quot;Jedidiah Auer&quot;,
        &quot;number&quot;: &quot;+1.619.341.5978&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers--customer_id--phones" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers--customer_id--phones"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers--customer_id--phones"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers--customer_id--phones" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers--customer_id--phones">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers--customer_id--phones" data-method="POST"
      data-path="api/v1/customers/{customer_id}/phones"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers--customer_id--phones', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers--customer_id--phones"
                    onclick="tryItOut('POSTapi-v1-customers--customer_id--phones');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers--customer_id--phones"
                    onclick="cancelTryOut('POSTapi-v1-customers--customer_id--phones');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers--customer_id--phones"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers/{customer_id}/phones</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="Home"
               data-component="body">
    <br>
<p>Example: <code>Home</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Home</code></li> <li><code>Office</code></li> <li><code>Mobile</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="number"                data-endpoint="POSTapi-v1-customers--customer_id--phones"
               value="+1-956-745-2290"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 32 characters. Example: <code>+1-956-745-2290</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-v1-customers--customer_id--phones" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="POSTapi-v1-customers--customer_id--phones"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-customers--customer_id--phones" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="POSTapi-v1-customers--customer_id--phones"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="customer-phones-GETapi-v1-customers--customer_id--phones--id-">Get Customer Phone</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--phones--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/phones/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/phones/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/phones/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/phones/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--phones--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Home&quot;,
        &quot;name&quot;: &quot;Mr. Oren Daugherty Jr.&quot;,
        &quot;number&quot;: &quot;(985) 862-0010&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--phones--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--phones--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--phones--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--phones--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--phones--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--phones--id-" data-method="GET"
      data-path="api/v1/customers/{customer_id}/phones/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--phones--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--phones--id-"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--phones--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--phones--id-"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--phones--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--phones--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/phones/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--phones--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--phones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--phones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--phones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-customers--customer_id--phones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the phone. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="customer-phones-PUTapi-v1-customers--customer_id--phones--id-">Update Customer Phone</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customers--customer_id--phones--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customers/1/phones/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"Home\",
    \"name\": \"John Doe\",
    \"number\": \"+1-956-745-2290\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/phones/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "Home",
    "name": "John Doe",
    "number": "+1-956-745-2290",
    "is_default": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/phones/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'Home',
            'name' =&gt; 'John Doe',
            'number' =&gt; '+1-956-745-2290',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/phones/1'
payload = {
    "type": "Home",
    "name": "John Doe",
    "number": "+1-956-745-2290",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customers--customer_id--phones--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Mobile&quot;,
        &quot;name&quot;: &quot;Jedidiah Auer&quot;,
        &quot;number&quot;: &quot;+1-234-936-3998&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customers--customer_id--phones--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customers--customer_id--phones--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customers--customer_id--phones--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customers--customer_id--phones--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customers--customer_id--phones--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customers--customer_id--phones--id-" data-method="PUT"
      data-path="api/v1/customers/{customer_id}/phones/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customers--customer_id--phones--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customers--customer_id--phones--id-"
                    onclick="tryItOut('PUTapi-v1-customers--customer_id--phones--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customers--customer_id--phones--id-"
                    onclick="cancelTryOut('PUTapi-v1-customers--customer_id--phones--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customers--customer_id--phones--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customers/{customer_id}/phones/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customers/{customer_id}/phones/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the phone. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="Home"
               data-component="body">
    <br>
<p>Example: <code>Home</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Home</code></li> <li><code>Office</code></li> <li><code>Mobile</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>number</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="number"                data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
               value="+1-956-745-2290"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 32 characters. Example: <code>+1-956-745-2290</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="PUTapi-v1-customers--customer_id--phones--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-v1-customers--customer_id--phones--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="PUTapi-v1-customers--customer_id--phones--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                <h1 id="customer-addresses">Customer Addresses</h1>

    <p>Customer Address Management API</p>

                                <h2 id="customer-addresses-GETapi-v1-customers--customer_id--addresses">List Customer Addresses</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--addresses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/addresses?type=Billing&amp;search=25+Brookfield+Road&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/addresses"
);

const params = {
    "type": "Billing",
    "search": "25 Brookfield Road",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/addresses';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'type' =&gt; 'Billing',
            'search' =&gt; '25 Brookfield Road',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/addresses'
params = {
  'type': 'Billing',
  'search': '25 Brookfield Road',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--addresses">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Billing&quot;,
            &quot;name&quot;: &quot;Walsh-Runte&quot;,
            &quot;line1&quot;: &quot;927 Gerson Village Apt. 946&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Port Wandabury&quot;,
            &quot;state&quot;: &quot;Florida&quot;,
            &quot;postal_code&quot;: &quot;44567&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Shipping&quot;,
            &quot;name&quot;: &quot;Runte-Ferry&quot;,
            &quot;line1&quot;: &quot;1529 Orland Flats&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;West Angelinemouth&quot;,
            &quot;state&quot;: &quot;Virginia&quot;,
            &quot;postal_code&quot;: &quot;21992&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--addresses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--addresses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--addresses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--addresses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--addresses" data-method="GET"
      data-path="api/v1/customers/{customer_id}/addresses"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--addresses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--addresses"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--addresses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--addresses"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--addresses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--addresses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/addresses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="Billing"
               data-component="query">
    <br>
<p>Example: <code>Billing</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Billing</code></li> <li><code>Shipping</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="25 Brookfield Road"
               data-component="query">
    <br>
<p>Search for customer addresses by name or any address fields Example: <code>25 Brookfield Road</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customers--customer_id--addresses"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="customer-addresses-POSTapi-v1-customers--customer_id--addresses">Create Customer Address</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-customers--customer_id--addresses">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/customers/1/addresses" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"Billing\",
    \"name\": \"John Doe\",
    \"line1\": \"2592 Jada Key\",
    \"line2\": \"Apartment 504\",
    \"city\": \"South Josh\",
    \"state\": \"Kentucky\",
    \"postal_code\": \"62249-8453\",
    \"country\": \"United States\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/addresses"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "Billing",
    "name": "John Doe",
    "line1": "2592 Jada Key",
    "line2": "Apartment 504",
    "city": "South Josh",
    "state": "Kentucky",
    "postal_code": "62249-8453",
    "country": "United States",
    "is_default": false
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/addresses';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'Billing',
            'name' =&gt; 'John Doe',
            'line1' =&gt; '2592 Jada Key',
            'line2' =&gt; 'Apartment 504',
            'city' =&gt; 'South Josh',
            'state' =&gt; 'Kentucky',
            'postal_code' =&gt; '62249-8453',
            'country' =&gt; 'United States',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/addresses'
payload = {
    "type": "Billing",
    "name": "John Doe",
    "line1": "2592 Jada Key",
    "line2": "Apartment 504",
    "city": "South Josh",
    "state": "Kentucky",
    "postal_code": "62249-8453",
    "country": "United States",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers--customer_id--addresses">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Billing&quot;,
        &quot;name&quot;: &quot;Leffler, Auer and Kirlin&quot;,
        &quot;line1&quot;: &quot;6271 Kihn Club&quot;,
        &quot;line2&quot;: null,
        &quot;city&quot;: &quot;Krajcikfort&quot;,
        &quot;state&quot;: &quot;New Hampshire&quot;,
        &quot;postal_code&quot;: &quot;15256-1660&quot;,
        &quot;country&quot;: &quot;United States&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers--customer_id--addresses" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers--customer_id--addresses"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers--customer_id--addresses"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers--customer_id--addresses" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers--customer_id--addresses">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers--customer_id--addresses" data-method="POST"
      data-path="api/v1/customers/{customer_id}/addresses"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers--customer_id--addresses', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers--customer_id--addresses"
                    onclick="tryItOut('POSTapi-v1-customers--customer_id--addresses');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers--customer_id--addresses"
                    onclick="cancelTryOut('POSTapi-v1-customers--customer_id--addresses');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers--customer_id--addresses"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers/{customer_id}/addresses</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="Billing"
               data-component="body">
    <br>
<p>Example: <code>Billing</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Billing</code></li> <li><code>Shipping</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>line1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="line1"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="2592 Jada Key"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>2592 Jada Key</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>line2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="line2"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="Apartment 504"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Apartment 504</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="South Josh"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>South Josh</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="state"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="Kentucky"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Kentucky</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="62249-8453"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>62249-8453</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="POSTapi-v1-customers--customer_id--addresses"
               value="United States"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>United States</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="POSTapi-v1-customers--customer_id--addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="POSTapi-v1-customers--customer_id--addresses"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-customers--customer_id--addresses" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="POSTapi-v1-customers--customer_id--addresses"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                    <h2 id="customer-addresses-GETapi-v1-customers--customer_id--addresses--id-">Get Customer Address</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customers--customer_id--addresses--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customers/1/addresses/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/addresses/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/addresses/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/addresses/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_id--addresses--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Billing&quot;,
        &quot;name&quot;: &quot;Walsh-Runte&quot;,
        &quot;line1&quot;: &quot;927 Gerson Village Apt. 946&quot;,
        &quot;line2&quot;: null,
        &quot;city&quot;: &quot;Port Wandabury&quot;,
        &quot;state&quot;: &quot;Florida&quot;,
        &quot;postal_code&quot;: &quot;44567&quot;,
        &quot;country&quot;: &quot;United States&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_id--addresses--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_id--addresses--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_id--addresses--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_id--addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_id--addresses--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_id--addresses--id-" data-method="GET"
      data-path="api/v1/customers/{customer_id}/addresses/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_id--addresses--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_id--addresses--id-"
                    onclick="tryItOut('GETapi-v1-customers--customer_id--addresses--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_id--addresses--id-"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_id--addresses--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_id--addresses--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_id}/addresses/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_id--addresses--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_id--addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_id--addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-customers--customer_id--addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-customers--customer_id--addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the address. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="customer-addresses-PUTapi-v1-customers--customer_id--addresses--id-">Update Customer Address</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customers--customer_id--addresses--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customers/1/addresses/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"Billing\",
    \"name\": \"John Doe\",
    \"line1\": \"2592 Jada Key\",
    \"line2\": \"Apartment 504\",
    \"city\": \"South Josh\",
    \"state\": \"Kentucky\",
    \"postal_code\": \"62249-8453\",
    \"country\": \"United States\",
    \"is_default\": false
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customers/1/addresses/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "Billing",
    "name": "John Doe",
    "line1": "2592 Jada Key",
    "line2": "Apartment 504",
    "city": "South Josh",
    "state": "Kentucky",
    "postal_code": "62249-8453",
    "country": "United States",
    "is_default": false
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customers/1/addresses/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'Billing',
            'name' =&gt; 'John Doe',
            'line1' =&gt; '2592 Jada Key',
            'line2' =&gt; 'Apartment 504',
            'city' =&gt; 'South Josh',
            'state' =&gt; 'Kentucky',
            'postal_code' =&gt; '62249-8453',
            'country' =&gt; 'United States',
            'is_default' =&gt; false,
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customers/1/addresses/1'
payload = {
    "type": "Billing",
    "name": "John Doe",
    "line1": "2592 Jada Key",
    "line2": "Apartment 504",
    "city": "South Josh",
    "state": "Kentucky",
    "postal_code": "62249-8453",
    "country": "United States",
    "is_default": false
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customers--customer_id--addresses--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;type&quot;: &quot;Billing&quot;,
        &quot;name&quot;: &quot;Leffler, Auer and Kirlin&quot;,
        &quot;line1&quot;: &quot;6271 Kihn Club&quot;,
        &quot;line2&quot;: null,
        &quot;city&quot;: &quot;Krajcikfort&quot;,
        &quot;state&quot;: &quot;New Hampshire&quot;,
        &quot;postal_code&quot;: &quot;15256-1660&quot;,
        &quot;country&quot;: &quot;United States&quot;,
        &quot;is_default&quot;: true,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customers--customer_id--addresses--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customers--customer_id--addresses--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customers--customer_id--addresses--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customers--customer_id--addresses--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customers--customer_id--addresses--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customers--customer_id--addresses--id-" data-method="PUT"
      data-path="api/v1/customers/{customer_id}/addresses/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customers--customer_id--addresses--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customers--customer_id--addresses--id-"
                    onclick="tryItOut('PUTapi-v1-customers--customer_id--addresses--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customers--customer_id--addresses--id-"
                    onclick="cancelTryOut('PUTapi-v1-customers--customer_id--addresses--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customers--customer_id--addresses--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customers/{customer_id}/addresses/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customers/{customer_id}/addresses/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer. Example: <code>1</code></p>
            </div>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the address. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="type"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="Billing"
               data-component="body">
    <br>
<p>Example: <code>Billing</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Billing</code></li> <li><code>Shipping</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="John Doe"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>John Doe</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>line1</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="line1"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="2592 Jada Key"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>2592 Jada Key</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>line2</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="line2"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="Apartment 504"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Apartment 504</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>city</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="city"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="South Josh"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>South Josh</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>state</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="state"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="Kentucky"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Kentucky</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>postal_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="postal_code"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="62249-8453"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>62249-8453</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>country</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="country"                data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
               value="United States"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>United States</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_default</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
                <label data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="true"
                   data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-" style="display: none">
            <input type="radio" name="is_default"
                   value="false"
                   data-endpoint="PUTapi-v1-customers--customer_id--addresses--id-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
        </div>
        </form>

                <h1 id="customer-categories">Customer Categories</h1>

    <p>Customer Category Management API</p>

                                <h2 id="customer-categories-GETapi-v1-customer-categories">List Customer Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customer-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customer-categories?search=Freelance&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customer-categories"
);

const params = {
    "search": "Freelance",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customer-categories';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'Freelance',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customer-categories'
params = {
  'search': 'Freelance',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customer-categories">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Est&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Illo&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customer-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customer-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customer-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customer-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customer-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customer-categories" data-method="GET"
      data-path="api/v1/customer-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customer-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customer-categories"
                    onclick="tryItOut('GETapi-v1-customer-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customer-categories"
                    onclick="cancelTryOut('GETapi-v1-customer-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customer-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customer-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customer-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customer-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customer-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-customer-categories"
               value="Freelance"
               data-component="query">
    <br>
<p>Search for customer categories by name Example: <code>Freelance</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customer-categories"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customer-categories"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="customer-categories-POSTapi-v1-customer-categories">Create Customer Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-customer-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/customer-categories" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Freelance\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customer-categories"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Freelance"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customer-categories';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Freelance',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customer-categories'
payload = {
    "name": "Freelance"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customer-categories">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Dolor&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customer-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customer-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customer-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customer-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customer-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customer-categories" data-method="POST"
      data-path="api/v1/customer-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customer-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customer-categories"
                    onclick="tryItOut('POSTapi-v1-customer-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customer-categories"
                    onclick="cancelTryOut('POSTapi-v1-customer-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customer-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customer-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customer-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customer-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customer-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customer-categories"
               value="Freelance"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Freelance</code></p>
        </div>
        </form>

                    <h2 id="customer-categories-GETapi-v1-customer-categories--id-">Get Customer Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-customer-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/customer-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customer-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customer-categories/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customer-categories/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customer-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Velit&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customer-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customer-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customer-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customer-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customer-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customer-categories--id-" data-method="GET"
      data-path="api/v1/customer-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customer-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customer-categories--id-"
                    onclick="tryItOut('GETapi-v1-customer-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customer-categories--id-"
                    onclick="cancelTryOut('GETapi-v1-customer-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customer-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customer-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customer-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customer-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customer-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-customer-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="customer-categories-PUTapi-v1-customer-categories--id-">Update Customer Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customer-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customer-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Freelance\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customer-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Freelance"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customer-categories/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Freelance',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customer-categories/1'
payload = {
    "name": "Freelance"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customer-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ut&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customer-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customer-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customer-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customer-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customer-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customer-categories--id-" data-method="PUT"
      data-path="api/v1/customer-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customer-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customer-categories--id-"
                    onclick="tryItOut('PUTapi-v1-customer-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customer-categories--id-"
                    onclick="cancelTryOut('PUTapi-v1-customer-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customer-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customer-categories/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customer-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customer-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customer-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customer-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-customer-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the customer category. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-customer-categories--id-"
               value="Freelance"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Freelance</code></p>
        </div>
        </form>

                    <h2 id="customer-categories-PUTapi-v1-customer-categories-sync">Sync Customer Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-customer-categories-sync">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/customer-categories-sync" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 1,
    \"category_ids\": [
        1
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/customer-categories-sync"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 1,
    "category_ids": [
        1
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/customer-categories-sync';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'customer_id' =&gt; 1,
            'category_ids' =&gt; [
                1,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/customer-categories-sync'
payload = {
    "customer_id": 1,
    "category_ids": [
        1
    ]
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-customer-categories-sync">
            <blockquote>
            <p>Example response (204, Customer Categories Synced):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-customer-categories-sync" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-customer-categories-sync"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-customer-categories-sync"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-customer-categories-sync" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-customer-categories-sync">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-customer-categories-sync" data-method="PUT"
      data-path="api/v1/customer-categories-sync"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-customer-categories-sync', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-customer-categories-sync"
                    onclick="tryItOut('PUTapi-v1-customer-categories-sync');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-customer-categories-sync"
                    onclick="cancelTryOut('PUTapi-v1-customer-categories-sync');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-customer-categories-sync"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/customer-categories-sync</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-customer-categories-sync"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-customer-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-customer-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="PUTapi-v1-customer-categories-sync"
               value="1"
               data-component="body">
    <br>
<p>Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_ids[0]"                data-endpoint="PUTapi-v1-customer-categories-sync"
               data-component="body">
        <input type="number" style="display: none"
               name="category_ids[1]"                data-endpoint="PUTapi-v1-customer-categories-sync"
               data-component="body">
    <br>

        </div>
        </form>

                <h1 id="products">Products</h1>

    <p>Product Management API</p>

                                <h2 id="products-GETapi-v1-products">List Products</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/products?search=Website+Hosting&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/products"
);

const params = {
    "search": "Website Hosting",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/products';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'Website Hosting',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/products'
params = {
  'search': 'Website Hosting',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-products">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Est Illo Velit&quot;,
            &quot;sku&quot;: &quot;OC7JTFAPHHZKGMUA&quot;,
            &quot;description&quot;: &quot;Magni explicabo non aperiam magnam. Voluptas voluptas eum quisquam facere quia. Adipisci aut perferendis nam quo quae expedita.&quot;,
            &quot;unit_type&quot;: &quot;kg&quot;,
            &quot;unit_price&quot;: 29.63,
            &quot;supplier&quot;: &quot;Ondricka, Kulas and Russel&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Cumque Commodi Maiores&quot;,
            &quot;sku&quot;: &quot;HVGGOOZY2GARBOYR&quot;,
            &quot;description&quot;: &quot;Et facere provident nostrum necessitatibus. Nesciunt quos cupiditate quaerat in. Unde sit tempora nobis natus aut eos facere.&quot;,
            &quot;unit_type&quot;: &quot;cm&quot;,
            &quot;unit_price&quot;: 22.27,
            &quot;supplier&quot;: &quot;Veum-Dare&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-products" data-method="GET"
      data-path="api/v1/products"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-products"
                    onclick="tryItOut('GETapi-v1-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-products"
                    onclick="cancelTryOut('GETapi-v1-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-products"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-products"
               value="Website Hosting"
               data-component="query">
    <br>
<p>Search for products by name or sku Example: <code>Website Hosting</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-products"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-products"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="products-POSTapi-v1-products">Create Product</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-products">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/products" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Website Hosting\",
    \"sku\": \"WH-001\",
    \"description\": \"Standard website hosting on shared server\",
    \"unit_type\": \"Each\",
    \"unit_price\": 12.99,
    \"supplier\": \"Drip Dropz Ltd\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/products"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Website Hosting",
    "sku": "WH-001",
    "description": "Standard website hosting on shared server",
    "unit_type": "Each",
    "unit_price": 12.99,
    "supplier": "Drip Dropz Ltd"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/products';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Website Hosting',
            'sku' =&gt; 'WH-001',
            'description' =&gt; 'Standard website hosting on shared server',
            'unit_type' =&gt; 'Each',
            'unit_price' =&gt; 12.99,
            'supplier' =&gt; 'Drip Dropz Ltd',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/products'
payload = {
    "name": "Website Hosting",
    "sku": "WH-001",
    "description": "Standard website hosting on shared server",
    "unit_type": "Each",
    "unit_price": 12.99,
    "supplier": "Drip Dropz Ltd"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-products">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Dolor Ut Et&quot;,
        &quot;sku&quot;: &quot;EA0N7TVLZKC4EOVY&quot;,
        &quot;description&quot;: &quot;Est ea asperiores ullam quasi delectus dolorem. Quo illo deserunt laborum voluptas sed. Nihil sed illo est illo velit debitis magni explicabo.&quot;,
        &quot;unit_type&quot;: &quot;cm&quot;,
        &quot;unit_price&quot;: 70.22,
        &quot;supplier&quot;: &quot;Rosenbaum, Streich and Stroman&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-products" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-products"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-products"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-products" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-products">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-products" data-method="POST"
      data-path="api/v1/products"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-products', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-products"
                    onclick="tryItOut('POSTapi-v1-products');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-products"
                    onclick="cancelTryOut('POSTapi-v1-products');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-products"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/products</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-products"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-products"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-products"
               value="Website Hosting"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Website Hosting</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sku</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sku"                data-endpoint="POSTapi-v1-products"
               value="WH-001"
               data-component="body">
    <br>
<p>Must not be greater than 32 characters. Example: <code>WH-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-products"
               value="Standard website hosting on shared server"
               data-component="body">
    <br>
<p>Example: <code>Standard website hosting on shared server</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="unit_type"                data-endpoint="POSTapi-v1-products"
               value="Each"
               data-component="body">
    <br>
<p>Must not be greater than 16 characters. Example: <code>Each</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_price"                data-endpoint="POSTapi-v1-products"
               value="12.99"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>12.99</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>supplier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="supplier"                data-endpoint="POSTapi-v1-products"
               value="Drip Dropz Ltd"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>Drip Dropz Ltd</code></p>
        </div>
        </form>

                    <h2 id="products-GETapi-v1-products--id-">Get Product</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-products--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/products/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/products/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/products/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/products/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-products--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Debitis Magni Explicabo&quot;,
        &quot;sku&quot;: &quot;XGZFNY8VBCHHRSVJ&quot;,
        &quot;description&quot;: &quot;Aperiam magnam enim voluptas voluptas eum. Facere quia odio adipisci aut perferendis. Quo quae expedita earum laborum aut eveniet.&quot;,
        &quot;unit_type&quot;: &quot;each&quot;,
        &quot;unit_price&quot;: 27.37,
        &quot;supplier&quot;: &quot;Mohr LLC&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-products--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-products--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-products--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-products--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-products--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-products--id-" data-method="GET"
      data-path="api/v1/products/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-products--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-products--id-"
                    onclick="tryItOut('GETapi-v1-products--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-products--id-"
                    onclick="cancelTryOut('GETapi-v1-products--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-products--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/products/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-products--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-products--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-products--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-products--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="products-PUTapi-v1-products--id-">Update Product</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-products--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/products/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Website Hosting\",
    \"sku\": \"WH-001\",
    \"description\": \"Standard website hosting on shared server\",
    \"unit_type\": \"Each\",
    \"unit_price\": 12.99,
    \"supplier\": \"Drip Dropz Ltd\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/products/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Website Hosting",
    "sku": "WH-001",
    "description": "Standard website hosting on shared server",
    "unit_type": "Each",
    "unit_price": 12.99,
    "supplier": "Drip Dropz Ltd"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/products/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Website Hosting',
            'sku' =&gt; 'WH-001',
            'description' =&gt; 'Standard website hosting on shared server',
            'unit_type' =&gt; 'Each',
            'unit_price' =&gt; 12.99,
            'supplier' =&gt; 'Drip Dropz Ltd',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/products/1'
payload = {
    "name": "Website Hosting",
    "sku": "WH-001",
    "description": "Standard website hosting on shared server",
    "unit_type": "Each",
    "unit_price": 12.99,
    "supplier": "Drip Dropz Ltd"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-products--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Est Est Ea&quot;,
        &quot;sku&quot;: &quot;CUDJV2NNLAYSWGQA&quot;,
        &quot;description&quot;: &quot;Ullam quasi delectus dolorem consequatur quo illo deserunt. Voluptas sed veritatis nihil sed. Est illo velit debitis magni explicabo.&quot;,
        &quot;unit_type&quot;: &quot;cm&quot;,
        &quot;unit_price&quot;: 70.03,
        &quot;supplier&quot;: &quot;Rosenbaum, Streich and Stroman&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-products--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-products--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-products--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-products--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-products--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-products--id-" data-method="PUT"
      data-path="api/v1/products/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-products--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-products--id-"
                    onclick="tryItOut('PUTapi-v1-products--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-products--id-"
                    onclick="cancelTryOut('PUTapi-v1-products--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-products--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/products/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/products/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-products--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-products--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-products--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-products--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-products--id-"
               value="Website Hosting"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Website Hosting</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sku</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="sku"                data-endpoint="PUTapi-v1-products--id-"
               value="WH-001"
               data-component="body">
    <br>
<p>Must not be greater than 32 characters. Example: <code>WH-001</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-products--id-"
               value="Standard website hosting on shared server"
               data-component="body">
    <br>
<p>Example: <code>Standard website hosting on shared server</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_type</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="unit_type"                data-endpoint="PUTapi-v1-products--id-"
               value="Each"
               data-component="body">
    <br>
<p>Must not be greater than 16 characters. Example: <code>Each</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_price"                data-endpoint="PUTapi-v1-products--id-"
               value="12.99"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>12.99</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>supplier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="supplier"                data-endpoint="PUTapi-v1-products--id-"
               value="Drip Dropz Ltd"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>Drip Dropz Ltd</code></p>
        </div>
        </form>

                <h1 id="product-categories">Product Categories</h1>

    <p>Product Category Management API</p>

                                <h2 id="product-categories-GETapi-v1-product-categories">List Product Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-product-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/product-categories?search=Hosting&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/product-categories"
);

const params = {
    "search": "Hosting",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/product-categories';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'Hosting',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/product-categories'
params = {
  'search': 'Hosting',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-product-categories">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Magni&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Explicabo&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-product-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-product-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-product-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-product-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-product-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-product-categories" data-method="GET"
      data-path="api/v1/product-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-product-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-product-categories"
                    onclick="tryItOut('GETapi-v1-product-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-product-categories"
                    onclick="cancelTryOut('GETapi-v1-product-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-product-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/product-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-product-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-product-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-product-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-product-categories"
               value="Hosting"
               data-component="query">
    <br>
<p>Search for product categories by name Example: <code>Hosting</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-product-categories"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-product-categories"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="product-categories-POSTapi-v1-product-categories">Create Product Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-product-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/product-categories" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Hosting\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/product-categories"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Hosting"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/product-categories';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Hosting',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/product-categories'
payload = {
    "name": "Hosting"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-product-categories">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Et&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-product-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-product-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-product-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-product-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-product-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-product-categories" data-method="POST"
      data-path="api/v1/product-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-product-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-product-categories"
                    onclick="tryItOut('POSTapi-v1-product-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-product-categories"
                    onclick="cancelTryOut('POSTapi-v1-product-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-product-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/product-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-product-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-product-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-product-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-product-categories"
               value="Hosting"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Hosting</code></p>
        </div>
        </form>

                    <h2 id="product-categories-GETapi-v1-product-categories--id-">Get Product Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-product-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/product-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/product-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/product-categories/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/product-categories/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-product-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Aperiam&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-product-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-product-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-product-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-product-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-product-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-product-categories--id-" data-method="GET"
      data-path="api/v1/product-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-product-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-product-categories--id-"
                    onclick="tryItOut('GETapi-v1-product-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-product-categories--id-"
                    onclick="cancelTryOut('GETapi-v1-product-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-product-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/product-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-product-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-product-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-product-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-product-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="product-categories-PUTapi-v1-product-categories--id-">Update Product Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-product-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/product-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Hosting\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/product-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Hosting"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/product-categories/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Hosting',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/product-categories/1'
payload = {
    "name": "Hosting"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-product-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Asperiores&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-product-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-product-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-product-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-product-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-product-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-product-categories--id-" data-method="PUT"
      data-path="api/v1/product-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-product-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-product-categories--id-"
                    onclick="tryItOut('PUTapi-v1-product-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-product-categories--id-"
                    onclick="cancelTryOut('PUTapi-v1-product-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-product-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/product-categories/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/product-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-product-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-product-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-product-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-product-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the product category. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-product-categories--id-"
               value="Hosting"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Hosting</code></p>
        </div>
        </form>

                    <h2 id="product-categories-PUTapi-v1-product-categories-sync">Sync Customer Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-product-categories-sync">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/product-categories-sync" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"product_id\": 1,
    \"category_ids\": [
        1
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/product-categories-sync"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "product_id": 1,
    "category_ids": [
        1
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/product-categories-sync';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'product_id' =&gt; 1,
            'category_ids' =&gt; [
                1,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/product-categories-sync'
payload = {
    "product_id": 1,
    "category_ids": [
        1
    ]
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-product-categories-sync">
            <blockquote>
            <p>Example response (204, Product Categories Synced):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-product-categories-sync" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-product-categories-sync"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-product-categories-sync"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-product-categories-sync" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-product-categories-sync">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-product-categories-sync" data-method="PUT"
      data-path="api/v1/product-categories-sync"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-product-categories-sync', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-product-categories-sync"
                    onclick="tryItOut('PUTapi-v1-product-categories-sync');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-product-categories-sync"
                    onclick="cancelTryOut('PUTapi-v1-product-categories-sync');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-product-categories-sync"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/product-categories-sync</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-product-categories-sync"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-product-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-product-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="product_id"                data-endpoint="PUTapi-v1-product-categories-sync"
               value="1"
               data-component="body">
    <br>
<p>Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_ids[0]"                data-endpoint="PUTapi-v1-product-categories-sync"
               data-component="body">
        <input type="number" style="display: none"
               name="category_ids[1]"                data-endpoint="PUTapi-v1-product-categories-sync"
               data-component="body">
    <br>

        </div>
        </form>

                <h1 id="services">Services</h1>

    <p>Service Management API</p>

                                <h2 id="services-GETapi-v1-services">List Services</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-services">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/services?search=Software+Development&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/services"
);

const params = {
    "search": "Software Development",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/services';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'Software Development',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/services'
params = {
  'search': 'Software Development',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-services">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Non Aperiam Magnam&quot;,
            &quot;description&quot;: &quot;Voluptas voluptas eum quisquam facere quia. Adipisci aut perferendis nam quo quae expedita. Laborum aut eveniet voluptatem aspernatur cumque.&quot;,
            &quot;unit_price&quot;: 72.03,
            &quot;supplier&quot;: &quot;Tromp, Funk and Shanahan&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Provident Nostrum Necessitatibus&quot;,
            &quot;description&quot;: &quot;Nesciunt quos cupiditate quaerat in. Unde sit tempora nobis natus aut eos facere. Quaerat ut reiciendis delectus voluptas culpa minima.&quot;,
            &quot;unit_price&quot;: 22.32,
            &quot;supplier&quot;: &quot;Runolfsson LLC&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-services" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-services"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-services"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-services" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-services">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-services" data-method="GET"
      data-path="api/v1/services"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-services', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-services"
                    onclick="tryItOut('GETapi-v1-services');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-services"
                    onclick="cancelTryOut('GETapi-v1-services');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-services"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/services</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-services"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-services"
               value="Software Development"
               data-component="query">
    <br>
<p>Search for services by name Example: <code>Software Development</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-services"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-services"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="services-POSTapi-v1-services">Create Service</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-services">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/services" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Software Development\",
    \"description\": \"Rate for software development\",
    \"unit_price\": 40.5,
    \"supplier\": \"Cardano Mercury Ltd\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/services"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Software Development",
    "description": "Rate for software development",
    "unit_price": 40.5,
    "supplier": "Cardano Mercury Ltd"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/services';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Software Development',
            'description' =&gt; 'Rate for software development',
            'unit_price' =&gt; 40.5,
            'supplier' =&gt; 'Cardano Mercury Ltd',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/services'
payload = {
    "name": "Software Development",
    "description": "Rate for software development",
    "unit_price": 40.5,
    "supplier": "Cardano Mercury Ltd"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-services">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Asperiores Ullam Quasi&quot;,
        &quot;description&quot;: &quot;Dolorem consequatur quo illo deserunt laborum voluptas sed veritatis. Sed illo est illo velit. Magni explicabo non aperiam magnam.&quot;,
        &quot;unit_price&quot;: 27.22,
        &quot;supplier&quot;: &quot;Stroman LLC&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-services" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-services"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-services"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-services" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-services">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-services" data-method="POST"
      data-path="api/v1/services"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-services', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-services"
                    onclick="tryItOut('POSTapi-v1-services');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-services"
                    onclick="cancelTryOut('POSTapi-v1-services');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-services"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/services</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-services"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-services"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-services"
               value="Software Development"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Software Development</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="POSTapi-v1-services"
               value="Rate for software development"
               data-component="body">
    <br>
<p>Example: <code>Rate for software development</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_price"                data-endpoint="POSTapi-v1-services"
               value="40.5"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>40.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>supplier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="supplier"                data-endpoint="POSTapi-v1-services"
               value="Cardano Mercury Ltd"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>Cardano Mercury Ltd</code></p>
        </div>
        </form>

                    <h2 id="services-GETapi-v1-services--id-">Get Service</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-services--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/services/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/services/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/services/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/services/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-services--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Enim Voluptas Voluptas&quot;,
        &quot;description&quot;: &quot;Quisquam facere quia odio adipisci aut perferendis nam. Quae expedita earum laborum aut. Voluptatem aspernatur cumque commodi maiores aspernatur.&quot;,
        &quot;unit_price&quot;: 72.65,
        &quot;supplier&quot;: &quot;Hahn-Murphy&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-services--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-services--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-services--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-services--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-services--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-services--id-" data-method="GET"
      data-path="api/v1/services/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-services--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-services--id-"
                    onclick="tryItOut('GETapi-v1-services--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-services--id-"
                    onclick="cancelTryOut('GETapi-v1-services--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-services--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/services/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-services--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-services--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-services--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-services--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the service. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="services-PUTapi-v1-services--id-">Update Service</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-services--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/services/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Software Development\",
    \"description\": \"Rate for software development\",
    \"unit_price\": 40.5,
    \"supplier\": \"Cardano Mercury Ltd\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/services/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Software Development",
    "description": "Rate for software development",
    "unit_price": 40.5,
    "supplier": "Cardano Mercury Ltd"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/services/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Software Development',
            'description' =&gt; 'Rate for software development',
            'unit_price' =&gt; 40.5,
            'supplier' =&gt; 'Cardano Mercury Ltd',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/services/1'
payload = {
    "name": "Software Development",
    "description": "Rate for software development",
    "unit_price": 40.5,
    "supplier": "Cardano Mercury Ltd"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-services--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Delectus Dolorem Consequatur&quot;,
        &quot;description&quot;: &quot;Illo deserunt laborum voluptas sed. Nihil sed illo est illo velit debitis magni explicabo. Aperiam magnam enim voluptas voluptas eum.&quot;,
        &quot;unit_price&quot;: 32.7,
        &quot;supplier&quot;: &quot;Brekke-Ratke&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-services--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-services--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-services--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-services--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-services--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-services--id-" data-method="PUT"
      data-path="api/v1/services/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-services--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-services--id-"
                    onclick="tryItOut('PUTapi-v1-services--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-services--id-"
                    onclick="cancelTryOut('PUTapi-v1-services--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-services--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/services/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/services/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-services--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-services--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-services--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-services--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the service. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-services--id-"
               value="Software Development"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Software Development</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="description"                data-endpoint="PUTapi-v1-services--id-"
               value="Rate for software development"
               data-component="body">
    <br>
<p>Example: <code>Rate for software development</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="unit_price"                data-endpoint="PUTapi-v1-services--id-"
               value="40.5"
               data-component="body">
    <br>
<p>Must be at least 0. Example: <code>40.5</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>supplier</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="supplier"                data-endpoint="PUTapi-v1-services--id-"
               value="Cardano Mercury Ltd"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>Cardano Mercury Ltd</code></p>
        </div>
        </form>

                <h1 id="service-categories">Service Categories</h1>

    <p>Service Category Management API</p>

                                <h2 id="service-categories-GETapi-v1-service-categories">List Service Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-service-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/service-categories?search=Online+Support&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/service-categories"
);

const params = {
    "search": "Online Support",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/service-categories';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'search' =&gt; 'Online Support',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/service-categories'
params = {
  'search': 'Online Support',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-service-categories">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Magnam&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Enim&quot;,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-service-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-service-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-service-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-service-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-service-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-service-categories" data-method="GET"
      data-path="api/v1/service-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-service-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-service-categories"
                    onclick="tryItOut('GETapi-v1-service-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-service-categories"
                    onclick="cancelTryOut('GETapi-v1-service-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-service-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/service-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-service-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-service-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-service-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="search"                data-endpoint="GETapi-v1-service-categories"
               value="Online Support"
               data-component="query">
    <br>
<p>Search for service categories by name Example: <code>Online Support</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-service-categories"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-service-categories"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="service-categories-POSTapi-v1-service-categories">Create Service Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-POSTapi-v1-service-categories">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/service-categories" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Online Support\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/service-categories"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Online Support"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/service-categories';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Online Support',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/service-categories'
payload = {
    "name": "Online Support"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-service-categories">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Ullam&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-service-categories" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-service-categories"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-service-categories"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-service-categories" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-service-categories">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-service-categories" data-method="POST"
      data-path="api/v1/service-categories"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-service-categories', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-service-categories"
                    onclick="tryItOut('POSTapi-v1-service-categories');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-service-categories"
                    onclick="cancelTryOut('POSTapi-v1-service-categories');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-service-categories"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/service-categories</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-service-categories"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-service-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-service-categories"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-service-categories"
               value="Online Support"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Online Support</code></p>
        </div>
        </form>

                    <h2 id="service-categories-GETapi-v1-service-categories--id-">Get Service Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-service-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/service-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/service-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/service-categories/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/service-categories/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-service-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Voluptas&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-service-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-service-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-service-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-service-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-service-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-service-categories--id-" data-method="GET"
      data-path="api/v1/service-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-service-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-service-categories--id-"
                    onclick="tryItOut('GETapi-v1-service-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-service-categories--id-"
                    onclick="cancelTryOut('GETapi-v1-service-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-service-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/service-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-service-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-service-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-service-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-service-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the service category. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="service-categories-PUTapi-v1-service-categories--id-">Update Service Category</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-service-categories--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/service-categories/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Online Support\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/service-categories/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Online Support"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/service-categories/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'name' =&gt; 'Online Support',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/service-categories/1'
payload = {
    "name": "Online Support"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-service-categories--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;name&quot;: &quot;Quasi&quot;,
        &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
        &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-service-categories--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-service-categories--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-service-categories--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-service-categories--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-service-categories--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-service-categories--id-" data-method="PUT"
      data-path="api/v1/service-categories/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-service-categories--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-service-categories--id-"
                    onclick="tryItOut('PUTapi-v1-service-categories--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-service-categories--id-"
                    onclick="cancelTryOut('PUTapi-v1-service-categories--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-service-categories--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/service-categories/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/service-categories/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-service-categories--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-service-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-service-categories--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-service-categories--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the service category. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PUTapi-v1-service-categories--id-"
               value="Online Support"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 64 characters. Example: <code>Online Support</code></p>
        </div>
        </form>

                    <h2 id="service-categories-PUTapi-v1-service-categories-sync">Sync Service Categories</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-PUTapi-v1-service-categories-sync">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/service-categories-sync" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"service_id\": 1,
    \"category_ids\": [
        1
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/service-categories-sync"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "service_id": 1,
    "category_ids": [
        1
    ]
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/service-categories-sync';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'service_id' =&gt; 1,
            'category_ids' =&gt; [
                1,
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/service-categories-sync'
payload = {
    "service_id": 1,
    "category_ids": [
        1
    ]
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-service-categories-sync">
            <blockquote>
            <p>Example response (204, Service Categories Synced):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-service-categories-sync" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-service-categories-sync"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-service-categories-sync"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-service-categories-sync" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-service-categories-sync">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-service-categories-sync" data-method="PUT"
      data-path="api/v1/service-categories-sync"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-service-categories-sync', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-service-categories-sync"
                    onclick="tryItOut('PUTapi-v1-service-categories-sync');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-service-categories-sync"
                    onclick="cancelTryOut('PUTapi-v1-service-categories-sync');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-service-categories-sync"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/service-categories-sync</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-service-categories-sync"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-service-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-service-categories-sync"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>service_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="service_id"                data-endpoint="PUTapi-v1-service-categories-sync"
               value="1"
               data-component="body">
    <br>
<p>Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>category_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="category_ids[0]"                data-endpoint="PUTapi-v1-service-categories-sync"
               data-component="body">
        <input type="number" style="display: none"
               name="category_ids[1]"                data-endpoint="PUTapi-v1-service-categories-sync"
               data-component="body">
    <br>

        </div>
        </form>

                <h1 id="invoices">Invoices</h1>

    <p>Invoice Management API</p>

                                <h2 id="invoices-GETapi-v1-invoices">List Invoices</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-invoices">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/invoices?customer_id=1234&amp;per_page=25&amp;page=1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoices"
);

const params = {
    "customer_id": "1234",
    "per_page": "25",
    "page": "1",
};
Object.keys(params)
    .forEach(key =&gt; url.searchParams.append(key, params[key]));

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoices';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'query' =&gt; [
            'customer_id' =&gt; '1234',
            'per_page' =&gt; '25',
            'page' =&gt; '1',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoices'
params = {
  'customer_id': '1234',
  'per_page': '25',
  'page': '1',
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers, params=params)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-invoices">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: 1,
            &quot;customer_id&quot;: 0,
            &quot;billing_address_id&quot;: 0,
            &quot;shipping_address_id&quot;: 0,
            &quot;invoice_reference&quot;: &quot;QLqBOqWxPA&quot;,
            &quot;customer_reference&quot;: &quot;INV-49055764&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;total&quot;: 0,
            &quot;issue_date&quot;: &quot;2025-04-11&quot;,
            &quot;due_date&quot;: &quot;2025-05-11&quot;,
            &quot;last_notified&quot;: null,
            &quot;is_overdue&quot;: false,
            &quot;status&quot;: &quot;Voided&quot;,
            &quot;created_at&quot;: &quot;2025-04-11 13:42:11&quot;,
            &quot;updated_at&quot;: &quot;2025-04-11 13:42:11&quot;,
            &quot;items&quot;: [
                {
                    &quot;product_id&quot;: null,
                    &quot;service_id&quot;: null,
                    &quot;sku&quot;: &quot;R9CB7C1OOOCRVDOI&quot;,
                    &quot;description&quot;: &quot;Itaque molestias enim accusamus labore.&quot;,
                    &quot;quantity&quot;: 2,
                    &quot;unit_price&quot;: 18.68,
                    &quot;tax_rate&quot;: 20
                },
                {
                    &quot;product_id&quot;: null,
                    &quot;service_id&quot;: null,
                    &quot;sku&quot;: &quot;XAWXYRYZNJJWKULN&quot;,
                    &quot;description&quot;: &quot;Aliquam voluptate aut et est ut dolorem.&quot;,
                    &quot;quantity&quot;: 2,
                    &quot;unit_price&quot;: 67.45,
                    &quot;tax_rate&quot;: 20
                }
            ]
        },
        {
            &quot;id&quot;: 1,
            &quot;customer_id&quot;: 0,
            &quot;billing_address_id&quot;: 0,
            &quot;shipping_address_id&quot;: 0,
            &quot;invoice_reference&quot;: &quot;QLqBOqWxPA&quot;,
            &quot;customer_reference&quot;: &quot;INV-45640787&quot;,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;total&quot;: 0,
            &quot;issue_date&quot;: &quot;2025-01-13&quot;,
            &quot;due_date&quot;: &quot;2025-02-12&quot;,
            &quot;last_notified&quot;: null,
            &quot;is_overdue&quot;: false,
            &quot;status&quot;: &quot;Draft&quot;,
            &quot;created_at&quot;: &quot;2025-01-13 14:50:54&quot;,
            &quot;updated_at&quot;: &quot;2025-01-13 14:50:54&quot;,
            &quot;items&quot;: [
                {
                    &quot;product_id&quot;: null,
                    &quot;service_id&quot;: null,
                    &quot;sku&quot;: &quot;9YNNHW0TTQ3EOHMW&quot;,
                    &quot;description&quot;: &quot;Voluptas molestiae provident quo similique sapiente porro ullam.&quot;,
                    &quot;quantity&quot;: 5,
                    &quot;unit_price&quot;: 11.95,
                    &quot;tax_rate&quot;: 20
                },
                {
                    &quot;product_id&quot;: null,
                    &quot;service_id&quot;: null,
                    &quot;sku&quot;: &quot;L1K8GXWOGGPZPB7U&quot;,
                    &quot;description&quot;: &quot;Similique cupiditate tenetur dolor rerum vel.&quot;,
                    &quot;quantity&quot;: 2,
                    &quot;unit_price&quot;: 39.6,
                    &quot;tax_rate&quot;: 20
                }
            ]
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;/?page=1&quot;,
        &quot;last&quot;: null,
        &quot;prev&quot;: null,
        &quot;next&quot;: null
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;current_page_url&quot;: &quot;/?page=1&quot;,
        &quot;from&quot;: 1,
        &quot;path&quot;: &quot;/&quot;,
        &quot;per_page&quot;: 25,
        &quot;to&quot;: 2
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-invoices" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-invoices"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-invoices"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-invoices" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-invoices">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-invoices" data-method="GET"
      data-path="api/v1/invoices"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-invoices', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-invoices"
                    onclick="tryItOut('GETapi-v1-invoices');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-invoices"
                    onclick="cancelTryOut('GETapi-v1-invoices');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-invoices"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/invoices</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-invoices"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-invoices"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-invoices"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_id"                data-endpoint="GETapi-v1-invoices"
               value="1234"
               data-component="query">
    <br>
<p>Filter invoices by customer id Example: <code>1234</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-invoices"
               value="25"
               data-component="query">
    <br>
<p>Number of results per page (Min 25, Max 100) Example: <code>25</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-invoices"
               value="1"
               data-component="query">
    <br>
<p>Page number Example: <code>1</code></p>
            </div>
                </form>

                    <h2 id="invoices-POSTapi-v1-invoices">Create Invoice</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>When invoices are created in <strong>Published</strong> status,
the invoice will go live and no further changes can be made and
email notifications will be sent to the specified <code>customer_email_ids</code> in the request body.</p>

<span id="example-requests-POSTapi-v1-invoices">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8100/api/v1/invoices" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 1,
    \"customer_email_ids\": [
        1
    ],
    \"billing_address_id\": 4,
    \"shipping_address_id\": 7,
    \"customer_reference\": \"INV-1234\",
    \"issue_date\": \"2024-01-31\",
    \"due_date\": \"2024-02-14\",
    \"items\": [
        {
            \"product_id\": 3,
            \"service_id\": null,
            \"sku\": \"ABC123\",
            \"description\": \"My product name\",
            \"quantity\": 1,
            \"unit_price\": 4.99,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": 4,
            \"sku\": null,
            \"description\": \"My service name\",
            \"quantity\": 3,
            \"unit_price\": 10.5,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": null,
            \"sku\": null,
            \"description\": \"My free text line (e.g. misc charges)\",
            \"quantity\": 1,
            \"unit_price\": 9.99,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": null,
            \"sku\": null,
            \"description\": \"Discount\",
            \"quantity\": 1,
            \"unit_price\": -5.99,
            \"tax_rate\": 0
        }
    ],
    \"save_mode\": \"Draft\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoices"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 1,
    "customer_email_ids": [
        1
    ],
    "billing_address_id": 4,
    "shipping_address_id": 7,
    "customer_reference": "INV-1234",
    "issue_date": "2024-01-31",
    "due_date": "2024-02-14",
    "items": [
        {
            "product_id": 3,
            "service_id": null,
            "sku": "ABC123",
            "description": "My product name",
            "quantity": 1,
            "unit_price": 4.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": 4,
            "sku": null,
            "description": "My service name",
            "quantity": 3,
            "unit_price": 10.5,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "My free text line (e.g. misc charges)",
            "quantity": 1,
            "unit_price": 9.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "Discount",
            "quantity": 1,
            "unit_price": -5.99,
            "tax_rate": 0
        }
    ],
    "save_mode": "Draft"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoices';
$response = $client-&gt;post(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'customer_id' =&gt; 1,
            'customer_email_ids' =&gt; [
                1,
            ],
            'billing_address_id' =&gt; 4,
            'shipping_address_id' =&gt; 7,
            'customer_reference' =&gt; 'INV-1234',
            'issue_date' =&gt; '2024-01-31',
            'due_date' =&gt; '2024-02-14',
            'items' =&gt; [
                [
                    'product_id' =&gt; 3,
                    'service_id' =&gt; null,
                    'sku' =&gt; 'ABC123',
                    'description' =&gt; 'My product name',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; 4.99,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; 4,
                    'sku' =&gt; null,
                    'description' =&gt; 'My service name',
                    'quantity' =&gt; 3,
                    'unit_price' =&gt; 10.5,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; null,
                    'sku' =&gt; null,
                    'description' =&gt; 'My free text line (e.g. misc charges)',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; 9.99,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; null,
                    'sku' =&gt; null,
                    'description' =&gt; 'Discount',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; -5.99,
                    'tax_rate' =&gt; 0,
                ],
            ],
            'save_mode' =&gt; 'Draft',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoices'
payload = {
    "customer_id": 1,
    "customer_email_ids": [
        1
    ],
    "billing_address_id": 4,
    "shipping_address_id": 7,
    "customer_reference": "INV-1234",
    "issue_date": "2024-01-31",
    "due_date": "2024-02-14",
    "items": [
        {
            "product_id": 3,
            "service_id": null,
            "sku": "ABC123",
            "description": "My product name",
            "quantity": 1,
            "unit_price": 4.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": 4,
            "sku": null,
            "description": "My service name",
            "quantity": 3,
            "unit_price": 10.5,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "My free text line (e.g. misc charges)",
            "quantity": 1,
            "unit_price": 9.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "Discount",
            "quantity": 1,
            "unit_price": -5.99,
            "tax_rate": 0
        }
    ],
    "save_mode": "Draft"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('POST', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-invoices">
            <blockquote>
            <p>Example response (201, Created):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;customer_id&quot;: 0,
        &quot;billing_address_id&quot;: 0,
        &quot;shipping_address_id&quot;: 0,
        &quot;invoice_reference&quot;: &quot;QLqBOqWxPA&quot;,
        &quot;customer_reference&quot;: &quot;INV-21949050&quot;,
        &quot;currency&quot;: &quot;USD&quot;,
        &quot;total&quot;: 0,
        &quot;issue_date&quot;: &quot;2024-07-04&quot;,
        &quot;due_date&quot;: &quot;2024-08-03&quot;,
        &quot;last_notified&quot;: null,
        &quot;is_overdue&quot;: false,
        &quot;status&quot;: &quot;Voided&quot;,
        &quot;created_at&quot;: &quot;2024-07-04 09:51:30&quot;,
        &quot;updated_at&quot;: &quot;2024-07-04 09:51:30&quot;,
        &quot;items&quot;: [
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;UFSXGRDG96F7S4EB&quot;,
                &quot;description&quot;: &quot;Consequatur quo illo deserunt laborum voluptas.&quot;,
                &quot;quantity&quot;: 2,
                &quot;unit_price&quot;: 69.55,
                &quot;tax_rate&quot;: 20
            },
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;EXBPAJO3OFJV4XJK&quot;,
                &quot;description&quot;: &quot;Sed illo est illo velit.&quot;,
                &quot;quantity&quot;: 3,
                &quot;unit_price&quot;: 92.66,
                &quot;tax_rate&quot;: 20
            }
        ],
        &quot;customer&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Bertrand Flatley&quot;,
            &quot;tax_number&quot;: &quot;01JWW81PZGGB17V3391KGKH6MY&quot;,
            &quot;tax_rate&quot;: 17.5,
            &quot;categories&quot;: [],
            &quot;default_email&quot;: null,
            &quot;default_phone&quot;: null,
            &quot;default_address&quot;: null,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;billing_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Billing&quot;,
            &quot;name&quot;: &quot;Streich-Stroman&quot;,
            &quot;line1&quot;: &quot;992 Erdman Plains&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Koelpinland&quot;,
            &quot;state&quot;: &quot;Arizona&quot;,
            &quot;postal_code&quot;: &quot;08133-2285&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;shipping_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Shipping&quot;,
            &quot;name&quot;: &quot;Ondricka, Kulas and Russel&quot;,
            &quot;line1&quot;: &quot;71972 Jacinto Roads&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Auroreside&quot;,
            &quot;state&quot;: &quot;Texas&quot;,
            &quot;postal_code&quot;: &quot;00397&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;notification_recipients&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Brandi Boyle DVM&quot;,
                &quot;address&quot;: &quot;ethyl.kertzmann@example.org&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Brant Dooley I&quot;,
                &quot;address&quot;: &quot;okessler@example.com&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            }
        ],
        &quot;payments&quot;: [
            {
                &quot;payment_date&quot;: &quot;04-06-2025&quot;,
                &quot;payment_method&quot;: &quot;Stripe&quot;,
                &quot;payment_currency&quot;: &quot;USD&quot;,
                &quot;payment_amount&quot;: 4186416.01,
                &quot;payment_reference&quot;: &quot;OIX9dcR8GzoXTyua&quot;,
                &quot;crypto_asset_name&quot;: null,
                &quot;crypto_asset_ada_price&quot;: null,
                &quot;crypto_asset_quantity&quot;: 0,
                &quot;crypto_wallet_name&quot;: null,
                &quot;crypto_payment_ttl&quot;: null,
                &quot;crypto_payment_recipient_address&quot;: null,
                &quot;crypto_payment_last_checked&quot;: null,
                &quot;crypto_payment_process_attempts&quot;: null,
                &quot;crypto_payment_last_error&quot;: null,
                &quot;status&quot;: &quot;Pending&quot;
            }
        ],
        &quot;activities&quot;: [
            {
                &quot;activity&quot;: &quot;Created new invoice&quot;,
                &quot;when&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;diff&quot;: &quot;0 seconds ago&quot;
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-invoices" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-invoices"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-invoices"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-invoices" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-invoices">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-invoices" data-method="POST"
      data-path="api/v1/invoices"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-invoices', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-invoices"
                    onclick="tryItOut('POSTapi-v1-invoices');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-invoices"
                    onclick="cancelTryOut('POSTapi-v1-invoices');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-invoices"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/invoices</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-invoices"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-invoices"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-invoices"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_id"                data-endpoint="POSTapi-v1-invoices"
               value="1"
               data-component="body">
    <br>
<p>ID of the customer, whom the invoice is being generated for. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_email_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_email_ids[0]"                data-endpoint="POSTapi-v1-invoices"
               data-component="body">
        <input type="number" style="display: none"
               name="customer_email_ids[1]"                data-endpoint="POSTapi-v1-invoices"
               data-component="body">
    <br>
<p>Array of email IDs associated with the customer (who will receive email notifications).</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_address_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="billing_address_id"                data-endpoint="POSTapi-v1-invoices"
               value="4"
               data-component="body">
    <br>
<p>ID of the customer address, to be used as billing address. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_address_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="shipping_address_id"                data-endpoint="POSTapi-v1-invoices"
               value="7"
               data-component="body">
    <br>
<p>ID of the customer address, to be used as shipping address. Example: <code>7</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="customer_reference"                data-endpoint="POSTapi-v1-invoices"
               value="INV-1234"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>INV-1234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>issue_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="issue_date"                data-endpoint="POSTapi-v1-invoices"
               value="2024-01-31"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2024-01-31</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="POSTapi-v1-invoices"
               value="2024-02-14"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2024-02-14</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.product_id"                data-endpoint="POSTapi-v1-invoices"
               value="7"
               data-component="body">
    <br>
<p>Optional: ID of the product you are selling. Example: <code>7</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>service_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.service_id"                data-endpoint="POSTapi-v1-invoices"
               value="3"
               data-component="body">
    <br>
<p>Optional: ID of the service you are selling. Example: <code>3</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>sku</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="items.0.sku"                data-endpoint="POSTapi-v1-invoices"
               value="XYZ-1234"
               data-component="body">
    <br>
<p>Optional: Stock Keeping Unit of the product you are selling (related to product_id). Must not be greater than 32 characters. Example: <code>XYZ-1234</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="items.0.description"                data-endpoint="POSTapi-v1-invoices"
               value="My free text line (e.g. misc charges)"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 1024 characters. Example: <code>My free text line (e.g. misc charges)</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>quantity</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.quantity"                data-endpoint="POSTapi-v1-invoices"
               value="10"
               data-component="body">
    <br>
<p>Must be at least 0.000001. Example: <code>10</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.unit_price"                data-endpoint="POSTapi-v1-invoices"
               value="4.99"
               data-component="body">
    <br>
<p>Price of single unit, can be negative value (for giving discount). Example: <code>4.99</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>tax_rate</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.tax_rate"                data-endpoint="POSTapi-v1-invoices"
               value="22.5"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>22.5</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>save_mode</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="save_mode"                data-endpoint="POSTapi-v1-invoices"
               value="Draft"
               data-component="body">
    <br>
<p>Example: <code>Draft</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Draft</code></li> <li><code>Publish</code></li></ul>
        </div>
        </form>

                    <h2 id="invoices-GETapi-v1-invoices--id-">Get Invoice</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>



<span id="example-requests-GETapi-v1-invoices--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8100/api/v1/invoices/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoices/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoices/1';
$response = $client-&gt;get(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoices/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('GET', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-invoices--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;customer_id&quot;: 0,
        &quot;billing_address_id&quot;: 0,
        &quot;shipping_address_id&quot;: 0,
        &quot;invoice_reference&quot;: &quot;QLqBOqWxPA&quot;,
        &quot;customer_reference&quot;: &quot;INV-77253998&quot;,
        &quot;currency&quot;: &quot;USD&quot;,
        &quot;total&quot;: 0,
        &quot;issue_date&quot;: &quot;2024-12-02&quot;,
        &quot;due_date&quot;: &quot;2025-01-01&quot;,
        &quot;last_notified&quot;: null,
        &quot;is_overdue&quot;: false,
        &quot;status&quot;: &quot;Voided&quot;,
        &quot;created_at&quot;: &quot;2024-12-02 21:59:42&quot;,
        &quot;updated_at&quot;: &quot;2024-12-02 21:59:42&quot;,
        &quot;items&quot;: [
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;TYH7SB1NTSMLFP2G&quot;,
                &quot;description&quot;: &quot;Magni explicabo non aperiam magnam.&quot;,
                &quot;quantity&quot;: 5,
                &quot;unit_price&quot;: 68.47,
                &quot;tax_rate&quot;: 20
            },
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;IEUTKZUQNOQCSU4U&quot;,
                &quot;description&quot;: &quot;Eum quisquam facere quia odio adipisci aut perferendis nam.&quot;,
                &quot;quantity&quot;: 4,
                &quot;unit_price&quot;: 91.49,
                &quot;tax_rate&quot;: 20
            }
        ],
        &quot;customer&quot;: {
            &quot;id&quot;: 1,
            &quot;name&quot;: &quot;Alexandrea Ferry&quot;,
            &quot;tax_number&quot;: &quot;01JWW81PZM1QYQVFXQKS2E52C6&quot;,
            &quot;tax_rate&quot;: 17.5,
            &quot;categories&quot;: [
                {
                    &quot;id&quot;: 1,
                    &quot;name&quot;: &quot;Tenetur&quot;,
                    &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                    &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
                }
            ],
            &quot;default_email&quot;: {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Maddison Brakus Jr.&quot;,
                &quot;address&quot;: &quot;hudson.frami@example.com&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_phone&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Office&quot;,
                &quot;name&quot;: &quot;Marjorie Christiansen DDS&quot;,
                &quot;number&quot;: &quot;+1.929.520.9451&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;default_address&quot;: {
                &quot;id&quot;: 1,
                &quot;type&quot;: &quot;Shipping&quot;,
                &quot;name&quot;: &quot;Mohr Group&quot;,
                &quot;line1&quot;: &quot;5467 Champlin Grove&quot;,
                &quot;line2&quot;: null,
                &quot;city&quot;: &quot;South Roslynburgh&quot;,
                &quot;state&quot;: &quot;Massachusetts&quot;,
                &quot;postal_code&quot;: &quot;79242&quot;,
                &quot;country&quot;: &quot;United States&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;billing_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Billing&quot;,
            &quot;name&quot;: &quot;Kulas-Russel&quot;,
            &quot;line1&quot;: &quot;71972 Jacinto Roads&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Auroreside&quot;,
            &quot;state&quot;: &quot;Texas&quot;,
            &quot;postal_code&quot;: &quot;00397&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;shipping_address&quot;: {
            &quot;id&quot;: 1,
            &quot;type&quot;: &quot;Billing&quot;,
            &quot;name&quot;: &quot;Boyle-Mitchell&quot;,
            &quot;line1&quot;: &quot;8854 Satterfield Pass Suite 206&quot;,
            &quot;line2&quot;: null,
            &quot;city&quot;: &quot;Kesslermouth&quot;,
            &quot;state&quot;: &quot;Oklahoma&quot;,
            &quot;postal_code&quot;: &quot;61620-5334&quot;,
            &quot;country&quot;: &quot;United States&quot;,
            &quot;is_default&quot;: true,
            &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
            &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
        },
        &quot;notification_recipients&quot;: [
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Lourdes Medhurst&quot;,
                &quot;address&quot;: &quot;casper.jacquelyn@example.net&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            },
            {
                &quot;id&quot;: 1,
                &quot;name&quot;: &quot;Kiley Veum&quot;,
                &quot;address&quot;: &quot;kellen.waelchi@example.com&quot;,
                &quot;is_default&quot;: true,
                &quot;created_at&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;updated_at&quot;: &quot;2025-06-04 01:14:18&quot;
            }
        ],
        &quot;payments&quot;: [
            {
                &quot;payment_date&quot;: &quot;04-06-2025&quot;,
                &quot;payment_method&quot;: &quot;Stripe&quot;,
                &quot;payment_currency&quot;: &quot;USD&quot;,
                &quot;payment_amount&quot;: 19.88,
                &quot;payment_reference&quot;: &quot;Ebuf8PwAcihOFSO2&quot;,
                &quot;crypto_asset_name&quot;: null,
                &quot;crypto_asset_ada_price&quot;: null,
                &quot;crypto_asset_quantity&quot;: 0,
                &quot;crypto_wallet_name&quot;: null,
                &quot;crypto_payment_ttl&quot;: null,
                &quot;crypto_payment_recipient_address&quot;: null,
                &quot;crypto_payment_last_checked&quot;: null,
                &quot;crypto_payment_process_attempts&quot;: null,
                &quot;crypto_payment_last_error&quot;: null,
                &quot;status&quot;: &quot;Pending&quot;
            }
        ],
        &quot;activities&quot;: [
            {
                &quot;activity&quot;: &quot;Created new invoice&quot;,
                &quot;when&quot;: &quot;2025-06-04 01:14:18&quot;,
                &quot;diff&quot;: &quot;0 seconds ago&quot;
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-invoices--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-invoices--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-invoices--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-invoices--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-invoices--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-invoices--id-" data-method="GET"
      data-path="api/v1/invoices/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-invoices--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-invoices--id-"
                    onclick="tryItOut('GETapi-v1-invoices--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-invoices--id-"
                    onclick="cancelTryOut('GETapi-v1-invoices--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-invoices--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/invoices/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-invoices--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-invoices--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-invoices--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="GETapi-v1-invoices--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="invoices-PUTapi-v1-invoices--id-">Update Invoice</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>When invoices are updated to <strong>Published</strong> status,
the invoice will go live and no further changes can be made and
email notifications will be sent to the specified <code>customer_email_ids</code> in the request body.</p>

<span id="example-requests-PUTapi-v1-invoices--id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/invoices/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"customer_id\": 1,
    \"customer_email_ids\": [
        1
    ],
    \"billing_address_id\": 4,
    \"shipping_address_id\": 7,
    \"customer_reference\": \"INV-1234\",
    \"issue_date\": \"2024-01-31\",
    \"due_date\": \"2024-02-14\",
    \"items\": [
        {
            \"product_id\": 3,
            \"service_id\": null,
            \"sku\": \"ABC123\",
            \"description\": \"My product name\",
            \"quantity\": 1,
            \"unit_price\": 4.99,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": 4,
            \"sku\": null,
            \"description\": \"My service name\",
            \"quantity\": 3,
            \"unit_price\": 10.5,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": null,
            \"sku\": null,
            \"description\": \"My free text line (e.g. misc charges)\",
            \"quantity\": 1,
            \"unit_price\": 9.99,
            \"tax_rate\": 20
        },
        {
            \"product_id\": null,
            \"service_id\": null,
            \"sku\": null,
            \"description\": \"Discount\",
            \"quantity\": 1,
            \"unit_price\": -5.99,
            \"tax_rate\": 0
        }
    ],
    \"save_mode\": \"Draft\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoices/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 1,
    "customer_email_ids": [
        1
    ],
    "billing_address_id": 4,
    "shipping_address_id": 7,
    "customer_reference": "INV-1234",
    "issue_date": "2024-01-31",
    "due_date": "2024-02-14",
    "items": [
        {
            "product_id": 3,
            "service_id": null,
            "sku": "ABC123",
            "description": "My product name",
            "quantity": 1,
            "unit_price": 4.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": 4,
            "sku": null,
            "description": "My service name",
            "quantity": 3,
            "unit_price": 10.5,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "My free text line (e.g. misc charges)",
            "quantity": 1,
            "unit_price": 9.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "Discount",
            "quantity": 1,
            "unit_price": -5.99,
            "tax_rate": 0
        }
    ],
    "save_mode": "Draft"
};

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoices/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'customer_id' =&gt; 1,
            'customer_email_ids' =&gt; [
                1,
            ],
            'billing_address_id' =&gt; 4,
            'shipping_address_id' =&gt; 7,
            'customer_reference' =&gt; 'INV-1234',
            'issue_date' =&gt; '2024-01-31',
            'due_date' =&gt; '2024-02-14',
            'items' =&gt; [
                [
                    'product_id' =&gt; 3,
                    'service_id' =&gt; null,
                    'sku' =&gt; 'ABC123',
                    'description' =&gt; 'My product name',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; 4.99,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; 4,
                    'sku' =&gt; null,
                    'description' =&gt; 'My service name',
                    'quantity' =&gt; 3,
                    'unit_price' =&gt; 10.5,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; null,
                    'sku' =&gt; null,
                    'description' =&gt; 'My free text line (e.g. misc charges)',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; 9.99,
                    'tax_rate' =&gt; 20,
                ],
                [
                    'product_id' =&gt; null,
                    'service_id' =&gt; null,
                    'sku' =&gt; null,
                    'description' =&gt; 'Discount',
                    'quantity' =&gt; 1,
                    'unit_price' =&gt; -5.99,
                    'tax_rate' =&gt; 0,
                ],
            ],
            'save_mode' =&gt; 'Draft',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoices/1'
payload = {
    "customer_id": 1,
    "customer_email_ids": [
        1
    ],
    "billing_address_id": 4,
    "shipping_address_id": 7,
    "customer_reference": "INV-1234",
    "issue_date": "2024-01-31",
    "due_date": "2024-02-14",
    "items": [
        {
            "product_id": 3,
            "service_id": null,
            "sku": "ABC123",
            "description": "My product name",
            "quantity": 1,
            "unit_price": 4.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": 4,
            "sku": null,
            "description": "My service name",
            "quantity": 3,
            "unit_price": 10.5,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "My free text line (e.g. misc charges)",
            "quantity": 1,
            "unit_price": 9.99,
            "tax_rate": 20
        },
        {
            "product_id": null,
            "service_id": null,
            "sku": null,
            "description": "Discount",
            "quantity": 1,
            "unit_price": -5.99,
            "tax_rate": 0
        }
    ],
    "save_mode": "Draft"
}
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers, json=payload)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-invoices--id-">
            <blockquote>
            <p>Example response (200, OK):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: 1,
        &quot;customer_id&quot;: 0,
        &quot;billing_address_id&quot;: 0,
        &quot;shipping_address_id&quot;: 0,
        &quot;invoice_reference&quot;: &quot;QLqBOqWxPA&quot;,
        &quot;customer_reference&quot;: &quot;INV-42649184&quot;,
        &quot;currency&quot;: &quot;USD&quot;,
        &quot;total&quot;: 0,
        &quot;issue_date&quot;: &quot;2024-07-04&quot;,
        &quot;due_date&quot;: &quot;2024-08-03&quot;,
        &quot;last_notified&quot;: null,
        &quot;is_overdue&quot;: false,
        &quot;status&quot;: &quot;Voided&quot;,
        &quot;created_at&quot;: &quot;2024-07-04 09:51:30&quot;,
        &quot;updated_at&quot;: &quot;2024-07-04 09:51:30&quot;,
        &quot;items&quot;: [
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;SYJSMH0XB7DAKZMA&quot;,
                &quot;description&quot;: &quot;Consequatur quo illo deserunt laborum voluptas.&quot;,
                &quot;quantity&quot;: 2,
                &quot;unit_price&quot;: 85.49,
                &quot;tax_rate&quot;: 20
            },
            {
                &quot;product_id&quot;: null,
                &quot;service_id&quot;: null,
                &quot;sku&quot;: &quot;KXPIXEWBE8PKXNNG&quot;,
                &quot;description&quot;: &quot;Sed illo est illo velit.&quot;,
                &quot;quantity&quot;: 3,
                &quot;unit_price&quot;: 47.48,
                &quot;tax_rate&quot;: 20
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation Failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Validation Failed&quot;,
    &quot;fields&quot;: {
        &quot;field_name&quot;: [
            &quot;The field name is required.&quot;
        ],
        &quot;another_field&quot;: [
            &quot;The another field must not be greater than 64 characters.&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-invoices--id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-invoices--id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-invoices--id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-invoices--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-invoices--id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-invoices--id-" data-method="PUT"
      data-path="api/v1/invoices/{id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-invoices--id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-invoices--id-"
                    onclick="tryItOut('PUTapi-v1-invoices--id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-invoices--id-"
                    onclick="cancelTryOut('PUTapi-v1-invoices--id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-invoices--id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/invoices/{id}</code></b>
        </p>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/invoices/{id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-invoices--id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-invoices--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-invoices--id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_id</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="1"
               data-component="body">
    <br>
<p>ID of the customer, whom the invoice is being generated for. Example: <code>1</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_email_ids</code></b>&nbsp;&nbsp;
<small>integer[]</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="customer_email_ids[0]"                data-endpoint="PUTapi-v1-invoices--id-"
               data-component="body">
        <input type="number" style="display: none"
               name="customer_email_ids[1]"                data-endpoint="PUTapi-v1-invoices--id-"
               data-component="body">
    <br>
<p>Array of email IDs associated with the customer (who will receive email notifications).</p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>billing_address_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="billing_address_id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="4"
               data-component="body">
    <br>
<p>ID of the customer address, to be used as billing address. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>shipping_address_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="shipping_address_id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="7"
               data-component="body">
    <br>
<p>ID of the customer address, to be used as shipping address. Example: <code>7</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>customer_reference</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="customer_reference"                data-endpoint="PUTapi-v1-invoices--id-"
               value="INV-1234"
               data-component="body">
    <br>
<p>Must not be greater than 64 characters. Example: <code>INV-1234</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>issue_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="issue_date"                data-endpoint="PUTapi-v1-invoices--id-"
               value="2024-01-31"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2024-01-31</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>due_date</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="due_date"                data-endpoint="PUTapi-v1-invoices--id-"
               value="2024-02-14"
               data-component="body">
    <br>
<p>Must be a valid date. Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2024-02-14</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>items</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
<br>

            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>product_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.product_id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="7"
               data-component="body">
    <br>
<p>Optional: ID of the product you are selling. Example: <code>7</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>service_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.service_id"                data-endpoint="PUTapi-v1-invoices--id-"
               value="3"
               data-component="body">
    <br>
<p>Optional: ID of the service you are selling. Example: <code>3</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>sku</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
                <input type="text" style="display: none"
                              name="items.0.sku"                data-endpoint="PUTapi-v1-invoices--id-"
               value="XYZ-1234"
               data-component="body">
    <br>
<p>Optional: Stock Keeping Unit of the product you are selling (related to product_id). Must not be greater than 32 characters. Example: <code>XYZ-1234</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>description</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="items.0.description"                data-endpoint="PUTapi-v1-invoices--id-"
               value="My free text line (e.g. misc charges)"
               data-component="body">
    <br>
<p>Must be at least 3 characters. Must not be greater than 1024 characters. Example: <code>My free text line (e.g. misc charges)</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>quantity</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.quantity"                data-endpoint="PUTapi-v1-invoices--id-"
               value="10"
               data-component="body">
    <br>
<p>Must be at least 0.000001. Example: <code>10</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>unit_price</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.unit_price"                data-endpoint="PUTapi-v1-invoices--id-"
               value="4.99"
               data-component="body">
    <br>
<p>Price of single unit, can be negative value (for giving discount). Example: <code>4.99</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>tax_rate</code></b>&nbsp;&nbsp;
<small>number</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="items.0.tax_rate"                data-endpoint="PUTapi-v1-invoices--id-"
               value="22.5"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 100. Example: <code>22.5</code></p>
                    </div>
                                    </details>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>save_mode</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="save_mode"                data-endpoint="PUTapi-v1-invoices--id-"
               value="Draft"
               data-component="body">
    <br>
<p>Example: <code>Draft</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>Draft</code></li> <li><code>Publish</code></li></ul>
        </div>
        </form>

                    <h2 id="invoices-PUTapi-v1-invoice-void--invoice_id-">Void Invoice</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Marks the invoice as <strong>Voided</strong> status, so the customer will not be expected to pay the invoice.</p>

<span id="example-requests-PUTapi-v1-invoice-void--invoice_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/invoice-void/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoice-void/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoice-void/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoice-void/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-invoice-void--invoice_id-">
            <blockquote>
            <p>Example response (204, Invoice Voided):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-invoice-void--invoice_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-invoice-void--invoice_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-invoice-void--invoice_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-invoice-void--invoice_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-invoice-void--invoice_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-invoice-void--invoice_id-" data-method="PUT"
      data-path="api/v1/invoice-void/{invoice_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-invoice-void--invoice_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-invoice-void--invoice_id-"
                    onclick="tryItOut('PUTapi-v1-invoice-void--invoice_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-invoice-void--invoice_id-"
                    onclick="cancelTryOut('PUTapi-v1-invoice-void--invoice_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-invoice-void--invoice_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/invoice-void/{invoice_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-invoice-void--invoice_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-invoice-void--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-invoice-void--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>invoice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="invoice_id"                data-endpoint="PUTapi-v1-invoice-void--invoice_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="invoices-PUTapi-v1-invoice-restore--invoice_id-">Restore Invoice</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Restores <strong>Voided</strong> invoice back into <strong>Draft</strong> status, so that it can be further updated.</p>

<span id="example-requests-PUTapi-v1-invoice-restore--invoice_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/invoice-restore/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoice-restore/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoice-restore/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoice-restore/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-invoice-restore--invoice_id-">
            <blockquote>
            <p>Example response (204, Invoice Restored):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-invoice-restore--invoice_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-invoice-restore--invoice_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-invoice-restore--invoice_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-invoice-restore--invoice_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-invoice-restore--invoice_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-invoice-restore--invoice_id-" data-method="PUT"
      data-path="api/v1/invoice-restore/{invoice_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-invoice-restore--invoice_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-invoice-restore--invoice_id-"
                    onclick="tryItOut('PUTapi-v1-invoice-restore--invoice_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-invoice-restore--invoice_id-"
                    onclick="cancelTryOut('PUTapi-v1-invoice-restore--invoice_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-invoice-restore--invoice_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/invoice-restore/{invoice_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-invoice-restore--invoice_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-invoice-restore--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-invoice-restore--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>invoice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="invoice_id"                data-endpoint="PUTapi-v1-invoice-restore--invoice_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="invoices-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">Send Invoice Reminder Notifications</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Sends invoice reminders to all recipients attached to the invoice, can only be used once a day on published invoices.</p>

<span id="example-requests-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/invoice-send-reminder-notifications/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoice-send-reminder-notifications/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoice-send-reminder-notifications/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoice-send-reminder-notifications/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">
            <blockquote>
            <p>Example response (204, Invoice Reminder Notifications Sent):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-" data-method="PUT"
      data-path="api/v1/invoice-send-reminder-notifications/{invoice_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-invoice-send-reminder-notifications--invoice_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
                    onclick="tryItOut('PUTapi-v1-invoice-send-reminder-notifications--invoice_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
                    onclick="cancelTryOut('PUTapi-v1-invoice-send-reminder-notifications--invoice_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/invoice-send-reminder-notifications/{invoice_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>invoice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="invoice_id"                data-endpoint="PUTapi-v1-invoice-send-reminder-notifications--invoice_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                    </form>

                    <h2 id="invoices-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">Manually Mark Invoice As Paid</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Updates the invoice status to <strong>Paid</strong> manually, without processing any form of payments.</p>

<span id="example-requests-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PUT \
    "http://localhost:8100/api/v1/invoice-manually-mark-as-paid/1" \
    --header "Authorization: Bearer {YOUR_ACCESS_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "http://localhost:8100/api/v1/invoice-manually-mark-as-paid/1"
);

const headers = {
    "Authorization": "Bearer {YOUR_ACCESS_TOKEN}",
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers,
}).then(response =&gt; response.json());</code></pre></div>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$url = 'http://localhost:8100/api/v1/invoice-manually-mark-as-paid/1';
$response = $client-&gt;put(
    $url,
    [
        'headers' =&gt; [
            'Authorization' =&gt; 'Bearer {YOUR_ACCESS_TOKEN}',
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="python-example">
    <pre><code class="language-python">import requests
import json

url = 'http://localhost:8100/api/v1/invoice-manually-mark-as-paid/1'
headers = {
  'Authorization': 'Bearer {YOUR_ACCESS_TOKEN}',
  'Content-Type': 'application/json',
  'Accept': 'application/json'
}

response = requests.request('PUT', url, headers=headers)
response.json()</code></pre></div>

</span>

<span id="example-responses-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">
            <blockquote>
            <p>Example response (204, Invoice Reminder Notifications Sent):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Unauthorized):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Unauthorized&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not Found):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Route or Record Not found&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Bad Request):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;The POST method is not supported for route api/v1/customers/1/phones/16. Supported methods: GET, HEAD, PUT, PATCH&quot;
}</code>
 </pre>
            <blockquote>
            <p>Example response (500, Internal Server Error):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;message&quot;: &quot;Internal Server Error&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-" data-method="PUT"
      data-path="api/v1/invoice-manually-mark-as-paid/{invoice_id}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
                    onclick="tryItOut('PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
                    onclick="cancelTryOut('PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-darkblue">PUT</small>
            <b><code>api/v1/invoice-manually-mark-as-paid/{invoice_id}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
               value="Bearer {YOUR_ACCESS_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_ACCESS_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>invoice_id</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="invoice_id"                data-endpoint="PUTapi-v1-invoice-manually-mark-as-paid--invoice_id-"
               value="1"
               data-component="url">
    <br>
<p>The ID of the invoice. Example: <code>1</code></p>
            </div>
                    </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="python">python</button>
                            </div>
            </div>
</div>
</body>
</html>
