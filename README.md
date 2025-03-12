# FFF (FortiGate FQDN Formatter)

## Overview
This PHP-based web tool allows users to input a mix of Fully Qualified Domain Names (FQDNs) and IP addresses. The script automatically separates them, formats them into Fortinet-style `edit` blocks, and presents both a formatted output and formatted lists.

## Features
- **Supports various delimiters**: Input can be space, tab, comma, semicolon, or mixed.
- **Automatically categorizes FQDNs and IPs**:
  - FQDNs are formatted in `set fqdn` format.
  - IPs are formatted in `set subnet` format with a `ST_Host_` prefix.
- **Live update**: The output updates dynamically as you type without refreshing the page.
- **Customizable color selection**: Default is `9`, but users can specify a different value.
- **Grouped Outputs**:
  - FQDNs and IPs are displayed in separate structured sections.
  - Each type has its own formatted `edit` block and a Formatted list.

## Installation
1. Ensure you have a working PHP environment (e.g., Apache, Nginx, or PHP CLI).
2. Save the PHP file to your web server directory.
3. Open the script in a web browser.

## Usage
1. **Enter FQDNs and IPs** in the provided textarea.
2. **Select a color** (optional, defaults to `9`).
3. **View formatted output**:
   - `edit` blocks for both FQDNs and IPs.
   - Formatted lists grouped by type.
4. **Copy the output** for use in Fortinet configurations.

## Example Input
```
example.com, 192.168.1.1 google.com; 10.0.0.2
```

## Example Output
### Edit Blocks:
```
edit "example.com"
    set type fqdn
    set color 9
    set fqdn "example.com"
next

edit "ST_Host_192.168.1.1"
    set color 9
    set subnet 192.168.1.1 255.255.255.255
next
```
### Formatted Lists:
```
FQDNs: "example.com" "google.com"
IPs: "ST_Host_192.168.1.1" "ST_Host_10.0.0.2"
```

---

## **Test Data**
Use the following block of **mixed FQDNs and IPs** to test how the tool processes different formats:
```
192.168.1.1, example.com 10.0.0.2; dev.myserver.net
8.8.8.8 test.example.net, 172.16.254.1; backup01.local
alpha.corp.com 192.168.100.50, beta.services.net; 203.0.113.45
server.internal, 10.10.10.10; gateway.office.local
cloud-hosting.net, 22.214.171.124; mydatabase.org dev-api.app
prod.backup.net, 192.0.2.10; testserver.org 198.51.100.75
firewall.office, 10.10.20.5; app1.enterprise.com 64.233.160.0
vpn.access.net, 15.16.17.18; staging.node.cloud
db01.global.tech 192.168.200.99, support.io 11.12.13.14
app.cluster.local; 203.0.113.100, infra.ops.company
public.www.example, 20.21.22.23; primary.router.net 172.217.14.206
lab-server03.local, 34.35.36.37; dmz.site.internal 45.46.47.48
node1.datacenter.com, 55.56.57.58; cache.edge.node 69.70.71.72
cloud.storage.net, 81.82.83.84; mobile.network.host 92.93.94.95
log.server.internal, 105.106.107.108; security.firewall.com
research.academy, 116.117.118.119; backup.node.net
```

---

## Notes
- Ensure proper formatting when copying output for Fortinet configurations.
- The script only recognizes valid FQDNs and IPs—malformed inputs are ignored.
- The default subnet mask for IPs is `255.255.255.255`.

## Contributions
Contributions are welcome! Please open an issue or submit a pull request if you'd like to improve this project.

## License
This project is open-source and available under the MIT License.