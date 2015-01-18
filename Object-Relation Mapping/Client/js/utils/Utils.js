/**
 * 
 */

function parseJsonToObject(json, object) {
	var j = JSON.parse(json);
	return new Provincia(j.id, j.nombre);
}