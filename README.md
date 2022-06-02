# LightCsv-Genieacs
Light CSV to Genieacs

LightCSV can be used to store CPE configurations and to manage those CPE. It offers some of GenieACS user interface functions and adds new ones:
1. Testing few selected TR-098 data model functions
2. Testing download speed
3. Shows presets matching selected CPE

Functionality is concentrated on managing CPE, not necessary on configuring whole GeniaACS.

LightCSV can also be used as example - how to integrate GenieACS with other systems. Therefore code is divided in a way that should simplify using it in other projects (in compliance with AGPL-3.0 licence).

# Support

For support options contact by mail: sylwesterzdanowski[at]gmail.com
# Usage
Add ext and provisioning to GenieACS.

Configure Apache2 for LightCsv-Genieacs. 
VirtualHost has to enable RewriteEngine:
```
RewriteEngine On
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
FallbackResource /index.php
```
LightCsv-Genieacs requires Genieacs IP in configuration.ini.

Ext script requires http address and token to get configuration from LightCsv-Genieacs. 

Add configuration file with device serial or ID as name. 
Example can be found in cpe directory. However each model may use
different parameter names.

After file name gets changed from serial to device ID, it can be
executed from web page.

# Elements
### 1. Light CSV Genieacs

Base class for communication with local data source. Whether its database, file or another REST.
Replacing this class should be enough to pass data from other data source to GenieACS.

- Checking hosts limits communication to system with pre-set token. At the same time communication works with assumption it is secure in internal network.
- Used file operations should be replaced by equivalet actions on user data source. 
  - Reading configuration
  - Creating new configuration
  - Adding to configuration
  - Updating configuration  

### 2. Light CSV Genieacs Server

Responds to Genieacs calls issued from ext/get_fileconfig/
After checking host token, we can read JSON from php://input and respond with propper JSON. 

### 3. Light CSV Genieacs Api

Cals Genieacs using CURL to execute tasks. Sending configuration requires device ID not serial.

### 4. Light CSV GUI Server

Enables writing changes to configuration files using [GenieACS-QT-smallGUI](https://github.com/ZdanowskiS/GenieACS-QT-smallGUI).
On its own GenieACS-QT-smallGUI would change CPE configuration, without ability to reload it after factory reset

### 5. ext/get_fileconfig

Script on Genieacs backend that calls Light CSV for CPE configuration. Data passed depend on porvision settings. At minimum it requires device Serial. However for two way communication device ID is required.

### 6. provision/getconfig

Genieacs provisioning called by propper presets. It should be run only after device bootstrap. This in turn may require propper conditions based on tags.
