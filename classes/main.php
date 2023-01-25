<?php
/**
 * main
 * function init will be started from bootstrap.php
 **/
namespace VNVE;

 
class main
{	
	public function init($args)
	{
        $html = '';
		#$html .= $this->Loadscripts();
		/**
		 * which function should be started
		 */
		if(!isset($args['function']))
        {

            $error = prana_Ptext('nofunction',"Geen functie opgegeven in plugin shortcode");
			$html .= '<div class="isa_error" >' . $error . '</div>';
        }
        else
        {
            switch ($args['function'])
            {
               	case "grenzenloos":
				$html .= $this->Grenzenloos($args);
				break;

				default:
                $error = sprintf(prana_Ptext('unknownfunction','%s onbekende functie'),$args['function']);
				$html .= '<div class="isa_error" >' . $error . '</div>';
                break;
			}
			
		}
		return($html);
	}
	/***
	 * grenzeloos is een lijst van onderwerpen met vetwijzingen naar documenten.
	 */
	public function Grenzenloos($args)
	{
        $html = '<h1>Grenzenloos </h1>';
		$prefix = isset($args['prefix']) ? $args['prefix'].'_': "";		#prefix given for databasetable
		/**
		* create table if not exist
		**/
		$dbio = new DBIO;
		$dbio -> CreateTable($prefix . Dbtables::titels['name'],Dbtables::titels['columns']);
		$action = \JURI::current();
		$function = isset($args['function']) ? $args['function'] : '';
		$task = isset($args['task']) ? $args['task'] : '';
		$helpfile = CAT_DOC_DIR . 'manual_' . $function . '_' . $task .'.html';
		$html .= HelpModal($helpfile);
		$html .= '<form role="form" action=' . $action . ' method="post" enctype="multipart/form-data" onSubmit="return ValFormGrenzenloos()">';
		$grenzenloos = new GRENZENLOOS;
		$html .= $grenzenloos->start($args);
		$html .= '</form>';
        return($html);
	}
}