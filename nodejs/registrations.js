var mysql = require('mysql');
var parameters = require('./parameters.json');
var pool = mysql.createPool(parameters.mysql);
var fs = require('fs');
var exec = require('child_process').exec;

var registrations = [];

pool.getConnection(function(err, connection){
	var options = {
		'sql': 'SELECT registration_code, receive_call FROM structure_subscription'
	}
	var query = connection.query(options);
	query.on('result', function(row) {
		connection.pause();
	    process(row, function() {
			connection.resume();
		});
	}).on('end', function() {
		connection.destroy();
		console.log(registrations);
		for (var i = 0; i < registrations.length; i++) {
			registrations[i] = "register => "+registrations[i];
		}
		fs.writeFile(parameters.asterisk.conf_dir+'test', String(registrations.join("\n")));
	});
})

function process(row, callback) {
	if (row.registration_code && row.receive_call) registrations.push(row.registration_code);
	callback();
}

exec(parameters.asterisk.cmd_reload_sip, function (error, stdout, stderr) {
	  if (error) throw error;
});