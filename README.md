trntSearch offers:

-   Access to torrent providers without geo-blocking
-   Private browsing and no tracking
-   A simple, user-friendly interface with essential details like magnet links and file sizes

To achieve these objectives, trntSearch aggregates data from a variety of sources:

-   Official APIs:
    
	-   Yts
    
	-   Eztv
    
-   Unofficial APIs:
    
	-   Piratebay
    
-   SQL Databases:
    
	-   Rarbg
    
	-   Kiwi-Torrent-Research
    
-   XML Databases:
    
	-   AcademicTorrent
    
-   Web Scraping:
    
	-   1337x



# **Getting Started with trntSearch**

We are utilizing a Debian-based distribution for the purpose of setting up the server. However, users with advanced expertise are at liberty to select an alternative distribution if preferred.

## **Prerequisites**

Before you begin, ensure that your system meets the following requirements:

-   **Operating System**: Debian-based Linux (e.g., Ubuntu, Debian).
    
-   **PHP Version**: PHP 8.3 or higher.
    
-   **Web Server**: Caddy (or any other web server of your choice, though this guide uses Caddy for its ease of configuration and automatic SSL support).
    

### **Required Packages**

The following packages are required to run trntSearch:

-   php8.3
    
-   php8.3-fpm (FastCGI Process Manager)
    
-   php-sqlite
    
-   php-curl
    
-   php-dom
    

These dependencies are essential for the proper functioning of the application.

## **Installation Steps**

### **1. Update System Packages**

Before installing new packages, ensure your system package list is up-to-date:

```
sudo apt update && sudo apt upgrade -y
```

### **2. Install PHP and Required Extensions**

Install the required PHP version and extensions using the following command:

```
sudo apt install -y php8.3 php8.3-fpm php-xml php-curl php-dom
```

This will install PHP 8.3, FastCGI Process Manager (php-fpm), and other necessary extensions.

### **3. Install Caddy Web Server**

trntSearch recommends using **Caddy** as the web server due to its ease of configuration and automatic SSL certificate provisioning via Let’s Encrypt.

To install Caddy as a server, please refer to the official Caddy documentation for step-by-step instructions. This will ensure a correct and secure installation. (https://caddyserver.com/docs/install)

1.  Add the official Caddy repository key and sources list:
    
```
sudo apt install -y debian-keyring debian-archive-keyring apt-transport-https curl

curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list

curl -1sLf 'https://dl.cloudsmith.io/public/caddy/stable/debian.deb.txt' | sudo tee /etc/apt/sources.list.d/caddy-stable.list
```

1.  Update the package list and install Caddy:
    
```
sudo apt update && sudo apt install -y caddy
```

### **4. Configure Caddy for trntSearch**

#### _**Step 1: Open the Caddy Configuration File**_

Open the Caddy configuration file in a text editor:

```
sudo nano /etc/caddy/Caddyfile
```

#### _**Step 2: Add Configuration for trntSearch**_

Replace the existing content with the following configuration (or modify it based on your needs):

```
:80 {

		# Set this path to your site's directory.

		root * /usr/share/caddy/trntSearch

		# Enable the static file server.

		file_server

		# Configure PHP FastCGI

		php_fastcgi unix//var/run/php/php8.3-fpm.sock

		# Enable GZIP compression for faster responses

		encode gzip

		# Logging configuration

		log {

				output file /var/log/caddy/caddy.log

		}

}

```
## How trntSearch configuration will look like
```
trnt.librey.org {
        root * /usr/share/caddy/trntSearch
        header Onion-Location http://po2fyxzl3tadffsiotc3zvgrb4hgex7xpvlutah3um6ick6cneidkrid.onion
        php_fastcgi unix//run/php-fpm/www.sock
        file_server
        encode gzip
        log {
                output file /var/log/caddy/trnt.log
                format json
        }
}

```

**Notes:**

-   Replace localhost with your domain name if you have one, caddy automatically configure with SSL if you provide the public domain.
    
-   The root * /usr/share/caddy/trntSearch path points to the location where trntSearch will be installed. Modify this path according to your setup.
    
-   Enabling HTTPS is strongly recommended for production environments.
    

#### _**Step 3: Save and Exit**_

Save the file (Ctrl+O in Nano) and exit (Ctrl+X).

### **5. Clone trntSearch Repository**

Clone the trntSearch repository into the directory specified in your Caddy configuration:

```
git clone https://github.com/sarthac/trntSearch /usr/share/caddy/trntSearch

```
**Note:** If you changed the root path in the Caddyfile, adjust the destination path accordingly.

### **7. Start Services**

#### _**Step 1: Start PHP-FPM and Caddy**_

```
sudo systemctl start php8.3-fpm caddy
```

#### _**Step 2: Enable Services to Start on Boot**_

To ensure that the services restart automatically after a reboot:
```
sudo systemctl enable php8.3-fpm caddy
```

### **8. Verify Installation**

Open your web browser and navigate to:

-   http://localhost (or your domain name) for HTTP access.
    
-   https://your-domain-name.com (or your domain name with HTTPS).
    

If you see the trntSearch interface, the installation was successful.

## **Troubleshooting Common Issues**

1.  **File Permissions**: Ensure that the web server has read access to the trntSearch files and directories.
    
2.  **Port Conflicts**: Check if ports 80 (HTTP) or 443 (HTTPS) are occupied by another service.
    
3.  **Log Files**: Review logs for errors:
    

-   PHP-FPM logs: /var/log/php8.3-fpm.log
    
-   Caddy logs: /var/log/caddy/caddy.log
