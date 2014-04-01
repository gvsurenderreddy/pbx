var fs = require('fs');
var lame = require('lame');
var wav = require('wav');

/*fs.readdir('./folder/', function(err, files){
	console.log(files);
});*/

var input = fs.createReadStream('./folder/beep.wav');
var output = fs.createWriteStream('./folder/beep.mp3');

var reader = new wav.Reader();
reader.on('format', onFormat);
input.pipe(reader);

function onFormat (format) {
	console.error('WAV format: %j', format);
	var encoder = new lame.Encoder(format);
	reader.pipe(encoder).pipe(output);
}