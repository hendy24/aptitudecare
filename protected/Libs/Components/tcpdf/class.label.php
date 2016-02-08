<?php
/**
 * @abstract Classe pour generer des étiquettes et d'y appliquer un modele / gabarit
 * @author    Ludovic Riaudel
 * @copyright 2006-2010 Ludovic Riaudel - lriaudel@gmail.com
 * @link http://cab.madvic.net/
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @version   1.3
 */

 abstract class label extends tcpdf{
	
	/**
	 * Acces au fichier de configuration (par defaut : label.xml)
	 *
	 * @var string $path . $filename
	 */
	protected $configFile = "labels.xml";

	/**
	 * id de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $idLabel;

	/**
	 * Nom de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelName;

	/**
	 * Description de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelDescription;

	/**
	 * Marque de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelBrand;

	/**
	 * Fournisseur de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelSupplier;

	/**
	 * Largeur de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelWidth;

	/**
	 * Hauteur de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelHeight;

	/**
	 * Marge de l'etiquette proveneant du fichier de parametrage
	 *
	 * @var string
	 */
	protected $labelMargin;

	/**
	 * Format de la planche d'etiquette proveneant du fichier de parametrage (voir type de format de la classe TCPDF (A3, A4, A5, etc..)
	 *
	 * @var string
	 */
	protected $sheetFormat;

	/**
	 * Orientation de la planche d'etiquette proveneant du fichier de parametrage (voir type de format de la classe TCPDF
	 * 'P' : Portrait
	 * 'L' : Paysage (landscape)
	 *
	 * @var string
	 */
	protected $sheetOrientation;

	/**
	 * Affiche ou non la bordure de l'etiquette
	 *
	 * @var boolean
	 */
	public $border;

	/**
	 * Couleur de la bordure de l'etiquette
	 *
	 * @var string
	 */
	protected $borderColor;

	/**
	 * Epaisseur de la bordure de l'etiquette
	 *
	 * @var string
	 */
	protected $borderWidth;

	/**
	 * Nombre de colonnes d'etiquettes de la planche
	 *
	 * @var integer
	 */
	protected $labelSheetCols = '';
	
	/**
	 * Nombre de lignes d'etiquettes de la planche
	 *
	 * @var integer
	 */
	protected $labelSheetRows;
	
	/**
	 * Marge superieur de la planche d'etiquettes 
	 *
	 * @var integer
	 */
	protected $labelSheetTopMargin;
	
	/**
	 * Marge gauche de la planche d'etiquettes 
	 *
	 * @var integer
	 */
	protected $labelSheetLeftMargin;

	/**
	 * Tableau des informations a passer au template
	 *
	 * @var array
	 */
	protected $data;
	
	/**
	 * Nombre de lignes d'etiquette a produire (Variable calculée)
	 *
	 * @var integer
	 */
	private $nb_rows;

	/**
	 * Nombre de pages d'etiquette a produire (Variable calculée)
	 *
	 * @var integer
	 */
	private $nb_pages;

	/**
	 * Marge horizontale entre les étiquettes (Variable calculée)
	 *
	 * @var integer
	 */
	private $h_Marge;

	/**
	 * Marge verticale entre les étiquettes	(Variable calculée)
	 *
	 * @var integer
	 */
	private $v_Marge;

	/**
	 * Constructeur de la classe label
	 *
	 *
	 * @param integer $label_id		id de l'etiquette dans le fichier de configuration
	 * @param integer $data			Tableau de données pour la réalisation du gabarit. Chaque entrée dans le tableau correspond à une étiquette (nombre d'étiquettes = count($data) ).
	 * @param integer $pathConfig	Chemin vers le répertoire ou est situé le fichier de paramétrage label.xml (avec le "/" à la fin).
	 * @param integer $configFile	Nom du fichier de paramétrage (Par défaut : "label.xml" )
	 * @param integer $border		Affiche ou non les traits de séparation de chaque étiquette (peux servir au découpage papier) (Par défaut : false).
	 */
	function __construct($label_id, $data, $pathConfig, $configFile, $border = false){

		if ($configFile == '' || $configFile == NULL){
			$this->configFile = $pathConfig.$this->configFile;
		}
		else{
			$this->configFile = $pathConfig.$configFile;
		}
		
		// Affectation d'un ID étiquette
		$this->loadLabelConfig($label_id);
		$this->data = $data;
		$this->border = $border;

		// Appel du constructeur parent TCPDF
		tcpdf::__construct($this->sheetOrientation , PDF_UNIT, $this->labelSheetFormat , true);

		$this->ctrlLabelConfig();
		
		// margin
		$this->SetMargins($this->labelMargin, $this->labelMargin);
		$this->SetCellPadding(0);
	}


	/**
	 * Sortie erreur
	 *
	 *
	 * @access private
	 * @param $string or $array	Message d'erreur ou tableau d'erreurs
	 */
	private function exitLabel($error){
	
		print "<pre style='background:#faebd7;margin:20px;'>";
		echo "<h2>Error :</h2>";
		print_r($error);
		print '</pre>';
		die();

	}// end function exit

	/**
	 * Charge le fichier de configuration dans les attribut de la classe
	 *
	 *
	 * @access private
	 * @param integer $label_id		id de l'etiquette dans le fichier de configuration
	 */
	private function loadLabelConfig($label_id){
		
		if (!isset($label_id) || $label_id==""){
			$this->exitLabel('Erreur : Aucun identifiant $label_id n\'a été déclaré dans le constructeur.');
		}
		
		if (file_exists($this->configFile)) {
			$labelXml = simplexml_load_file($this->configFile);
		} else {
			$this->exitLabel('Erreur : Le fichier '.$this->configFile.' est introuvable, v&eacute;rifier votre configuration');
		} 
		
		
		foreach ($labelXml->label as $labelName=>$label) {

			if ($label['id'] == $label_id){
			
				$this->labelName = strval($label->name);
				$this->labelDescription = strval($label->description);
				$this->labelBrand = strval($label->brand);
				$this->labelSupplier = strval($label->supplier);
				$this->labelWidth = floatval($label->width);
				$this->labelHeight = floatval($label->height);
				$this->labelMargin = intval($label->margin);
				
				$this->labelSheetFormat = strval($label->sheet['format']);
				$this->sheetOrientation = strval($label->sheet['orientation']);
				$this->borderColor = strval($label->sheet['bordercolor']);
				$this->borderWidth = strval($label->sheet['borderwidth']);
	
				// FORMAT
				if ($label->sheet && ($label->sheet != '') ){
					$this->labelSheetCols = strval($label->sheet->cols);
					$this->labelSheetRows = strval($label->sheet->rows);
					$this->labelSheetTopMargin = strval($label->sheet->margins->topmargin);
					$this->labelSheetLeftMargin = strval($label->sheet->margins->leftmargin);
				}
				else{
					// Si pas de format précisé, donc pas de grille, donc largeur et hauteur étiquette
					//$this->labelSheetFormat = array($this->labelWidth, $this->labelHeight);
					$this->labelSheetFormat = array($this->labelHeight, $this->labelWidth);
					// Le nombre de colonne et de ligne est égal à 1, et les marges à 0
					$this->labelSheetCols = 1;
					$this->labelSheetRows = 1;
					$this->labelSheetTopMargin = 0;
					$this->labelSheetLeftMargin = 0;
					$this->borderColor = '#000000';
					$this->borderWidth = 0.1;
					// Format de la page en fonction de la largeur de l'etiquette
					if($this->labelHeight < $this->labelWidth){
						$this->sheetOrientation = 'L';
					}
					else{
						$this->sheetOrientation = 'P';
					}
				}
				
				// Si l'orientation n'est pas déclaré
				if ($this->sheetOrientation == '' || $this->sheetOrientation == null){
					$this->sheetOrientation = PDF_PAGE_ORIENTATION;
				}
				return;
			}
		}
	}// End of function loadLabelConfig

	/**
	 * Controle si les donnees de la planche soient logique (largeur etiquette < largeur planche, etc..)
	 *
	 *
	 * @access private
	 */
	private function ctrlLabelConfig(){

		$error = '';
		if ( ceil($this->getPageWidth()*10000)  < ceil(( ($this->labelSheetCols * $this->labelWidth) + $this->labelSheetLeftMargin)*10000) ){
			$this->exitLabel("<b>Erreur Fichier Config (".$this->configFile.")</b> : La sommes des &eacute;tiquettes en largeur (".$this->labelSheetCols." x ".$this->labelWidth.") + ".$this->labelSheetLeftMargin.") est sup&eacute;rieur &agrave; la largeur du format de la page (".$this->getPageWidth().") en ".$this->sheetOrientation.".");
		}

		if ( ceil($this->getPageHeight()*10000)  < ceil(( ($this->labelSheetRows * $this->labelHeight) + $this->labelSheetTopMargin)*10000) ){
			$this->exitLabel("<b>Erreur Fichier Config (".$this->configFile.")</b> : La sommes des &eacute;tiquettes en hauteur (".$this->labelSheetRows." x ".$this->labelHeight.") + ".$this->labelSheetTopMargin.") est sup&eacute;rieur &agrave; la hauteur du format de la page (".$this->getPageHeight().") en ".$this->sheetOrientation.".");
		}

	} // End of function ctrlLabelConfig

	/**
	 * Affecte les paramètres d'affichages a l'objet TCPDF
	 * (non utilise / en attente)
	 *
	 * @access private
	 */
	private function Affichage(){
		$lg = Array();
		$lg['a_meta_charset'] = "ISO-8859-1";
		$lg['a_meta_dir'] = "ltr";
		$lg['a_meta_language'] = "en";
		//set some language-dependent strings
		$this->setLanguageArray($lg); 
	}

	/**
	 * Initialisation des données pour la création des étiquettes et des pages PDF
	 * Appel à la boucle de creation d'etiquette
	 *
	 *
	 * @access public
	 */
	public function AddLabel(){
	
		/* Test
			$nb_el = 0;
			for ($i =0; $i < count($this->data) ; $i++){
				$nb_el += $this->data[$i]["qteEtiq"];
			}
			echo $nb_el;
		*/
		// Nombre d'elements a afficher
		$nb_el = count($this->data);

		// Nombre de lignes
		$this->nb_rows = ceil($nb_el / $this->labelSheetCols);
		
		// Nombre de page
		$this->nb_pages = ceil($this->nb_rows / $this->labelSheetRows);

		// Calcul du nombre horizontaux d'espaces entre élements
		if ($this->labelSheetCols <= 1){ $nb_space = 1; } else { $nb_space  = $this->labelSheetCols - 1; }
			// Marge horizontale entre les élements
			// (Largeur page - (nb colonnes * largeur étiquette) - (2 x marge de gauche) ) / (nb colonnes - 1)
		$this->h_Marge = ($this->getPageWidth() -  ($this->labelSheetCols * $this->labelWidth) - (2 * $this->labelSheetLeftMargin) ) / $nb_space;
		
		// Calcul du nombre d'espaces verticaux entre élements
		if ($this->nb_rows <= 1){ $nb_space = 1;  } else{   $nb_space  = $this->nb_rows - 1; }
			// Marge verticale entre les étiquettes
		$this->v_Marge = ($this->getPageHeight() -  ($this->labelSheetRows * $this->labelHeight) - (2 * $this->labelSheetTopMargin) ) / $nb_space;
		
		// Option d'affichage
		//$this->Affichage();

		// Lance le positionnement du template
		$this->posLoop();

	}//fin fonction

	/**
	 * Surcharge de la methode Addpage de l'objet TCPDF
	 *
	 *
	 * @access public
	 */
	public function AddPage(){
	
		$this->SetMargins($this->labelMargin, $this->labelMargin);
		$this->SetCellPadding(0);
		tcpdf::AddPage();
		
	}

	/**
	 * Methode de positionnement des etiquettes et du template
	 *
	 *
	 * @access private
	 */
	private function posLoop(){
			
		// Compteur element
		$n=0;
		
		//echo "nb lignes : ".$this->nb_rows." - nb pages : ".$this->nb_pages."<br/>";
		
		// BOUCLE PAGES
		for ($k=0; $k < $this->nb_pages; $k++){
			$this->AddPage(); // fonction d'heritage
			$x = 0;
			$y = 0;
			// Marge supérieur
	
					// Boucle pour les lignes
					for ($j=0; $j< $this->labelSheetRows; $j++){
 
							// ligne de l'image x hauteur + marge page + marge etiquette
							$y = $this->labelSheetTopMargin + ( $j * $this->labelHeight) + ($j * $this->v_Marge);
	
							// Boucle pour les cellules
							for ( $i=0 ; $i< $this->labelSheetCols; $i++ ){

								// colonne de l'image x largeur + marge page + marge etiquette	
								$x = $this->labelSheetLeftMargin+ ( $i  * $this->labelWidth) + ($i * $this->h_Marge);
								
								// Si on ne depasse pas le nombre d'elements
								if ($n < count($this->data)) {
									
									// Si on affiche une bordure
									if($this->border && !is_null( $this->data[$n])){
										//$this->borderColor = '#FF0000';
										$color_array = $this->convertHTMLColorToDec($this->borderColor);
										$borderstyle = array('width' => $this->borderWidth, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'color' => array($color_array['R'], $color_array['G'], $color_array['B']));
										$this->Rect($x, $y, $this->labelWidth, $this->labelHeight, "D", array('all'=>$borderstyle ) ); 
									}
									
									// Si des informations sont dans le tableau
									if ( !is_null( $this->data[$n]) ){
										// Application du template
										$this->template(  $x, $y, $this->data[$n] );
									}
									
								}
								$n++;
								
							} // Fin boucle cellule	
	 
					} // Fin boucle ligne   
	
		} // Fin Boucle pages
	
	} // fin fonction posLoop

	/**
	 * Methode a surcharger afin de designer son etiquette
	 * Voir les exemples sur http://www.tecnick.com/public/code/cp_dpage.php?aiocp_dp=tcpdf_examples
	 *
	 * @param integer $x 			Coordonnee des abscisses de l'etiquette en cours. Permet d'utiliser cette variable pour calculer les objets a positionner
	 * @param integer $y 			Coordonnee des ordonnees de l'etiquette en cours. Permet d'utiliser cette variable pour calculer les objets a positionner
	 * @param integer $dataPrint	Tableau des informations permettant la creation d'un modele.
	 * @access abstract 
	 */
	abstract function template($x, $y, $dataPrint);


	public function Output($name='',$dest=''){
		tcpdf::Output($name, $dest);
	}

}//End of class labels

?>