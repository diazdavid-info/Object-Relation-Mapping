/**
 * 
 */

function Provincia(id, nombre){
	this.id = id;
	this.nombre = nombre;
	
	this.getId = function() {
		return this.id;
	}
	
	this.getNombre = function() {
		return this.nombre;
	}
}