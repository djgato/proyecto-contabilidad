 <?php echo "
	 Nombre del activo: <input name='nombre' type='text'/> | Precio: <input name='precio' type='text' /> <br> 
	 Depreciable?: <input name='dep' id='dep' type='checkbox' onchange='activarDep()'/> <div id='depre'></div>
	 Compra a credio?: <input name='credito' id='cre' type='checkbox' onchange='activarCred()'/> <div id='cred'></div>
	 <input name='sub' type='submit' value='Procesar' />
	 <input name='operacion' id='operacion' type='hidden' value='compraActivo' />
	 ";?>