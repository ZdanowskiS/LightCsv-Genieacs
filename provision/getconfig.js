log("FromFileConfiguration");
const now = Date.now();
clear("Device", Date.now());

clear("InternetGatewayDevice", Date.now());
let CPEStatus = declare("Tags.Configured", {value: 1});

let serialNumber = declare("InternetGatewayDevice.DeviceInfo.SerialNumber", {value: 1}).value[0];

let cpeid = declare("DeviceID.ID", {value: 1}).value[0];

let software = declare("InternetGatewayDevice.DeviceInfo.SoftwareVersion", {value: 1}).value[0];
let args = {serial: serialNumber, cpeid: cpeid};

if(CPEStatus.value === undefined)
{
    let actionList = ext('get_fileconfig', 'getConfiguration', JSON.stringify(args));
    log("FCC: actionlist -"+JSON.stringify(actionList));

    if(actionList ===undefined)
    {
        log('lms_ng: No NodeAdd action')
        return;
    }

    if (actionList !== null)
	    for (let [i, task] of Object.entries(actionList)) {
      	    log("FFC: TASKNAME "+task.taskname);
		    if(task.taskname=='addObject'){	
			    log('FFC: addobject: ' + task.param +' ' + task.value);
			    declare(task.param, null, {path: 1});
		    }
		    else if(task.taskname=='addTag')
		    {
          	    log('FFC: addTag');
			    declare("Tags."+task.param, null, {value: true}); 
		    }
      	    else if(task.taskname=='reboot')
		    {
                log('FFC:  reboot');
                declare("Reboot", null, {value: Date.now()});      
            }
      	    else{
                log('FFC:declare : ' + task.param +' ' + task.taskname+' ' + task.value);
                if(task.value){
					log('FFC: declare : ' + task.param +' ' + task.value);
                    declare(task.param, {value: now}, {value: task.value});
			    }
            }
	    }

    declare("Tags.Configured", null, {value: true}); 
}
