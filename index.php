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
    <title>FQDN & IP Sorter</title>
    <script>
        function updateOutput() {
            const form = document.getElementById("inputForm");
            const formData = new FormData(form);
            fetch("", { method: "POST", body: formData })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser().parseFromString(html, "text/html");
                    document.getElementById("output").innerHTML = parser.getElementById("output").innerHTML;
                    document.getElementById("fqdn-list").innerText = parser.getElementById("fqdn-list").innerText;
                    document.getElementById("ip-list").innerText = parser.getElementById("ip-list").innerText;
                });
        }
    </script>
</head>
<body>
    <h2>FQDN & IP Sorter</h2>
    <form id="inputForm" method="post" oninput="updateOutput()">
        <textarea name="input" rows="5" cols="50" placeholder="Enter FQDNs and IPs here..."><?php echo htmlspecialchars($_POST['input'] ?? ''); ?></textarea>
        <br>
        <label for="color">Color:</label>
        <input type="number" name="color" id="color" min="1" max="32" value="<?php echo htmlspecialchars($_POST['color'] ?? '9'); ?>">
        <br>
        <button type="submit">Submit</button>
    </form>
    
    <h3>Generated Output:</h3>
    <pre id="output">
        <?php if (!empty($fqdn_output) || !empty($ip_output)) { 
            echo "# FQDN Entries\n" . $fqdn_output;
            echo "# IP Entries\n" . $ip_output;
        } ?>
    </pre>
    
    <h3>Comma-Separated Lists:</h3>
    <p><strong>FQDNs:</strong> <span id="fqdn-list"><?php echo $fqdn_csv; ?></span></p>
    <p><strong>IPs:</strong> <span id="ip-list"><?php echo $ip_csv; ?></span></p>
</body>
</html>