<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['input'] ?? '';
    $color = $_POST['color'] ?? '9';
    
    // Extract FQDNs and IPs
    $entries = preg_split('/[\s,;]+/', trim($input));
    $fqdns = [];
    $ips = [];
    
    foreach ($entries as $entry) {
        if (filter_var($entry, FILTER_VALIDATE_IP)) {
            $ips[] = "ST_Host_$entry";
        } elseif (preg_match('/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $entry)) {
            $fqdns[] = $entry;
        }
    }
    
    // Generate edit blocks for FQDNs
    function generateFqdnBlocks($list, $color) {
        $output = "";
        foreach ($list as $item) {
            $output .= "edit \"$item\"\n";
            $output .= "    set type fqdn\n";
            $output .= "    set color $color\n";
            $output .= "    set fqdn \"$item\"\n";
            $output .= "next\n\n";
        }
        return $output;
    }
    
    // Generate edit blocks for IPs
    function generateIpBlocks($list, $color) {
        $output = "";
        foreach ($list as $item) {
            $ip = str_replace("ST_Host_", "", $item);
            $output .= "edit \"$item\"\n";
            $output .= "    set color $color\n";
            $output .= "    set subnet $ip 255.255.255.255\n";
            $output .= "next\n\n";
        }
        return $output;
    }
    
    $fqdn_output = generateFqdnBlocks($fqdns, $color);
    $ip_output = generateIpBlocks($ips, $color);
    
    // Generate formatted CSV lines
    $fqdn_csv = !empty($fqdns) ? '"' . implode('" "', $fqdns) . '"' : "No FQDNs found.";
    $ip_csv = !empty($ips) ? '"' . implode('" "', $ips) . '"' : "No IPs found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FortiGate FQDN & IP Sorter</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tabler@latest/dist/css/tabler.min.css">
    <style>
        .output-container {
            display: flex;
            gap: 20px;
        }
        .output-box {
            flex: 1;
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f8f9fa;
        }
    </style>
    <script>
        function updateOutput() {
            const form = document.getElementById("inputForm");
            const formData = new FormData(form);
            fetch("", { method: "POST", body: formData })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser().parseFromString(html, "text/html");
                    document.getElementById("fqdn-output").innerHTML = parser.getElementById("fqdn-output").innerHTML;
                    document.getElementById("ip-output").innerHTML = parser.getElementById("ip-output").innerHTML;
                    document.getElementById("fqdn-list").innerText = parser.getElementById("fqdn-list").innerText;
                    document.getElementById("ip-list").innerText = parser.getElementById("ip-list").innerText;
                });
        }
    </script>
</head>
<body class="theme-light">
    <div class="container mt-4">
        <h2 class="mb-4">FortiGate FQDN & IP Sorter</h2>
        <form id="inputForm" method="post" oninput="updateOutput()">
            <div class="mb-3">
                <textarea name="input" class="form-control" rows="5" placeholder="Enter FQDNs and IPs here..."><?php echo htmlspecialchars($_POST['input'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Colour:</label>
                <input type="number" name="color" id="color" class="form-control" min="1" max="32" value="<?php echo htmlspecialchars($_POST['color'] ?? '9'); ?>">
            </div>
        </form>

        <h3 class="mt-4">Formatted Entries</h3>
        <div class="output-container">
            <div class="output-box">
                <pre id="fqdn-output"><?php echo $fqdn_output; ?></pre>
            </div>
            <div class="output-box">
                <pre id="ip-output"><?php echo $ip_output; ?></pre>
            </div>
        </div>

        <h3 class="mt-4">Formatted Lists</h3>
        <div class="output-container">
            <div class="output-box">
                <p><span id="fqdn-list" class="text-monospace"><?php echo $fqdn_csv; ?></span></p>
            </div>
            <div class="output-box">
                <p><span id="ip-list" class="text-monospace"><?php echo $ip_csv; ?></span></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/tabler@latest/dist/js/tabler.min.js"></script>
</body>
</html>
