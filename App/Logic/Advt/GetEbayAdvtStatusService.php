<?php 
/**
*@desc 指定siteCode和item_id获取广告在线信息
*
**/
namespace App\Logic\Advt;

use App\Func\UtilsTool;


class GetEbayAdvtStatusService {


	public $url;
    
    public function getAdvtStatus($siteCode,$itemId) {

		try{

            $status = "Ended";

            $this->url = UtilsTool::getSiteUri($siteCode, $itemId);
            $html = UtilsTool::singelGet($this->url);

            if ($html['status'] == 0) {
                throw new \Exception($html['msg']);
            }
           
			$regex = "#".$this->getLowerTips($siteCode)."#";
			
            preg_match($regex,$html['data'],$matches);
            
            print_r($matches);

		    echo sprintf("itrm:%s=>%s\n",$itemId,(!empty($matches) ? "Ended":"Online"));
            
            $status = !empty($matches) ? "Ended":"Online";
            
		
		} catch(\Throwable $e) {
			echo $e->getMessage().PHP_EOL;
		}

        return $status;
    }



    public   function getLowerTips($siteCode) {
        
        switch (strtoupper($siteCode)) {
            case 'DE':
                $tip = "Dieses Angebot wurde vom Verkäufer beendet, da der Artikel nicht mehr verfügbar ist.";
                break;
            case 'FR':
                $tip = "Le vendeur a terminé cette vente, car l'objet n'est plus disponible.";
                break;
            case 'IT':
                $tip = "Questa inserzione è stata chiusa dal venditore perché l'oggetto non è più disponibile.";
                break;
            case 'ES':
                $tip = 'El vendedor ha terminado esta lista porque el artículo ya no está disponible.';
                break;
            default:
                $tip = "This listing was ended by the seller because the item is no longer available.";           

        }
        return $tip;
    }

   
}








