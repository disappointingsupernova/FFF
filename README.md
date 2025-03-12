# FFF (Fortinet FQDN Formatter)

## Overview
This PHP-based web tool allows users to input a mix of Fully Qualified Domain Names (FQDNs) and IP addresses. The script automatically separates them, formats them into Fortinet-style `edit` blocks, and presents both a formatted output and comma-separated lists.

## Features
- **Supports various delimiters**: Input can be space, tab, comma, semicolon, or mixed.
- **Automatically categorizes FQDNs and IPs**:
  - FQDNs are formatted in `set fqdn` format.
  - IPs are formatted in `set subnet` format with a `ST_Host_` prefix.
- **Live update**: The output updates dynamically as you type without refreshing the page.
- **Customizable color selection**: Default is `9`, but users can specify a different value.
- **Comma-separated lists**: FQDNs and IPs are displayed at the bottom in a formatted string.

## Installation
1. Ensure you have a working PHP environment (e.g., Apache, Nginx, or PHP CLI).
2. Save the PHP file to your web server directory.
3. Open the script in a web browser.

## Usage
1. **Enter FQDNs and IPs** in the provided textarea.
2. **Select a color** (optional, defaults to `9`).
3. **View formatted output**:
   - `edit` blocks for both FQDNs and IPs.
   - Comma-separated lists at the bottom.
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
### Comma-Separated Lists:
```
FQDNs: "example.com" "google.com"
IPs: "ST_Host_192.168.1.1" "ST_Host_10.0.0.2"
```

## Notes
- Ensure proper formatting when copying output for Fortinet configurations.
- The script only recognizes valid FQDNs and IPs—malformed inputs are ignored.
- The default subnet mask for IPs is `255.255.255.255`.

## License
This script is free to use and modify.
