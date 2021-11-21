/**
 * Check JQuery Support
 */
if (typeof jQuery === "undefined") {
  var errorMsg = "O seu navegador non soporta a versión actual da libraría jQuery. A aplicación non se pode executar.";
  if (typeof bowser !== "undefined") {
    errorMsg = errorMsg + "\n\r\n\r" +
      "Navegador: " + bowser.name + " v." + bowser.version + "\n\r" +
      "Sistema Operativo: " + bowser.osname + " v." + bowser.osversion;
  }
  console.error(errorMsg);
  alert(errorMsg);
}