<?php 
class articolo
{
	var $idArticolo;
	var $title;
	var $abstract;
	var $text;
	var $insertDate;
	var $idsquadra;
	var $idGiornata;
	
	function articolo()
	{
		$this->idArticolo = NULL;
		$this->title = NULL;
		$this->abstract = NULL;
		$this->text = NULL;
		$this->insertDate = NULL;
		$this->idSquadra = NULL;
		$this->idGiornata = NULL;
	}
	
	function setFromRow($row)
	{
		$this->idArticolo = $row['idArticolo'];
		$this->title = $row['title'];
		$this->abstract = $row['abstract'];
		$this->text = $row['text'];
		$this->insertDate = $row['insertDate'];
		$this->idSquadra = $row['idSquadra'];
		$this->idGiornata = $row['idGiornata'];
	}
	
	function getDataRow($row)
	{
		$row[] = $this->idArticolo;
		$row[] = $this->title;
		$row[] = $this->abstract;
		$row[] = $this->text;
		$row[] = $this->insertDate;
		$row[] = $this->idSquadra;
		$row[] = $this->idGiornata;
		return $row;
	}
	
	function getidarticolo() { return $this->idArticolo; }
	function gettitle() { return $this->title; }
	function getabstract() { return $this->abstract; }
	function gettext() { return $this->text; }
	function getinsertdate() { return $this->insertDate; }
	function getidsquadra() { return $this->idSquadra; }
	function getidgiornata() { return $this->idGiornata; }
	
	function setidarticolo($articolo_idarticolo) { $this->idArticolo = $articolo_idarticolo; }
	function settitle($articolo_title) { $this->title = $articolo_title; }
	function setabstract($articolo_abstract) { $this->abstract = $articolo_abstract; }
	function settext($articolo_text) { $this->text = $articolo_text; }
	function setinsertdate($articolo_insertdate) { $this->insertDate = $articolo_insertdate; }
	function setidsquadra($articolo_idsquadra) { $this->idSquadra = $articolo_idsquadra; }
	function setidgiornata($articolo_idgiornata) { $this->idGiornata = $articolo_idgiornata; }
	
	
	function add($articolo)
	{
		echo $this->idSquadra;
		$q = "INSERT INTO articolo (title , abstract , text , insertDate , idSquadra, idGiornata) VALUES ('" . $articolo->title . "' , '" . $articolo->abstract . "' , '" . $articolo->text . "' , '" . $articolo->insertDate . "' , '" . $articolo->idSquadra . "' , '" . $articolo->idGiornata . "');";
		mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$q = "SELECT idArticolo FROM articolo WHERE title = '" . $articolo->title . "' AND abstract = '" . $articolo->abstract . "' AND text = '" . $articolo->text . "' AND insertDate = '" . $articolo->insertDate . "' AND idSquadra = '" . $articolo->idSquadra . "' AND idGiornata = '" . $articolo->idGiornata . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
		$data = mysql_fetch_row($exe);
		return $data[0];
	}
	
	function update($articolo)
	{
		$q = "UPDATE articolo SET title = '" . $articolo->title . "' , abstract = '" . $articolo->abstract . "' , text = '" . $articolo->text . "' , insertDate = '" . $articolo->insertDate . "' , idSquadra = '" . $articolo->idSquadra . "' , idGiornata = '" . $articolo->idGiornata . "'  WHERE idArticolo = '" . $articolo->idArticolo . "';";
		mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
	}
	
	function delete($articolo)
	{
		$q = "DELETE FROM articolo WHERE idArticolo = '" . $articolo->idArticolo . "';";
		return mysql_query($q) or die(MYSQL_ERRNO()." ".MYSQL_ERROR());
	}
	
	function select($articolo , $equal , $field, $start = NULL , $end = NULL, $order = NULL)
	{
		$flag=0;
		$perc="";
		if(strtolower($equal) == "like")
		{
			$perc="%";
		}
		$q = "SELECT " . $field . " FROM articolo"; 
		if($equal != NULL)
		{
			$q .= " WHERE";
			if(!empty($articolo->idArticolo))
			{
				$q .= " articolo.idArticolo = '" . $articolo->idArticolo . "'";
				$flag++;
			}
			if(!empty($articolo->title))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " title ". $equal . " '" . $perc . $articolo->title . $perc . "'";
				$flag++;
			}
			if(!empty($articolo->abstract))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " abstract ". $equal . " '" . $perc . $articolo->abstract . $perc . "'";
				$flag++;
			}
			if(!empty($articolo->text))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " text ". $equal . " '" . $perc . $articolo->text . $perc . "'";
				$flag++;
			}
			if(!empty($articolo->insertDate))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " insertDate ". $equal . " '" . $perc . $articolo->insertDate . $perc . "'";
				$flag++;
			}
			if(!empty($articolo->idSquadra))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " idSquadra ". $equal . " '" . $perc . $articolo->idSquadra . $perc . "'";
				$flag++;
			}
			if(!empty($articolo->idGiornata))
			{
				if($flag != 0)
					$q .= ' AND';
				$q .= " idGiornata ". $equal . " '" . $perc . $articolo->idGiornata . $perc . "'";
				$flag++;
			}
		}
		if($order != NULL)
			$q .= " ORDER BY " .$order  . " DESC";
		if($start != NULL || $end != NULL)
			$q .= " LIMIT ".$start.','.$end;
		$q .= ";";
		//echo $q;
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row=mysql_fetch_array($exe))
		{
			$values[] = $row;
		}
		if(isset($values))
		return $values;
		else
		return FALSE;
	}
	
	function getGiornateArticoliExist()
	{
		$q = "SELECT DISTINCT idGiornata FROM articolo;";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_row($exe))
		{
			$values[] = $row[0];
		}
		if(isset($values))
			return $values;
		else
			return FALSE;
	}
	
	function getArticoloById($id)
	{
		$q = "SELECT * FROM articolo WHERE idArticolo = '" . $id . "';";
		$exe = mysql_query($q) or die(MYSQL_ERRNO(). $q ." ".MYSQL_ERROR());
		while($row = mysql_fetch_array($exe))
			return $row;

	}
}
?>