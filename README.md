# LightCsv-Genieacs
Light CSV to Genieacs
This solution is ment as an example, how to pass data to Genieacs. It
can be used as testing enviroment or starting point for integration of other data sources for Genieacs.
# Usage
Add ext and provisioning in Genieacs.

Configure Apache2 for LightCsv-Genieacs and set Genieacs IP in
configuration.ini.

Ext script requires http address from which to get configuration. 

Add configuration file with device serial or ID as name. 
Example can be found in cpe directory. However each model may use
different parameter names.

After file name gets changed from serial to device ID, it can be
executed from web page.

# Elements
* Light CSV Genieacs

Base class for communication with local data source. Whether its database file or another REST.

Checking hosts is limited to pre-set token, assuming connection betwean system and Genieacs is secure.

* Light CSV Genieacs Server

Responds to Genieacs calls issued from ext/get_fileconfig/
After checking host recives JSON from php://input and responds with propper JSON. 

* Light CSV Genieacs Api

Cals Genieacs using CURL to execute tasks. Sending configuration requires device ID not serial.

* ext/get_fileconfig

Script on Genieacs backend that calls Light CSV Configuration for CPE configuration. Data passed depend on porvision settings. At minimum it requires device Serial. However for two way consiguration device ID is required.

* provision/getconfig

Genieacs provisioning called by propper presets. It should be rune only after device bootstrap. This in turn may require propper conditions based on tags.
