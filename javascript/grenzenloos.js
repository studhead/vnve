// javascripts voor docman
//
function ValFormGrenzenloos()
{
	if(buttonclicked == "cancel")
	{
		return true;			// don't validate if cancel was clicked
	}
	if(buttonclicked == "store")
	{
		return(ValidateRecord());
	}
	return true;
}
function ValidateRecord()
{
	var newfile = document.getElementById("artikel");	// is er een document gekozen.
	var currentdocument = document.getElementById("currentartikel");	
	//
	// is er een ander document opgegeven dan dat er al staat?
	// Zo ja vraag of dat de bedoeling is.
	//
	var newdocument = basename(newfile.value);
	if(newdocument != '' && currentdocument.value != '' && currentdocument.value != newdocument)
	{
		if(confirm('bestand wordt vervangen') != true)
		{
			return false;
		}
	}
	return true;

}
function basename (path) 
{
	return path.substring(path.lastIndexOf('\\') + 1)
}