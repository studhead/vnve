<?php
namespace VNVE;
#
# Beheer van grenenloos tabel
#
class Grenzenloos extends Tableform
{
    protected $fields = array();    #content of fields of a record which is modified
    
    /**
     * Function Start
     * Will be started by function OnShortCode in bootstrap,php
     * Which means that this function will be started by shorcode in an joomla article: {docman}
     * A shortcode can be accompanied by arguments : {docman prefix="grens" function="search"}
     * prefix and function are reserved arguments
     * prefix is a prfix for the databasetable
     * function is a special function
     * In this case: function="manager" (default function)
     *               function="publicsearch" (search function for the public.)
     */
	public function Start($args)
	{
        $this->single = 'titel';
        $this->plural = 'titels';
        $this->class = "grenzenloos";
		$prefix = isset($args['prefix']) ? $args['prefix'].'_': "";		#prefix given for databasetable
		$this->table = $prefix . Dbtables::titels['name'];
        $this->primarykey="id";	#the primary key of the records
        if(!isset($args['task']))
        {
            $html = $this->Manager($args);
        }
        else
        {
            $html = "No Function" . $args["function"];
            switch ($args['task'])
            {
               case "manager":
                    $html = $this->Manager($args);
                    break;

                case "publicsearch":
                    $html = $this->PublicSearch($args);
                     break;

                default:
                    $html = $this->Manager($args);
                    break;
            }
        }
        return($html);
    }
     /**
     * * Start is the standard function for maintaining a databasetable.
     * This function is a important tool for the webmanager to maintain a table
     */
    public function Manager($args)
    {
        $html='';
        $this->columns= [
                                ["id","id","int"],         #table column name, columnname to be displayed, display orientation
                                ["nummer","nummer","int"],
                                ["oudnummer","oudnummer","int"],
								["seizoen","seizoen","string"],
                                ["auteur","auteur","string"],
                                ["titel","titel","string"]];
		$this->filtercolumns = array("auteur"=>"auteur","nummer"=>"nummer","oudnummer"=>"oudnummer","titel"=>"titel","artikel"=>"bestand");
        $this->permissions = ["vw","cr","md","dl"];
        $this->num_rows=explode(',',$GLOBALS['numrows']);   #default rows per page
        $this->rows_per_page = $this->num_rows[1];
        
        $html .= $this->MaintainTable(); # start or restart tableform
        return($html);
    }
    /**
     * frontend function for the users. 1 form field for searching in multiple fields
     */
    public function PublicSearch($args)
    {
        $html='';
        /**
         * columns te be diaplayed
         */
        $this->columns= [
                                ["nummer","nummer","string"],
								["oudnummer","oudnummer","string"],
								["seizoen","seizoen","string"],
                                ["titel","titel","string"],
                                ["auteur","auteur","string"],
                                ["artikel","bekijk bestand","viewer"],   #set viewer button
								["artikel","download bestand","download"],   #set downloadbutton
                            ];
        /**
         * columns to be searched 
         */
		$this->searchcolumns = array("nummer","oudnummer","seizoen","titel","auteur");
        $this->permissions = ["vw"];
        $this->rows_per_page=10;
        $this->num_rows=explode(',',$GLOBALS['numrows']);
        $this->rows_per_page = $this->num_rows[1]; #default rows per page
        $html .= $this->SearchTable(); # Just search and display records
        return($html);
    }
    /**
     * Display form for maintaining a record 
     * Will be started from tableform
     * $crmod = "create" of "modify"
     */
    public function FormTable($crmod) : string
	{
        $form = new Forms();
        $html = '';
        $html .= '<div class="prana-display">';
        $html .= '<br>';
        if($crmod == "modify")
        {
            $html .= $form->Text(array("label"=>prana_Ptext("id","id"), "id"=>"primarykey", "value"=>$this->fields['id'], "width"=>"100px;", "readonly"=>TRUE));
        }
        $html .= $form->Text(array("label"=>prana_Ptext("nummer","nummer"), "id"=>"nummer", "value"=>$this->fields['nummer'], "width"=>"300px;"));
        $html .= $form->Text(array("label"=>prana_Ptext("oudnummer","oudnummer"), "id"=>"oudnummer", "value"=>$this->fields['oudnummer'], "width"=>"300px;"));
        $html .= $form->Text(array("label"=>prana_Ptext("seizoen","seizoen"), "id"=>"seizoen", "value"=>$this->fields['seizoen'], "width"=>"300px;"));
        $html .= $form->TextArea(array("label"=>prana_Ptext("titel","titel"), "id"=>"titel", "value"=>$this->fields['titel'], "width"=>"300px;"));
        $html .= $form->Text(array("label"=>prana_Ptext("auteur","auteur"), "id"=>"auteur", "value"=>$this->fields['auteur'], "width"=>"300px;","required"=>FALSE));
        $html .= $form->Text(array("label"=>prana_Ptext("bladzijden","bladzijde(n)"), "id"=>"bladzijden", "value"=>$this->fields['bladzijden'], "width"=>"300px;"));
		$html .= $form->Text(array("label"=>prana_Ptext("artikel","bestand"),"id"=>"currentartikel","value"=>$this->fields['artikel'],"width"=>"300px","readonly"=>TRUE));
       
        $html .= $form->File(array("label"=>prana_Ptext("artikel","welk bestand (optioneel)"), "id"=>"artikel", "value"=>"artikel","accept"=>$GLOBALS['filetypes'],"width"=>"300px","required"=>FALSE));
         #
		# checkbox for deleting artikel
		#
		if($this->fields['artikel'])
		{
            $html .= $form->Check(array("label"=>prana_Ptext("verwijderen","bestand verwijderen"), "id"=>"deleteartikel", "width"=>"300px;","checked"=>FALSE,"required=>FALSE","confirm"=>"Bestand echt verwijderen?"));
		}
		$form->buttons = [
							['id'=>'writerecord','value'=>prana_Ptext("opslaan","opslaan"), "onclick"=>"buttonclicked='store'"],
                            ['id'=>'cancel','value'=>prana_Ptext("annuleren","annuleren"),"status"=>"formnovalidate","onclick"=>"buttonclicked='cancel'"]
                        ];
		$html .= $form->DisplayButtons();
        $html .='<input id="crmod" name="crmod" value="' . $crmod . '" type="hidden" />';
        $html .= '</div>';
        return($html);
    }
    /**
     * DocumentExist() : bool
     */
    public function DocumentExist($article) : bool
    {
        $file = JPATH_SITE . $GLOBALS['docdir'] . '/' . $article;
        if(!file_exists($file)) return(FALSE);
        else return(TRUE);
    }
    /**
    * Download a document
    */
    public function DownloadDocument($article) : string
    {
        $form = new Forms();
        $html = '';
        $file = JPATH_SITE . $GLOBALS['docdir'] . '/' . $article;
        $html .= $form->DownloadFile($file);
        return($html);
    }
     /*
        Download viewer
    */
    public function DocumentViewer($article) : string
    {
        $action = \JURI::current();
        $form = new Forms();
        $html = '';
        #$file_url =  CAT_PLUGIN_URL . 'documents' . '/' . $article;
        #$file =  CAT_PLUGIN_DIR . 'documents' . '/' . $article;
        $file_url =  \JURI::base() . $GLOBALS['docdir'] . '/' . $article;
        $file = JPATH_SITE . $GLOBALS['docdir'] . '/' . $article;
        if(!file_exists($file))
		{
			$error = sprintf(prana_Ptext('nofile','bestand %s bestaat niet'),$file);
			$html .= '<div class="isa_error" >' . $error . '</div>';
		}
        else
        {
            $html .= '<iframe src="'.$file_url.'" height="750" width="750"></iframe>';
        }
        $html .= '<br><button class="prana-btnsmall" id="continue" name="continue">' .  prana_PText('','terug naar overzicht') . '</button><br><br>';
        return($html);
    }
    /*
        Check the input of the form
    */
    public function CheckModify() : bool
    {
        $form = new Forms();
        $dbio = new DBIO();
        /**
         * delete the artikel from record and from store if no other records using it.
         */
        if(isset($_POST['deleteartikel']))
        {
            $response = $this->DeleteArticle($_POST['currentartikel']);
            $_POST['artikel'] = '';
        }
        /**
         * Upload the file which is choosen in inputform.
         */
        foreach ($_FILES as $file)
        {
            if(!$file['error'])
            {
                $targetdir = JPATH_SITE . $GLOBALS['docdir'];
                if ( $form->UploadFile(array("file"=>$file,"targetdir"=>$targetdir,"filetypes"=>$GLOBALS['filetypes'],"maxkb"=>$GLOBALS['maxdocsize'],"overwrite"=>TRUE)) == FALSE)
                {
                    pranaAlert($form->uploaderror);
                    return(FALSE);
                }
                $_POST['artikel'] = $file['name'];
            }
        }
        return(TRUE);
    }
    /**
	*	Wat doen we nadat een record is verwijderd?
	**/
	public function AfterDelete($id)
	{
        $dbio = new DBIO();
		$c=$dbio->ReadRecord(array("table"=>$this->table,"id"=>$id));
        if($c->artikel) { $this->DeleteArticle($c->artikel); }
		return;
	}
    /**
     * Delete an article only when there are no other records pointing to
     */
	public function DeleteArticle($article)
	{
        $dbio = new DBIO();
        $file = JPATH_SITE . $GLOBALS['docdir'] . '/' . $article;
		#Zijn er andere artikelen die naar hetzelfde bestand wijzen?
		$articlerecords = $dbio->ReadRecords(array("table"=>$this->table,"where"=>array("artikel"=>$article)));
        $nar = count($articlerecords);

		# Alleen bestand verwijderen als er geen andere artikelen meer naar toewijzen.
        if($nar == 0)
        {
            pranaAlert(sprintf(prana_Ptext('x','Bestand %s onbekende fout'),$article));
        }
		elseif($nar == 1)
		{
            if(!file_exists($file))
            {
                pranaAlert(sprintf(prana_Ptext('x','Bestand %s bestaat niet'),$article));
                return(0);
            }
			if (unlink($file)) 	{ pranaAlert(sprintf(prana_Ptext('x','Bestand %s verwijderd'),$article)); }
			else				{ pranaAlert(sprintf(prana_Ptext('x','Fout bij verwijderen Bestand %s'),$article)); }
		}
		else
		{
            pranaAlert(sprintf(prana_Ptext('x','Bestand %s niet verwijderd , omdat %d ander(e) record(s) naar toe verwijzen'),$article,$nar));
			return($nar);
		}
	}
}
?>