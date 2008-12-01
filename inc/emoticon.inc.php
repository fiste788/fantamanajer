<?php
class emoticon
{
	var $emoticon;
	
	function emoticon()
	{
		$this->emoticon[] = array('name'=>'andy','cod'=>':^)','title'=>'Scioccato');
		$this->emoticon[] = array('name'=>'angry','cod'=>':@','title'=>'Arrabbiato');
		$this->emoticon[] = array('name'=>'Baring_teeth_smiley','cod'=>'8o|','title'=>'Incazzato');
		$this->emoticon[] = array('name'=>'beer','cod'=>'(b)','title'=>'Birra');
		$this->emoticon[] = array('name'=>'brheart','cod'=>'(u)','title'=>'Cuore Spezzato');
		$this->emoticon[] = array('name'=>'Cigarette','cod'=>'(ci)','title'=>'Sigaretta');
		$this->emoticon[] = array('name'=>'coffee','cod'=>'(c)','title'=>'Caffè');
		$this->emoticon[] = array('name'=>'computer','cod'=>'(co)','title'=>'Computer');
		$this->emoticon[] = array('name'=>'confused','cod'=>':s','title'=>'Confuso');
		$this->emoticon[] = array('name'=>'cry','cod'=>':\'(','title'=>'Pianto');
		$this->emoticon[] = array('name'=>'devil','cod'=>'(6)','title'=>'Diavolo');
		$this->emoticon[] = array('name'=>'Dont_tell_anyone','cod'=>':-#','title'=>'Bocca chiusa');
		$this->emoticon[] = array('name'=>'drink','cod'=>'(d)','title'=>'Drink');
		$this->emoticon[] = array('name'=>'mail','cod'=>'(e)','title'=>'Mail');
		$this->emoticon[] = array('name'=>'blushing','cod'=>':$','title'=>'Arrossire');
		$this->emoticon[] = array('name'=>'Fingerscrossed','cod'=>'(yn)','title'=>'Dita Incrociate');
		$this->emoticon[] = array('name'=>'Hi_five','cod'=>'(h5)','title'=>'Batti Cinque');
		$this->emoticon[] = array('name'=>'coolglasses','cod'=>'(h)','title'=>'Occhiali da Sole');
		$this->emoticon[] = array('name'=>'lamp','cod'=>'(i)','title'=>'Idea');
		$this->emoticon[] = array('name'=>'grin','cod'=>':D','title'=>'Felice');
		$this->emoticon[] = array('name'=>'emblem-favorite','cod'=>'(l)','title'=>'Cuore');
		$this->emoticon[] = array('name'=>'kiss','cod'=>'(k)','title'=>'Bacio');
		$this->emoticon[] = array('name'=>'unhappy','cod'=>':(','title'=>'Triste');
		$this->emoticon[] = array('name'=>'Sarcastic_smiley','cod'=>'^o)','title'=>'Sarcastico');
		$this->emoticon[] = array('name'=>'sick','cod'=>'+o(','title'=>'Vomito');
		$this->emoticon[] = array('name'=>'sleeping','cod'=>'|-)','title'=>'Addormentato');
		$this->emoticon[] = array('name'=>'smile','cod'=>':)','title'=>'Felice');
		$this->emoticon[] = array('name'=>'tongue','cod'=>':p','title'=>'Linguaccia');
		$this->emoticon[] = array('name'=>'Soccer_ball','cod'=>'(so)','title'=>'Pallone');
		$this->emoticon[] = array('name'=>'Thinking_smiley','cod'=>'*-)','title'=>'Pensieroso');
		$this->emoticon[] = array('name'=>'wink','cod'=>';)','title'=>'Occhilino');
	}
	
	function replaceEmoticon($text,$path)
	{
		foreach($this->emoticon as $key => $val)
			$text = str_replace($val['cod'],'<img class="emoticon" src="'.$path.$val['name'].'.png" alt="' . $val['cod'] . '" title="' . $val['title'] . '" />',$text);
		return $text;
	}
}
?>